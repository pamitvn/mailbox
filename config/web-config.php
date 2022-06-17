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
                    'items' => [
                        [
                            'label' => 'Users',
                            'class' => '',
                            'icon' => "<i data-feather='users'></i>",
                            'target' => fn() => route('admin.user.index'),
                            'extraMatched' => 'admin\/users(.+)'
                        ],
                        [
                            'label' => 'Blacklisted',
                            'class' => '',
                            'icon' => "<i data-feather='hexagon'></i>",
                            'target' => 'javascript:;',
                            'items' => [
                                [
                                    'label' => 'Users',
                                    'class' => '',
                                    'icon' => "<i data-feather='users'></i>",
                                    'target' => fn() => route('admin.blacklisted.user.index'),
                                    'extraMatched' => 'admin\/blacklisted\/users(.+)'
                                ],
                            ]
                        ],
                        [
                            'label' => 'Banks',
                            'class' => '',
                            'icon' => "<i data-feather='dollar-sign'></i>",
                            'target' => fn() => route('admin.bank.index'),
                            'extraMatched' => 'admin\/banks(.+)'
                        ],
                        [
                            'label' => 'Recharge History',
                            'class' => '',
                            'icon' => "<i data-feather='dollar-sign'></i>",
                            'target' => fn() => route('admin.recharge-history.index'),
                            'extraMatched' => 'admin\/recharge-histories(.+)'
                        ],
                    ]
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
