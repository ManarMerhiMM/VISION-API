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

        $testimonials = [
            'This device has truly improved my daily mobility.',
            'The alerts help me stay aware of my health in real time.',
            'I feel much safer walking outside now, thank you Vision team!',
            'Easy to use and very reliable. Highly recommended!',
            'The system has helped me detect early signs of fatigue.',
            'It’s amazing how accurate the heart rate readings are.',
            'Great companion for visually impaired users like me.',
            'Sometimes the sensors need recalibration, but overall very useful.',
            'Customer support was quick to assist with setup.',
            'I love how seamlessly it connects to the mobile app.',
        ];



        $testimonialMessage = $this->faker->optional(0.5)->randomElement($testimonials);
        $testimonialRate = $testimonialMessage
            ? $this->faker->numberBetween(1, 5)
            : $this->faker->optional(0.3)->numberBetween(1, 5);

        // Probabilities:
        // P(M & R) = 50%
        // P(!M & R) = 15%
        // P(!M & !R) = 35%
        return [
            'is_admin' => false,
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // default password for testing
            'gender' => $this->faker->randomElement(['male', 'female']),
            'is_activated' => $this->faker->boolean(90), // 90% chance active
            'date_of_birth' => $this->faker->dateTimeBetween('-80 years', '-10 years')->format('Y-m-d'),
            'account_type' => $this->faker->randomElement(['normal', 'visually impaired']),
            'caretaker_phone_number' => $this->faker->phoneNumber(),
            'caretaker_name' => $this->faker->name(),
            'testimonial_rate' => $testimonialRate,
            'testimonial_message' => $testimonialMessage,
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
