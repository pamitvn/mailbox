<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateParentSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->inGroup('parents', function (SettingsBlueprint $blueprint) {
            $blueprint->add('auth_user', 'adminmailmarket');
            $blueprint->add('auth_password', '123132');
            $blueprint->add('api_create', 'http://139.59.236.2:55/api/get-account');
            $blueprint->add('api_count', 'http://139.59.236.2:55/api/get-log');
            $blueprint->add('types', [
                'hotmail' => 'Hotmail',
                'outlook' => 'Outlook'
            ]);
        });

    }
}
