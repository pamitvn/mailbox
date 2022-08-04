<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\PAM\Facades\ApiResponse;
use App\Rules\ProductPayloadUniqueUidRule;
use App\Services\Admin\ProductService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            'payload' => ['required', 'string', new ProductPayloadUniqueUidRule],
        ]);

        try {
            $product = $this->_service->save($data['service_id'], trim($data['payload']));

            return filled($product?->id)
                ? ApiResponse::withSuccess()->withData($product->toArray())
                : ApiResponse::withFailed()->withMessage('Failed');
        } catch (Exception $exception) {
            Log::error($exception);

            return ApiResponse::withFailed()->withMessage('Failed');
        }
    }
}
