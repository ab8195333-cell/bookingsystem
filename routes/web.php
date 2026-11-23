<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return response()->json([
        'status' => 'error',
        'message' => 'استخدم واجهة تسجيل الدخول في التطبيق.',
    ], 200);
})->name('login');