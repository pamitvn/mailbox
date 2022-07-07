<?php

namespace App\Models;

use Bavix\Wallet\Interfaces\Customer;
use Bavix\Wallet\Interfaces\ProductLimitedInterface;
use Bavix\Wallet\Traits\HasWallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model implements ProductLimitedInterface
{
    use HasWallet;

    protected $fillable = [
        'service_id',
        'mail',
        'password',
        'recovery_mail',
        'status'
    ];

    protected $casts = [
        'status' => 'integer'
    ];

    protected $with = [
        'service',
        'order'
    ];

    public function getAmountProduct(Customer $customer): int|string
    {
        return $this->service->price;
    }

    public function getMetaProduct(): ?array
    {
        return [
            'title' => $this->service->name,
            'description' => 'Purchase of Product #' . $this->id,
        ];
    }

    public function canBuy(Customer $customer, int $quantity = 1, bool $force = false): bool
    {
        return blank($this->order?->id);
    }

    public function service(): HasOne
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'product_id', 'id');
    }

    public function scopeBought(Builder $query): Builder
    {
        return $query->whereHas('order');
    }

    public function scopeWithoutBought(Builder $query): Builder
    {
        return $query->whereNot(fn(Builder $builder) => $builder->whereHas('order'));
    }
}
