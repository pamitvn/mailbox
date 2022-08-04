<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderProductResource;
use App\Models\Service;
use App\PAM\Facades\ApiResponse;
use App\Services\Admin\ProductService;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    private ProductService $_service;

    public function __construct(ProductService $service)
    {
        $this->_service = $service;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $params = $request->except('search');

        $services = Service::query()
            ->whereVisible(true)
            ->orderByDesc('id')
            ->withCanAccess(auth()->id());

        search_by_cols($services, $search, ['name', 'slug', 'lifetime']);
        query_by_cols($services, ['id', 'name'], $params);

        $services = $services->get([
            'id',
            'name',
            'price',
            'imap',
            'pop3',
            'extras',
        ])
            ->filter(function ($item) {
                $enablePermission = $item->extras?->get('permission', false);

                if (! $enablePermission) {
                    return true;
                }

                return filled($item->userCanAccess->first(fn ($ite) => $ite->id === auth()->id()));
            })
            ->map(function ($item) {
                $item->unsetRelations();

                return Arr::except($item->toArray(), 'extras');
            })
            ->values();

        return ApiResponse::withSuccess()
            ->withData($services);
    }

    public function buyProduct(Request $request)
    {
        $data = $request->validate([
            'service_id' => [
                'required', 'string',
                Rule::exists(table_name_of_model(Service::class), 'id'),
                function ($attribute, $value, $fail) use ($request) {
                    $userId = $request->user()->id;
                    $service = Service::withCanAccess($userId)
                        ->find($value);

                    if (blank($service)) {
                        return $fail('The '.$attribute.' is invalid.');
                    }

                    if ((bool) $service->extras?->get('permission', false) && blank($service->userCanAccess->first(fn ($ite) => $ite->id === $userId))) {
                        return $fail('The '.$attribute.' is invalid.');
                    }
                },
            ],
            'quantity' => ['required', 'int', 'min:1'],
        ]);

        $serviceId = Arr::get($data, 'service_id');
        $quantity = Arr::get($data, 'quantity');

        $service = Service::find($serviceId);
        $user = $request->user();

        if ($quantity && $user->balanceInt < ($service->price * $quantity)) {
            return ApiResponse::withFailed()->withMessage(__('The balance in the account is not enough to make the request'));
        }

        $products = $this->_service->{$service->is_local ? 'buyRandomProduct' : 'buyProductFromParent'}($service, $quantity);

        if (blank($products)) {
            return ApiResponse::withFailed()->withMessage(__('The product is out of stock'));
        }

        try {
            $order = $this->_service->createOrderAndBuy($user, [
                'service_id' => $service->id,
                'user_id' => $user->id,
                'price' => $service->price,
            ], collect($products), $service->is_local);

            return ApiResponse::withSuccess()
                ->withData(new OrderProductResource($order))
                ->withMessage(__('Your purchase has been completed.'));
        } catch (BalanceIsEmpty|InsufficientFunds $exception) {
            return ApiResponse::withFailed()->withMessage($exception->getMessage());
        } catch (Exception $exception) {
            Log::error($exception);

            return ApiResponse::withFailed()->withMessage('Server error');
        }
    }
}
