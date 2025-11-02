<?php
// Returns all users with at least a testimonial rate, including username, rate, and optional message.

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
}