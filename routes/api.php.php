<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController; // <-- 1. أضف هذا السطر للاستدعاء
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 2. أضف مسار تسجيل الدخول العام هنا (خارج مجموعة الحماية)
Route::post('/login', [AuthController::class, 'login']);

// مسار لجلب بيانات المستخدم الحالي المصادق عليه (مفيد جداً في Next.js)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'status' => 'success',
        'data'   => $request->user()
    ]);
});

// مسارات النظام المحمية للمستخدمين عبر الـ API
Route::middleware(['auth:sanctum'])->group(function () {
    
    // عرض الحجوزات
    Route::get('/bookings', [BookingController::class, 'index']);
    
    // إضافة حجز جديد
    Route::post('/bookings', [BookingController::class, 'store']);
    
    // عرض تفاصيل حجز معين (للتعديل)
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    
    // تعديل حجز
    Route::put('/bookings/{id}', [BookingController::class, 'update']);
    
    // حذف حجز
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
    
    // التقرير الكامل
    Route::get('/bookings/report', [BookingController::class, 'downloadFullReport']);

    // مسارات الدفع والاشتراكات
    Route::post('/subscription/stripe-checkout', [BookingController::class, 'stripeCheckout']);
});