<?php

namespace App\Http\Controllers\Admin\Services;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\PAM\Enums\ProductStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ServiceController extends Controller
{
    protected array $rules = [
        'name' => ['required', 'string', 'max:150'],
        'lifetime' => ['required', 'string', 'max:150'],
        'price' => ['required', 'integer'],
        'pop3' => ['nullable', 'boolean'],
        'imap' => ['nullable', 'boolean'],
        'visible' => ['nullable', 'boolean'],
        'is_local' => ['nullable', 'boolean'],
        'clean_after' => ['required', 'integer'],
        'extras' => ['nullable', 'array'],
        'feature_image' => [
            'nullable',
            'image',
            'mimes:jpeg,png',
            'mimetypes:image/jpeg,image/png',
            'max:2048',
        ],
    ];

    public function __construct(protected \App\Services\Admin\Service $_service)
    {
        $this->rules = array_merge($this->rules, collect(Service::extraFields())
            ->map(
                fn ($item) => blank(Arr::get($item, 'rule', []))
                    ? ['nullable']
                    : Arr::get($item, 'rule', [])
            )
            ->keyBy(fn ($value, $key) => sprintf('extras.%s', $key))
            ->toArray());
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $search = $request->get('search');

        $records = Service::query()
            ->withCount('products')
            ->orderBy('id', 'desc');

        search_by_cols($records, $search, [
            'name',
            'slug',
        ]);

        return inertia('Admin/Services/Manager', [
            'paginationData' => cursor_paginate_with_params($records, $params),
        ]);
    }

    public function create()
    {
        return inertia('Admin/Services/Create', [
            'extraFields' => Service::extraFields(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules);

        if (filled($data['feature_image'])) {
            $data['feature_image'] = $this->_service->uploadFeatureImage($data['feature_image']);
        }

        return send_message_if(
            boolean: $this->_service->create($data),
            message: __('Created new service'),
            unlessMessage: __('Service cannot be created'),
            allowBack: true
        );
    }

    public function show(Request $request, Service $service)
    {
        $search = $request->get('search');
        $params = $request->except('search');

        $records = Order::query()
            ->with('user')
            ->withWhereHas('product', fn ($query) => $query->where('service_id', $service->id))
            ->orderByDesc('id');

        search_relation_by_cols($records, $search, [
            'product' => [
                'id',
                'mail',
                'password',
                'recovery_mail',
            ],
        ]);

        query_by_cols($records, ['id'], $params);
        query_relation_by_cols($records, [
            'product' => [
                'status',
                'service_id',
            ],
        ], $params);

        return inertia('Admin/Services/Orders', [
            'statusHtmlLabel' => ProductStatus::toBadgeHtmlArray(),
            'statusLabel' => ProductStatus::toLabelArray(),
            'service' => $service,
            'paginationData' => paginate_with_params($records, $params),
        ]);
    }

    public function edit(Service $service)
    {
        return inertia('Admin/Services/Edit', [
            'service' => $service,
            'extraFields' => Service::extraFields(),
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

        return send_message_if(
            boolean: $this->_service->update($service, $data),
            message: __('Updated service #:id', ['id' => $service->id]),
            unlessMessage: __('Service #:id cannot be updated', ['id' => $service->id]),
            allowBack: true
        );
    }

    public function destroy(Service $service)
    {
        return send_message_if(
            boolean: $this->_service->delete($service),
            message: __('Deleted service #:id', ['id' => $service->id]),
            unlessMessage: __('Service #:id cannot be deleted', ['id' => $service->id]),
            allowBack: true
        );
    }
}
