<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Notifications\WelcomeCredentials;

class AuthController extends Controller
{
    /**
     * POST /api/login
     * Body: { "username": "john", "password": "secret" }
     * 200: { token }
     * 401: invalid credentials / not activated
     * 422: validation errors
     */
    public function login(Request $request)
    {
        $data = Validator::make($request->all(), [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ])->validate();

        // Find by username (change to 'email' if you want email login)
        $user = User::where('username', $data['username'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        if (! $user->is_activated) {
            return response()->json([
                'message' => 'Account is not activated.',
            ], 401);
        }

        // Create a Sanctum personal access token
        $token = $user->createToken('react-client')->plainTextToken;

        return response()->json([
            'token' => $token,
        ], 200);
    }

    public function signup(Request $request)
    {
        // use Validator::make(...)->validate() instead of $request->validate(...)
        $data = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users,email'],
            'gender' => ['nullable', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date'],
            'caretaker_phone_number' => ['nullable', 'string'],
            'caretaker_name' => ['nullable', 'string'],
        ])->validate();

        $email = strtolower(trim($data['email']));
        $base  = Str::of(explode('@', $email)[0])->lower()->slug('_');
        $username = $this->generateUniqueUsername((string) $base);

        $plainPassword = Str::random(12);

        $user = \App\Models\User::create([
            'username' => $username,
            'email' => $email,
            'password' => $plainPassword,   // your User mutator will hash this
            'gender' => $data['gender'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'account_type' => 'normal',
            'caretaker_phone_number' => $data['caretaker_phone_number'] ?? null,
            'caretaker_name' => $data['caretaker_name'] ?? null,
            'is_admin' => false,
            'is_activated' => true,
        ]);

        $emailSent = true;
        try {
            $user->notify(new WelcomeCredentials($username, $plainPassword));
        } catch (\Throwable $e) {
            $emailSent = false;
            Log::warning('Failed to send signup credentials email', [
                'user_id' => $user->id,
                'err'     => $e->getMessage(),
            ]);
        }

        return response()->json([
            'message' => 'User created. Credentials have been emailed.',
            'email_sent' => $emailSent,
        ], 201);
    }

    /**
     * Ensure username uniqueness by appending a short random suffix until free.
     */
    private function generateUniqueUsername(string $base): string
    {
        $candidate = $base;
        while (User::where('username', $candidate)->exists()) {
            $candidate = $base . '_' . Str::lower(Str::random(4));
        }
        return $candidate;
    }
}
