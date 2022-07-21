<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserBlacklisted extends Model
{
    protected $table = 'user_blacklisted';

    protected $fillable = [
        'user_id',
        'by_user_id',
        'reason',
        'duration',
    ];

    protected $casts = [
        'duration' => 'datetime',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function byUser(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'by_user_id');
    }
}
