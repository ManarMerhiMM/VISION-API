<?php

namespace Database\Factories;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlertFactory extends Factory
{
    protected $model = Alert::class;

    protected static $nonAdminUsers = null;

    public function definition(): array
    {
        // Cache non-admin users once (for performance)
        if (static::$nonAdminUsers === null) {
            static::$nonAdminUsers = User::where('is_admin', false)
                ->get(['id', 'created_at'])
                ->all();
        }

        // Pick a random non-admin user
        $user = static::$nonAdminUsers[array_rand(static::$nonAdminUsers)];

        // Alert messages
        $alertMessages = [
            'Abnormal heart rate detected. Please check your vitals.',
            'Low SpO₂ levels detected. Consider resting or seeking assistance.',
            'Sudden change in skin resistance detected — possible stress event.',
            'High humidity detected around the device sensors - weather is turning bad',
        ];

        // Pick a message
        $message = $this->faker->randomElement($alertMessages);

        // Determine alert type (Internal / External)
        $internalMessages = [
            'Abnormal heart rate detected. Please check your vitals.',
            'Low SpO₂ levels detected. Consider resting or seeking assistance.',
        ];

        $type = in_array($message, $internalMessages)
            ? 'Internal'
            : 'External';

        // raised_at must be after the user's creation
        $raisedAt = $this->faker->dateTimeBetween($user->created_at, 'now');

        return [
            'user_id' => $user->id,
            'type' => $type,
            'message' => $message,
            'status' => $this->faker->boolean(70), // 70% resolved
            'raised_at' => $raisedAt,
        ];
    }
}