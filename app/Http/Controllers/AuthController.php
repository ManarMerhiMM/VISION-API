<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
}
