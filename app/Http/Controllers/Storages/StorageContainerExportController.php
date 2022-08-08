<?php

namespace App\Http\Controllers\Storages;

use App\Http\Controllers\Controller;
use App\Models\Storage;
use App\Models\StorageContainer;
use Illuminate\Http\Request;

class StorageContainerExportController extends Controller
{
    public function __invoke(Request $request, Storage $storage)
    {
        $data = $request->validate([
            'includes' => ['required', 'array', 'min:1'],
            'delete' => ['boolean'],
        ]);
        $isDelete = $request->boolean('delete');

        $builder = StorageContainer::query()
            ->whereStorageId($storage->id)
            ->whereIn('id', $data['includes']);

        return stream_export_storage_containers($builder, $isDelete);
    }
}
