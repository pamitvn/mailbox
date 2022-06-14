<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;

class UserObserver
{
    public function creating(User $user)
    {
        if (!$user->api_key) {
            $user->api_key = Str::uuid()->toString();
        }
    }
}
