<?php

namespace App\Settings;

use App\PAM\Traits\AdminSetting;
use Spatie\LaravelSettings\Settings;

class GeneralSetting extends Settings
{
    use AdminSetting;

    protected ?string $adminLabel = 'General';

    protected ?string $adminGroup = null;

    public string $site_name;

    public static function group(): string
    {
        return 'general';
    }

    public function adminFields(): array
    {
        return [
            'site_name' => [
                'rule' => ['required', 'string'],
                'attribute' => [
                    'type' => 'text',
                    'label' => 'Site Name',
                ],
            ],
        ];
    }
}
