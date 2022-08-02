<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServiceResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function home(Request $request)
    {
        $search = $request->get('search');
        $services = Service::query()
            ->whereVisible(true)
            ->withCanAccess(auth()->id());

        search_by_cols($services, $search, [
            'name',
        ]);

        $services = $services->get()
            ->filter(function ($item) {
                $enablePermission = $item->extras->get('permission', false);

                if (! $enablePermission) {
                    return true;
                }

                return filled($item->userCanAccess->first(fn ($ite) => $ite->id === auth()->id()));
            });

        return inertia('Home', [
            'services' => ServiceResource::collection($services)->toArray($request),
        ]);
    }

    public function statistic()
    {
        $user = auth()->user();

        return inertia('Statistics', [
            'balance' => number_format($user->balance),
            'spending' => number_format($user->orders()->sum('price')),
            'order' => number_format($user->orders()->count()),
        ]);
    }

    public function test()
    {
//        $order = Order::first();
//        $order->products()->sync(Product::all('id')->pluck('id'));
//        dd($order->products);

//        $product = Product::first();
//        Order::query()->forceDelete();
//        Product::query()->forceDelete();

//        $duplicated = \DB::table('products')
//            ->select('payload', \DB::raw('count(`payload`) as occurences'))
//            ->groupBy('payload')
//            ->having('occurences', '>', 1)
//            ->pluck('payload');
//
//        Product::query()
//            ->whereIn('payload', $duplicated)
//            ->forceDelete();

        dd(Order::count(), Product::count());
    }
}
