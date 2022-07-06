<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\Products\ImportProductJob;
use App\Models\Product;
use App\Models\Service;
use App\PAM\Enums\ProductStatus;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductManagerController extends Controller
{
    private ProductService $_productService;

    public function __construct(ProductService $service)
    {
        $this->_productService = $service;
    }

    public function index(Request $request)
    {
        $params = $request->except('service');
        $serviceId = $request->get('service');
        $search = $request->get('search');

        $service = Service::findOrFail($serviceId);
        $records = Product::query()
            ->whereServiceId($service->id)
            ->orderBy('id', 'desc');

        search_by_cols($records, $search, [
            'mail',
            'recovery_mail',
            'password'
        ]);

        query_by_cols($records, ['id', 'email', 'status'], $params);

        return inertia('Admin/Product/Manager', [
            'statusHtmlLabel' => ProductStatus::toBadgeHtmlArray(),
            'statusLabel' => ProductStatus::toLabelArray(),
            'service' => $service,
            'paginationData' => paginate_with_params($records, $params)
        ]);
    }

    public function store(Request $request)
    {
        $service = Service::findOrFail($request->get('service'));
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

        $payload = $file
            ? $this->_productService->uploadFile($file)
            : array_unique($data['products'], SORT_REGULAR);

        ImportProductJob::dispatch($this->_productService, auth()->user(), $service, $payload);

        send_current_user_message('info', __('This action has been added to the pending queue'));

        return back();
    }

    public function destroy(Product $product)
    {
        send_message_if(
            $this->_productService->delete($product),
            __('Deleted product #:id', ['id' => $product->id]),
            __('Product #:id cannot be deleted', ['id' => $product->id])
        );

        return back();
    }

    public function bulkDestroy(Request $request)
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

        send_message_if(
            $results,
            __('The specified records were successfully removed.'),
            __('There was a problem with the deletion.')
        );

        return back();
    }
}
