<?php

namespace Database\Factories;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlertFactory extends Factory
{
    protected $model = Alert::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(['Internal', 'External']),
            'message' => $this->faker->sentence(10),
            'status' => $this->faker->boolean(70), // 70% resolved
            'raised_at' => $this->faker->dateTimeBetween('-10 days', 'now'),
        ];
    }
}
