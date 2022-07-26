<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'service_id',
        'price',
        'quantity',
    ];

    protected $casts = [
        'price' => 'integer',
        'quantity' => 'integer',
    ];

    protected $appends = [
        'expired',
    ];

    public function service(): HasOne
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_has_products', 'order_id', 'product_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function expired(): Attribute
    {
        return Attribute::get(function () {
            return $this->created_at <= now()->subDay();
        });
    }
}
