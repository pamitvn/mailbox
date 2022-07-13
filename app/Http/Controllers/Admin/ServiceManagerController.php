<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\PAM\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceManagerController extends Controller
{
    protected \App\Services\Admin\Service $_service;

    protected array $rules = [
        'name' => ['required', 'string', 'max:150'],
        'lifetime' => ['required', 'string', 'max:150'],
        'price' => ['required', 'integer'],
        'pop3' => ['nullable', 'boolean'],
        'imap' => ['nullable', 'boolean'],
        'visible' => ['nullable', 'boolean'],
        'clean_after' => ['nullable', 'integer'],
        'feature_image' => [
            'nullable',
            'image',
            'mimes:jpeg,png',
            'mimetypes:image/jpeg,image/png',
            'max:2048',
        ]
    ];

    public function __construct(\App\Services\Admin\Service $_service)
    {
        $this->_service = $_service;
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $search = $request->get('search');

        $records = Service::query()->orderBy('id', 'desc');

        search_by_cols($records, $search, [
            'name',
            'slug'
        ]);

        return inertia('Admin/Services/Manager', [
            'paginationData' => paginate_with_params($records, $params)
        ]);
    }

    public function create()
    {
        return inertia('Admin/Services/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules);

        if (filled($data['feature_image'])) {
            $data['feature_image'] = $this->_service->uploadFeatureImage($data['feature_image']);
        }

        return $this->_service->create($data)
            ? back()->with('success', __('Created new service'))
            : back()->with('error', __('Service cannot be created'))->withErrors('Error', 'globalError');
    }

    public function show(Request $request, Service $service)
    {
        $search = $request->get('search');
        $params = $request->except('search');

        $records = Order::query()
            ->with('user')
            ->withWhereHas('product', fn($query) => $query->where('service_id', $service->id))
            ->orderByDesc('id');

        search_relation_by_cols($records, $search, [
            'product' => [
                'id',
                'mail',
                'password',
                'recovery_mail'
            ]
        ]);

        query_by_cols($records, ['id'], $params);
        query_relation_by_cols($records, [
            'product' => [
                'status',
                'service_id'
            ]
        ], $params);

        return inertia('Admin/Services/Orders', [
            'statusHtmlLabel' => ProductStatus::toBadgeHtmlArray(),
            'statusLabel' => ProductStatus::toLabelArray(),
            'service' => $service,
            'paginationData' => paginate_with_params($records, $params)
        ]);
    }

    public function edit(Service $service)
    {
        return inertia('Admin/Services/Edit', [
            'service' => $service
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate($this->rules);

        if (filled($data['feature_image'])) {
            $data['feature_image'] = $this->_service->uploadFeatureImage($data['feature_image'], $service->feature_image);
        } else {
            unset($data['feature_image']);
        }

        return $this->_service->update($service, $data)
            ? back()->with('success', __('Updated service #:id', ['id' => $service->id]))
            : back()->with('error', __('Service #:id cannot be updated', ['id' => $service->id]))->withErrors('Error', 'globalError');
    }

    public function destroy(Service $service)
    {
        return $this->_service->delete($service)
            ? back()->with('success', __('Deleted service #:id', ['id' => $service->id]))
            : back()->with('error', __('Service #:id cannot be deleted', ['id' => $service->id]))->withErrors('Error', 'globalError');
    }

    public function bulkDestroy(Request $request, Service $service)
    {
        $data = $request->validate([
            'includes' => ['required', 'array', 'min:1'],
        ]);

        $results = DB::transaction(function () use ($service, $data) {
            return Order::query()
                ->select(['id', 'service_id'])
                ->withWhereHas('product', fn($query) => $query->where('service_id', $service->id))
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
