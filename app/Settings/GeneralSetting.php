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

    public bool $buy_random;

    public bool $recent_order;

    public static function group(): string
    {
        return 'general';
    }

    public function adminFields(): array
    {
        return [
            'site_name' => [
                'rule' => ['required', 'string'],
                'attrs' => [
                    'type' => 'text',
                    'label' => 'Site Name',
                ],
            ],
            'buy_random' => [
                'rule' => ['required', 'boolean'],
                'is' => 'v-switch',
                'attrs' => [
                    'label' => 'Buy random',
                ],
            ],
            'recent_order' => [
                'rule' => ['required', 'boolean'],
                'is' => 'v-switch',
                'attrs' => [
                    'label' => 'Show recent order',
                ],
            ],
        ];
    }
}
