<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendtl;

class AttendanceDetailController extends Controller
{
    public function store(Request $request) {
        $attendtl = Attendtl::create($request->all());
        if(!empty($attendtl)) {
            return response()->json([
                "success" => true,
                "message" => "Attendance created successfully",
                "data" => $attendtl,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong",
                "data" => (object)[],
            ]);
        }
    }

    public function update(Request $request) {
        $attendtl = Attendtl::where("id", $request->id)->first();
        if(!empty($attendtl)) {
            $attendtl->update($request->all());
            return response()->json([
                "success" => true,
                "message" => "Attendance update successfully",
                "data" => $attendtl,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong",
                "data" => (object)[],
            ]);
        }
    }

    public function todayAttendance(Request $request) {
        $attendtl = Attendtl::where("engineer_id", $request->engineer_id)->where("in_date",date('Y-m-d'))->first();
        if(!empty($attendtl)) {
            $attendtl->update($request->all());
            return response()->json([
                "success" => true,
                "message" => "Attendance fetched successfully",
                "data" => $attendtl,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong",
                "data" => (object)[],
            ]);
        }

    }
}
