<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\PAM\Enums\ProductStatus;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    static string $cacheKey = 'controller::order::cache::%s';

    public function __invoke(Request $request)
    {
        $search = $request->get('search');
        $params = $request->except('search');

        $records = Order::query()
            ->with(['product' => ['order', 'service']])
            ->whereUserId(auth()->id())
            ->orderByDesc('id');
        $services = Service::whereVisible(true)->get(['id', 'name'])->pluck('name', 'id');

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

        return inertia('Orders/Manager', [
            'statusHtmlLabel' => ProductStatus::toBadgeHtmlArray(),
            'statusLabel' => ProductStatus::toLabelArray(),
            'services' => $services,
            'paginationData' => paginate_with_params($records, $params)
        ]);
    }
}
