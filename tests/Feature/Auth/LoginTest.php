<?php

use App\Models\User;

test('user can authenticate and receive an api token', function () {
    $user = User::factory()->create([
        'email' => 'developer@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'developer@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'status',
                 'message',
                 'user',
                 'token',
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