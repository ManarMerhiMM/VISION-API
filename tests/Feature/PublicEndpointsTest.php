<?php
// Verifies public endpoints: /api/status, /api/testimonials, /api/admins.
// For /api/admins we assert the response contains only admins with {username,email}.

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublicEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_ok(): void
    {
        $this->getJson('/api/status')
            ->assertOk()
            ->assertJson(['status' => 'ok']);
    }

    public function test_testimonials_list(): void
    {
        // Optional seed if your endpoint reads testimonials from users
        User::factory()->create([
            'is_admin' => false,
            'is_activated' => true,
            'testimonial_rate' => 5,
            'testimonial_message' => 'great app',
        ]);

        $this->getJson('/api/testimonials')
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_admins_list(): void
    {
        // seed: 2 admins + 1 normal user
        $a1 = User::factory()->create([
            'is_admin' => true,
            'is_activated' => true,
            'username' => 'admin_one',
            'email' => 'a1@example.com',
        ]);
        $a2 = User::factory()->create([
            'is_admin' => true,
            'is_activated' => true,
            'username' => 'admin_two',
            'email' => 'a2@example.com',
        ]);
        $u  = User::factory()->create([
            'is_admin' => false,
            'is_activated' => true,
            'username' => 'nonadmin',
            'email' => 'u@example.com',
        ]);

        $this->getJson('/api/admins')
            ->assertOk()
            // exact shape: array of objects having username + email
            ->assertJsonStructure(['data' => [['username', 'email']]])
            // must contain our two admins
            ->assertJsonFragment(['username' => 'admin_one', 'email' => 'a1@example.com'])
            ->assertJsonFragment(['username' => 'admin_two', 'email' => 'a2@example.com'])
            // must NOT contain the non-admin
            ->assertJsonMissing(['username' => 'nonadmin', 'email' => 'u@example.com'])
            // since DB is fresh, expect exactly 2 admins
            ->assertJsonCount(2, 'data');
    }
}
