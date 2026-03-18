<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendtl;
use App\Models\EmployeeAttendence;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\Models\Location;

class AttendanceDetailController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function store(Request $request)
    {
        
        //dd($request);
        $attendtl = Attendtl::create($request->all());
        $files = $request->file('in_selfie');

        if (isset($files)) {
            //dd('in iff');
            $folderName = 'atdselfie'; // Replace with your folder name
            $path = public_path($folderName);
    
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true); // Create directory with permissions
            }
            //dd($files);
            $filename = rand(0, 999999) . 'in.' . $files->getClientOriginalExtension();
            $files->move(public_path('atdselfie'), $filename);
            $filedata['in_selfie'] = $filename;        
        }
        //dd($adImageArr);
        // update in selfie image
        if (isset($filedata) && !empty($filedata)) {
            $attendtl->update($filedata);
        }
        

        if (!empty($attendtl)) {

            // Send push notification
            if (isset($attendtl->engineer_id) && $attendtl->engineer_id != "") {
                $user = User::find($attendtl->engineer_id);
                $admin = User::find(1);
                // Do not remove admin id 1 from DB

                if (!empty($admin->device_token)) {
                    $title = 'Attendance In';
                    $body = $user['name'] . ' ' . ' is in on ' .  date('h:i A');
                    $token = $admin->device_token;
                    if ($title != "" && $body != "" && $token != "") {
                        // Get the response from the helper function
                        // sendNotification($title, $body, $token);
                        $this->notificationService->sendNotification($title, $body, $token);
                    }
                }
            }

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

    public function update(Request $request)
    {
        $attendtl = Attendtl::where("id", $request->id)->first();
        if (!empty($attendtl)) {
            $attendtl->update($request->all());


            $files = $request->file('out_selfie');

            if (isset($files)) {
                //dd('in iff');
                $folderName = 'atdselfie'; // Replace with your folder name
                $path = public_path($folderName);
        
                if (!File::isDirectory($path)) {
                    File::makeDirectory($path, 0755, true, true); // Create directory with permissions
                }
                //dd($files);
                $filename = rand(0, 999999) . 'out.' . $files->getClientOriginalExtension();
                $files->move(public_path('atdselfie'), $filename);
                $filedata['out_selfie'] = $filename;        
            }
            //dd($adImageArr);
            // update in selfie image
            if (isset($filedata) && !empty($filedata)) {
                $attendtl->update($filedata);
            }

            // Send push notification
            if (isset($attendtl->engineer_id) && $attendtl->engineer_id != "") {
                $user = User::find($attendtl->engineer_id);
                $admin = User::find(1);
                // Do not remove admin id 1 from DB

                if (!empty($admin->device_token)) {
                    $title = 'Attendance Out';
                    $body = $user['name'] . ' ' . ' is out on ' .  date('h:i A');
                    $token = $admin->device_token;
                    if ($title != "" && $body != "" && $token != "") {
                        // Get the response from the helper function
                        // sendNotification($title, $body, $token);
                        $this->notificationService->sendNotification($title, $body, $token);
                    }
                }
            }

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

    public function todayAttendance(Request $request)
    {
        $attendtl = Attendtl::where("engineer_id", $request->engineer_id)->where("in_date", date('Y-m-d'))->first();
        if (!empty($attendtl)) {
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

    // Get the attendance data date wise
    public function engineerAttendanceByDate(Request $request, string $date = null)
    {
        $todayDate = date('Y-m-d'); // Get today's date

        // Fetch all users along with their attendance status for today and closed complaints
        $usersWithAttendance = User::leftJoin('attendtl', function ($join) use ($todayDate) {
            $join->on('users.id', '=', 'attendtl.engineer_id')
                ->where('attendtl.in_date', '=', $todayDate);
        })
            ->leftJoin('leaves', function ($join) use ($todayDate) {
                $join->on('users.id', '=', 'leaves.user_id')
                    ->where('leaves.is_approved', 1)
                    ->whereDate('leaves.leave_from', '<=', $todayDate)
                    ->whereDate('leaves.leave_till', '>=', $todayDate);
            })
            ->select(
                'users.*',
                'attendtl.engineer_id',
                'attendtl.in_time',
                'attendtl.out_time',
                'attendtl.in_date',
                'attendtl.out_date',
                'attendtl.ap',
                'attendtl.late_hrs',
                'attendtl.earligoing_hrs',
                'attendtl.working_hrs',
                'attendtl.pdays',
                'attendtl.in_address',
                'attendtl.out_address',
                DB::raw(
                    'CASE 
                        WHEN attendtl.id IS NOT NULL THEN attendtl.ap
                        WHEN leaves.id IS NOT NULL THEN "L"
                        ELSE "A"
                    END as attendance_status'
                )
            )
            ->where('users.is_active', 1)
            ->orderBy('users.name', 'ASC');

        // Apply the 'attn' filter if set on 'attendance_status'
        if ($request->has('attn') && !empty($request->attn)) {
            $usersWithAttendance->having('attendance_status', $request->attn);
        }

        // Apply the 'role_id' filter if set
        if ($request->has('role_id') && !empty($request->role_id)) {
            $usersWithAttendance->whereHas('roles', function ($q) use ($request) {
                $q->where('id', $request->role_id);
            });
        }

        // Execute the query to get the user attendance data
        $usersWithAttendance = $usersWithAttendance->get();

        // Add complaints count to each user
        foreach ($usersWithAttendance as $user) {

            // Add designation (roles) column
            $user->designation = $user->getRoleNames()->implode(', ');

            // Check for Pending status
            $pendingComplaintsCount = DB::table('complaints')
                ->where('engineer_id', $user->id)
                ->where('status_id', 1)
                ->count();

            // Check for In Progress status
            $inProgressComplaintsCount = DB::table('complaints')
                ->where('engineer_id', $user->id)
                ->where('status_id', 2)
                ->count();

            // Check for closed status
            $closedComplaintsCount = DB::table('complaints')
                ->where('engineer_id', $user->id)
                ->where('status_id', 3)
                ->where('engineer_out_date', $todayDate)
                ->count();

            $user->pending_complaints_count = $pendingComplaintsCount;
            $user->in_progress_complaints_count = $inProgressComplaintsCount;
            $user->closed_complaints_count = $closedComplaintsCount;
        }

        if (!empty($usersWithAttendance)) {
            return response()->json([
                "success" => true,
                "message" => "Attendance fetched successfully",
                "data" => $usersWithAttendance,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "No attendance found for the given date",
                "data" => [], // Return an empty array instead of an empty object
            ]);
        }


        // $data['title'] = 'Today Report';

        // $complaints = User::with('attendtl', 'complaints')
        //     ->where('is_active', 1)
        //     ->whereHas('roles', function ($query) {
        //         $query->whereIn('name', ['Engineer', 'Sales']);
        //     })
        //     ->withCount([
        //         'complaints as complaints_status_1_count' => function ($query) {
        //             $query->where('status_id', 1)
        //                 ->where('date', '<=', date('Y-m-d'));
        //         },
        //         'complaints as complaints_status_2_count' => function ($query) {
        //             $query->where('status_id', 2)
        //                 ->where('date', '<=', date('Y-m-d'));
        //         },
        //         'complaints as complaints_status_3_count' => function ($query) {
        //             $query->where('status_id', 3)
        //                 ->where('engineer_out_date', date('Y-m-d'));
        //         }
        //     ])
        //     ->selectRaw("users.*, 
        //                             CASE 
        //                                 WHEN EXISTS (
        //                                     SELECT 1 
        //                                     FROM leaves 
        //                                     WHERE leaves.user_id = users.id 
        //                                     AND leaves.leave_from <= ? 
        //                                     AND leaves.leave_till >= ? 
        //                                     AND leaves.is_approved = 1
        //                                 ) THEN 'L' 
        //                                 ELSE COALESCE((SELECT attendtl.ap FROM attendtl WHERE attendtl.engineer_id = users.id LIMIT 1), 'A') 
        //                             END as ap", [date('Y-m-d'), date('Y-m-d')]);

        // if (isset($request->attn)) {
        //     $complaints->whereHas('attendtl', function ($q) use ($request) {
        //         $q->where('ap', $request->attn);
        //     });
        // }

        // if (isset($request->role_id)) {
        //     $complaints->whereHas('roles', function ($q) use ($request) {
        //         $q->where('id', $request->role_id);
        //     });
        // }

        // $complaints = $complaints->get();

        // if (!empty($complaints)) {
        //     return response()->json([
        //         "success" => true,
        //         "message" => "Attendance fetched successfully",
        //         "data" => $complaints,
        //     ], 200);
        // } else {
        //     return response()->json([
        //         "success" => false,
        //         "message" => "No attendance found for the given date",
        //         "data" => [], // Return an empty array instead of an empty object
        //     ]);
        // }
    }

    // Get the attendance data date wise
    public function servicetlAttendanceByDate(Request $request, string $date = null)
    {
        $todayDate = date('Y-m-d'); // Get today's date

        // Fetch all users along with their attendance status for today and closed complaints
        $main_machine_type = $request->main_machine_type;
        $usersWithAttendance = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Service Team Leader', 'Engineer']); // show only engineers to managers
                    })
                ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                $query->where('main_machine_type', $main_machine_type);
                })->leftJoin('attendtl', function ($join) use ($todayDate) {
            $join->on('users.id', '=', 'attendtl.engineer_id')
                ->where('attendtl.in_date', '=', $todayDate);
        })
            ->leftJoin('leaves', function ($join) use ($todayDate) {
                $join->on('users.id', '=', 'leaves.user_id')
                    ->where('leaves.is_approved', 1)
                    ->whereDate('leaves.leave_from', '<=', $todayDate)
                    ->whereDate('leaves.leave_till', '>=', $todayDate);
            })
            ->select(
                'users.*',
                'attendtl.engineer_id',
                'attendtl.in_time',
                'attendtl.out_time',
                'attendtl.in_date',
                'attendtl.out_date',
                'attendtl.ap',
                'attendtl.late_hrs',
                'attendtl.earligoing_hrs',
                'attendtl.working_hrs',
                'attendtl.pdays',
                'attendtl.in_address',
                'attendtl.out_address',
                DB::raw(
                    'CASE 
                        WHEN attendtl.id IS NOT NULL THEN attendtl.ap
                        WHEN leaves.id IS NOT NULL THEN "L"
                        ELSE "A"
                    END as attendance_status'
                )
            )
            ->where('users.is_active', 1)
            ->orderBy('users.name', 'ASC');

        // Apply the 'attn' filter if set on 'attendance_status'
        if ($request->has('attn') && !empty($request->attn)) {
            $usersWithAttendance->having('attendance_status', $request->attn);
        }

        // Apply the 'role_id' filter if set
        if ($request->has('role_id') && !empty($request->role_id)) {
            $usersWithAttendance->whereHas('roles', function ($q) use ($request) {
                $q->where('id', $request->role_id);
            });
        }

        // Execute the query to get the user attendance data
        $usersWithAttendance = $usersWithAttendance->get();

        // Add complaints count to each user
        foreach ($usersWithAttendance as $user) {

            // Add designation (roles) column
            $user->designation = $user->getRoleNames()->implode(', ');

            // Check for Pending status
            $pendingComplaintsCount = DB::table('complaints')
                ->where('engineer_id', $user->id)
                ->where('status_id', 1)
                ->count();

            // Check for In Progress status
            $inProgressComplaintsCount = DB::table('complaints')
                ->where('engineer_id', $user->id)
                ->where('status_id', 2)
                ->count();

            // Check for closed status
            $closedComplaintsCount = DB::table('complaints')
                ->where('engineer_id', $user->id)
                ->where('status_id', 3)
                ->where('engineer_out_date', $todayDate)
                ->count();

            $user->pending_complaints_count = $pendingComplaintsCount;
            $user->in_progress_complaints_count = $inProgressComplaintsCount;
            $user->closed_complaints_count = $closedComplaintsCount;
        }

        if (!empty($usersWithAttendance)) {
            return response()->json([
                "success" => true,
                "message" => "Attendance fetched successfully",
                "data" => $usersWithAttendance,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "No attendance found for the given date",
                "data" => [], // Return an empty array instead of an empty object
            ]);
        }


        // $data['title'] = 'Today Report';

        // $complaints = User::with('attendtl', 'complaints')
        //     ->where('is_active', 1)
        //     ->whereHas('roles', function ($query) {
        //         $query->whereIn('name', ['Engineer', 'Sales']);
        //     })
        //     ->withCount([
        //         'complaints as complaints_status_1_count' => function ($query) {
        //             $query->where('status_id', 1)
        //                 ->where('date', '<=', date('Y-m-d'));
        //         },
        //         'complaints as complaints_status_2_count' => function ($query) {
        //             $query->where('status_id', 2)
        //                 ->where('date', '<=', date('Y-m-d'));
        //         },
        //         'complaints as complaints_status_3_count' => function ($query) {
        //             $query->where('status_id', 3)
        //                 ->where('engineer_out_date', date('Y-m-d'));
        //         }
        //     ])
        //     ->selectRaw("users.*, 
        //                             CASE 
        //                                 WHEN EXISTS (
        //                                     SELECT 1 
        //                                     FROM leaves 
        //                                     WHERE leaves.user_id = users.id 
        //                                     AND leaves.leave_from <= ? 
        //                                     AND leaves.leave_till >= ? 
        //                                     AND leaves.is_approved = 1
        //                                 ) THEN 'L' 
        //                                 ELSE COALESCE((SELECT attendtl.ap FROM attendtl WHERE attendtl.engineer_id = users.id LIMIT 1), 'A') 
        //                             END as ap", [date('Y-m-d'), date('Y-m-d')]);

        // if (isset($request->attn)) {
        //     $complaints->whereHas('attendtl', function ($q) use ($request) {
        //         $q->where('ap', $request->attn);
        //     });
        // }

        // if (isset($request->role_id)) {
        //     $complaints->whereHas('roles', function ($q) use ($request) {
        //         $q->where('id', $request->role_id);
        //     });
        // }

        // $complaints = $complaints->get();

        // if (!empty($complaints)) {
        //     return response()->json([
        //         "success" => true,
        //         "message" => "Attendance fetched successfully",
        //         "data" => $complaints,
        //     ], 200);
        // } else {
        //     return response()->json([
        //         "success" => false,
        //         "message" => "No attendance found for the given date",
        //         "data" => [], // Return an empty array instead of an empty object
        //     ]);
        // }
    }

    // Update attendance
    public function updateAttendance(Request $request)
    {
        $attendtl = Attendtl::where('id', $request->id)->first();

        if (!empty($attendtl)) {
            $attendtl->update($request->all());
            return response()->json([
                "success" => true,
                "message" => "Attendance updated successfully",
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

    // Half day leave
    public function attendanceHalfDay(Request $request)
    {
        // Find the attendance record
        $attendence = Attendtl::where(['engineer_id' => $request->id, 'in_date' => date('Y-m-d')])->first();
        // dd($attendence);
        // If attendance does not exist, return an error response
        if (empty($attendence)) {
            return response()->json([
                "success" => false,
                "message" => "Attendance record not found",
                "data" => (object)[],
            ], 404);
        }

        // If attendance exists, update the record
        $attendence->update($request->all());

        // Return a success response with the updated data
        return response()->json([
            "success" => true,
            "message" => "Attendance updated successfully",
            "data" => $attendence->fresh(), // Use fresh() to get the updated record
        ], 200);
    }


    // Get attendance report
    public function attendanceReport(Request $request)
    {
        // $attendance = User::with('isPresent')->get()->toArray();
        // dd($attendance);

        $data['title'] = 'Today Report';

        $complaints = User::with('attendtl', 'complaints')
            ->where('is_active', 1)
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['Engineer', 'Sales']);
            })
            ->withCount([
                'complaints as complaints_status_1_count' => function ($query) {
                    $query->where('status_id', 1)
                        ->where('date', '<=', date('Y-m-d'));
                },
                'complaints as complaints_status_2_count' => function ($query) {
                    $query->where('status_id', 2)
                        ->where('date', '<=', date('Y-m-d'));
                },
                'complaints as complaints_status_3_count' => function ($query) {
                    $query->where('status_id', 3)
                        ->where('engineer_out_date', date('Y-m-d'));
                }
            ])
            ->selectRaw("users.*, 
                                    CASE 
                                        WHEN EXISTS (
                                            SELECT 1 
                                            FROM leaves 
                                            WHERE leaves.user_id = users.id 
                                            AND leaves.leave_from <= ? 
                                            AND leaves.leave_till >= ? 
                                            AND leaves.is_approved = 1
                                        ) THEN 'L' 
                                        ELSE COALESCE((SELECT attendtl.ap FROM attendtl WHERE attendtl.engineer_id = users.id LIMIT 1), 'A') 
                                    END as ap", [date('Y-m-d'), date('Y-m-d')]);

        if (isset($request->attn)) {
            $complaints->whereHas('attendtl', function ($q) use ($request) {
                $q->where('ap', $request->attn);
            });
        }

        if (isset($request->role_id)) {
            $complaints->whereHas('roles', function ($q) use ($request) {
                $q->where('id', $request->role_id);
            });
        }

        $complaints = $complaints->get();

        if (!empty($complaints)) {
            return response()->json([
                "success" => true,
                "message" => "Attendance fetched successfully",
                "data" => $complaints,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "No attendance found for the given date",
                "data" => [], // Return an empty array instead of an empty object
            ]);
        }
    }

    // Get attendance report
    public function attendanceonMap(Request $request)
    {
        // $attendance = User::with('isPresent')->get()->toArray();
        // dd($attendance);

        //$data['title'] = 'Today Staff on Map';
        $main_machine_type = $request->main_machine_type;
        $todayDate = date('Y-m-d'); // Get today's date

        // Fetch all users along with their attendance status for today and closed complaints
        // $usersWithAttendanceonMap = User::join('attendtl', function ($join) use ($todayDate) {
        //     $join->on('users.id', '=', 'attendtl.engineer_id')
        //         ->where('attendtl.ap', '=', 'P')
        //         ->where('attendtl.in_date', '=', $todayDate);
        // })
            
        //     ->where('users.is_active', 1)
        //     ->orderBy('users.name', 'ASC');

        // New present engineers with live location
        $latestLocationSub = DB::table('locations as l1')
        ->select('l1.user_id', 'l1.lat', 'l1.lng')
        ->join(DB::raw('(
            SELECT user_id, MAX(id) as max_id
            FROM locations
            GROUP BY user_id
        ) as l2'), function ($join) {
            $join->on('l1.user_id', '=', 'l2.user_id')
                ->on('l1.id', '=', 'l2.max_id');
        });

        $usersWithAttendanceonMap = Attendtl::where('in_date', date('Y-m-d'))
        ->where('ap', 'P')
        ->join('users', 'attendtl.engineer_id', '=', 'users.id')
        ->join('userwise_machines', 'users.id', '=', 'userwise_machines.user_id')
        ->joinSub($latestLocationSub, 'latest_location', function ($join) {
            $join->on('users.id', '=', 'latest_location.user_id');
        })
        ->with(['users.roles'])
        ->where('userwise_machines.main_machine_type', $main_machine_type)
        ->select(
            'attendtl.in_selfie',
            'users.name as engineer_name',
            'latest_location.lat',
            'latest_location.lng'
        )->get();


        // Apply the 'role_id' filter if set
        // Removed to show all employee on the map like all engineers and sales executives
        // if ($request->has('role_id') && !empty($request->role_id)) {
        //     $usersWithAttendanceonMap->whereHas('roles', function ($q) use ($request) {
        //         $q->where('id', $request->role_id);
        //     });
        // }

        // Apply the 'main_machine_type' filter if set
        // if ($request->has('main_machine_type') && !empty($request->main_machine_type)) {
        //     $usersWithAttendanceonMap->where('users.main_machine_type', $request->main_machine_type);
        // }

        // Execute the query to get the user attendance data
        //$usersWithAttendanceonMap = $usersWithAttendanceonMap->get();

        if (!empty($usersWithAttendanceonMap)) {
            return response()->json([
                "success" => true,
                "message" => "Attendance on Map fetched successfully",
                "data" => $usersWithAttendanceonMap,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "No attendance found for the given date",
                "data" => [], // Return an empty array instead of an empty object
            ]);
        }
    }

    public function apDetails(Request $request)
    {
        $usersWithAttendance = Attendtl::query();

        if (!empty($request->engineer_id)) {
            $usersWithAttendance->where('engineer_id', $request->engineer_id);
        } else {
            $usersWithAttendance->where('engineer_id', auth()->user()->id);
        }

        if (!empty($request->month)) {
            $month = $request->month;
            $currentYear = Carbon::now()->year;
            $startDate = Carbon::createFromDate($currentYear, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($currentYear, $month, 1)->endOfMonth();
        } else {
            $currentDate = Carbon::now();
            $startDate = $currentDate->copy()->startOfMonth();
            $endDate = $currentDate->copy()->endOfMonth();
        }

        // Add pagination
        //$usersWithAttendance = $usersWithAttendance->with(['users','locations'])
        $usersWithAttendance = $usersWithAttendance->with(['users','locations'])
            ->whereBetween('in_date', [$startDate, $endDate])
            ->orderBy('id', 'DESC')
            ->paginate(10); // You can adjust the number of items per page

        if ($usersWithAttendance->count() > 0) {
            return response()->json([
                "success" => true,
                "message" => "Attendance details fetched successfully",
                "data" => $usersWithAttendance,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Attendance details not found",
                "data" => [],
            ], 200);
        }
    }

    public function apDetailsReport(Request $request)
    {
        $usersWithAttendance = Attendtl::query();

        if (!empty($request->engineer_id)) {
            $usersWithAttendance->where('engineer_id', $request->engineer_id);
        } else {
            $usersWithAttendance->where('engineer_id', auth()->user()->id);
        }

        if (!empty($request->month)) {
            $month = $request->month;
            $currentYear = Carbon::now()->year;
            $startDate = Carbon::createFromDate($currentYear, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($currentYear, $month, 1)->endOfMonth();
        } else {
            $currentDate = Carbon::now();
            $startDate = $currentDate->copy()->startOfMonth();
            $endDate = $currentDate->copy()->endOfMonth();
        }

        $usersWithAttendance = $usersWithAttendance->with('users')
            ->whereBetween('in_date', [$startDate, $endDate])->get();

        if ($usersWithAttendance->count() > 0) {
            return response()->json([
                "success" => true,
                "message" => "Attendance details fetched successfully",
                "data" => $usersWithAttendance,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Attendance details not found",
                "data" => [],
            ], 200);
        }
    }
}
