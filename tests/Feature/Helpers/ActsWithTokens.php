<?php
// Small helpers for creating users/admins and issuing Sanctum tokens.
namespace Tests\Feature\Helpers;

use App\Models\User;

trait ActsWithTokens
{
    protected function makeAdmin(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'is_admin'     => true,
            'is_activated' => true,
        ], $overrides));
    }

    protected function makeUser(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'is_admin'     => false,
            'is_activated' => true,
        ], $overrides));
    }

    protected function bearer(User $user): array
    {
        $token = $user->createToken('react-client')->plainTextToken;
        return ['Authorization' => 'Bearer ' . $token];
    }
}
