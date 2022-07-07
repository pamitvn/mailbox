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
        $data = $request->validated();

        $builder = Order::query()
            ->select([
                'id',
                'product_id'
            ])
            ->with('product')
            ->where(function ($query) use ($data) {
                foreach ($data['includes'] as $id) {
                    $query->orWhere('id', $id);
                }
            });

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
                        fputcsv($handle, array_filter([$row->product?->mail, $row->product?->password, $row->product?->recovery_mail]), '|');
                    }
                }
            );

            // Close the output stream
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/plain;charset=utf-8',
        ]);

    }
}
