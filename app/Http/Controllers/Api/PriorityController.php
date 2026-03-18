<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Priority;
use Illuminate\Http\Request;

class PriorityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (isset($request->is_priority) && $request->is_priority == 1) {
            $priorities = Priority::where('is_priority', 1)->get();
        } else if (isset($request->is_status) && $request->is_status == 1) {
            $priorities = Priority::where('is_status', 1)->get();
        } else {
            $priorities = Priority::all();
        }

        if ($priorities->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No record found',
                'data' => [],
                'total' => 0,
            ], 404); // Return 404 for not found
        }

        return response()->json([
            'success' => true,
            'message' => 'Data fetched successfully',
            'data' => $priorities,
            'total' => $priorities->count(),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
