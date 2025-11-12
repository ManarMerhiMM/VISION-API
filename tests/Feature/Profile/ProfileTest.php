<?php

namespace Tests\Feature\Profile;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_profile_requires_auth(): void
    {
        $this->patchJson('/api/user', [])->assertStatus(401);
    }

    public function test_update_profile_rejects_wrong_current_password(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        Sanctum::actingAs($user);

        $this->patchJson('/api/user', [
            'current_password' => 'WRONG',
            'username' => 'newname',
        ])->assertStatus(422); // validation fails because current password callback fails
    }

    public function test_update_profile_changes_username_and_password(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        Sanctum::actingAs($user);

        $uniqueUsername = 'newname_' . uniqid();

        $this->patchJson('/api/user', [
            'current_password' => 'password',
            'username' => $uniqueUsername,
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ])->assertOk()
            ->assertJson(['message' => 'Profile updated successfully.']);

        // Login with new password should work
        $this->postJson('/api/login', [
            'username' => $uniqueUsername,
            'password' => 'new_password',
        ])->assertOk();
    }

    public function test_upsert_testimonial_cases(): void
    {
        $user  = User::factory()->create();
        Sanctum::actingAs($user);

        // message without rate -> 422
        $this->postJson('/api/testimonial', ['testimonial_message' => 'hello'])
            ->assertStatus(422);

        // rate only -> ok
        $this->postJson('/api/testimonial', ['testimonial_rate' => 5])
            ->assertOk();

        // both -> ok
        $this->postJson('/api/testimonial', ['testimonial_rate' => 4, 'testimonial_message' => 'nice'])
            ->assertOk();

        // neither -> clears existing
        $this->postJson('/api/testimonial', [])
            ->assertOk();
    }
}
