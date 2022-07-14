<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\PAM\Enums\ProductStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    static string $cacheKey = 'controller::order::cache::%s';

    public function __invoke(Request $request)
    {
        $cacheTag = sprintf(self::$cacheKey, auth()->id());
        $cacheKey = md5(json_encode($request->toArray()));

        $services = Service::whereVisible(true)->get(['id', 'name'])->pluck('name', 'id');
        $paginationData = Cache::tags($cacheTag)->rememberForever($cacheKey, function () use ($request) {
            $search = $request->get('search');
            $params = $request->except('search');

            $records = Order::query()
                ->with(['product' => ['order', 'service']])
                ->whereUserId(auth()->id())
                ->orderByDesc('id');

            search_relation_by_cols($records, $search, [
                'product' => [
                    'id',
                    'mail',
                    'password',
                    'recovery_mail'
                ]
            ]);

            query_by_cols($records, ['id'], $params);
            query_relation_by_cols($records, [
                'product' => [
                    'status',
                    'service_id'
                ]
            ], $params);

            return paginate_with_params($records, $params);
        });

        return inertia('Orders/Manager', [
            'statusHtmlLabel' => ProductStatus::toBadgeHtmlArray(),
            'statusLabel' => ProductStatus::toLabelArray(),
            'services' => $services,
            'paginationData' => $paginationData
        ]);
    }
}
