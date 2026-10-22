<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// المسارات العامة (تسجيل الدخول وإنشاء حساب جديد)
Route::post('/register', [AuthController::class, 'register']); // <-- إضافة مسار إنشاء الحساب
Route::post('/login', [AuthController::class, 'login']);

// مسار لجلب بيانات المستخدم الحالي المصادق عليه
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'status' => 'success',
        'data'   => $request->user()
    ]);
});

// مسارات النظام المحمية بـ Sanctum
Route::middleware(['auth:sanctum'])->group(function () {
    
    // تسجيل الخروج
    Route::post('/logout', [AuthController::class, 'logout']);

    // عرض الحجوزات
    Route::get('/bookings', [BookingController::class, 'index']);
    
    // إضافة حجز جديد
    Route::post('/bookings', [BookingController::class, 'store']);
    
    // عرض تفاصيل حجز معين
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