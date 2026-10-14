<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminController;

// تحويل الصفحة الرئيسية تلقائياً إلى الحجوزات
Route::get('/', function () {
    return redirect()->route('bookings.index');
});

// تعريف مسارات تسجيل الدخول والخروج تلقائياً
Auth::routes();

// مسارات النظام المحمية للمستخدمين العاديين
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [BookingController::class, 'index'])->name('home');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/subscribe', [BookingController::class, 'subscribe'])->name('subscription.index');
    Route::post('/start-trial', [BookingController::class, 'startTrial'])->name('subscription.start_trial');

    // العمليات الأخرى
    Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/bookings/report', [BookingController::class, 'downloadFullReport'])->name('bookings.pdf_all');

    // مسار الانتقال لصفحة أو عملية الدفع
    Route::get('/subscription/checkout', [BookingController::class, 'checkout'])->name('subscription.checkout');
    Route::post('/subscription/stripe-checkout', [BookingController::class, 'stripeCheckout'])->name('subscription.stripe');
});

// مسارات لوحة تحكم الأدمن (محمية بـ auth و admin معاً بشكل منفصل وصحيح)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
});