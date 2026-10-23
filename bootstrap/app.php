<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckSubscription;
use App\Http\Middleware\Cors; // استدعاء الـ Cors Middleware الذي أنشأناه

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // تسجيل الـ Middleware الخاصة بالأدمن والاشتراكات
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'CheckSubscription' => CheckSubscription::class,
        ]);

        // إضافة تفعيل الـ CORS تلقائياً على جميع الطلبات لضمان عدم حظر المتصفح
        $middleware->append(Cors::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();