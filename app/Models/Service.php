<?php

namespace App\Models;

use App\PAM\Enums\ProductStatus;
use App\PAM\Facades\ParentManager;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;

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
        'clean_after',
        'is_local',
        'extras',
    ];

    protected $casts = [
        'price' => 'integer',
        'pop3' => 'boolean',
        'imap' => 'boolean',
        'visible' => 'boolean',
        'is_local' => 'boolean',
        'clean_after' => 'integer',
        'extras' => 'collection',
    ];

    protected $appends = [
        //        'in_stock_count'
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
                'source' => ['name', 'id'],
            ],
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
            if ($this->is_local) {
                $products = $this->products()
                    ->select(['id'])
                    ->whereStatus(ProductStatus::LIVE)
                    ->withoutBought();

                return $products->count();
            }

            return ParentManager::getCount($this->extras?->get('parent_count_key'));
        });
    }

    public static function extraFields(): array
    {
        return [
            'parent_count_key' => [
                'rule' => [Rule::requiredIf(fn () => (bool) request()->get('is_local') === false), 'string'],
                'show_unless' => 'is_local',
                'attribute' => [
                    'type' => 'text',
                    'label' => 'Parent Count Key',
                ],
            ],
            'parent_type' => [
                'rule' => [Rule::requiredIf(fn () => (bool) request()->get('is_local') === false), 'string'],
                'show_unless' => 'is_local',
                'attribute' => [
                    'type' => 'text',
                    'label' => 'Parent Type',
                ],
            ],
        ];
    }
}
