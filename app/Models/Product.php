<?php

namespace App\Models;

use App\PAM\Enums\ProductStatus;
use Bavix\Wallet\Interfaces\Customer;
use Bavix\Wallet\Interfaces\ProductLimitedInterface;
use Bavix\Wallet\Traits\HasWallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model implements ProductLimitedInterface
{
    use HasWallet;

    protected $fillable = [
        'service_id',
        'payload',
        'status',
        'created_at',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

//    protected $with = [
//        'service',
//        'order',
//    ];

    protected $withCount = [
        'order',
    ];

    protected $appends = [
        'in_stock',
    ];

    public function getAmountProduct(Customer $customer): int|string
    {
        return $this->service->price;
    }

    public function getMetaProduct(): ?array
    {
        return [
            'title' => $this->service->name,
            'description' => 'Purchase of Product #'.$this->id,
        ];
    }

    public function canBuy(Customer $customer, int $quantity = 1, bool $force = false): bool
    {
        return $this->status === ProductStatus::LIVE && blank($this->order?->id);
    }

    public function service(): HasOne
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }

    public function order(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_has_products', 'product_id', 'order_id');
    }

    public function scopeBought(Builder $query): Builder
    {
        return $query->whereHas('order');
    }

    public function scopeWithoutBought(Builder $query): Builder
    {
        return $query->whereNot(fn (Builder $builder) => $builder->whereHas('order'));
    }

    public function scopeRandomQuantity(Builder $query, $quantity = 1): Builder
    {
        return $query->where('status', ProductStatus::LIVE)
            ->whereNot(fn (Builder $builder) => $builder->whereHas('order'))
            ->inRandomOrder()
            ->limit($quantity);
    }

    public function scopeQuantity(Builder $query, $quantity = 1): Builder
    {
        return $query->where('status', ProductStatus::LIVE)
            ->whereNot(fn (Builder $builder) => $builder->whereHas('order'))
            ->orderBy('id')
            ->limit($quantity);
    }

    public function inStock(): Attribute
    {
        return Attribute::get(fn () => ! $this->order_count);
    }
}
