<?php

namespace App\Http\Controllers;

use App\Models\ContactPerson;
use App\Models\MachineSalesEntry;
use App\Models\Machine;
use App\Models\Owner;
use App\Models\User;
use App\Models\Leave;
use App\Models\Location;
use App\Models\Attendtl;
use App\Models\Salary;
use App\Models\UserwiseMachine;
use App\Models\UsersPayroll;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{

    public function index($id, Request $request)
    {
        
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $data['title'] = $main_machine_name. '-> User List';
        if ($request->ajax()) {

            if(auth()->user()->getRoleNames()->first() == "Admin")
            {
                //$users = User::latest();
                $users = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Service Team Leader', 'Engineer']); // show only engineers to managers
                })
                ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                $query->where('main_machine_type', $main_machine_type);
                })->latest();
                if (isset($request->is_active)) {
                    $users->where('is_active', $request->is_active);
                }
                if (isset($request->role)) {
                    $users->whereHas('roles', function ($query) use ($request) {
                        $query->where('roles.id', $request->role);
                    });
                }
                $users = $users->get();
            }
            if(auth()->user()->getRoleNames()->first() == "Manager")
            {
                //$users = User::latest();
                $users = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Service Team Leader', 'Engineer']); // show only engineers to managers
                })
                ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                $query->where('main_machine_type', $main_machine_type);
                })->latest();
                if (isset($request->is_active)) {
                    $users->where('is_active', $request->is_active);
                }
                if (isset($request->role)) {
                    $users->whereHas('roles', function ($query) use ($request) {
                        $query->where('roles.id', $request->role);
                    }); 
                }
                $users = $users->get();
            }
            if(auth()->user()->getRoleNames()->first() == "Team Leader")
            {
                //$users = User::latest();
                $users = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Engineer']); // show only engineers to managers
                })
                ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                $query->where('main_machine_type', $main_machine_type);
                })->latest();
                if (isset($request->is_active)) {
                    $users->where('is_active', $request->is_active);
                }
                if (isset($request->role)) {
                    $users->whereHas('roles', function ($query) use ($request) {
                        $query->where('roles.id', $request->role);
                    }); 
                }
                $users = $users->get();
            }
            if(auth()->user()->getRoleNames()->first() == "Service Team Leader")
            {
                //$users = User::latest();
                $users = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Engineer']); // show only engineers to managers
                })
                ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                $query->where('main_machine_type', $main_machine_type);
                })->latest();
                if (isset($request->is_active)) {
                    $users->where('is_active', $request->is_active);
                }
                if (isset($request->role)) {
                    $users->whereHas('roles', function ($query) use ($request) {
                        $query->where('roles.id', $request->role);
                    }); 
                }
                $users = $users->get();
            }
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($main_machine_type) {

                    $btn = '<div class="btn-group m-1"><a href="' . route('usersprofile', ['main_machine_type' => $main_machine_type, $row->id]) . '" class="view btn btn-primary btn-sm" data-bs-toggle="tooltip" title="View Profile" data-bs-placement="top" data-bs-original-title="View Profile"><i class="fa fa-eye"></i><a href="' . route('users.edit', ['main_machine_type' => $main_machine_type, $row->id]) . '" class="edit btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Edit Profile" data-bs-placement="top" data-bs-original-title="Edit Profile"><i class="fa fa-edit"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="deleteUser(' . $row->id . ')" class="edit btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></div>';
                    // $btn .= '<a href="' . route('users.assignRoles', ['user' => $row->id]) . '" class="edit btn btn-primary btn-sm"><i class="fa fa-lock"></i></a>';
                    return $btn;
                })
                ->addColumn('roles', function ($row) {
                    return $row->getRoleNames()->implode(', ');
                })
                ->addColumn('inTime', function ($row) {
                    return isset($row->duty_start) ? date("h:i A", strtotime($row->duty_start)) : '-';
                })
                ->addColumn('outTime', function ($row) {
                    return isset($row->duty_end) ? date("h:i A", strtotime($row->duty_end)) : '-';
                })
                ->addColumn('leader', function ($row) {
                    return $row->is_leader == 1 ? 'Yes' : 'No';
                })
                ->rawColumns(['action', 'roles'])
                ->make(true);
        }
        return view('users.index', $data);
    }

    public function salesuserlist($id, Request $request)
    {
        
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $data['title'] = $main_machine_name. '-> User List';
        if ($request->ajax()) {

            if(auth()->user()->getRoleNames()->first() == "Admin")
            {
                //$users = User::latest();
                $users = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Sales Team Leader', 'Sales Person']); // show only engineers to managers
                })
                ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                $query->where('main_machine_type', $main_machine_type);
                })->latest();
                if (isset($request->is_active)) {
                    $users->where('is_active', $request->is_active);
                }
                if (isset($request->role)) {
                    $users->whereHas('roles', function ($query) use ($request) {
                        $query->where('roles.id', $request->role);
                    });
                }
                $users = $users->get();
            }
            if(auth()->user()->getRoleNames()->first() == "Manager")
            {
                //$users = User::latest();
                $users = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Sales Team Leader', 'Sales Person']); // show only engineers to managers
                })
                ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                $query->where('main_machine_type', $main_machine_type);
                })->latest();
                if (isset($request->is_active)) {
                    $users->where('is_active', $request->is_active);
                }
                if (isset($request->role)) {
                    $users->whereHas('roles', function ($query) use ($request) {
                        $query->where('roles.id', $request->role);
                    }); 
                }
                $users = $users->get();
            }
            if(auth()->user()->getRoleNames()->first() == "Team Leader")
            {
                //$users = User::latest();
                $users = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Sales Team Leader']); // show only engineers to managers
                })
                ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                $query->where('main_machine_type', $main_machine_type);
                })->latest();
                if (isset($request->is_active)) {
                    $users->where('is_active', $request->is_active);
                }
                if (isset($request->role)) {
                    $users->whereHas('roles', function ($query) use ($request) {
                        $query->where('roles.id', $request->role);
                    }); 
                }
                $users = $users->get();
            }
            if(auth()->user()->getRoleNames()->first() == "Sales Team Leader")
            {
                //$users = User::latest();
                $users = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Sales Person']); // show only engineers to managers
                })
                ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                $query->where('main_machine_type', $main_machine_type);
                })->latest();
                if (isset($request->is_active)) {
                    $users->where('is_active', $request->is_active);
                }
                if (isset($request->role)) {
                    $users->whereHas('roles', function ($query) use ($request) {
                        $query->where('roles.id', $request->role);
                    }); 
                }
                $users = $users->get();
            }
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($main_machine_type) {

                    $btn = '<div class="btn-group m-1"><a href="' . route('saleusersprofile', ['main_machine_type' => $main_machine_type, $row->id]) . '" class="view btn btn-primary btn-sm" data-bs-toggle="tooltip" title="View Profile" data-bs-placement="top" data-bs-original-title="View Profile"><i class="fa fa-eye"></i><a href="' . route('saleuseredit', ['main_machine_type' => $main_machine_type, $row->id]) . '" class="edit btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Edit Profile" data-bs-placement="top" data-bs-original-title="Edit Profile"><i class="fa fa-edit"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="deleteUser(' . $row->id . ')" class="edit btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></div>';
                    // $btn .= '<a href="' . route('users.assignRoles', ['user' => $row->id]) . '" class="edit btn btn-primary btn-sm"><i class="fa fa-lock"></i></a>';
                    return $btn;
                })
                ->addColumn('roles', function ($row) {
                    return $row->getRoleNames()->implode(', ');
                })
                ->addColumn('inTime', function ($row) {
                    return isset($row->duty_start) ? date("h:i A", strtotime($row->duty_start)) : '-';
                })
                ->addColumn('outTime', function ($row) {
                    return isset($row->duty_end) ? date("h:i A", strtotime($row->duty_end)) : '-';
                })
                ->addColumn('leader', function ($row) {
                    return $row->is_leader == 1 ? 'Yes' : 'No';
                })
                ->rawColumns(['action', 'roles'])
                ->make(true);
        }
        return view('users.saleindex', $data);
    }

    public function usersProfile($main_machine_type, $id, Request $request) 
    {    
        $data['title'] = "User Profile";
        $data['user_id'] = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $main_machine_type)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;

        $userDetail = User::where('id', $id)->with('roles')->first();
        //dd($userDetail);
        $data['userDetail'] = $userDetail;
        $upid = $userDetail->id;
        $userPayroll = UsersPayroll::where('user_id', $upid)->first();
        
        if($userPayroll){
           $data['userPayroll'] = $userPayroll;  
        } else {
            $data['userPayroll'] = [];
        }

        $utleaves = Leave::where('user_id', $upid)->sum('total_leave');
        $data['total_leaves'] = $utleaves;
        $usersWithAttendance = Attendtl::where('engineer_id', $upid);
        $currentDate = Carbon::now();
        $startDate = $currentDate->copy()->startOfMonth();
        $endDate = $currentDate->copy()->endOfMonth();
        // Execute the query
        $usersWithAttendance = $usersWithAttendance->whereBetween('in_date', [$startDate, $endDate])->get();
        $data['userAttendance'] = $usersWithAttendance;
        $usersSalaries = Salary::where('emp_id', $upid)->get();
        $data['userSalaries'] = $usersSalaries;
        $usersLocations = Location::where('user_id', $upid)->whereDate('created_at', date('Y-m-d'))->get();
        $usersLocationsjou = Location::where('user_id', $upid)->whereDate('created_at', date('Y-m-d'))->get(['lat', 'lng']); // fetch only needed columns
        $locationsJson = $usersLocationsjou->toJson();
        $data['usersLocations'] = $usersLocations;
        $data['locationsJson'] = $locationsJson;
        if($userDetail){
            return view('users.userprofile', $data);
        } else {
            return view('users.userprofile', $data);
        }
    }

    public function saleUsersProfile($main_machine_type, $id, Request $request) 
    {    
        $data['title'] = "User Profile";
        $data['user_id'] = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $main_machine_type)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;

        $userDetail = User::where('id', $id)->with('roles')->first();
        //dd($userDetail);
        $data['userDetail'] = $userDetail;
        $upid = $userDetail->id;
        $userPayroll = UsersPayroll::where('user_id', $upid)->first();
        
        if($userPayroll){
           $data['userPayroll'] = $userPayroll;  
        } else {
            $data['userPayroll'] = [];
        }

        $utleaves = Leave::where('user_id', $upid)->sum('total_leave');
        $data['total_leaves'] = $utleaves;
        $usersWithAttendance = Attendtl::where('engineer_id', $upid);
        $currentDate = Carbon::now();
        $startDate = $currentDate->copy()->startOfMonth();
        $endDate = $currentDate->copy()->endOfMonth();
        // Execute the query
        $usersWithAttendance = $usersWithAttendance->whereBetween('in_date', [$startDate, $endDate])->get();
        $data['userAttendance'] = $usersWithAttendance;
        $usersSalaries = Salary::where('emp_id', $upid)->get();
        $data['userSalaries'] = $usersSalaries;

        if($userDetail){
            return view('users.saleuserprofile', $data);
        } else {
            //$data[] = "";
            return view('users.saleuserprofile', $data);
        }
    }

    public function create($id, Request $request)
    {
        
        $user_main_machine_type = $id;
        $data['user_main_machine_type'] = $user_main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        if(auth()->user()->getRoleNames()->first() == "Admin")
        {
            $all_machine_data = Machine::where('status', 1)->get();
        } else {
            $all_machine_data = Machine::where(['id' => $id, 'status' => 1])->get();
        }
        $data['all_machine_data'] = $all_machine_data;
        $data['title'] = $main_machine_name. '-> Create User';
        $specificRoles = "";
        if(auth()->user()->getRoleNames()->first() == "Admin")
        {
            $specificRoles = Role::whereIn('name', ['Manager', 'Team Leader', 'Service Team Leader', 'Engineer', 'Sales Team Leader', 'Sales Person'])->get();
        }
        if(auth()->user()->getRoleNames()->first() == "Manager")
        {
            $specificRoles = Role::whereIn('name', ['Service Team Leader', 'Engineer', 'Sales Team Leader', 'Sales Person'])->get();
        }
        if(auth()->user()->getRoleNames()->first() == "Team Leader")
        {
            $specificRoles = Role::whereIn('name', ['Engineer', 'Sales Person'])->get();
        }
        if(auth()->user()->getRoleNames()->first() == "Service Team Leader")
        {
            $specificRoles = Role::whereIn('name', ['Engineer'])->get();
        }
        if(auth()->user()->getRoleNames()->first() == "Sales Team Leader")
        {
            $specificRoles = Role::whereIn('name', ['Sales Person'])->get();
        }
        $data['specificRoles'] = $specificRoles;
        return view('users.create', $data);
    }

    public function saleUserCreate($id, Request $request)
    {
        $data['title'] = $main_machine_name. '-> Create User';
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;

        $specificRoles = "";
        if(auth()->user()->getRoleNames()->first() == "Manager")
        {
            $specificRoles = Role::whereIn('name', ['Service Team Leader', 'Engineer', 'Sales Team Leader', 'Sales Person'])->get();
        }
        if(auth()->user()->getRoleNames()->first() == "Team Leader")
        {
            $specificRoles = Role::whereIn('name', ['Engineer', 'Sales Person'])->get();
        }
        if(auth()->user()->getRoleNames()->first() == "Service Team Leader")
        {
            $specificRoles = Role::whereIn('name', ['Engineer'])->get();
        }
        if(auth()->user()->getRoleNames()->first() == "Sales Team Leader")
        {
            $specificRoles = Role::whereIn('name', ['Sales Person'])->get();
        }
        $data['specificRoles'] = $specificRoles;
        return view('users.salecreate', $data);
    }

    public function store(Request $request)
    {
        
        $data['title'] = 'Create User';
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone_no' => 'required|numeric|digits:10|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except('password', 'confirm_password', 'image'); // Exclude sensitive or file inputs
        $data['password'] = Hash::make($request->password);

        if (!isset($request->is_active)) {
            $data['is_active'] = 0;
        }

        if (!isset($request->is_leader)) {
            $data['is_leader'] = 0;
        }

        $data['is_salse_emb'] = null;
        $data['is_salse_cir'] = null;
        $data['doj'] = $request->doj;
        $data['deparment_id'] = $request->deparment_id;


        // Handle Image Upload
        if ($request->hasFile('profile')) {
            $profile = $request->file('profile');
            $profileName = time() . '.' . $profile->getClientOriginalExtension();
            //$profile->storeAs('/user_dp', $profileName); // Save in /public/user_dp
            $profile->move(public_path('user_dp'), $profileName); // Save in /public/user_dp
            $data['profile'] = $profileName; // Save relative path in DB
        }

        // Handle aadhar_card Upload
        if ($request->hasFile('aadhar_card')) {
            $aadhar_card = $request->file('aadhar_card');
            $aadharName = time() . 'ac.' . $aadhar_card->getClientOriginalExtension();
            //$aadhar_card->storeAs('/user_dp', $aadharName); // Save in /public/user_dp
            $aadhar_card->move(public_path('user_dp'), $aadharName); // Save in /public/user_dp
            $data['aadhar_card'] = $aadharName; // Save relative path in DB
        }

        // Handle pan_card Upload
        if ($request->hasFile('pan_card')) {
            $pan_card = $request->file('pan_card');
            $panName = time() . 'pc.' . $pan_card->getClientOriginalExtension();
            //$pan_card->storeAs('/user_dp', $panName); // Save in /public/public/user_dp
            $pan_card->move(public_path('user_dp'), $panName); // Save in /public/user_dp
            $data['pan_card'] = $panName; // Save relative path in DB
        }

        $user = User::create($data); // Use $data array for creation
        $user->assignRole($request->input('roles')); // Assign Roles

        // Add user Payroll Details
        
        $pruserId = $user->id;
        //$pruserId = 199;
        $prdata['user_id'] = $pruserId;
        $prdata['aadharno'] = $request->aadharno;
        $prdata['panno'] = $request->panno;
        $prdata['bank_name'] = $request->bank_name;
        $prdata['ifsc'] = $request->ifsc;
        $prdata['ahname'] = $request->ahname;
        $prdata['account_no'] = $request->account_no;
        $prdata['upi_id'] = $request->upi_id;
        $basic_sal = 0; 
        $hra = 0;
        $da = 0; 
        $msc_allow = 0;
        $ptrl_allow = 0; 
        $pt = 0;
        $total_act_sal_monthly = 0;
        $total_ctc_yearly = 0;
        $perday_sal = 0;
        if(isset($request->basic_sal) && !empty($request->basic_sal)){
            $prdata['basic_sal'] = $request->basic_sal;
            $basic_sal = $request->basic_sal;
        }
        if(isset($request->hra) && !empty($request->hra)){
            $prdata['hra'] = $request->hra;
            $hra = $request->hra;
        }
        if(isset($request->da) && !empty($request->da)){
            $prdata['da'] = $request->da;
            $da = $request->da;
        }
        if(isset($request->pt) && !empty($request->pt)){
            $prdata['pt'] = $request->pt;
            $pt = $request->pt;
        }
        if(isset($request->msc_allow) && !empty($request->msc_allow)){
            $prdata['msc_allow'] = $request->msc_allow;
            $msc_allow = $request->msc_allow;
        }
        if(isset($request->ptrl_allow) && !empty($request->ptrl_allow)){
            $prdata['ptrl_allow'] = $request->ptrl_allow;
            $ptrl_allow = $request->ptrl_allow;
        }
        $total_act_sal_monthly = (($basic_sal + $hra + $da + $msc_allow + $ptrl_allow) - ($pt));
        if(isset($total_act_sal_monthly) && $total_act_sal_monthly > 0)
        {
            $total_ctc_yearly = ($total_act_sal_monthly * 12);
        }
        $totalDays = Carbon::now()->daysInMonth;
        if(isset($total_act_sal_monthly) && $total_act_sal_monthly > 0)
        {
            $perday_sal = ($total_act_sal_monthly / $totalDays);
        }
        $prdata['perday_sal'] = $perday_sal;
        $prdata['total_act_sal_monthly'] = $total_act_sal_monthly;
        $prdata['total_ctc_yearly'] = $total_ctc_yearly;

        //dd($prdata);
        $pruser = UsersPayroll::create($prdata);

        // Add Userwise machines
        if (isset($request->main_machine_type)) {
            $iuserId = $user->id;
            $mdata['user_id'] = $iuserId;
            foreach($request->main_machine_type as $machineKey => $machinetype) 
            {
                $mdata['main_machine_type'] = $machinetype;
                $uwisem = UserwiseMachine::create($mdata); // Use $mdata array for creation
            }
            
        }
        if(isset($request->is_saleuser) && $request->is_saleuser == 1)
        {
            toastr()->success('User created successfully');
            return redirect()->route('salesusers', ['main_machine_type' => $request->user_main_machine_type])->with('success', 'User created successfully');
        } else {
            toastr()->success('User created successfully');
            return redirect()->route('users.index', ['main_machine_type' => $request->user_main_machine_type])->with('success', 'User created successfully');
        }
    }


    public function edit($id, User $user)
    {
        $user_main_machine_type = $id;
        $data['user_main_machine_type'] = $user_main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $all_machine_data = Machine::where('status', 1)->get();
        $data['all_machine_data'] = $all_machine_data;
        $data['title'] = $main_machine_name. '-> Edit User';
        $data['user'] = $user;
        $iuid = $user->id;
        $userPayroll = UsersPayroll::where('user_id', $iuid)->first();
        $data['userPayroll'] = $userPayroll;
        $uwisem = UserwiseMachine::where('user_id', $iuid)
        ->get()
        ->toArray();
        //dd($uwisem);
        if(isset($uwisem))
        {
            foreach($uwisem as $machinetype) 
            {
                foreach($machinetype as $machineKey1 => $machinetype1) 
                {
                     //dd($machineKey);
                     
                    if($machineKey1=="main_machine_type")
                    {
                         $mdata[$machineKey1][] = $machinetype1;
                    }
                }
            }
            //dd($mdata);
            if(isset($mdata) && !empty($mdata))
            {    
            $data['main_machine_type'] = $mdata;
            //dd($data);
            }
        }
        $specificRoles = "";
        if(auth()->user()->getRoleNames()->first() == "Service Team Leader")
        {
            $specificRoles = Role::whereIn('name', ['Engineer'])->get();
        }
        $data['specificRoles'] = $specificRoles;
        return view('users.edit', $data);
    }

    public function saleUserEdit($id, User $user)
    {
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $data['title'] = $main_machine_name. '-> Edit User';
        $data['user'] = $user;
        $iuid = $user->id;
        $userPayroll = UsersPayroll::where('user_id', $iuid)->first();
        $data['userPayroll'] = $userPayroll;
        $uwisem = UserwiseMachine::where('user_id', $iuid)
        ->get()
        ->toArray();
        //dd($uwisem);
        if(isset($uwisem))
        {
            foreach($uwisem as $machinetype) 
            {
                foreach($machinetype as $machineKey1 => $machinetype1) 
                {
                     //dd($machineKey);
                     
                    if($machineKey1=="main_machine_type")
                    {
                         $mdata[$machineKey1][] = $machinetype1;
                    }
                }
            }
            //dd($mdata);
            if(isset($mdata) && !empty($mdata))
            {    
            $data['main_machine_type'] = $mdata;
            //dd($data);
            }
        }
        return view('users.saleedit', $data);
    }

    public function update(Request $request, User $user)
    {
        $data['title'] = 'Edit User';
        //dd($request);
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone_no' => 'required|numeric|digits:10|unique:users,phone_no,' . $user->id,
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Profile image validation
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Exclude sensitive inputs initially
        $data = $request->except('password', 'confirm_password', 'profile');

        // Update password only if provided
        if (!empty($request->password)) {
            $data['password'] = bcrypt($request->password);
        }

        // Handle checkbox default value for `is_active`
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['is_leader'] = $request->has('is_leader') ? 1 : 0;

        // Handle custom roles and engineer data
        $data['is_salse_emb'] = null;
        $data['is_salse_cir'] = null;
        $data['doj'] = $request->doj;

        

        // Handle profile image update
        if ($request->hasFile('profile')) {
            // Delete old profile image if it exists
            if ($user->profile && Storage::exists('public/user_dp/' . $user->profile)) {
                Storage::delete('public/user_dp/' . $user->profile);
            }

            // Store new profile image
            $profileImage = $request->file('profile');
            $profileImageName = time() . '.' . $profileImage->getClientOriginalExtension();
            //$profileImage->storeAs('/user_dp', $profileImageName);
            $profileImage->move(public_path('user_dp'), $profileImageName);

            // Set profile image path for database
            $data['profile'] = $profileImageName;
        }

        // Handle Aadhar update
        if ($request->hasFile('aadhar_card')) {
            // Delete old aadhar_card image if it exists
            if ($user->aadhar_card && Storage::exists('public/user_dp/' . $user->aadhar_card)) {
                Storage::delete('public/user_dp/' . $user->aadhar_card);
            }

            // Store new aadhar_card image
            $aadharImage = $request->file('aadhar_card');
            $aadharImageName = time() . 'ac.' . $aadharImage->getClientOriginalExtension();
            $aadharImage->move(public_path('user_dp'), $aadharImageName);

            // Set profile image path for database
            $data['aadhar_card'] = $aadharImageName;
        }

        // Handle Pan update
        if ($request->hasFile('pan_card')) {
            // Delete old pan_card image if it exists
            if ($user->pan_card && Storage::exists('public/user_dp/' . $user->pan_card)) {
                Storage::delete('public/user_dp/' . $user->pan_card);
            }

            // Store new pan_card image
            $panImage = $request->file('pan_card');
            $panImageName = time() . 'pc.' . $panImage->getClientOriginalExtension();
            $panImage->move(public_path('user_dp'), $panImageName);

            // Set profile image path for database
            $data['pan_card'] = $panImageName;
        }

        // Update user data
        $user->update($data);

        // Update roles
        $user->roles()->detach();
        $user->assignRole($request->input('roles'));

        // Add or Update Data to User payroll Table
        $iuid = $user->id;
        $userPayroll = UsersPayroll::where('user_id', $iuid)->first();
        if($userPayroll) {
            $prdata['user_id'] = $iuid;
            $prdata['aadharno'] = $request->aadharno;
            $prdata['panno'] = $request->panno;
            $prdata['bank_name'] = $request->bank_name;
            $prdata['ifsc'] = $request->ifsc;
            $prdata['ahname'] = $request->ahname;
            $prdata['account_no'] = $request->account_no;
            $prdata['upi_id'] = $request->upi_id;
            $basic_sal = 0; 
            $hra = 0;
            $da = 0; 
            $msc_allow = 0;
            $ptrl_allow = 0; 
            $pt = 0;
            $total_act_sal_monthly = 0;
            $total_ctc_yearly = 0;
            $perday_sal = 0;
            if(isset($request->basic_sal) && !empty($request->basic_sal)){
                $prdata['basic_sal'] = $request->basic_sal;
                $basic_sal = $request->basic_sal;
            }
            if(isset($request->hra) && !empty($request->hra)){
                $prdata['hra'] = $request->hra;
                $hra = $request->hra;
            }
            if(isset($request->da) && !empty($request->da)){
                $prdata['da'] = $request->da;
                $da = $request->da;
            }
            if(isset($request->pt) && !empty($request->pt)){
                $prdata['pt'] = $request->pt;
                $pt = $request->pt;
            }
            if(isset($request->msc_allow) && !empty($request->msc_allow)){
                $prdata['msc_allow'] = $request->msc_allow;
                $msc_allow = $request->msc_allow;
            }
            if(isset($request->ptrl_allow) && !empty($request->ptrl_allow)){
                $prdata['ptrl_allow'] = $request->ptrl_allow;
                $ptrl_allow = $request->ptrl_allow;
            }
            $total_act_sal_monthly = (($basic_sal + $hra + $da + $msc_allow + $ptrl_allow) - ($pt));
            if(isset($total_act_sal_monthly) && $total_act_sal_monthly > 0)
            {
                $total_ctc_yearly = ($total_act_sal_monthly * 12);
            }
            $totalDays = Carbon::now()->daysInMonth;
            if(isset($total_act_sal_monthly) && $total_act_sal_monthly > 0)
            {
                $perday_sal = ($total_act_sal_monthly / $totalDays);
            }
            $prdata['perday_sal'] = $perday_sal;
            $prdata['total_act_sal_monthly'] = $total_act_sal_monthly;
            $prdata['total_ctc_yearly'] = $total_ctc_yearly;

            //dd($prdata);
            $pruser = $userPayroll->update($prdata);
        } else {
            $prdata['user_id'] = $iuid;
            $prdata['aadharno'] = $request->aadharno;
            $prdata['panno'] = $request->panno;
            $prdata['bank_name'] = $request->bank_name;
            $prdata['ifsc'] = $request->ifsc;
            $prdata['ahname'] = $request->ahname;
            $prdata['account_no'] = $request->account_no;
            $prdata['upi_id'] = $request->upi_id;
            $basic_sal = 0; 
            $hra = 0;
            $da = 0; 
            $msc_allow = 0;
            $ptrl_allow = 0; 
            $pt = 0;
            $total_act_sal_monthly = 0;
            $total_ctc_yearly = 0;
            $perday_sal = 0;
            if(isset($request->basic_sal) && !empty($request->basic_sal)){
                $prdata['basic_sal'] = $request->basic_sal;
                $basic_sal = $request->basic_sal;
            }
            if(isset($request->hra) && !empty($request->hra)){
                $prdata['hra'] = $request->hra;
                $hra = $request->hra;
            }
            if(isset($request->da) && !empty($request->da)){
                $prdata['da'] = $request->da;
                $da = $request->da;
            }
            if(isset($request->pt) && !empty($request->pt)){
                $prdata['pt'] = $request->pt;
                $pt = $request->pt;
            }
            if(isset($request->msc_allow) && !empty($request->msc_allow)){
                $prdata['msc_allow'] = $request->msc_allow;
                $msc_allow = $request->msc_allow;
            }
            if(isset($request->ptrl_allow) && !empty($request->ptrl_allow)){
                $prdata['ptrl_allow'] = $request->ptrl_allow;
                $ptrl_allow = $request->ptrl_allow;
            }
            $total_act_sal_monthly = (($basic_sal + $hra + $da + $msc_allow + $ptrl_allow) - ($pt));
            if(isset($total_act_sal_monthly) && $total_act_sal_monthly > 0)
            {
                $total_ctc_yearly = ($total_act_sal_monthly * 12);
            }
            $totalDays = Carbon::now()->daysInMonth;
            if(isset($total_act_sal_monthly) && $total_act_sal_monthly > 0)
            {
                $perday_sal = ($total_act_sal_monthly / $totalDays);
            }
            $prdata['perday_sal'] = $perday_sal;
            $prdata['total_act_sal_monthly'] = $total_act_sal_monthly;
            $prdata['total_ctc_yearly'] = $total_ctc_yearly;

            //dd($prdata);
            $pruser = UsersPayroll::create($prdata);
        }

        if (isset($request->main_machine_type)) {
            //dd($request->main_machine_type);
            $iuserId = $user->id;
            $mdata['user_id'] = $iuserId;
            // Remove old machine types
            UserwiseMachine::where('user_id', $iuserId)->delete();
            //dd($iuserId.'-'.$request->main_machine_type);
            foreach($request->main_machine_type as $machineKey => $machinetype) 
            {
                $mdata['main_machine_type'] = $machinetype;
                $uwisem = UserwiseMachine::create($mdata); // Use $mdata array for creation
            }    
        }

        // Show success message and redirect
        if(isset($request->is_saleuser) && $request->is_saleuser == 1)
        {
            toastr()->success('User updated successfully');
            return redirect()->route('salesusers', ['main_machine_type' => $request->user_main_machine_type])->with('success', 'User created successfully');
        } else {
            toastr()->success('User updated successfully');
            return redirect()->route('users.index', ['main_machine_type' => $request->user_main_machine_type])->with('success', 'User updated successfully');
        }
    }

    public function destroy(User $user)
    {
        if ($user->id == 1) {
            return response()->json(['success' => 0, 'message' => 'You can not delete this user'], 422);
        }
        if ($user->id == auth()->user()->id) {
            return response()->json(['success' => 0, 'message' => 'You can not delete your self'], 422);
        }
        if ($user->complaints()->count() > 0 || MachineSalesEntry::where('mic_fitting_engineer_id', $user->id)->orWhere('delivery_engineer_id', $user->id)->count() > 0) {
            return response()->json([
                'success' => 0,
                'message' => 'Engineer can not be deleted its associated with complaints or machine sales entry',
            ], 422);
        }
        // return response()->json(['success' => 0, 'message' => 'You can not delete this user']);
        $user->delete();
        return response()->json(['success' => 'User deleted successfully']);
    }

    public function verifyopt(Request $request)
    { 
        //dd($request->phone_no);
        $data['mobile'] = $request->phone_no;
        $user = User::where('phone_no', $request->phone_no)->where('is_active', 1)->with('roles')->first();
        if($user){
            $otp = random_int(100000, 999999);
            //$phonenum1 = 9879260346;
            $phonenum = $user->phone_no;
            //$phonenum = 9879260346;
            $user->update(['otp' => $otp]);
            $otpapiurl = "http://mobizz.hginfosys.co.in/sendsms.jsp?user=PICKWELL&password=b4672740cfXX&senderid=PIKWEL&mobiles=+91".$phonenum."&sms=".$otp." is the OTP to verify your mobile number with PICKWELL EXIM. OTP is valid for the next 5 mins. Do not share with anyone PICKWELL EXIM.&tempid=1707176543676263550";
            //$otpapiurl = "http://mobizz.hginfosys.co.in/sendsms.jsp?user=SAISTAR&password=9c88265930XX&senderid=SSIMPL&mobiles=+91".$phonenum."&sms=".$otp." is the OTP to verify your mobile number with SAISTAR IMPEX PRIVATE LIMITED. OTP is valid for the next 5 mins. Do not share with anyone SAISTAR GROUP.&tempid=1707175110503248018";
            //$otpapiurl1 = "http://mobizz.hginfosys.co.in/sendsms.jsp?user=SAISTAR&password=9c88265930XX&senderid=SSIMPL&mobiles=+91".$phonenum1."&sms=".$otp." is the OTP to verify your mobile number with SAISTAR IMPEX PRIVATE LIMITED. OTP is valid for the next 5 mins. Do not share with anyone SAISTAR GROUP.&tempid=1707175110503248018";
            //$response1 = Http::get($otpapiurl1);
            //Send OTP to user mobile
            $response = Http::get($otpapiurl);
             $data['mobile'] = $user->phone_no;
                if ($response->successful()) {
                    // Create a new token
                    return view('auth.otpsv', $data);
                } else {
                    // return view('auth.otpsv', $data);
                    return "Error";
                }    
        }  
    }

    public function resendotp($mobile, Request $request)
    {
        //dd($mobile);
        $data['mobile'] = $mobile;
        $user = User::where('phone_no', $mobile)->where('is_active', 1)->with('roles')->first();
        if($user){
            $otp = random_int(100000, 999999);
            //$phonenum1 = 9879260346;
            $phonenum = $user->phone_no;
            //$phonenum = 9879260346;
            $user->update(['otp' => $otp]);
            $otpapiurl = "http://mobizz.hginfosys.co.in/sendsms.jsp?user=PICKWELL&password=b4672740cfXX&senderid=PIKWEL&mobiles=+91".$phonenum."&sms=".$otp." is the OTP to verify your mobile number with PICKWELL EXIM. OTP is valid for the next 5 mins. Do not share with anyone PICKWELL EXIM.&tempid=1707176543676263550";
            //$otpapiurl = "http://mobizz.hginfosys.co.in/sendsms.jsp?user=SAISTAR&password=9c88265930XX&senderid=SSIMPL&mobiles=+91".$phonenum."&sms=".$otp." is the OTP to verify your mobile number with SAISTAR IMPEX PRIVATE LIMITED. OTP is valid for the next 5 mins. Do not share with anyone SAISTAR GROUP.&tempid=1707175110503248018";
            //$otpapiurl1 = "http://mobizz.hginfosys.co.in/sendsms.jsp?user=SAISTAR&password=9c88265930XX&senderid=SSIMPL&mobiles=+91".$phonenum1."&sms=".$otp." is the OTP to verify your mobile number with SAISTAR IMPEX PRIVATE LIMITED. OTP is valid for the next 5 mins. Do not share with anyone SAISTAR GROUP.&tempid=1707175110503248018";
            //$response1 = Http::get($otpapiurl1);
            //Send OTP to user mobile
            $response = Http::get($otpapiurl);
             $data['mobile'] = $user->phone_no;
                if ($response->successful()) {
                    // Create a new token
                    return view('auth.otpsv', $data);
                } else {
                    return view('auth.otpsv', $data);
                }    
        }  
    }

    public function ownerIndex(Request $request)
    {
        $data['title'] = 'Owner List';
        $owner = Owner::latest()->get();
        if ($request->ajax()) {
            return DataTables::of($owner)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group m-1"><a href="' . route('owners.edit', ['owner' => $row->id]) . '" class="edit btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="deleteUser(' . $row->id . ')" class="edit btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></div>';
                    // $btn .= '<a href="' . route('users.assignRoles', ['user' => $row->id]) . '" class="edit btn btn-primary btn-sm"><i class="fa fa-lock"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('owner.index', $data);
    }

    public function ownerCreate()
    {
        $data['title'] = 'Create Owner';
        return view('owner.create', $data);
    }

    public function ownerStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:255',
                'phone_no' => ['required', 'max:10', 'unique:owners', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            ],
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Something went wrong', 'error' => $validator->errors()], 422);
        }
        $owner = Owner::create(['name' => $request->name, 'phone_no' => $request->phone_no]);
        return redirect()->route('owners.index')->with('success', 'Area created successfully');
    }

    public function ownerEdit($id)
    {
        $data['title'] = 'Edit Owner';
        $data['owner'] = Owner::find($id);
        return view('owner.edit', $data);
    }

    public function ownerUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'phone_no' => ['required', 'max:10'],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Something went wrong', 'error' => $validator->errors()], 422);
        }
        $owner = owner::find($id);
        $owner->update(['name' => $request->name, 'phone_no' => $request->phone_no]);
        return redirect()->route('owners.index')->with('success', 'owner updated successfully');
        return response()->json($owner);
    }

    public function ownerDestroy($id)
    {
        $owner = owner::find($id);
        if ($owner->parties->count() > 0) {
            return response()->json(['success' => 0, 'message' => 'You can not delete this owner, it has parties'], 422);
        }
        $owner->delete();
        return response()->json(['success' => 'owner deleted successfully']);
    }

    public function contactPersonIndex(Request $request)
    {
        $data['title'] = 'Contact Person List';
        $owner = ContactPerson::latest()->get();
        if ($request->ajax()) {
            return DataTables::of($owner)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group m-1"><a href="' . route('contact_persons.edit', ['contact_person' => $row->id]) . '"  class="edit btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="deleteUser(' . $row->id . ')" class="edit btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></div>';
                    // $btn .= '<a href="' . route('users.assignRoles', ['user' => $row->id]) . '" class="edit btn btn-primary btn-sm"><i class="fa fa-lock"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('contact_persons.index', $data);
    }

    public function contactPersonCreate()
    {
        $data['title'] = 'Create Contact Person';
        return view('contact_persons.create', $data);
    }
    public function contactPersonStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:255',
                'phone_no' => ['required', 'max:10', 'unique:contact_persons', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            ],
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Something went wrong', 'error' => $validator->errors()], 422);
        }
        $contactPerson = ContactPerson::create(['name' => $request->name, 'phone_no' => $request->phone_no]);
        return redirect()->route('contact_persons.index')->with('success', 'Area created successfully');
    }

    public function contactPersonEdit($id)
    {
        $data['title'] = 'Edit Contact Person';
        $data['contact_person'] = ContactPerson::find($id);
        return  view('contact_persons.edit', $data);
    }

    public function contactPersonUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'phone_no' => ['required', 'max:10'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $contactPerson = ContactPerson::find($id);
        $contactPerson->update(['name' => $request->name, 'phone_no' => $request->phone_no]);
        return redirect()->route('contact_persons.index')->with('success', 'Contact Person updated successfully');
    }

    public function contactPersonDestroy($id)
    {
        $contactPerson = ContactPerson::find($id);
        if ($contactPerson->parties->count() > 0) {
            return response()->json(['success' => 0, 'message' => 'You can not delete this owner, it has parties'], 422);
        }
        $contactPerson->delete();
        return response()->json(['success' => 'Contact Person deleted successfully']);
    }
}
