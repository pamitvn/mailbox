<?php

namespace App\Settings;

use App\PAM\Traits\AdminSetting;
use Spatie\LaravelSettings\Settings;

class PaymentSetting extends Settings
{
    use AdminSetting;

    protected ?string $adminLabel = 'Payment';
    protected ?string $adminGroup = null;

    public string $recharge_code;

    public static function group(): string
    {
        return 'payment';
    }

    function adminFields(): array
    {
        return [
            'recharge_code' => [
                'rule' => ['required', 'string'],
                'attribute' => [
                    'label' => 'Recharge code',
                ]
            ],
        ];
    }
}
