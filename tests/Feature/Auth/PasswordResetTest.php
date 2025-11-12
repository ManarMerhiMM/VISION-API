<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Config;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable throttling for this class to avoid 429 flakiness
        $this->withoutMiddleware(ThrottleRequests::class);

        // Ensure the rate limiter uses ephemeral cache per process/run
        Config::set('cache.default', 'array');
    }

    public function test_forgot_password_is_generic(): void
    {
        $this->postJson('/api/forgot-password', ['email' => 'whatever@example.com'])
            ->assertOk()
            ->assertJson(['message' => 'If that email exists, a reset link has been sent.']);
    }

    public function test_reset_password_success(): void
    {
        $user  = User::factory()->create(['password' => 'old_password']);
        $token = Password::createToken($user);

        $this->postJson('/api/reset-password', [
            'email'                 => $user->email,
            'token'                 => $token,
            'password'              => 'new_password',
            'password_confirmation' => 'new_password',
        ])->assertOk()->assertJson(['message' => 'Password reset successful.']);

        // Sanity: new password should work
        $this->postJson('/api/login', [
            'username' => $user->username,
            'password' => 'new_password',
        ])->assertOk();
    }

    public function test_reset_password_invalid_token(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/reset-password', [
            'email'                 => $user->email,
            'token'                 => 'BAD_TOKEN',
            'password'              => 'new_password',
            'password_confirmation' => 'new_password',
        ])->assertStatus(422);
    }
}
