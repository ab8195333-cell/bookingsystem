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
Route::post('/register', [AuthController::class, 'register']);
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
    
    // التقرير الكامل (يجب أن يكون قبل مسار {id} لكي لا يقرأه لارافيل كـ ID)
    Route::get('/bookings/report', [BookingController::class, 'downloadFullReport']);
    
    // إضافة حجز جديد
    Route::post('/bookings', [BookingController::class, 'store']);
    
    // عرض تفاصيل حجز معين
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    
    // تعديل حجز
    Route::put('/bookings/{id}', [BookingController::class, 'update']);
    
    // حذف حجز
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);

    // مسارات الدفع والاشتراكات
    Route::post('/subscription/stripe-checkout', [BookingController::class, 'stripeCheckout']);
});