<?php

namespace App\Rules;

use App\Models\Service;
use Illuminate\Contracts\Validation\Rule;

class ServicePermissionRule implements Rule
{
    protected string $message = 'The service is invalid';

    public function __construct(protected string|int $userId, protected bool $api = false)
    {
    }

    public function passes($attribute, $value): bool
    {
        $service = Service::withCanAccess($this->userId)->find($value);

        $this->message = $this->api
            ? __('The :attribute is invalid.', compact('attribute'))
            : __('You are not allowed to perform this action');

        return blank($service) || ((bool) $service->extras?->get('permission', false) && blank($service->userCanAccess->first(fn ($ite) => $ite->id === $this->userId)));
    }

    public function message(): string
    {
        return $this->message;
    }
}
