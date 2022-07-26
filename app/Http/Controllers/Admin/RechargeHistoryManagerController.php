<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RechargeHistory;
use Illuminate\Http\Request;

class RechargeHistoryManagerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $records = RechargeHistory::query()
            ->with(['bank', 'user'])
            ->orderByDesc('id');

        search_by_cols($records, $search, [
            'note',
        ]);

        search_relation_by_cols($records, $search, [
            'user' => [
                'name',
                'email',
                'username',
            ],
        ]);

        return inertia('Admin/Recharge/HistoryManager', [
            'paginationData' => cursor_paginate_with_params($records, $request->all()),
        ]);
    }

    public function destroy($history)
    {
        $history = RechargeHistory::findOrFail($history);

        return send_message_if(
            boolean: $history->delete(),
            message: __('Deleted history #:id', ['id' => $history->id]),
            unlessMessage: __('History #:id cannot be deleted', ['id' => $history->id]),
            allowBack: true
        );
    }
}
