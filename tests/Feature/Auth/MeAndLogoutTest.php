<?php
// Tests /api/me and /api/logout behaviors for authenticated users.
namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;

class MeAndLogoutTest extends TestCase
{
    public function test_me_requires_auth(): void
    {
        $this->getJson('/api/me')->assertStatus(401);
    }

    public function test_me_ok_when_authenticated(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('react-client')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/me')
            ->assertOk()
            ->assertJsonPath('id', $user->id);
    }

    public function test_logout_requires_auth(): void
    {
        $this->postJson('/api/logout')->assertStatus(401);
    }

    public function test_logout_deletes_current_token(): void
    {
        $user  = User::factory()->create();
        $token = $user->createToken('react-client')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout')
            ->assertOk()
            ->assertJson(['message' => 'Logged out']);
    }
}
