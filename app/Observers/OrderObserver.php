<?php

namespace App\Observers;

use App\Events\Sockets\RecentOrdersEvent;
use App\Models\Order;

class OrderObserver
{
    public function created(Order $order)
    {
        ServiceObserver::sendSocket($order->service_id);
        RecentOrdersEvent::dispatch($order->id);
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
