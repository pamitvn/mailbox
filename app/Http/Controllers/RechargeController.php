<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\RechargeHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class RechargeController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->except('search');
        $search = $request->get('search');

        $records = RechargeHistory::query()
            ->with('bank')
            ->whereUserId(auth()->id())
            ->orderByDesc('id');

        search_by_cols($records, $search, [
            'note',
        ]);

        search_relation_by_cols($records, $search, [
            'bank' => ['name'],
        ]);

        return inertia('RechargeManager', [
            'transferContent' => Blade::render(config('web-config.recharge_code'), array_merge(auth()->user()->toArray(), [
                'hostname' => request()->getHttpHost(),
            ])),
            'banks' => Bank::get(),
            'paginationData' => cursor_paginate_with_params($records, $params),
        ]);
    }
}
