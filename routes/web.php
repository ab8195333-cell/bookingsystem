<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// أضف هذا المسار لكي يتعرف عليه Sanctum عند عدم وجود توكن ويمنع ظهور الخطأ
Route::get('/login', function () {
    return response()->json([
        'status' => 'error',
        'message' => 'غير مصرح لك بالوصول. الرجاء تسجيل الدخول.'
    ], 401);
})->name('login');