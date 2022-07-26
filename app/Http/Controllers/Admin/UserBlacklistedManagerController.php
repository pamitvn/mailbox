<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserBlacklisted;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserBlacklistedManagerController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->except('search');
        $search = $request->get('search');

        $records = UserBlacklisted::query()
            ->with(['user', 'byUser'])
            ->orderByDesc('id');

        search_relation_by_cols($records, $search, [
            'user' => [
                'username',
                'name',
                'email',
            ],
            'byUser' => [
                'username',
                'name',
                'email',
            ],
        ]);

        query_by_cols($records, [
            'id',
            'user_id',
            'by_user_id',
        ], $params);

        return inertia('Admin/Blacklisted/User/Manager', [
            'paginationData' => cursor_paginate_with_params($records, $params),
        ]);
    }

    public function create()
    {
        return inertia('Admin/Blacklisted/User/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', Rule::unique(table_name_of_model(UserBlacklisted::class), 'user_id')],
            'reason' => ['required', 'string'],
            'duration' => ['nullable', 'date'],
        ]);

        $blacklist = UserBlacklisted::create(array_merge($data, [
            'by_user_id' => auth()->user()->id,
            'duration' => filled($data['duration']) ? Carbon::parse($data['duration'])->endOfDay() : null,
        ]));

        return send_message_if(
            boolean: filled($blacklist),
            message: __('User #:id has been added to the blacklist.', ['id' => $blacklist?->user_id]),
            unlessMessage: __('Blacklist cannot be created'),
            allowBack: true
        );
    }

    public function edit(UserBlacklisted $blacklisted)
    {
        return inertia('Admin/Blacklisted/User/Edit', [
            'blacklisted' => $blacklisted->load(['user']),
        ]);
    }

    public function update(Request $request, UserBlacklisted $blacklisted)
    {
        $data = $request->validate([
            'reason' => ['required', 'string'],
            'duration' => ['nullable', 'date'],
        ]);

        $status = $blacklisted->update(array_merge($data, [
            'duration' => filled($data['duration']) ? Carbon::parse($data['duration'])->endOfDay() : null,
        ]));

        return send_message_if(
            boolean: $status,
            message: __('Updated blacklist #:id', ['id' => $blacklisted->id]),
            unlessMessage: __('Blacklist #:id cannot be updated', ['id' => $blacklisted->id]),
            allowBack: true
        );
    }

    public function destroy(UserBlacklisted $blacklisted)
    {
        return send_message_if(
            boolean: $blacklisted->delete(),
            message: __('Deleted blacklist #:id', ['id' => $blacklisted->id]),
            unlessMessage: __('Blacklist #:id cannot be deleted', ['id' => $blacklisted->id]),
            allowBack: true
        );
    }
}
