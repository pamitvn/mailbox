<?php

namespace App\Actions;

use App\Jobs\Products\PurchaseProcessingJob;
use App\Models\Service;
use App\Models\User;
use App\Services\Admin\ProductService;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;

class BuyProductAction extends Action
{
    public function rules(): array
    {
        return [
            'service' => [
                'required',
                Rule::exists(table_name_of_model(Service::class), 'id'),
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }

    public function asController(ActionRequest $request)
    {
        $data = $request->validated();

        $serviceId = Arr::get($data, 'service');
        $quantity = (int) Arr::get($data, 'quantity', 1);

        $service = Service::whereVisible(true)->findOrFail($serviceId);
        $user = $request->user();

        if ($quantity && $user->balanceInt < ($service->price * $quantity)) {
            send_current_user_message('info', __('The balance in the account is not enough to make the request'), $user->id);

            return back()->withErrors('Error', 'globalError');
        }

        return $this->handle($request, $user, $service, $quantity);
    }

    public function handle(
        ActionRequest $request,
        User $user,
        Service $service,
        int $quantity
    ) {
        $_service = app(ProductService::class);

        PurchaseProcessingJob::dispatch(
            $_service,
            $service,
            $user,
            $quantity
        );

        send_current_user_message('info', __('Your order has been added to the list of pending orders.'), $user->id);

        return back();
    }
}
