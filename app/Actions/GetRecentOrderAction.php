<?php

namespace App\Actions;

use App\Models\Order;
use Lorisleiva\Actions\Action;

class GetRecentOrderAction extends Action
{
    public function authorize(): bool
    {
        return true;
    }

    public function handle(): array
    {
        return Order::query()
            ->withRecentOrder()
            ->orderByDesc('id')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $item->user->username = $item?->user?->mask_username;

                $time = $item->created_at->diffForHumans();

                return collect($item->toArray())
                    ->only(['id', 'price', 'quantity', 'service', 'user', 'created_at'])
                    ->put('created_at', $time);
            })->toArray();
    }
}
