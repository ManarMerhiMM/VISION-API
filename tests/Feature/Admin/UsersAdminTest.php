<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class UsersAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_list_users(): void
    {
        $user = User::factory()->create(['is_admin' => false, 'is_activated' => true]);

        Sanctum::actingAs($user);

        $this->getJson('/api/users')
            ->assertStatus(403);
    }

    public function test_admin_lists_all_but_self(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'is_activated' => true]);
        $other = User::factory()->create(['is_admin' => false, 'is_activated' => true]);

        Sanctum::actingAs($admin);

        $this->getJson('/api/users')
            ->assertOk()
            ->assertJsonMissing(['id' => $admin->id])
            ->assertJsonFragment(['id' => $other->id]);
    }

    public function test_user_can_only_deactivate_self(): void
    {
        $u1 = User::factory()->create(['is_admin' => false, 'is_activated' => true]);
        $u2 = User::factory()->create(['is_admin' => false, 'is_activated' => true]);

        // user1 tries to deactivate user2 -> forbidden
        Sanctum::actingAs($u1);
        $this->postJson("/api/deactivate/{$u2->id}")
            ->assertStatus(403);

        // user1 deactivates self -> ok
        Sanctum::actingAs($u1);
        $this->postJson("/api/deactivate/{$u1->id}")
            ->assertOk()
            ->assertJson(['message' => 'User deactivated successfully.']);
    }

    public function test_only_admin_can_activate_anyone(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'is_activated' => true]);
        $user  = User::factory()->create(['is_admin' => false, 'is_activated' => false]);

        // non-admin cannot activate
        Sanctum::actingAs($user);
        $this->postJson("/api/activate/{$user->id}")
            ->assertStatus(403);

        // admin can activate
        Sanctum::actingAs($admin);
        $this->postJson("/api/activate/{$user->id}")
            ->assertOk()
            ->assertJson(['message' => 'User activated successfully.']);
    }

    public function test_admin_can_deactivate_non_admin(): void
    {
        $admin    = User::factory()->create(['is_admin' => true,  'is_activated' => true]);
        $nonAdmin = User::factory()->create(['is_admin' => false, 'is_activated' => true]);

        Sanctum::actingAs($admin);

        $this->postJson("/api/deactivate/{$nonAdmin->id}")
            ->assertOk()
            ->assertJson(['message' => 'User deactivated successfully.']);

        $this->assertFalse($nonAdmin->fresh()->is_activated);
    }

    public function test_admin_cannot_deactivate_other_admin(): void
    {
        $adminA = User::factory()->create(['is_admin' => true, 'is_activated' => true]);
        $adminB = User::factory()->create(['is_admin' => true, 'is_activated' => true]);

        Sanctum::actingAs($adminA);

        $this->postJson("/api/deactivate/{$adminB->id}")
            ->assertStatus(403); // or 422/401 depending on your controller’s response

        // ensure still activated
        $this->assertTrue($adminB->fresh()->is_activated);
    }
}
