<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'          => User::factory(),
            'service_name'     => $this->faker->word(),
            'customer_name'    => $this->faker->name(),
            'amount'           => $this->faker->randomFloat(2, 50, 500),
            'booking_date'     => now()->addDays(rand(1, 10))->format('Y-m-d'),
            'appointment_date' => now()->addDays(rand(1, 10)),
            'status'           => 'confirmed',
        ];
    }
}