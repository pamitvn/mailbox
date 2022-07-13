<?php

namespace App\Models;

use App\PAM\Enums\ProductStatus;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use function Clue\StreamFilter\fun;

class Service extends Model
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'feature_image',
        'lifetime',
        'price',
        'pop3',
        'imap',
        'visible',
        'clean_after'
    ];

    protected $casts = [
        'price' => 'integer',
        'pop3' => 'boolean',
        'imap' => 'boolean',
        'visible' => 'boolean',
        'clean_after' => 'integer'
    ];

    protected $appends = [
        'in_stock_count'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['name', 'id']
            ]
        ];
    }

    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id', 'service_id');
    }

    public function expiredProducts(): BelongsTo
    {
        return $this->products()->whereDate('created_at', '<', now()->subHours($this->clean_after));
    }

    public function inStockCount(): Attribute
    {
        return Attribute::get(function () {
            $products = $this->products()
                ->select(['id'])
                ->whereStatus(ProductStatus::LIVE)
                ->withoutBought();

            return $products->count();
        });
    }
}
