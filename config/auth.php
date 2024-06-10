<?php
use App\Models\Admin;
use App\Models\User;

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],
    'guards' => [
        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],
    ],
    'providers' => [
        // 'users' => [
        //     'driver' => 'eloquent',
        //     'model' => User::class
        // ],
        'users' => [ // Default provider for users with role 0
            'driver' => 'eloquent',
            'model' => User::class,
        ],
        'admins' => [ // Provider for users with role 1
            'driver' => 'eloquent',
            'model' => User::class,
        ],
    ]
];