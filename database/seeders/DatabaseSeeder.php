<?php
// Seeds core data: 5 admins (UserSeeder), +30 non-admin users, +1,000,000 records, +300 alerts.
// Optimized for memory and performance — safe for large dataset seeding.

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Record;
use App\Models\Alert;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- 1) Seed the VISION team admin accounts ---
        $this->call(UserSeeder::class);

        // --- 2) Create 30 regular users (non-admin) ---
        User::factory(30)->create();

        // Preload non-admin user IDs once for performance
        $nonAdminIds = User::where('is_admin', false)->pluck('id')->all();

        // Disable query log to save memory
        DB::disableQueryLog();

        // --- 3) Create 1,000,000 records in batches ---
        $totalRecords = 1_000_000;
        $batchSize = 10_000; // tune between 2k–20k depending on system memory

        for ($i = 0; $i < $totalRecords; $i += $batchSize) {
            $remaining = min($batchSize, $totalRecords - $i);

            Record::withoutEvents(function () use ($remaining, $nonAdminIds) {
                Record::factory()
                    ->count($remaining)
                    ->state(fn () => [
                        'user_id' => $nonAdminIds[array_rand($nonAdminIds)],
                    ])
                    ->createQuietly(); // skips model events for speed
            });

            echo "Seeded " . ($i + $remaining) . " / {$totalRecords} records\n";
        }

        // --- 4) Create 300 alerts (uses non-admins as well) ---
        Alert::withoutEvents(function () use ($nonAdminIds) {
            Alert::factory(300)
                ->state(fn () => [
                    'user_id' => $nonAdminIds[array_rand($nonAdminIds)],
                ])
                ->createQuietly();
        });

        echo "✅ Seeding complete: 5 admins, 30 users, 1,000,000 records, 300 alerts.\n";
    }
}
