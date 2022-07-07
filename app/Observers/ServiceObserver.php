<?php

namespace App\Observers;

use App\Events\ServiceEvent;
use App\Models\Service;

class ServiceObserver
{
    public function created(Service $service)
    {
        self::sendSocket($service);
    }

    public function updated(Service $service)
    {
        self::sendSocket($service);
    }

    public static function sendSocket(Service|string|int $service): void
    {
        ServiceEvent::dispatch($service);
    }
}
