<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * المسار إلى صفحة "الرئيسية" لمشروعك.
     *
     * يستخدم هذا المسار من قبل نظام المصادقة (Auth) في لارافل لتوجيه المستخدمين
     * بعد تسجيل الدخول أو التسجيل الناجح.
     *
     * تم تعديله من '/home' إلى '/bookings' لحل مشكلة 404.
     */
    public const HOME = '/bookings';

    /**
     * تحديد روابط المسارات للمشروع.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // مسارات واجهة البرمجة (API)
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // مسارات الويب (Web)
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * تكوين حدود الطلبات (Rate Limiters) للمشروع.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}