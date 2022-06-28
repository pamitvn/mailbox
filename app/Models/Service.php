<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'feature_image',
        'price',
        'recovery_mail',
        'visible'
    ];

    protected $casts = [
        'price' => 'integer',
        'recovery_mail' => 'boolean',
        'visible' => 'boolean'
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
}
