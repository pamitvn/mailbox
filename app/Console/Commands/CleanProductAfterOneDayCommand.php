<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\PAM\Enums\ProductStatus;
use Illuminate\Console\Command;

class CleanProductAfterOneDayCommand extends Command
{
    protected $signature = 'clean:product-after-one-day';

    protected $description = 'Command description';

    public function handle()
    {
        Product::query()
            ->bought()
            ->whereDate('created_at', '<=', now()->subHours(24)->toDateTimeString())
            ->forceDelete();

        Product::query()
            ->where('status', ProductStatus::DIE)
            ->forceDelete();
    }
}
