<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;

class ProductManagerController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $serviceId = $request->get('service');
        $search = $request->get('search');

        abort_if(blank($serviceId), 404);

        $service = Service::findOrFail($serviceId);
        $records = Product::query()->whereId($service->id)->orderBy('id', 'desc');

        search_by_cols($records, $search, [
            'name',
            'slug'
        ]);

        return inertia('Admin/Product/Manager', [
            'service' => $service,
            'paginationData' => paginate_with_params($records, $params)
        ]);
    }

    public function store(Request $request)
    {
        dd($request->all());
    }

    public function destroy(Product $product)
    {
    }
}
