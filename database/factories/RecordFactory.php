<?php

namespace Database\Factories;

use App\Models\Record;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecordFactory extends Factory
{
    protected $model = Record::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // automatically associates a user
            'spo2' => $this->faker->randomFloat(2, 90, 100),
            'heart_rate' => $this->faker->numberBetween(40, 200),
            'galvanic_skin_resistance' => $this->faker->randomFloat(3, 2, 50),
            'relative_humidity' => $this->faker->randomFloat(2, 20, 90),
            'recorded_at' => $this->faker->dateTimeBetween('-2 weeks', 'now'),
        ];
    }
}