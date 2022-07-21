<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreatePaymentSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->inGroup('payment', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('recharge_code', 'mailbox{{ $id }}');
            $blueprint->add('web2m_acb', 'https://api.web2m.com/historyapiacb/HuAz1201@/3999981/1077CE99-6A77-2777-B73F-73F3E8B982B5');
        });
    }
}
