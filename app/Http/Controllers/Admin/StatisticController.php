<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class StatisticController extends Controller
{
    public function __invoke()
    {
        $orderRevenue = Order::query();
        return inertia('Admin/Statistics', [
            'total_orders' => number_format(Order::count()),
            'total_users' => number_format(User::count()),
            'order_revenue' => number_format($orderRevenue->sum('price')),
            'date' => [
                'month' => [
                    'label' => "Month " . now()->format('m/Y'),
                    'order_revenue' => number_format(
                        $orderRevenue->whereYear('created_at', now()->format('Y'))
                            ->whereMonth('created_at', now()->format('m'))
                            ->sum('price')
                    ),
                    'total_users' => number_format(
                        User::whereYear('created_at', now()->format('Y'))
                            ->whereMonth('created_at', now()->format('m'))
                            ->count()
                    ),
                ],
                'week' => [
                    'label' => 'Week',
                    'order_revenue' => number_format(
                        $orderRevenue->whereBetween(
                            'created_at',
                            [
                                Carbon::now()->startOfWeek(CarbonInterface::SUNDAY),
                                Carbon::now()->endOfWeek(CarbonInterface::SATURDAY)
                            ]
                        )->sum('price')
                    ),
                    'total_users' => number_format(
                        User::whereBetween(
                            'created_at',
                            [
                                Carbon::now()->startOfWeek(CarbonInterface::SUNDAY),
                                Carbon::now()->endOfWeek(CarbonInterface::SATURDAY)
                            ]
                        )->count()
                    ),
                ],
                'day' => [
                    'label' => 'Today',
                    'order_revenue' => number_format($orderRevenue->whereDay('created_at', now())->sum('price')),
                    'total_users' => number_format(User::whereDay('created_at', now())->count()),
                ]
            ],
        ]);
    }
}
