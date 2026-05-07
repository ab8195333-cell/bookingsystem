<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// الصفحة الرئيسية (يمكنك تغييرها لتكون صفحة هبوط لاحقاً)
Route::get('/', function () {
    return view('welcome');
});

// روابط نظام المصادقة (تسجيل الدخول، الاشتراك، استعادة كلمة المرور)
Auth::routes();

// روابط النظام المحمية - لا يمكن الدخول إليها إلا بعد تسجيل الدخول
Route::middleware(['auth'])->group(function () {
    
    // 1. رابط تصدير البيانات إلى Excel (يجب أن يكون قبل الـ Resource ليعمل بشكل صحيح)
    Route::get('/bookings/export', [BookingController::class, 'exportExcel'])->name('bookings.export');

    // 2. رابط تحديث حالة الحجز (مقبول / ملغي)
    Route::patch('/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');

    // 3. روابط العمليات الأساسية (عرض، إضافة، تعديل، حذف)
    Route::resource('bookings', BookingController::class);

    // يمكنك إضافة رابط الصفحة الشخصية للمستأجر هنا مستقبلاً
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});