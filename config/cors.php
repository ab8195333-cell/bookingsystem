<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // أو يمكنك وضع رابط الفرونت إند الخاص بك هنا بدلاً من النجمة
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false, // احذر: إذا كانت true يجب عدم استخدام '*' في allowed_origins بل تحديد النطاق بدقة
];