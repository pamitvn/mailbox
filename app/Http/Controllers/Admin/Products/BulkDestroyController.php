<?php

namespace App\Http\Controllers\Admin\Products;

use App\Events\ServiceEvent;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BulkDestroyController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'includes' => ['required', 'array', 'min:1'],
        ]);

        $service = Service::findOrFail($request->get('service'));
        $results = DB::transaction(function () use ($service, $data) {
            return Product::query()
                ->select(['id', 'service_id'])
                ->whereServiceId($service->id)
                ->where(function ($query) use ($data) {
                    foreach ($data['includes'] as $id) {
                        $query->orWhere('id', $id);
                    }
                })
                ->delete();
        });

        ServiceEvent::dispatch($service->id);

        send_message_if(
            $results,
            __('The specified records were successfully removed.'),
            __('There was a problem with the deletion.')
        );

        return back();
    }
}
