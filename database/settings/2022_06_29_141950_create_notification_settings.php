<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateNotificationSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->inGroup('notification', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('enable', false);
            $blueprint->add('title', 'Thông báo!');
            $blueprint->add('content', 'This is notification');
        });
    }
}
