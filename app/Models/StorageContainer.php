<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StorageContainer extends Model
{
    protected $fillable = [
        'storage_id',
        'payload',
        'status',
    ];

    protected $casts = [
        'storage_id' => 'integer',
        'payload' => 'string',
        'status' => 'integer',
    ];

    protected $with = [
        'parent',
    ];

    public function parent(): HasOne
    {
        return $this->hasOne(Storage::class, 'id', 'storage_id');
    }
}
