<?php

namespace App\Actions;

use App\Jobs\Products\PurchaseProcessingJob;
use App\Models\Service;
use App\Models\User;
use App\PAM\Enums\ProductStatus;
use App\PAM\Facades\ParentManager;
use App\Services\Admin\ProductService;
use Bavix\Wallet\Exceptions\ProductEnded;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
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
        $_service = app(ProductService::class);

        PurchaseProcessingJob::dispatch(
            $_service,
            $service,
            $user,
            $quantity
        );

        send_current_user_message('info', 'Your order has been added to the list of pending orders.', $user->id);

        return back();
    }
}
