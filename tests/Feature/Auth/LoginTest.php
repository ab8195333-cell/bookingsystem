<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can authenticate and receive an api token', function () {
    // 1. Arrange: إنشاء مستخدم افتراضي في قاعدة بيانات الاختبار
    $user = User::factory()->create([
        'email' => 'developer@example.com',
        'password' => bcrypt('password123'),
    ]);

    // 2. Act: إرسال طلب POST لتسجيل الدخول
    $response = $this->postJson('/api/login', [
        'email' => 'developer@example.com',
        'password' => 'password123',
    ]);

    // 3. Assert: التحقق من صحة الاستجابة
    $response->assertStatus(200)
             ->assertJsonStructure([
                 'access_token',
                 'token_type',
             ]);
});

test('user cannot authenticate with invalid password', function () {
    $user = User::factory()->create([
        'email' => 'developer@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'developer@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401);
});