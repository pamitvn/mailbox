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
    ]
];
