<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;

class StatisticController extends Controller
{
    public function __invoke()
    {
        $orderRevenue = Order::query();

        return inertia('Admin/Statistics', [
            'total_orders' => number_format(Order::count()),
            'total_users' => number_format(User::count()),
            'order_revenue' => number_format($orderRevenue->sum('price')),
        ]);
    }
}
