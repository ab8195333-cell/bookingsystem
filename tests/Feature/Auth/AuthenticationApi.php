<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can register via api', function () {
    $payload = [
        'name'                  => 'عبدالله ماهر',
        'email'                 => 'abdullah@example.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->postJson('/api/register', $payload);

    $response->assertStatus(201)
             ->assertJsonStructure(['token', 'user']);

    $this->assertDatabaseHas('users', ['email' => 'abdullah@example.com']);
});

test('user can login via api and get bearer token', function () {
    $user = User::factory()->create([
        'email'    => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email'    => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
             ->assertJsonStructure(['token']);
});

test('authenticated user can logout via api', function () {
    $user = User::factory()->create();
    $token = $user->createToken('auth_token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                     ->postJson('/api/logout');

    $response->assertStatus(200);
});