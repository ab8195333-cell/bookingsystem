<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'register'],

    'allowed_methods' => ['*'],

    // السماح لكل النطاقات مؤقتاً لضمان عدم حدوث أي حظر بسبب اختلاف الروابط
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false, // اجعلها false عند استخدام '*' لكي لا يتعارض مع المتصفح
];