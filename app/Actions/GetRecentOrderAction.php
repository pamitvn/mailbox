<?php

namespace App\Actions;

use App\Models\Order;
use Illuminate\Support\Str;
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
            ->withWhereHas('service', fn ($q) => $q->whereVisible(true)->select(['id', 'name', 'feature_image']))
            ->with([
                'user' => fn ($q) => $q->select(['id', 'username']),
            ])
            ->orderByDesc('id')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                if ($item->user?->username) {
                    $item->user->username = Str::mask($item->user?->username, '*', round((40 / 100) * strlen($item->user?->username)));
                }

                $time = $item->created_at->diffForHumans();

                return collect($item->toArray())
                    ->only(['id', 'price', 'quantity', 'service', 'user', 'created_at'])
                    ->put('created_at', $time);
            })->toArray();
    }
}
