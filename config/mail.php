<?php

// return [

//     'driver' => env('MAIL_MAILER', 'smtp'),

//     'host' => env('MAIL_HOST', '10.11.15.10:1025'),

//     'port' => env('MAIL_PORT', 8025),

//     'from' => [
//         'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
//         'name' => env('MAIL_FROM_NAME', 'Example'),
//     ],

//     'encryption' => env('MAIL_ENCRYPTION', 'tls'),

//     'username' => env('MAIL_USERNAME'),

//     'password' => env('MAIL_PASSWORD'),

//     'sendmail' => '/usr/sbin/sendmail -bs',

//     'markdown' => [
//         'theme' => 'default',

//         'paths' => [
//             resource_path('views/vendor/mail'),
//         ],
//     ],

// ];
return [

    'default' => env('MAIL_MAILER', 'smtp'),

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'localhost'),
            'port' => env('MAIL_PORT', 1025),
            'encryption' => env('MAIL_ENCRYPTION', null),
            'username' => env('MAIL_USERNAME', null),
            'password' => env('MAIL_PASSWORD', null),
        ],
    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

];
