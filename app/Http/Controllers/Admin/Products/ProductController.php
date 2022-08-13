<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Jobs\Products\ImportProductJob;
use App\Models\Product;
use App\Models\Service;
use App\PAM\Enums\ProductStatus;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    private ProductService $_productService;

    public function __construct(ProductService $service)
    {
        $this->_productService = $service;
    }

    public function index(Request $request, Service $service)
    {
        $params = $request->except('service');
        $search = $request->get('search');
        $inStock = $request->boolean('in_stock');

        $records = Product::query()
            ->whereServiceId($service->id)
            ->orderBy('id', 'desc')
            ->when(fn () => $request->filled('in_stock'), fn ($q) => $q->{$inStock ? 'withoutBought' : 'bought'}());

        search_by_cols($records, $search, [
            'payload',
        ]);

        query_by_cols($records, ['id', 'status'], $params);

        return inertia('Admin/Product/Manager', [
            'statusHtmlLabel' => ProductStatus::toBadgeHtmlArray(),
            'statusLabel' => ProductStatus::toLabelArray(),
            'service' => $service,
            'paginationData' => cursor_paginate_with_params($records, $params),
        ]);
    }

    public function store(Request $request, Service $service)
    {
        $data = $request->validate([
            'products' => [
                'nullable',
                Rule::requiredIf(fn () => ! $request->file('file') instanceof UploadedFile),
                'array',
                'min:1',
            ],
            'file' => [
                'nullable',
                Rule::requiredIf(fn () => $request->file('file') instanceof UploadedFile),
                'file',
                'mimes:txt',
                'mimetypes:text/plain',
                'max:10240',
            ],
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
}
