<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can create a booking', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/bookings', [
        'service_name' => 'Consultation',
        'booking_date' => now()->addDays(2)->format('Y-m-d'),
    ]);

    $response->assertStatus(201);
});

// 1. اختبار رفض الحجز إذا كان اسم الخدمة مفقوداً
test('booking fails if service name is missing', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/bookings', [
        'booking_date' => now()->addDays(2)->format('Y-m-d'),
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['service_name']);
});

// 2. اختبار رفض الحجز إذا كان التاريخ مفقوداً
test('booking fails if booking date is missing', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/bookings', [
        'service_name' => 'Consultation',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['booking_date']);
});