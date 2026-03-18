<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\LeaveRequest;
use Flasher\Toastr\Laravel\Facade\Toastr;
use Carbon\Carbon;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['title'] = 'Leave List';
    if ($request->ajax()) {

        $leaves = Leave::with('user')->latest();

        return DataTables::of($leaves)
            ->addIndexColumn()
            ->addColumn('date', fn ($row) =>
                date('d-m-Y', strtotime($row->date_time))
            )
            // ->addColumn('staff', fn ($row) =>
            //     $row->user->name ?? '-'
            // )
            ->addColumn('approved', fn ($row) =>
                $row->is_approved
                    ? '<span class="text-success">Yes</span>'
                    : '<span class="text-danger">No</span>'
            )
            ->addColumn('action', function ($leave) {
                return "
                    <div class='btn-group m-1'>
                        <a class='btn btn-sm btn-primary' href='".route('leave.edit', $leave->id)."'>
                            <i class='fa fa-edit'></i>
                        </a>
                        <a class='btn btn-sm btn-danger' href='javascript:void(0)'
                            onclick='deleteLeave({$leave->id})'>
                            <i class='fa fa-trash'></i>
                        </a>
                    </div>
                ";
            })

            ->rawColumns(['approved','action'])
            ->make(true);
    }

        return view('leave.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Create Leave';
        $users = User::where('is_active', 1)->latest()->get();
        $data['users'] = $users;
        return view('leave.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeaveRequest $request)
    {
        if ($request->validated()) {
            $request['leave_from'] = isset($request->leave_from) ? date('Y-m-d', strtotime($request->leave_from)) : null;
            $request['leave_till'] = isset($request->leave_till) ? date('Y-m-d', strtotime($request->leave_till)) : null;
            $today = Carbon::now()->format('Y-m-d H:i:s');
            $request['date_time'] = $today;
            $request['firm_id'] = 1;
            $request['year_id'] = 1;
                Leave::create($request->all());
                return redirect()->route('leave.index')->with('success', 'Leave created successfully');    
        } else {
            return redirect()->back()->withErrors($request->errors())->withInput();
        }
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leave $leave)
    {
        $data['title'] = 'Edit Leave';
        $data['leave'] = $leave;
        $users = User::where('is_active', 1)->latest()->get();
        $data['users'] = $users;
        return view('leave.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LeaveRequest $request, Leave $leave)
    {
        if ($request->validated()) {
            $request['leave_from'] = isset($request->leave_from) ? date('Y-m-d', strtotime($request->leave_from)) : null;
            $request['leave_till'] = isset($request->leave_till) ? date('Y-m-d', strtotime($request->leave_till)) : null;
            $today = Carbon::now()->format('Y-m-d H:i:s');
            //$request['date_time'] = $today;
            $request['firm_id'] = 1;
            $request['year_id'] = 1;

            $leave->update($request->all());

            return redirect()->route('leave.index')->with('success', 'Leave updated successfully');
        } else {
            return redirect()->back()->withErrors($request->errors())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        $leave->delete();
        Toastr::success('Leave deleted successfully');
        return response()->json(['success' => 1, 'message' => 'Leave deleted successfully']);
    }
}
