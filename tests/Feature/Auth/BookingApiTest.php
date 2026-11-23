<?php

use App\Models\Booking;
use App\Models\User;

test('authenticated user can create a booking', function () {
    $user = User::factory()->create();

    $payload = [
        'service_name' => 'Consultation',
        'booking_date' => now()->addDays(2)->format('Y-m-d'),
        'amount'       => 150,
        'status'       => 'pending',
    ];

    $response = $this->actingAs($user)->postJson('/api/bookings', $payload);

    $response->assertStatus(201)
             ->assertJson([
                 'success' => true,
                 'message' => 'تم إنشاء الحجز بنجاح',
             ])
             ->assertJsonPath('data.service_name', 'Consultation');

    $this->assertDatabaseHas('bookings', [
        'user_id'      => $user->id,
        'service_name' => 'Consultation',
    ]);
});

test('booking fails if required fields are missing', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/bookings', []);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['service_name', 'booking_date']);
});

test('user can fetch their own bookings list', function () {
    $user = User::factory()->create();
    
    Booking::factory()->count(2)->create(['user_id' => $user->id]);
    
    $otherUser = User::factory()->create();
    Booking::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->getJson('/api/bookings');

    $response->assertStatus(200);
});

test('user can update their own booking', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()->create([
        'user_id'      => $user->id,
        'service_name' => 'Old Service',
        'status'       => 'pending',
    ]);

    $updateData = [
        'service_name' => 'Updated Service',
        'booking_date' => now()->addDays(5)->format('Y-m-d'),
        'status'       => 'confirmed',
    ];

    $response = $this->actingAs($user)->putJson("/api/bookings/{$booking->id}", $updateData);

    $response->assertStatus(200);

    $this->assertDatabaseHas('bookings', [
        'id'           => $booking->id,
        'service_name' => 'Updated Service',
        'status'       => 'confirmed',
    ]);
});

test('user can delete their booking', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->deleteJson("/api/bookings/{$booking->id}");

    $response->assertStatus(200);

    $this->assertDatabaseMissing('bookings', [
        'id' => $booking->id,
    ]);
});

test('unauthenticated guests cannot access bookings api', function () {
    $response = $this->postJson('/api/bookings', [
        'service_name' => 'Consultation',
        'booking_date' => now()->addDays(2)->format('Y-m-d'),
    ]);

    $response->dump();

    $response->assertStatus(401);
});