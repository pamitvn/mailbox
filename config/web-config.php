<?php

return [
    'layouts' => [
        'menu' => [],
    ],
    'recharge_code' => 'mailbox{{ $id }}',
    'TinyMCE' => [
        'api_key' => env('TINPYMCE_API_KEY', null),
    ],
];
