<?php

namespace App\Http\Controllers\Admin\Services;

use App\Http\Controllers\Controller;
use App\Jobs\UserPurchasedDestroyJob;
use App\Models\Service;
use App\Models\User;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceUserPurchasedController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    public function index(Request $request, Service $service)
    {
        $params = $request->except('search');

        $records = User::query()
            ->whereHas('orders', fn ($q) => $q->whereServiceId($service->id))
            ->select('*', DB::raw("(SELECT SUM(quantity) FROM orders WHERE orders.user_id = users.id AND orders.service_id = {$service->id}) as count"))
            ->orderByDesc('count');

        return inertia('Admin/Services/UserPurchased', [
            'service' => $service,
            'paginationData' => cursor_paginate_with_params($records, $params),
        ]);
    }

    public function destroy(Service $service, User $user)
    {
        dispatch(new UserPurchasedDestroyJob(auth()->user(), $service, [$user->id]));

        send_current_user_message('info', __('Added to the delete queue'));

        return back();
    }
}
