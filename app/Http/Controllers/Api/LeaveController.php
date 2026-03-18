<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeaveRequest;
use App\Http\Requests\updateLeaveRequest;
use App\Models\Leave;
use App\Models\User;
use App\Models\UserwiseMachine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class LeaveController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (Auth::user()->hasRole('Admin')) {
            $leave = Leave::with('user')->orderBy('id', 'desc')->get();
        } else if(Auth::user()->hasRole('Sales Manager') || Auth::user()->hasRole('Service Manager')){
            $leave = Leave::with('user')->orderBy('id', 'desc')->get();
        } else if(Auth::user()->hasRole('Service Team Leader') || Auth::user()->hasRole('Sales Team Leader')){
            //$leave = Leave::with('user')->orderBy('id', 'desc')->get();
            //$main_machine_type = UserwiseMachine::where('user_id', Auth::user()->id)->value('main_machine_type');
             $main_machine_type = $request->main_machine_type;
            $leave = Leave::with('user')
                ->whereHas('user.userwiseMachines', function ($query) use ($main_machine_type) {
                     $query->where('main_machine_type', $main_machine_type);
                 })->orderBy('id', 'desc')->get();
        }
        else {
            $leave = Leave::with('user')->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        }

        if (!$leave) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Leaves fetched successfully',
            'data' => $leave,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeaveRequest $request)
    {
        // If validation passes, create the leave
        $leave = Leave::create($request->validated());

        // Attendance Approved Push Notification
        if (!empty($leave)) {
            if (!empty($leave->user->device_token)) {
                // Send push notification
                if($leave->is_approved ==  1) {
                    $title = 'Leave Approved';
                    $body = 'Your leave has been approved from '. date('d-m-Y', strtotime($leave->leave_from)) .' to '.date('d-m-Y', strtotime($leave->leave_till));
                } else {
                    $title = 'Leave Rejected';
                    $body = 'Your leave has been rejected from '. date('d-m-Y', strtotime($leave->leave_from)) .' to '.date('d-m-Y', strtotime($leave->leave_till));
                }
                $token = $leave->user->device_token;

                if ($title != "" && $body != "" && $token != "") {
                    // Get the response from the helper function
                    $this->notificationService->sendNotification($title, $body, $token);
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Leave created successfully',
            'data' => $leave,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $leave = Leave::find($id);

        if (!$leave) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Leave fetched successfully',
            'data' => $leave,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updateLeaveRequest $request, string $id)
    {
        $leave = Leave::find($id);

        if (!$leave) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found',
                'data' => []
            ], 404);
        }

        $leave->update($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Leave updated successfully',
            'data' => $leave,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $leave = Leave::find($id);

        if (!$leave) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found',
                'data' => []
            ], 404);
        }

        $leave->delete();

        return response()->json([
            'status' => true,
            'message' => 'Leave deleted successfully',
        ], 200);
    }

    // Approve or Reject leave 
    public function approveLeave(string $id)
    {
        $leave = Leave::with('user')->find($id);

        if (!$leave) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found',
                'data' => []
            ], 404);
        }

        // Toggle the status between '0' (approved) and '1' (not approved)
        $leave->is_approved = $leave->is_approved == '0' ? '1' : '0';
        $leave->save();

        // Attendance Approved Push Notification
        if (!empty($leave)) {
            if (!empty($leave->user->device_token)) {
                // Send push notification
                if($leave->is_approved ==  1) {
                    $title = 'Leave Approved';
                    $body = 'Your leave has been approved from '. date('d-m-Y', strtotime($leave->leave_from)) .' to '.date('d-m-Y', strtotime($leave->leave_till));
                } else {
                    $title = 'Leave Rejected';
                    $body = 'Your leave has been rejected from '. date('d-m-Y', strtotime($leave->leave_from)) .' to '.date('d-m-Y', strtotime($leave->leave_till));
                }
                $token = $leave->user->device_token;

                if ($title != "" && $body != "" && $token != "") {
                    // Get the response from the helper function
                    $this->notificationService->sendNotification($title, $body, $token);
                }
            }
        }


        return response()->json([
            'status' => true,
            'message' => $leave->is_approved == '1' ? 'Leave approved successfully' : 'Leave rejected successfully',
            'data' => $leave,
        ], 200);
    }
}
