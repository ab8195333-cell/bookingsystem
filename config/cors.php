<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'register'],

    'allowed_methods' => ['*'],

    // ضع رابط الفرونت إند الخاص بك هنا بدلاً من النجمة ليتوافق مع supports_credentials => true
    'allowed_origins' => ['https://booking-frontend-3zo0.onrender.com', 'http://localhost:3000'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];