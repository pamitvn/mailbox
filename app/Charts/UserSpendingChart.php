<?php

declare(strict_types=1);

namespace App\Charts;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserSpendingChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $hours = ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'];

        $orders = auth()->user()
            ->orders()
            ->whereDay('created_at', Carbon::now())
            ->get()
            ->groupBy(fn ($date) => Carbon::parse($date->created_at)->format('H'))
            ->map(fn ($item) => $item->sum('price'));

        $recharge = auth()->user()->rechargeHistories()
            ->whereDay('created_at', Carbon::now())
            ->whereNotNull('bank_id')
            ->get()
            ->groupBy(fn ($date) => Carbon::parse($date->created_at)->format('H'))
            ->map(fn ($item) => $item->sum('amount'));

        $values = [];

        foreach ($hours as $hour) {
            $values['order'][] = $orders->get($hour, 0);
            $values['recharge'][] = $recharge->get($hour, 0);
        }

        return Chartisan::build()
            ->labels($hours)
            ->dataset('Order', $values['order'])
            ->dataset('Recharge', $values['recharge']);
    }
}
