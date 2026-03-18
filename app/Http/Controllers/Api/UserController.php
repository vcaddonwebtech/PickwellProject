<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UsersPayroll;
use App\Models\Salary;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::where('is_active', 1)->get()->toArray();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No user found',
                'data' => []
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'User fetched successfully',
            'data' => $user
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

    // Get the users role wise
    public function roleWiseUsers(Request $request)
    {
        // Initialize the query for active users
        $users = User::where('is_active', 1);

        if ($request->is_emb == 1 || $request->is_cir == 1) {
            // Add conditions for is_emb and is_cir
            $users = $users->where(function ($query) use ($request) {
                $query->where('is_salse_emb', $request->is_emb)
                    ->orWhere('is_salse_cir', $request->is_cir);
            });
        }

        // Handle role-specific logic
        if ($request->role_name === 'Office Staff') {
            // Fetch users with Office Staff or Admin roles
            $users = $users->where(function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', 'Office Staff')
                    ->orWhere('name', 'Admin');
                });
            });
        } else {
            // Fetch users with only the sales role
            $users = $users->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role_name);
            });
        }

        // Finalize query and fetch results
        $users = $users->orderBy('name')->get();

        // Return response
        return response()->json([
            'success' => true,
            'message' => 'Users fetched successfully',
            'data' => $users
        ], 200);
    }

    public function userDp(Request $request) {
        $data = $request->all();
        $user = User::where('id', $data['user_id'])->where('is_active', 1)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No user found',
                'data' => []
            ], 404);
        }

        $folderName = 'user_dp'; // Replace with your folder name
        $path = public_path($folderName);

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true, true); // Create directory with permissions
        }

        if (isset($data['profile'])) {
            $file = $data['profile'];
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('user_dp'), $filename);
            $data['profile'] = $filename;
        }

        $user->profile = $data['profile'];
        $user->save();
        $user->profile_url = url('/user_dp/' . $user->profile);
        return response()->json([
            'success' => true,
            'message' => 'User dp updated successfully',
            'data' => $user
        ], 200);
    }

    public function updateUserProfile(Request $request) {
        $data = $request->all();
        $user = User::where(['id' => $data['user_id'], 'is_active' => 1])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No user found',
                'data' => []
            ], 404);
        }
        if (isset($data['name'])) {
            $user->name = $data['name'];
        } 
        if (isset($data['email'])) {
            $user->email = $data['email'];
        }
        if (isset($data['phone_no'])) {
            $user->phone_no = $data['phone_no'];
        }     
        if (isset($data['profile'])) {
            $file = $data['profile'];
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('user_dp'), $filename);
            $data['profile'] = $filename;
            $user->profile = $data['profile'];
        }
        if (isset($data['aadhar_card'])) {
            $afile = $data['aadhar_card'];
            $afilename = time() . 'ac.' . $afile->getClientOriginalExtension();
            $afile->move(public_path('user_dp'), $afilename);
            $data['aadhar_card'] = $afilename;
            $user->aadhar_card = $data['aadhar_card'];
        }
        if (isset($data['pan_card'])) {
            $pfile = $data['pan_card'];
            $pfilename = time() . 'pc.' . $pfile->getClientOriginalExtension();
            $pfile->move(public_path('user_dp'), $pfilename);
            $data['pan_card'] = $pfilename;
            $user->pan_card = $data['pan_card'];
        }
        $user->save();
        $user->profile_url = url('/user_dp/' . $user->profile);
        $user->aadhar_url = url('/user_dp/' . $user->aadhar_card);
        $user->pan_url = url('/user_dp/' . $user->pan_card);
        return response()->json([
            'success' => true,
            'message' => 'User Profile updated successfully',
            'data' => $user
        ], 200);
    }

    public function userSalarydata(Request $request) {
        $id = $request->id;
        $mid = $request->mid;
        //dd('Salary data'.$id);
        $user = User::find($id);
        //dd($id);
        $roleName = $user->getRoleNames(); // returns a collection of role names
        //$firstRole = $roleName->first();
        $userSaldata = [];
        $year = Carbon::now()->year; // You can also use Carbon::now()->year
        $totalDays = Carbon::createFromDate($year, $mid, 1)->daysInMonth;
        //dd($roleName);
        $userSaldata = User::leftJoin('users_payroll', function ($join) {
                $join->on('users.id', '=', 'users_payroll.user_id');
                })
                ->leftJoin('salaries', function ($join) {
                    $join->on('users.id', '=', 'salaries.emp_id');
                })
                ->select(
                    'users.name',
                    'users_payroll.basic_sal',
                    'users_payroll.hra',
                    'users_payroll.da',
                    'users_payroll.pt',
                    'users_payroll.msc_allow',
                    'users_payroll.ptrl_allow',
                    'salaries.month_id',
                    'salaries.pdays',
                    'salaries.working_days',
                    'salaries.hdays',
                    'salaries.total_salary',
                )
                ->where('users.is_active', 1)
                ->where('salaries.month_id', $mid)
                ->where('users.id', $id)->first();
        //dd($userSaldata);
        if($userSaldata) {    
        $userSaldata['role'] = $roleName;
        $userSaldata['total_days'] = $totalDays;
        $totalEarnings = ($userSaldata->basic_sal + $userSaldata->hra + $userSaldata->da + $userSaldata->msc_allow + $userSaldata->ptrl_allow);     
        $totalDeduction = ($userSaldata->pt);
        $netSalary = ($totalEarnings - $totalDeduction);
        $userSaldata['total_earnings'] = $totalEarnings;
        $userSaldata['total_deduction'] = $totalDeduction;
        $perdaySal = ($totalEarnings / $userSaldata->working_days);
        $netSalary = ($perdaySal * $userSaldata->pdays);
        $userSaldata['total_salary'] = $netSalary;
        // Leave Data
        $mtotalLeaves = Leave::where('user_id', $id)
        ->whereMonth('leave_from', $mid) // Or use leave_till if needed
        ->whereYear('leave_from', $year)
        ->sum('total_leave');
        $userSaldata['leave'] = $mtotalLeaves;            
               
            return response()->json([
                'success' => true,
                'message' => 'User Salary Data Fetched Successfully',
                'data' => $userSaldata
            ], 200);
        } else {
            $userSaldata = [];
            return response()->json([
                'success' => false,
                'message' => 'User Salary Data Not Fetched!',
                'data' => (object)[]
            ], 200);
        }        
    }            
}
