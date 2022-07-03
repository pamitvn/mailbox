<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

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
        $data = $request->validate([
            'products' => [
                'nullable',
                Rule::requiredIf(fn() => !$request->file('file') instanceof UploadedFile),
                'array',
                'min:1'
            ],
            'file' => [
                'nullable',
                Rule::requiredIf(fn() => $request->file('file') instanceof UploadedFile),
                'file',
                'mimes:txt',
                'mimetypes:text/plain',
                'max:10240',
            ]
        ]);

        $file = $request->file('file');

        if ($file) {
            $dir = 'app/handlers/products';
            $fileName = md5(now() . $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();

            File::ensureDirectoryExists(storage_path($dir));

            $file?->move(storage_path($dir), $fileName);
            $payload = "handlers/products/$fileName";
        } else {
            $payload = array_unique($data['products'], SORT_REGULAR);
        }

        send_current_user_message('info', __('This action has been added to the pending queue'));

        return back();
    }

    public function destroy(Product $product)
    {
    }
}
