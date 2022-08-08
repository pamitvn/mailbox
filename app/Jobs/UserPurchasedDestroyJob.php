<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UserPurchasedDestroyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected User $user, protected Service $service, protected $userIds = [])
    {
    }

    public function handle()
    {
        try {
            $trans = DB::transaction(function () {
                $orders = Order::query()
                    ->with('products')
                    ->whereIn('user_id', $this->userIds)
                    ->whereServiceId($this->service->id);
                $productIds = collect($orders->get()->toArray())
                    ->pluck('products')
                    ->collapse()
                    ->pluck('id')
                    ->filter()
                    ->unique()->toArray();

                $product = Product::query()
                    ->whereServiceId($this->service->id)
                    ->whereIn('id', $productIds)
                    ->delete();
                $orderDelete = $orders->delete();

                return $product || $orderDelete;
            });
        } catch (Exception $exception) {
            error_log($exception);
            $trans = false;
        }

        send_message_if(
            (bool) $trans,
            __('Deleted all orders at service #:id', ['id' => $this->service->id]),
            __('Cannot delete all orders at service #:id', ['id' => $this->service->id]),
            userId: $this->user->id,
            allowBack: false
        );
    }
}
