<?php

namespace App\Http\Controllers\Storages;

use App\Http\Controllers\Controller;
use App\Jobs\ContainerImportProductJob;
use App\Models\Storage;
use App\Models\StorageContainer;
use App\PAM\Enums\ProductStatus;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class ContainerController extends Controller
{
    public function __construct(private readonly ProductService $_productService)
    {
        $this->authorizeResource(StorageContainer::class);
    }

    public function index(Request $request, Storage $storage)
    {
        $this->authorize('storage.container', $storage);

        $search = $request->get('search');
        $params = $request->except('search');

        $records = StorageContainer::query()
            ->whereStorageId($storage->id)
            ->orderByDesc('id');

        search_by_cols($records, $search, [
            'id',
            'payload',
        ]);

        query_by_cols($records, [
            'id',
            'status',
        ], $params);

        return inertia('Storage/Containers/Manager', [
            'storage' => $storage,
            'statusHtmlLabel' => ProductStatus::toBadgeHtmlArray(),
            'statusLabel' => ProductStatus::toLabelArray(),
            'paginationData' => cursor_paginate_with_params($records, $params),
        ]);
    }

    public function store(Request $request, Storage $storage)
    {
        $data = $request->validate([
            'products' => [
                'nullable',
                Rule::requiredIf(fn () => ! $request->file('file') instanceof UploadedFile),
                'array',
                'min:1',
            ],
            'file' => [
                'nullable',
                Rule::requiredIf(fn () => $request->file('file') instanceof UploadedFile),
                'file',
                'mimes:txt',
                'mimetypes:text/plain',
                'max:100240',
            ],
        ]);

        $file = $request->file('file');

        $payload = $file
            ? $this->_productService->uploadFile($file, 'containers')
            : array_unique($data['products'], SORT_REGULAR);

        ContainerImportProductJob::dispatch($this->_productService, auth()->user(), $storage, $payload);

        send_current_user_message('info', __('This action has been added to the pending queue'));

        return back();
    }

    public function destroy(Storage $storage, $storageContainerId)
    {
        return send_message_if(
            boolean: StorageContainer::query()->whereStorageId($storage->id)
                ->whereId($storageContainerId)
                ->delete(),
            message: __('Deleted storage item #:id', ['id' => $storageContainerId]),
            unlessMessage: __('Storage item #:id cannot be deleted', ['id' => $storageContainerId]),
            allowBack: true
        );
    }
}
