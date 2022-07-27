<?php

namespace App\Actions;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportProductAction extends Action
{
    public function rules(): array
    {
        return [
            'includes' => ['required', 'array', 'min:1'],
        ];
    }

    public function asController(ActionRequest $request)
    {
        $action = $request->get('action');
        $data = $request->validated();

        $builder = Order::query()
            ->select([
                'id',
            ])
            ->with('products')
            ->where(function ($query) use ($data) {
                foreach ($data['includes'] as $id) {
                    $query->orWhere('id', $id);
                }
            });

        if ($action === 'view') {
            return $this->handleView($builder);
        }

        return $this->handle($builder);
    }

    public function handle(Builder $builder)
    {
        return new StreamedResponse(function () use ($builder) {
            // Open output stream
            $handle = fopen('php://output', 'w');

            // Get results in chunks to stream large downloads
            $builder->chunk(100,
                function ($rows) use ($handle) {
                    foreach ($rows as $row) {
                        foreach ($row?->products ?? [] as $product) {
                            if (! $product?->payload) {
                                continue;
                            }

                            fwrite($handle, $product?->payload);
                        }
                    }
                }
            );

            // Close the output stream
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/plain;charset=utf-8',
        ]);
    }

    public function handleView(Builder $builder)
    {
        $data = [];

        foreach ($builder->get() as $item) {
            $data = array_merge($data, $item->products->toArray());
        }

        return $data;
    }
}
