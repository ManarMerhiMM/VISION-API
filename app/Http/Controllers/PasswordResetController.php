<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    // POST /api/forgot-password  { email }
    public function sendLink(Request $request)
    {
        $data = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ])->validate();

        // Do not leak whether the email exists
        if ($user = User::where('email', $data['email'])->first()) {
            Password::sendResetLink(['email' => $user->email]);
        }

        return response()->json([
            'message' => 'If that email exists, a reset link has been sent.',
        ]);
    }

    // POST /api/reset-password  { token, email, password, password_confirmation }
    public function reset(Request $request)
    {
        $data = Validator::make($request->all(), [
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();

        $status = Password::reset(
            [
                'email' => $data['email'],
                'token' => $data['token'],
                'password' => $data['password'],
            ],
            function ($user) use ($data) {
                $user->forceFill([
                    'password' => $data['password'],
                ]);

                $user->save();

                // Revoke all existing API tokens after password change
                if (method_exists($user, 'tokens')) {
                    $user->tokens()->delete();
                }

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successful.'])
            : response()->json(['message' => __($status)], 422);
    }
}
