<?php

namespace App\Http\Controllers\Storages;

use App\Http\Controllers\Controller;
use App\Models\Storage;
use App\PAM\Enums\ProductStatus;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Storage::class);
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $params = $request->except('search');
        $user = $request->user();

        $records = Storage::query()
            ->when(! $user->is_admin, fn ($q) => $q->whereUserId($user->id))
            ->when($user->is_admin, fn ($q) => $q->with('user'))
            ->orderByDesc('id');
        $storageQuery = $records;

        search_by_cols($records, $search, ['id', 'name']);

        return inertia('Storage/Manager', [
            'statistics' => [
                'count' => $storageQuery->withCount('containers')->get('id')->sum('containers_count'),
                'live_count' => $storageQuery
                    ->withCount(['containers' => fn ($q) => $q->whereStatus(ProductStatus::LIVE)])
                    ->get('id')->sum('containers_count'),
                'die_count' => $storageQuery
                    ->withCount(['containers' => fn ($q) => $q->whereStatus(ProductStatus::DIE)])
                    ->get('id')->sum('containers_count'),
            ],
            'is_admin' => $user->is_admin,
            'paginationData' => cursor_paginate_with_params($records->withCount('containers'), $params),
        ]);
    }

    public function create()
    {
        return inertia('Storage/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
        ]);

        $storage = Storage::create(array_merge($data, [
            'user_id' => $request->user()->id,
        ]));

        return send_message_if(
            boolean: filled($storage),
            message: __('Created new storage'),
            unlessMessage: __('Storage cannot be created'),
            allowBack: true
        );
    }

    public function edit(Storage $storage)
    {
        return inertia('Storage/Edit', [
            'storage' => $storage,
        ]);
    }

    public function update(Request $request, Storage $storage)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
        ]);

        return send_message_if(
            boolean: $storage->update($data),
            message: __('Updated storage #:id', ['id' => $storage->id]),
            unlessMessage: __('Storage #:id cannot be updated', ['id' => $storage->id]),
            allowBack: true
        );
    }

    public function destroy(Storage $storage)
    {
        return send_message_if(
            boolean: $storage->delete(),
            message: __('Deleted storage #:id', ['id' => $storage->id]),
            unlessMessage: __('Storage #:id cannot be deleted', ['id' => $storage->id]),
            allowBack: true
        );
    }
}
