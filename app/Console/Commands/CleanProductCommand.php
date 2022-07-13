<?php

namespace App\Console\Commands;

use App\Models\Service;
use Illuminate\Console\Command;

class CleanProductCommand extends Command
{
    protected $signature = 'clean:products';

    protected $description = 'Auto clean products';

    public function handle()
    {
        $services = Service::with('products')
            ->where('cleanAfter', '>', 0)
            ->get();

        foreach ($services as $service) {
            $service->expiredProducts()->delete();
        }
    }
}
