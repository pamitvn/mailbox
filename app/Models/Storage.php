<?php

namespace App\Models;

use App\PAM\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Storage extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'extras',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function containers(): BelongsTo
    {
        return $this->belongsTo(StorageContainer::class, 'id', 'storage_id');
    }

    public function containerCountWith($status = ProductStatus::LIVE): int
    {
        return $this->containers()->whereStatus($status)->get(['id', 'status'])->count();
    }
}
