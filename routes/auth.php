<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // عرض صفحة التسجيل
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    // معالجة بيانات التسجيل
    Route::post('register', [RegisteredUserController::class, 'store']);

    // عرض صفحة الدخول
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    // معالجة الدخول
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    // تسجيل الخروج
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});