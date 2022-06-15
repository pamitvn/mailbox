<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'balance',
        'is_admin',
        'api_key'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'balance' => 'integer'
    ];

    public function isAdministrator()
    {
        return $this->is_admin;
    }

    public function blacklisted(): HasOne
    {
        return $this->hasOne(UserBlacklisted::class, 'user_id', 'id');
    }

    public function blacklistedByMe(): BelongsTo
    {
        return $this->belongsTo(UserBlacklisted::class, 'id', 'by_user_id');
    }

    public function rechargeHistories(): BelongsTo
    {
        return $this->belongsTo(RechargeHistory::class, 'id', 'user_id');
    }
}
