<?php

namespace App\Observers;

use App\Events\Sockets\Admin\ProductEvent;
use App\Models\Product;

class ProductObserver
{
    public function created(Product $product)
    {
        ProductEvent::dispatch($product->service_id, $product, true);
        ServiceObserver::sendSocket($product->service_id);
    }

    public function updated(Product $product)
    {
        ProductEvent::dispatch($product->service_id, $product, false);
        ServiceObserver::sendSocket($product->service_id);
    }

    public function deleted(Product $product)
    {
        ServiceObserver::sendSocket($product->service_id);
    }
}
