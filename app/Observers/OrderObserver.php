<?php

namespace App\Observers;

use App\Http\Controllers\OrderController;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class OrderObserver
{
    public function created(Order $order)
    {
        ServiceObserver::sendSocket($order->service_id);
    }

    public function updated(Order $order)
    {
        ServiceObserver::sendSocket($order->service_id);
    }

    public function deleted(Order $order)
    {
        ServiceObserver::sendSocket($order->service_id);
    }

}
