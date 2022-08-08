<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\PAM\Enums\ProductStatus;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->except('search');
        $search = $request->get('search');

        $records = Order::query()
            ->with(['service', 'user'])
            ->orderByDesc('id');
        $services = Service::whereVisible(true)
            ->get(['id', 'name'])
            ->pluck('name', 'id');

        search_relation_by_cols($records, $search, [
            'service' => [
                'name',
            ],
            'user' => [
                'name', 'username', 'email',
            ],
        ]);

        query_by_cols($records, ['id', 'service_id', 'user_id'], $params);

        return inertia('Admin/OrderManager', [
            'statusHtmlLabel' => ProductStatus::toBadgeHtmlArray(),
            'statusLabel' => ProductStatus::toLabelArray(),
            'services' => $services,
            'paginationData' => cursor_paginate_with_params($records, $params),
        ]);
    }

    public function destroy($history)
    {
        $history = Order::findOrFail($history);

        return send_message_if(
            boolean: $history->delete(),
            message: __('Deleted order #:id', ['id' => $history->id]),
            unlessMessage: __('Order #:id cannot be deleted', ['id' => $history->id]),
            allowBack: true
        );
    }
}
