<?php

namespace App\Http\Middleware;

use App\Models\Service;
use App\PAM\Layout;
use Closure;
use Illuminate\Http\Request;

class HandleLayoutMenuMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $this->handleService();

        return $next($request);
    }

    protected function handleService(): void
    {
//        $services = Service::whereIsShow(true)->get(['name', 'is_show', 'slug']);
//
//        $services->each(
//            fn($val) => Layout::make()->mergeGroupMenuItems('services', [
//                [
//                    'label' => $val->name,
//                    'icon' => "<i data-feather='shopping-cart'></i>",
//                    'target' => fn() => route('service', $val->slug)
//                ]
//            ])
//        );
    }
}
