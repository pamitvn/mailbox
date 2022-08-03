<?php

declare(strict_types=1);

namespace App\Charts\Admin;

use App\Models\Order;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StatisticOrderChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $dates = day_of_month_to_array(Carbon::now()->startOfMonth(), Carbon::now());
        $query = Order::query()
            ->whereYear('created_at', now()->format('Y'))
            ->whereMonth('created_at', now()->format('m'))
            ->get()
            ->groupBy(fn ($date) => Carbon::parse($date->created_at)->format('d'))
            ->map(fn ($item) => $item->count());

        $values = group_chart_by_day($query, $dates);

        return Chartisan::build()
            ->labels($dates)
            ->dataset('Month', $values['month'])
            ->dataset('Week', $values['week'])
            ->dataset('Today', $values['today']);
    }
}
