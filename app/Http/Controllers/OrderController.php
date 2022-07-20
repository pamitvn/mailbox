<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\PAM\Enums\ProductStatus;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public static string $cacheKey = 'controller::order::cache::%s';

    public function __invoke(Request $request)
    {
        $params = $request->except('search');

        $records = Order::query()
            ->with(['service'])
            ->whereUserId(auth()->id())
            ->orderByDesc('id');
        $services = Service::whereVisible(true)->get(['id', 'name'])->pluck('name', 'id');

        query_by_cols($records, ['id', 'service_id'], $params);

        return inertia('Orders/Manager', [
            'statusHtmlLabel' => ProductStatus::toBadgeHtmlArray(),
            'statusLabel' => ProductStatus::toLabelArray(),
            'services' => $services,
            'paginationData' => paginate_with_params($records, $params),
        ]);
    }
}
