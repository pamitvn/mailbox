<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RechargeHistory extends Model
{
    protected $fillable = [
        'user_id',
        'bank_id',
        'amount',
        'after_transaction',
        'before_transaction',
        'note',
        'extras',
    ];

    protected $casts = [
        'amount' => 'float',
        'after_transaction' => 'float',
        'before_transaction' => 'float',
        'extras' => 'array',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function bank(): HasOne
    {
        return $this->hasOne(Bank::class, 'id', 'bank_id');
    }
}
