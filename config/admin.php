<?php

return [
    'settings' => [
        'default' => 'general',

        'defines' => [
            \App\Settings\GeneralSetting::class,
            \App\Settings\NotificationSetting::class,
            \App\Settings\PaymentSetting::class,
        ]
    ]
];
