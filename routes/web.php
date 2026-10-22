<?php

use Illuminate\Support\Facades\Route;

// مسار رئيسي بسيط لإعلام أي زائر أن الـ API يعمل
Route::get('/', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Booking System API Server'
    ]);
});