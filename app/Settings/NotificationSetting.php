<?php

namespace App\Settings;

use App\PAM\Traits\AdminSetting;
use Spatie\LaravelSettings\Settings;

class NotificationSetting extends Settings
{
    use AdminSetting;

    protected ?string $adminLabel = 'Notification';

    protected ?string $adminGroup = null;

    public bool $enable;

    public ?string $title;

    public ?string $content;

    public static function group(): string
    {
        return 'notification';
    }

    public function adminFields(): array
    {
        return [
            'enable' => [
                'is' => 'v-switch',
                'rule' => ['required', 'boolean'],
                'attrs' => [
                    'label' => 'Enable',
                ],
            ],
            'title' => [
                'rule' => ['required', 'string'],
                'attrs' => [
                    'type' => 'title',
                    'label' => 'Site Name',
                ],
            ],
            'content' => [
                'is' => 'v-tiny-rich-text',
                'rule' => ['required', 'string'],
                'attrs' => [
                    'label' => 'Content',
                ],
            ],
        ];
    }
}
