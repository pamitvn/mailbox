<?php

namespace App\Http\Controllers\Storages;

use App\Http\Controllers\Controller;
use App\Models\Storage;
use App\Models\StorageContainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StorageContainerBulkDestroyController extends Controller
{
    public function __invoke(Request $request, Storage $storage)
    {
        $data = $request->validate([
            'includes' => ['required', 'array', 'min:1'],
        ]);

        $results = DB::transaction(function () use ($storage, $data) {
            return StorageContainer::query()
                ->whereStorageId($storage->id)
                ->whereIn('id', $data['includes'])
                ->delete();
        });

        send_message_if(
            $results,
            __('The specified records were successfully removed.'),
            __('There was a problem with the deletion.')
        );

        return back();
    }
}
