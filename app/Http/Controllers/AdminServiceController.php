<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Service;
use App\PAM\Facades\ApiResponse;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminServiceController extends Controller
{
    private ProductService $_service;

    public function __construct(ProductService $service)
    {
        $this->_service = $service;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $params = $request->except('search');

        $services = Service::query()->orderByDesc('id');

        search_by_cols($services, $search, ['name', 'slug', 'lifetime']);
        query_by_cols($services, ['id', 'name'], $params);

        return ApiResponse::withSuccess()->withData($services->get());
    }

    public function addProduct(Request $request)
    {
        $data = $request->validate([
            'service_id' => ['required', 'string', Rule::exists(table_name_of_model(Service::class), 'id')],
            'mail' => ['required', 'email', Rule::unique(table_name_of_model(Product::class), 'mail')],
            'password' => ['required'],
            'recovery_mail' => ['nullable', 'email'],
        ]);

        $product = $this->_service->save($data['service_id'], $data['mail'], $data['password'], $data['recovery_mail']);

        return filled($product?->id)
            ? ApiResponse::withSuccess()->withData($product->toArray())
            : ApiResponse::withFailed()->withMessage('Failed');
    }
}
