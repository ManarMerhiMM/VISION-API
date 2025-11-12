<?php
// Tests login behavior: invalid creds, deactivated, and success token return.
namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    public function test_login_invalid_credentials(): void
    {
        $user = User::factory()->create(['password' => 'password']);
        $this->postJson('/api/login', ['username' => $user->username, 'password' => 'wrong'])
            ->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials.']);
    }

    public function test_login_deactivated(): void
    {
        $user = User::factory()->create(['password' => 'password', 'is_activated' => false]);
        $this->postJson('/api/login', ['username' => $user->username, 'password' => 'password'])
            ->assertStatus(401)
            ->assertJson(['message' => 'Account is not activated.']);
    }

    public function test_login_success_returns_token(): void
    {
        $user = User::factory()->create(['password' => 'password', 'is_activated' => true]);
        $this->postJson('/api/login', ['username' => $user->username, 'password' => 'password'])
            ->assertOk()
            ->assertJsonStructure(['token']);
    }
}
