<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderProductResource;
use App\Models\Service;
use App\PAM\Facades\ApiResponse;
use App\Services\Admin\ProductService;
use Bavix\Wallet\Exceptions\ProductEnded;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use PHPUnit\Exception;

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
            ->orderByDesc('id');

        search_by_cols($services, $search, ['name', 'slug', 'lifetime',]);
        query_by_cols($services, ['id', 'name'], $params);

        return ApiResponse::withSuccess()
            ->withData($services->get([
                'id',
                'name',
                'price',
                'imap',
                'pop3',
            ]));
    }

    public function buyProduct(Request $request)
    {
        $data = $request->validate([
            'service_id' => ['required', 'string', Rule::exists(table_name_of_model(Service::class), 'id')],
            'quantity' => ['required', 'int', 'min:1']
        ]);

        $serviceId = Arr::get($data, 'service_id');
        $quantity = Arr::get($data, 'quantity');

        $service = Service::find($serviceId);
        $user = $request->user();

        if ($quantity && $user->balanceInt < ($service->price * $quantity)) {
            return ApiResponse::withFailed()->withMessage(__('The balance in the account is not enough to make the request'));
        }

        $orders = [];
        $messages = [];
        $products = $service->products()
            ->randomQuantity($quantity)
            ->get();

        if (blank($products)) return ApiResponse::withFailed()->withMessage(__('The product is out of stock'));

        foreach ($products as $product) {
            try {
                $orders[] = $this->_service->buy($service, $product, $user, $service->price);
            } catch (Exception $exception) {
                Log::info($exception->getMessage());
                break;
//                return ApiResponse::withFailed()->withSuccess('Failed');
            } catch (ProductEnded $exception) {
                $messages[] = $exception->getMessage();
            }
        }

        $messages = array_unique($messages, SORT_REGULAR);

        if (blank($orders) || filled($messages)) {
            return ApiResponse::withFailed()
                ->withSuccess(Arr::first($messages))
                ->withErrors($messages);
        }

        return ApiResponse::withSuccess()
            ->withData(OrderProductResource::collection($orders))
            ->withMessage(__('Product purchased successfully'));
    }
}
