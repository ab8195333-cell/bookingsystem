<?php

return [

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'register',
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://booking-frontend-3zo0.onrender.com',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];