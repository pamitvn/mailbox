<?php

namespace App\Actions;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
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
            ->whereIn('id', $data['includes']);

        if (! $request->user()?->is_admin) {
            $builder = $builder->whereUserId($request->user()->id);
        }

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

                            fwrite($handle, $product?->payload."\n");
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
            $data = Cache::remember(
                sprintf('export.order.%s', $item->id),
                Carbon::parse($item->created_at)->addHours(24)->timestamp,
                fn () => array_merge($data, $item->products->toArray())
            );
        }

        return $data;
    }
}
