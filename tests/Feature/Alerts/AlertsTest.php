<?php

namespace Tests\Feature\Alerts;

use Tests\TestCase;
use App\Models\User;
use App\Models\Alert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class AlertsTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_alert_requires_auth(): void
    {
        $this->postJson('/api/alert', ['type' => 'Internal', 'message' => 'M'])
            ->assertStatus(401);
    }

    public function test_store_alert_ok(): void
    {
        $u = User::factory()->create(['is_admin' => false, 'is_activated' => true]);

        Sanctum::actingAs($u);

        $this->postJson('/api/alert', [
            'type' => 'Internal',
            'message' => 'Abnormal heart rate detected.',
        ])->assertCreated()
            ->assertJson(['message' => 'Alert added successfully.']);
    }

    public function test_solve_only_owner_can_solve(): void
    {
        $owner = User::factory()->create(['is_admin' => false, 'is_activated' => true]);
        $intr  = User::factory()->create(['is_admin' => false, 'is_activated' => true]);

        $alert = Alert::create([
            'user_id' => $owner->id,
            'type'    => 'Internal',
            'message' => 'Test',
            'status'  => false,
        ]);

        // intruder tries
        Sanctum::actingAs($intr);
        $this->postJson("/api/solve/{$alert->id}")
            ->assertStatus(403);

        // owner solves
        Sanctum::actingAs($owner);
        $this->postJson("/api/solve/{$alert->id}")
            ->assertOk()
            ->assertJson(['message' => 'Alert solved successfully.']);

        // idempotent
        Sanctum::actingAs($owner);
        $this->postJson("/api/solve/{$alert->id}")
            ->assertOk()
            ->assertJson(['message' => 'Alert is already solved.']);
    }

    public function test_get_user_alerts_authz(): void
    {
        $admin = User::factory()->create(['is_admin' => true,  'is_activated' => true]);
        $u1    = User::factory()->create(['is_admin' => false, 'is_activated' => true]);
        $u2    = User::factory()->create(['is_admin' => false, 'is_activated' => true]);

        // non-admin cannot view another user's alerts
        Sanctum::actingAs($u1);
        $this->getJson("/api/alerts/{$u2->id}")
            ->assertStatus(403);

        // admin can
        Sanctum::actingAs($admin);
        $this->getJson("/api/alerts/{$u2->id}")
            ->assertOk()
            ->assertJsonStructure(['data']);
    }
}
