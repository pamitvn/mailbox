<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function home(Request $request)
    {
        $search = $request->get('search');
        $services = Service::query()
            ->whereVisible(true);

        search_by_cols($services, $search, [
            'name',
            'slug',
            'lifetime'
        ]);

        return inertia('Home', [
            'services' => $services->get()
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
}
