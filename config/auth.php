<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */

   'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'travelers' => [
        'driver' => 'session',
        'provider' => 'travelers',
    ],

    'admins' => [
        'driver' => 'session',
        'provider' => 'admins',
    ],

    'hotels' => [
        'driver' => 'session',
        'provider' => 'hotels',
    ],
],
    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

   'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],

    'travelers' => [
        'driver' => 'eloquent',
        'model' => App\Models\Traveler::class,
    ],

    'admins' => [
        'driver' => 'eloquent',
        'model' => App\Models\Admin::class,
    ],

    'hotels' => [
        'driver' => 'eloquent',
        'model' => App\Models\Hotel::class,
    ],
],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    //],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],

        'admins' => [
            'provider' => 'admins',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],

        'travelers' => [
            'provider' => 'travelers',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],

        'hotels' => [
            'provider' => 'hotels',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
