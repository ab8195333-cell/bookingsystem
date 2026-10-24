<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubscriptionController;
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

// مسارات الحجوزات المحمية بـ Sanctum (كل مستخدم يرى ويدير حجوزاته فقط)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/report', [BookingController::class, 'downloadFullReport']);
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    Route::put('/bookings/{id}', [BookingController::class, 'update']);
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
});

// مسارات النظام الأخرى المحمية بـ Sanctum
Route::middleware(['auth:sanctum'])->group(function () {
    
    // تسجيل الخروج
    Route::post('/logout', [AuthController::class, 'logout']);

    // مسارات الدفع والاشتراكات
    Route::post('/subscription/stripe-checkout', [BookingController::class, 'stripeCheckout']);

    // مسارات الاشتراكات والتجربة المجانية
    Route::get('/subscription/status', [SubscriptionController::class, 'checkStatus']);
    Route::post('/subscribe/trial', [SubscriptionController::class, 'trial']);
    Route::post('/subscribe/receipt', [SubscriptionController::class, 'uploadReceipt']);
});