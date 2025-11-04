<?php
// app/Http/Controllers/AlertController.php
// Creates a new alert linked to the logged-in user with strict validation.

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \App\Models\User;

class AlertController extends Controller
{
    public function store(Request $request)
    {
        $data = Validator::make($request->all(), [
            'type'    => ['required', 'in:Internal,External'],
            'message' => ['required', 'string', 'max:10000'],
            'status'  => ['sometimes', 'boolean'],
        ])->validate();

        Alert::create([
            'user_id' => $request->user()->id,
            'type'    => $data['type'],
            'message' => $data['message'],
            'status'  => $data['status'] ?? false,
            // 'raised_at' omitted so DB default (CURRENT_TIMESTAMP) is used
        ]);

        return response()->json([
            'message' => "Alert added successfully."
        ], 201);
    }

    public function solve(Request $request, Alert $alert)
    {
        $authUser = $request->user();

        // Only the owner of the alert can solve it
        if ($authUser->id !== $alert->user_id) {
            return response()->json([
                'message' => 'You are not authorized to solve this alert.'
            ], 403);
        }

        // Already solved
        if ($alert->status) {
            return response()->json([
                'message' => 'Alert is already solved.'
            ], 200);
        }

        // Solve the alert
        $alert->update(['status' => true]);

        return response()->json([
            'message' => 'Alert solved successfully.'
        ], 200);
    }

    public function getUserAlerts(Request $request, User $user)
    {
        $authUser = $request->user();

        // Authorization check: must be admin OR same user
        if (!$authUser->is_admin && $authUser->id !== $user->id) {
            return response()->json([
                'message' => 'You are not authorized to view these alerts.'
            ], 403);
        }

        // Retrieve alerts for that user (latest first)
        $alerts = Alert::where('user_id', $user->id)
            ->select('id', 'type', 'message', 'status', 'raised_at')
            ->orderByDesc('raised_at')
            ->get();

        return response()->json([
            'data' => $alerts
        ], 200);
    }
}
