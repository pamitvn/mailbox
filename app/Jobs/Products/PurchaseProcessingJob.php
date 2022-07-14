<?php

namespace App\Jobs\Products;

use App\Models\Service;
use App\Models\User;
use App\PAM\Enums\ProductStatus;
use App\PAM\Facades\ParentManager;
use App\Services\Admin\ProductService;
use Bavix\Wallet\Exceptions\ProductEnded;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use PHPUnit\Exception;

class PurchaseProcessingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ProductService $_productService;
    private Service $service;
    private User $user;
    private int $quantity;

    public function __construct(
        ProductService $productService,
        Service        $service,
        User           $user,
        int            $quantity
    )
    {
        $this->_productService = $productService;
        $this->service = $service;
        $this->user = $user;
        $this->quantity = $quantity;
    }

    public function handle()
    {
        $this->sendMessage('info', __('Begin handling your order'));

        [$orders, $messages] = $this->service->is_local
            ? $this->buyFromLocal()
            : $this->buyFromParent();

        $messages = array_unique($messages);

        if (blank($messages)) return $this->sendMessage('success', __('Your purchase has been completed.'));

        foreach ($messages as $message) {
            $this->sendMessage('danger', $message);
        }
    }

    private function buyFromLocal(): array
    {
        $orders = [];
        $messages = [];

        $products = $this->service->products()
            ->randomQuantity($this->quantity)
            ->get();

        if (blank($products)) {
            $messages[] = __('The product is out of stock');
        }

        foreach ($products as $product) {
            try {
                $orders[] = $this->_productService->buy($this->service, $product, $this->user, $this->service->price);
            } catch (Exception $exception) {
                Log::error($exception->getMessage());
                break;
            } catch (ProductEnded $exception) {
                $messages[] = $exception->getMessage();
            }
        }

        return [
            $orders,
            $messages
        ];
    }

    private function buyFromParent(): array
    {
        $orders = [];
        $messages = [];

        $parentManager = ParentManager::withAuth();

        $data = $parentManager
            ->withType($this->service->extras?->get('parent_type'))
            ->withQuantity($this->quantity)
            ->getMail();

        if (!$data->count()) {
            $messages[] = __('The product is out of stock');
        }

        foreach ($data->toArray() as $item) {
            try {
                $data = explode('|', $item);

                $mail = Arr::get($data, '0');
                $password = Arr::get($data, '1');
                $recoveryEmail = Arr::get($data, '2');

                if (blank($mail) || blank($password)) continue;

                $orders[] = $this->_productService->createProductAndBuy($this->service, [
                    'mail' => $mail,
                    'password' => $password,
                    'recovery_mail' => $recoveryEmail,
                    'status' => ProductStatus::LIVE,
                    'service_id' => $this->service->id
                ], $this->user, $this->service->price);

            } catch (Exception $exception) {
                Log::error($exception->getMessage());
                break;
            } catch (ProductEnded $exception) {
                $messages[] = $exception->getMessage();
            }
        }

        return [$orders, $messages];
    }

    private function sendMessage($type, $msg)
    {
        send_current_user_message($type, $msg, $this->user->id);
    }
}
