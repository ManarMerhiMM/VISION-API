<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'is_admin' => false,
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // default password for testing
            'gender' => $this->faker->randomElement(['male', 'female']),
            'is_activated' => $this->faker->boolean(90), // 90% chance active
            'date_of_birth' => $this->faker->date('Y-m-d', '-20 years'),
            'account_type' => $this->faker->randomElement(['normal', 'visually impaired']),
            'caretaker_phone_number' => $this->faker->phoneNumber(),
            'caretaker_name' => $this->faker->name(),
            'testimonial_rate' => $this->faker->optional()->numberBetween(1, 5),
            'testimonial_message' => $this->faker->optional()->sentence(),
            'created_at' => now(),
        ];
    }
}
