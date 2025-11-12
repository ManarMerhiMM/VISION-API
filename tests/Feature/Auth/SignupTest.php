<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WelcomeCredentials;
use App\Models\User;

class SignupTest extends TestCase
{
    use RefreshDatabase;

    public function test_signup_sends_credentials_email(): void
    {
        Notification::fake();

        $email = 'new_' . uniqid() . '@example.com';

        $this->postJson('/api/signup', [
            'email' => $email,
            'gender' => 'male',
        ])->assertCreated()
            ->assertJson(['message' => 'User created. Credentials have been emailed.']);

        $user = User::where('email', $email)->firstOrFail();

        Notification::assertSentTo($user, WelcomeCredentials::class);
    }
}
