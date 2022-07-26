<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class CleanProductAfterOneDayCommand extends Command
{
    protected $signature = 'clean:product-after-one-day';

    protected $description = 'Command description';

    public function handle()
    {
        $products = Product::query()->whereDate('created_at', '<=', now()->subHours(24)->toDateTimeString());

        $products->forceDelete();
    }
}
