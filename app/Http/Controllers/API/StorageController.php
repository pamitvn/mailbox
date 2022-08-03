<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Storage;
use App\Models\StorageContainer;
use App\PAM\Facades\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Throwable;

class StorageController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view,storage', [])->only('show');
        $this->middleware('can:create,storage', [])->only('import');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $params = $request->except('search');
        $user = $request->user();

        $records = Storage::query()
            ->whereUserId($user->id)
            ->orderByDesc('id');

        search_by_cols($records, $search, ['id', 'name']);

        $pagination = cursor_paginate_with_params($records, $params)->toArray();

        $pagination['records'] = $pagination['data'];
        unset($pagination['data']);

        return ApiResponse::withSuccess()->withData(Arr::sort($pagination));
    }

    public function show(Request $request, Storage $storage)
    {
        $request->validate([
            'limit' => ['nullable', 'integer'],
        ]);
        $limit = (int) $request->get('limit', 1);
        $afterDelete = $request->has('afterDelete');

        $containers = $storage->containers()->limit($limit);
        $payloads = $containers->pluck('payload');

        if ($afterDelete) {
            $containers->delete();
        }

        return ApiResponse::withSuccess()
            ->withData($payloads);
    }

    public function import(Request $request, Storage $storage)
    {
        $data = $request->validate([
            'payload' => ['required', 'string'],
        ]);

        try {
            $product = StorageContainer::create([
                'storage_id' => $storage->id,
                'payload' => $data['payload'],
            ]);

            $product->unsetRelations();

            return filled($product?->id)
                ? ApiResponse::withSuccess()->withData($product->toArray())
                : ApiResponse::withFailed()->withMessage('Failed');
        } catch (Exception|Throwable $exception) {
            Log::error($exception);

            return ApiResponse::withFailed()->withMessage('Failed');
        }
    }
}
