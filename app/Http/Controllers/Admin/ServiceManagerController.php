<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
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
}
