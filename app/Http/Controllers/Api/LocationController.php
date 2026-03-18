<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Events\LocationUpdated;

class LocationController extends Controller
{
    public function store(Request $request)
    {
       // dd('in location controller');
      
            // $validated = $request->validate([
            //     'user_id' => 'nullable|integer',
            //     'lat' => 'required|numeric',
            //     'lng' => 'required|numeric',
            // ]);

            // Store location
            $location = Location::create([
                'user_id' => $request->user_id,
                'lat' => $request->latitude,
                'lng' => $request->longitude
            ]);

            // Broadcast location update
            event(new LocationUpdated(
                $request->user_id,
                $request->latitude,
                $request->longitude
            ));
            if(!empty($location))
            {
            return response()->json([
                'status' => 'Location stored and broadcasted',
                'data' => $location
            ]);
            } else {
                return response()->json([
                    'status' => 'Location Not stored and broadcasted',
                    'data' => 'Location Not stored and broadcasted'
                ]);
            }
       
    }

    public function getEngLivelocation(Request $request)
    {
        $eid = $request->engineer_id;
        $location = Location::where('user_id', $eid)->latest()->first();    
        if(!empty($location))
            {
            return response()->json([
                'status' => 'Location fetched',
                'success' => true,
                'data' => $location
            ]);
            } else {
                return response()->json([
                    'status' => 'Location Not fetched',
                    'success' => false,
                    'data' => 'Location Not fetched'
                ]);
            }
    }    
}