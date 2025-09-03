<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rate Limiter Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure rate limiters for your application. These are
    | used by the Laravel authentication system and API rate limiting.
    |
    */

    'limiters' => [

        'api' => [
            'decay_minutes' => 1,
            'max_attempts' => 1000,
        ],

        // You can add other limiters here too
        'login' => [
            'decay_minutes' => 1,
            'max_attempts' => 59,
        ],

    ],

];