<?php

namespace App\Http\Controllers\Admin\Services;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceOrderBulkDestroyController extends Controller
{
    public function __invoke(Request $request, Service $service)
    {
        $data = $request->validate([
            'includes' => ['required', 'array', 'min:1'],
        ]);

        $results = DB::transaction(function () use ($service, $data) {
            return Order::query()
                ->select(['id', 'service_id'])
                ->withWhereHas('product', fn ($query) => $query->where('service_id', $service->id))
                ->where(function ($query) use ($data) {
                    foreach ($data['includes'] as $id) {
                        $query->orWhere('id', $id);
                    }
                })
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
