<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckSubscription;
use App\Http\Middleware\Cors; // <--- 1. استدعاء ملف الـ Cors

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // <--- 2. تفعيل الـ CORS على مستوى التطبيق بالكامل لضمان عدم حظر أي طلب
        $middleware->append(Cors::class);

        // تسجيل الـ Middleware الخاصة بالأدمن والاشتراكات معاً
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'CheckSubscription' => CheckSubscription::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();