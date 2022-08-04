<?php

namespace App\Rules;

use App\Models\Product;
use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ProductPayloadUniqueUidRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        try {
            $uid = Arr::first(explode('|', $value));
            $productExist = Product::query()
                ->where('payload', 'LIKE', "%$uid%")
                ->exists();

            return ! $productExist;
        } catch (Exception $exception) {
            Log::error($exception);

            return false;
        }
    }

    public function message(): string
    {
        return 'This :attribute already exists in the system.';
    }
}
