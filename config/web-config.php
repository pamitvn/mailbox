<?php

return [
    'layouts' => [
        'menu' => [
            'main' => [
                [
                    'label' => 'Home',
                    'class' => '',
                    'icon' => "<i data-feather='activity'></i>",
                    'target' => fn() => url('/')
                ],
            ],
            'admin' => [
                [
                    'group' => 'Admin Area',
                    'items' => []
                ],
            ],
            'account' => [
                [
                    'label' => 'Profile',
                    'class' => '',
                    'target' => fn() => '/'
                ],
                [
                    'label' => 'Reset Password',
                    'class' => '',
                    'target' => fn() => '/'
                ],
                [
                    'label' => 'API',
                    'class' => '',
                    'target' => fn() => '/'
                ],
            ],
        ]
    ],
    'recharge_code' => 'mailbox{{ $id }}',
    'notification' => [
        'enable' => true,
        'title' => 'Thông báo!',
        'content' => 'This is notification'
    ],
    'web2m' => [
        'endpoint' => 'https://api.web2m.com/historyapimb/{{ $password }}/{{ $accountId }}/{{ $token }}',
        'options' => [
            'token' => env('WEB2M_TOKEN'),
            'accountId' => env('WEB2M_ACCOUNT_ID'),
            'password' => env('WEB2M_PASSWORD'),
        ]
    ]
];
