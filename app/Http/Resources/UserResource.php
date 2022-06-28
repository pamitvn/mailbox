<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'is_admin' => $this->is_admin,
            'api_key' => $this->api_key,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'balance' => $this->balance,
            'balance_int' => $this->balance_int,
            'balanceInt' => $this->balanceInt,
            'notifications_count' => $this->notifications_count,
            'transactions_count' => $this->transactions_count,
            'transfers_count' => $this->transfers_count,
            'wallet_transactions_count' => $this->wallet_transactions_count,
        ];
    }
}
