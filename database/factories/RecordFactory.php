<?php

namespace Database\Factories;

use App\Models\Record;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecordFactory extends Factory
{
    protected $model = Record::class;

    protected static $nonAdminUsers = null;

    public function definition(): array
    {
        // Cache non-admin users (with created_at) once for efficiency
        if (static::$nonAdminUsers === null) {
            static::$nonAdminUsers = User::where('is_admin', false)
                ->get(['id', 'created_at'])
                ->all();
        }

        // Randomly pick a non-admin user
        $user = static::$nonAdminUsers[array_rand(static::$nonAdminUsers)];

        // Generate a realistic record time after the user's account creation
        $recordedAt = $this->faker->dateTimeBetween($user->created_at, 'now');

        return [
            'user_id' => $user->id,
            'spo2' => $this->faker->randomFloat(2, 90, 100),
            'heart_rate' => $this->faker->numberBetween(40, 200),
            'galvanic_skin_resistance' => $this->faker->randomFloat(3, 2, 50),
            'relative_humidity' => $this->faker->randomFloat(2, 20, 90),
            'recorded_at' => $recordedAt,
        ];
    }
}
