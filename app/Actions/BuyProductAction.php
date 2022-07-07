<?php

namespace App\Actions;

use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use App\PAM\Enums\ProductStatus;
use App\Services\Admin\ProductService;
use Bavix\Wallet\Exceptions\ProductEnded;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;
use PHPUnit\Exception;

class BuyProductAction extends Action
{
    public function rules(): array
    {
        return [
            'service' => [
                'required',
                Rule::exists(table_name_of_model(Service::class), 'id')
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1'
            ]
        ];
    }

    public function asController(ActionRequest $request)
    {
        $data = $request->validated();

        $serviceId = Arr::get($data, 'service');
        $quantity = (int)Arr::get($data, 'quantity', 1);

        $service = Service::whereVisible(true)->findOrFail($serviceId);
        $user = $request->user();

        if ($quantity && $user->balanceInt < ($service->price * $quantity)) {
            return back()->with('error', __('The balance in the account is not enough to make the request'))
                ->withErrors('Error', 'globalError');
        }

        return $this->handle($request, $user, $service, $quantity);
    }

    public function handle(
        ActionRequest $request,
        User          $user,
        Service       $service,
        int           $quantity
    )
    {
        $orders = [];
        $messages = [];
        $_service = app(ProductService::class);
        $products = $service->products()
            ->randomQuantity($quantity)
            ->get();

        if (blank($products)) {
            send_current_user_message('danger', __('The product is out of stock'));
            return back()->withErrors('globalError', '');
        }

        foreach ($products as $product) {
            try {
                $orders[] = $_service->buy($service, $product, $user, $service->price);
            } catch (Exception $exception) {
                Log::error($exception->getMessage());
                break;
            } catch (ProductEnded $exception) {
                $messages[] = $exception->getMessage();
            }
        }

        $messages = array_unique($messages, SORT_REGULAR);

        if (blank($orders) || filled($messages)) {
            foreach ($messages as $message) {
                send_current_user_message('danger', $message, $user->id);
            }
        } else {
            send_current_user_message('success', 'Product purchased successfully', $user->id);
        }

        return back();
    }
}
