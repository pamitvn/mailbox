<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreatePaymentSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->inGroup('payment', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('recharge_code', 'mailbox{{ $id }}');
        });
    }
}
