<?php

namespace Tests\Feature\Records;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class RecordsTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_record_requires_auth(): void
    {
        $this->postJson('/api/record', [
            'spo2' => 98.5,
            'heart_rate' => 88,
            'galvanic_skin_resistance' => 4.321,
            'relative_humidity' => 55.4,
        ])->assertStatus(401);
    }

    public function test_store_record_ok(): void
    {
        $u = User::factory()->create(['is_admin' => false, 'is_activated' => true]);

        Sanctum::actingAs($u);

        $this->postJson('/api/record', [
            'spo2' => 97.25,
            'heart_rate' => 72,
            'galvanic_skin_resistance' => 3.333,
            'relative_humidity' => 45.67,
        ])->assertCreated()
            ->assertJson(['message' => 'Record added successfully.']);
    }

    public function test_get_user_records_authz(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'is_activated' => true]);
        $u1    = User::factory()->create(['is_admin' => false, 'is_activated' => true]);
        $u2    = User::factory()->create(['is_admin' => false, 'is_activated' => true]);

        // non-admin cannot view another user's records
        Sanctum::actingAs($u1);
        $this->getJson("/api/records/{$u2->id}")
            ->assertStatus(403);

        // admin can
        Sanctum::actingAs($admin);
        $this->getJson("/api/records/{$u2->id}")
            ->assertOk()
            ->assertJsonStructure(['data']);
    }
}
