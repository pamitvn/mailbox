<?php

declare(strict_types=1);

namespace App\Charts\Admin;

use App\Models\Product;
use App\Models\Service;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StatisticServiceImportProductPerHour extends BaseChart
{
    private array $values = [];

    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $hours = [
            '00', '01', '02', '03', '04',
            '05', '06', '07', '08', '09',
            '10', '11', '12', '13', '14',
            '15', '16', '17', '18', '19',
            '20', '21', '22', '23',
        ];
        $initial = collect($hours)->map(fn () => 0)->toArray();
        $service = Service::query()
            ->local()
            ->get();

        $products = Product::query()
            ->whereIn('service_id', $service->pluck('id'))
            ->whereDay('created_at', Carbon::today())
            ->get()
            ->groupBy('service_id')
            ->map(fn ($item) => $item
                ->groupBy(fn ($ite) => Carbon::parse($ite->created_at)->format('H'))
                ->map(fn ($subGroup) => $subGroup->count())
            );

        $values = [
            ...$service
                ->keyBy('name')
                ->map(fn () => $initial)
                ->toArray(),
        ];

        foreach ($products as $id => $product) {
            $name = $service->first(fn ($ite) => $ite->id === (int) $id)->name;

            foreach ($hours as $hour) {
                $values[$name][] = $product->get($hour, 0);
            }
        }

        $builder = Chartisan::build()->labels($hours);

        foreach ($values as $key => $value) {
            $builder = $builder->dataset((string) $key, $value);
        }

        return $builder;
    }
}
