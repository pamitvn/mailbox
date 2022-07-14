<?php

namespace App\Observers;

use App\Http\Controllers\OrderController;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class OrderObserver
{
    public function cacheTag(Order $order)
    {
        return sprintf(OrderController::$cacheKey, $order->user_id);
    }

    public function created(Order $order)
    {
        ServiceObserver::sendSocket($order->product->service_id);
        Cache::tags($this->cacheTag($order))->flush();
    }

    public function updated(Order $order)
    {
        ServiceObserver::sendSocket($order->service_id);
        Cache::tags($this->cacheTag($order))->flush();
    }

    public function deleted(Order $order)
    {
        ServiceObserver::sendSocket($order->service_id);
        Cache::tags($this->cacheTag($order))->flush();
    }

}
