<?php
// app/Http/Controllers/RecordController.php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \App\Models\User;

class RecordController extends Controller
{
    public function store(Request $request)
    {
        $data = Validator::make($request->all(), [
            'spo2'                     => ['required', 'numeric', 'between:0,999.99'],     // decimal(5,2)
            'heart_rate'               => ['required', 'integer', 'between:0,65535'],      // unsignedSmallInteger
            'galvanic_skin_resistance' => ['required', 'numeric', 'between:0,99999.999'],  // decimal(8,3)
            'relative_humidity'        => ['required', 'numeric', 'between:0,999.99'],     // decimal(5,2)
        ])->validate();


        Record::create([
            'user_id'                  => $request->user()->id,
            'spo2'                     => $data['spo2'],
            'heart_rate'               => $data['heart_rate'],
            'galvanic_skin_resistance' => $data['galvanic_skin_resistance'],
            'relative_humidity'        => $data['relative_humidity'],
        ]);

        return response()->json([
            'message' => 'Record added successfully.'
        ], 201);
    }

    public function getUserRecords(Request $request, User $user)
    {
        $authUser = $request->user();

        // Authorization check: must be admin OR same user
        if (!$authUser->is_admin && $authUser->id !== $user->id) {
            return response()->json([
                'message' => 'You are not authorized to view these records.'
            ], 403);
        }

        // Retrieve all records for that user
        $records = Record::where('user_id', $user->id)
            ->select(
                'id',
                'spo2',
                'heart_rate',
                'galvanic_skin_resistance',
                'relative_humidity',
                'recorded_at'
            )
            ->orderByDesc('recorded_at')
            ->get();

        return response()->json([
            'data' => $records
        ], 200);
    }
}
