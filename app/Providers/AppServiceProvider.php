<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // أضف هذا السطر في الأعلى

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // إجبار كل الروابط والنماذج على استخدام HTTPS لإخفاء تحذير المتصفح
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}