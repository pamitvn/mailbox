<?php

namespace App\Jobs\Products;

use App\Models\Service;
use App\Models\User;
use App\Services\Admin\ProductService;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PurchaseProcessingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ProductService $_productService;

    private Service $service;

    private User $user;

    private int $quantity;

    public function __construct(
        ProductService $productService,
        Service $service,
        User $user,
        int $quantity
    ) {
        $this->_productService = $productService;
        $this->service = $service;
        $this->user = $user;
        $this->quantity = $quantity;
    }

    public function handle()
    {
        $this->sendMessage('info', __('Begin handling your order'));

        $products = $this->_productService->{$this->service->is_local ? 'buyRandomProduct' : 'buyProductFromParent'}($this->service, $this->quantity);

        if (blank($products)) {
            return $this->sendMessage('danger', __('The product is out of stock'));
        }

        try {
            $products = collect($products);

            $this->_productService->createOrderAndBuy($this->user, [
                'service_id' => $this->service->id,
                'user_id' => $this->user->id,
                'price' => $this->service->price,
            ], $products, $this->service->is_local);
            $this->sendMessage('success', __('Your purchase has been completed.'));
        } catch (BalanceIsEmpty|InsufficientFunds $exception) {
            $this->sendMessage('danger', $exception->getMessage());
        }
    }

    private function sendMessage($type, $msg)
    {
        send_current_user_message($type, $msg, $this->user->id);
    }
}
