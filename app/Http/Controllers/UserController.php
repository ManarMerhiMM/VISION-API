<?php
// Returns all users with at least a testimonial rate, including username, rate, and optional message.

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function testimonials(Request $request)
    {
        $testimonials = User::query()
            ->whereNotNull('testimonial_rate')       // at least a rate
            ->select('username', 'testimonial_rate', 'testimonial_message', 'created_at')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($u) {
                return [
                    'username' => $u->username,
                    'testimonial_rate' => (int) $u->testimonial_rate,
                    // include message only when present
                    'testimonial_message' => $u->testimonial_message,
                    'created_at' => $u->created_at->toDateString(), // or ->toDateTimeString()
                ];
            });

        return response()->json(['data' => $testimonials]);
    }


    public function admins(Request $request)
    {
        $admins = User::query()
            ->where('is_admin', true)
            ->select('username', 'email')
            ->orderBy('username')
            ->get();

        return response()->json([
            'data' => $admins
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = Validator::make($request->all(), [
            'current_password' => ['required', 'string', function ($attr, $value, $fail) use ($user) {
                if (! Hash::check($value, $user->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
            'gender' => ['nullable', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date'],
            'account_type' => ['nullable', 'in:normal,visually impaired'],
            'caretaker_phone_number' => ['nullable', 'string'],
            'caretaker_name' => ['nullable', 'string'],
        ])->validate();

        // normalize "" → null (and trim)
        foreach ($data as $k => $v) {
            if (is_string($v)) $v = trim($v);
            if ($v === '') $v = null;
            $data[$k] = $v;
        }
        // never clear password if null
        if (array_key_exists('password', $data) && $data['password'] === null) {
            unset($data['password']);
        }
        // drop current_password (not a column)
        unset($data['current_password']);

        $user->fill($data);     // mutator will hash new password if provided
        $user->save();

        return response()->json(['message' => 'Profile updated successfully.']);
    }


    public function upsertTestimonial(Request $request)
    {
        $user = $request->user();

        // Only touch fields that are sent; empty strings mean "clear it" (null).
        $data = Validator::make($request->all(), [
            'testimonial_rate'     => ['nullable', 'integer', 'min:1', 'max:5'],
            'testimonial_message'  => ['nullable', 'string', 'max:500'],
        ])->validate();


        // Normalize: trim strings, then "" -> null
        foreach ($data as $k => $v) {
            if (is_string($v)) $v = trim($v);
            if ($v === '')     $v = null;
            $data[$k] = $v;
        }

        // Business rule: message requires a (non-null) rate.
        // - If client is clearing message (null), allow with or without rate.
        // - If message is non-null: rate must be present and non-null.
        if (array_key_exists('testimonial_message', $data) && $data['testimonial_message'] !== null) {
            if (!array_key_exists('testimonial_rate', $data) || $data['testimonial_rate'] === null) {
                return response()->json([
                    'message' => 'Either send a rate or both a rate and a message or neither (for removal)'
                ], 422);
            }
        }


        $user->fill($data)->save();

        return response()->json([
            'message' => 'Testimonial saved.',
        ], 200);
    }

    public function deactivate(Request $request, User $user)
    {
        $authUser = $request->user();

        // Authorization check: must be admin OR same user
        if (!$authUser->is_admin && $authUser->id !== $user->id) {
            return response()->json([
                'message' => 'You are not authorized to deactivate this user.'
            ], 403);
        }

        // Prevent admins from deactivating other admins
        if ($user->is_admin && $authUser->id !== $user->id) {
            return response()->json([
                'message' => 'Admins cannot deactivate other admins.'
            ], 403);
        }

        // Already deactivated
        if (!$user->is_activated) {
            return response()->json([
                'message' => 'User is already deactivated.'
            ], 200);
        }

        // Deactivate
        $user->update(['is_activated' => false]);

        return response()->json([
            'message' => 'User deactivated successfully.'
        ], 200);
    }

    public function activate(Request $request, User $user)
    {
        $authUser = $request->user();

        // Only admins can activate anyone (including other admins)
        if (!$authUser->is_admin) {
            return response()->json([
                'message' => 'Only admins can activate users.'
            ], 403);
        }

        // Already active
        if ($user->is_activated) {
            return response()->json([
                'message' => 'User is already active.'
            ], 200);
        }

        // Activate user
        $user->update(['is_activated' => true]);

        return response()->json([
            'message' => 'User activated successfully.'
        ], 200);
    }

    public function index(Request $request)
    {
        $authUser = $request->user();

        // Only admins can view all users
        if (!$authUser->is_admin) {
            return response()->json([
                'message' => 'You are not authorized to view users.'
            ], 403);
        }

        // Retrieve all users except the admin making the request
        $users = User::where('id', '!=', $authUser->id)
            ->select(
                'id',
                'is_admin',
                'username',
                'email',
                'gender',
                'is_activated',
                'date_of_birth',
                'account_type',
                'caretaker_name',
                'caretaker_phone_number',
                'testimonial_rate',
                'testimonial_message',
                'created_at'
            )
            ->get();

        return response()->json([
            'data' => $users
        ], 200);
    }
}
