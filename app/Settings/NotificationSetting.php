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
                'is' => 'the-switch-field',
                'rule' => ['required', 'boolean'],
                'attribute' => [
                    'label' => 'Enable',
                ],
            ],
            'title' => [
                'rule' => ['required', 'string'],
                'attribute' => [
                    'type' => 'title',
                    'label' => 'Site Name',
                ],
            ],
            'content' => [
                'is' => 'the-textarea-field',
                'rule' => ['required', 'string'],
                'attribute' => [
                    'label' => 'Content',
                ],
            ],
        ];
    }
}
