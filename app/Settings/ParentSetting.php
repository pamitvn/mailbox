<?php

namespace App\Settings;

use App\PAM\Traits\AdminSetting;
use Spatie\LaravelSettings\Settings;

class ParentSetting extends Settings
{
    use AdminSetting;

    protected ?string $adminLabel = 'Parents';

    protected ?string $adminGroup = null;

    public string $auth_user;

    public string $auth_password;

    public string $api_create;

    public string $api_count;

    public array $types;

    public static function group(): string
    {
        return 'parents';
    }

    public function adminFields(): array
    {
        return [
            'auth_user' => [
                'rule' => ['required', 'string'],
                'attrs' => [
                    'label' => 'Auth User',
                ],
            ],
            'auth_password' => [
                'rule' => ['required', 'string'],
                'attrs' => [
                    'label' => 'Auth Password',
                ],
            ],
            'api_create' => [
                'rule' => ['nullable', 'url'],
                'attrs' => [
                    'label' => 'Create API Endpoint',
                ],
            ],
            'api_count' => [
                'rule' => ['nullable', 'url'],
                'attrs' => [
                    'label' => 'Get Count API Endpoint',
                ],
            ],
        ];
    }
}
