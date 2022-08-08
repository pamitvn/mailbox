<?php

namespace App\Http\Controllers\Storages;

use App\Http\Controllers\Controller;
use App\Models\Storage;
use App\Models\StorageContainer;
use App\PAM\Enums\ProductStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StorageExportController extends Controller
{
    public function __invoke(Request $request, Storage $storage)
    {
        $data = $request->validate([
            'deleteAfterExport' => ['boolean'],
            'status' => ['nullable', Rule::in(ProductStatus::toArray())],
        ]);
        $isDelete = $request->boolean('deleteAfterExport');

        $containers = StorageContainer::query()
            ->whereStorageId($storage->id)
            ->when(filled($data['status']), fn ($q) => $q->whereStatus($data['status']));

        return stream_export_storage_containers($containers, $isDelete);
    }
}
