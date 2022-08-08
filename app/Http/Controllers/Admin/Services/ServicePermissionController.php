<?php

namespace App\Http\Controllers\Admin\Services;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServicePermissionController extends Controller
{
    public function __construct(protected \App\Services\Admin\Service $_service)
    {
    }

    public function index(Service $service)
    {
        $service->load('userCanAccess');

        return collect($service->userCanAccess)
            ->map(fn ($ite) => [
                'id' => $ite->id,
                'label' => $ite?->name ?? $ite?->username ?? 'Unknown Name',
                'email' => $ite?->email,
            ]);
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'enable' => ['required', 'boolean'],
            'users' => ['nullable', 'array'],
        ]);

        return send_message_if(
            $this->_service->updatePermission($service, $data['enable'], $data['users'] ?? []),
            message: __('Updated permission for service #:id', ['id' => $service->id]),
            unlessMessage: __('Service #:id cannot be updated permission', ['id' => $service->id]),
            allowBack: true
        );
    }
}
