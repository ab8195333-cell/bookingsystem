<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['https://booking-frontend-3zo0.onrender.com'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false, // احذر: إذا كانت true يجب عدم استخدام '*' في allowed_origins بل تحديد النطاق بدقة
];