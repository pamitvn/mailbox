<?php

namespace App\Observers;

use App\Events\Sockets\Admin\ProductEvent;
use App\Models\Product;

class ProductObserver
{
    public function created(Product $product)
    {
        ProductEvent::dispatch($product->service_id, $product, true);
    }

    public function updated(Product $product)
    {
        ProductEvent::dispatch($product->service_id, $product, false);
    }
}
