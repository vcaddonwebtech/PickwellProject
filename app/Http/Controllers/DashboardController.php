<?php

namespace App\Http\Controllers;

use App\Models\Attendtl;
use App\Models\Complaint;
use App\Models\SalesPerson;
use App\Models\User;
use App\Models\MachineSalesEntry;
use App\Models\Machine;
use App\Models\UserwiseMachine;
use App\Models\PartywiseMachine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Import the facade for DomPDF
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function states()
    {
        $user = Auth::user();
               
        $userRole = strtolower(auth()->user()->getRoleNames()->first());
        if($userRole == "admin") {
            //$assignedMachineTypes = array_keys(config('constants.MACHINE_TYPES'));
            $assignedMachineTypes = Machine::where('status', 1)->get();
            //$assignedMachineTypes = array_keys($machineTypes);
        } else if($userRole == "service team leader" || $userRole == "sales team leader" || $userRole == "team leader" || $userRole == "payroll manager") {
             //$assignedMachineTypes = UserwiseMachine::where('user_id',$user->id)->select('main_machine_type')->pluck('main_machine_type')->toArray();
            $assignedMachineTypes =  UserwiseMachine::with('machine')
                                                        ->where('user_id', $user->id)
                                                        ->get();

          //dd($assignedMachineTypes);                                              

        } else{
            $assignedMachineTypes =  UserwiseMachine::with('machine')
                                                        ->where('user_id', $user->id)
                                                        ->get();
        }
        //dd($assignedMachineTypes);
        $data['assignedMachineTypes'] = $assignedMachineTypes;

        $data['airjet_pending_complaints'] = Complaint::where(['status_id' => 1, 'date' => date('Y-m-d'), 'main_machine_type' => 2])->whereIn('service_type_id', [2, 3])->count();
        $data['airjet_in_progress_complaints'] = Complaint::where(['status_id' => 2, 'date' => date('Y-m-d'), 'main_machine_type' => 2])->whereIn('service_type_id', [2, 3])->count();
        $data['airjet_closed_complaints'] = Complaint::where(['status_id' => 3, 'date' => date('Y-m-d'), 'main_machine_type' => 2])->whereIn('service_type_id', [2, 3])->count();
        $data['airjet_total_complaints'] = $data['airjet_pending_complaints'] + $data['airjet_in_progress_complaints'] + $data['airjet_closed_complaints'];

        $data['waterjet_pending_complaints'] = Complaint::where(['status_id' => 1, 'date' => date('Y-m-d'), 'main_machine_type' => 3])->whereIn('service_type_id', [2, 3])->count();
        $data['waterjet_in_progress_complaints'] = Complaint::where(['status_id' => 2, 'date' => date('Y-m-d'), 'main_machine_type' => 3])->whereIn('service_type_id', [2, 3])->count();
        $data['waterjet_closed_complaints'] = Complaint::where(['status_id' => 3, 'date' => date('Y-m-d'), 'main_machine_type' => 3])->whereIn('service_type_id', [2, 3])->count();
        $data['waterjet_total_complaints'] = $data['waterjet_pending_complaints'] + $data['waterjet_in_progress_complaints'] + $data['waterjet_closed_complaints'];

        $data['rapier_pending_complaints'] = Complaint::where(['status_id' => 1, 'date' => date('Y-m-d'), 'main_machine_type' => 1])->whereIn('service_type_id', [2, 3])->count();
        $data['rapier_in_progress_complaints'] = Complaint::where(['status_id' => 2, 'date' => date('Y-m-d'), 'main_machine_type' => 1])->whereIn('service_type_id', [2, 3])->count();
        $data['rapier_closed_complaints'] = Complaint::where(['status_id' => 3, 'date' => date('Y-m-d'), 'main_machine_type' => 1])->whereIn('service_type_id', [2, 3])->count();
        $data['rapier_total_complaints'] = $data['rapier_pending_complaints'] + $data['rapier_in_progress_complaints'] + $data['rapier_closed_complaints'];


        $data['airjet_pending_installation'] = Complaint::where(['status_id' => 1, 'date' => date('Y-m-d'), 'main_machine_type' => 2, 'service_type_id' => 4])->count();
        $data['airjet_in_progress_installation'] = Complaint::where(['status_id' => 2, 'date' => date('Y-m-d'), 'main_machine_type' => 2, 'service_type_id' => 4])->count();
        $data['airjet_closed_installation'] = Complaint::where(['status_id' => 3, 'date' => date('Y-m-d'), 'main_machine_type' => 2, 'service_type_id' => 4])->count();
        $data['airjet_total_installation'] = $data['airjet_pending_installation'] + $data['airjet_in_progress_installation'] + $data['airjet_closed_installation'];

        $data['waterjet_pending_installation'] = Complaint::where(['status_id' => 1, 'date' => date('Y-m-d'), 'main_machine_type' => 3, 'service_type_id' => 4])->count();
        $data['waterjet_in_progress_installation'] = Complaint::where(['status_id' => 2, 'date' => date('Y-m-d'), 'main_machine_type' => 3, 'service_type_id' => 4])->count();
        $data['waterjet_closed_installation'] = Complaint::where(['status_id' => 3, 'date' => date('Y-m-d'), 'main_machine_type' => 3, 'service_type_id' => 4])->count();
        $data['waterjet_total_installation'] = $data['waterjet_pending_installation'] + $data['waterjet_in_progress_installation'] + $data['waterjet_closed_installation'];

        $data['rapier_pending_installation'] = Complaint::where(['status_id' => 1, 'date' => date('Y-m-d'), 'main_machine_type' => 1, 'service_type_id' => 4])->count();
        $data['rapier_in_progress_installation'] = Complaint::where(['status_id' => 2, 'date' => date('Y-m-d'), 'main_machine_type' => 1, 'service_type_id' => 4])->count();
        $data['rapier_closed_installation'] = Complaint::where(['status_id' => 3, 'date' => date('Y-m-d'), 'main_machine_type' => 1, 'service_type_id' => 4])->count();
        $data['rapier_total_installation'] = $data['rapier_pending_installation'] + $data['rapier_in_progress_installation'] + $data['rapier_closed_installation'];

        $data['airjet_closed_installationss'] = MachineSalesEntry::where(['status' => 3, 'date' => date('Y-m-d'), 'main_machine_type' => 2])->count();
        $data['waterjet_closed_installationss'] = MachineSalesEntry::where(['status' => 3,'date' => date('Y-m-d'), 'main_machine_type' => 3])->count();
        $data['rapier_closed_installationss'] = MachineSalesEntry::where(['status' => 3,'date' => date('Y-m-d'), 'main_machine_type' => 1])->count();

        $data['today'] = date('d-m-Y');

        $data['airjet_pending_leads'] = SalesPerson::where(['status_id' => 4, 'date' => date('Y-m-d'), 'main_machine_type'=> 2])->count();
        $data['airjet_inprogress_leads'] = SalesPerson::where([ 'status_id' => 5, 'date' => date('Y-m-d'), 'main_machine_type'=> 2])->count();
        $data['airjet_done_leads'] = SalesPerson::where(['status_id' => 7, 'date' => date('Y-m-d'), 'main_machine_type' => 2])->count();
        $data['airjet_total_leads'] = $data['airjet_pending_leads'] + $data['airjet_inprogress_leads'] + $data['airjet_done_leads'];

        $data['waterjet_pending_leads'] = SalesPerson::where(['status_id' => 4, 'date' => date('Y-m-d'), 'main_machine_type'=> 3])->count();
        $data['waterjet_inprogress_leads'] = SalesPerson::where([ 'status_id' => 5, 'date' => date('Y-m-d'), 'main_machine_type'=> 3])->count();
        $data['waterjet_done_leads'] = SalesPerson::where(['status_id' => 7, 'date' => date('Y-m-d'), 'main_machine_type' => 3])->count();
        $data['waterjet_total_leads'] = $data['waterjet_pending_leads'] + $data['waterjet_inprogress_leads'] + $data['waterjet_done_leads'];

        $data['rapier_pending_leads'] = SalesPerson::where(['status_id' => 4, 'date' => date('Y-m-d'), 'main_machine_type'=> 1])->count();
        $data['rapier_inprogress_leads'] = SalesPerson::where([ 'status_id' => 5, 'date' => date('Y-m-d'), 'main_machine_type'=> 1])->count();
        $data['rapier_done_leads'] = SalesPerson::where(['status_id' => 7, 'date' => date('Y-m-d'), 'main_machine_type' => 1])->count();
        $data['rapier_total_leads'] = $data['rapier_pending_leads'] + $data['rapier_inprogress_leads'] + $data['rapier_done_leads'];

        $userRole = strtolower(auth()->user()->getRoleNames()->first());
        if($userRole == "payroll manager") {
            $pmdata = [];
            return view('pmdashboard', $data);
        } else {
            return view('dashboard', $data);
        }
    }

    public function machineservicestates($id, Request $request)
    {
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
       
        $data['total_todays_complaints'] = 0;
        $data['todays_total_done_complaints'] = 0;
        $data['todays_total_pending_complaints'] = 0;
        $data['todays_total_inprogress_complaints'] = 0;

        $data['total_todays_complaints'] = Complaint::where(['date' => Carbon::today(), 'main_machine_type' => $main_machine_type])->count();

        $data['todays_total_done_complaints'] = Complaint::where(['engineer_out_date' => Carbon::today(), 'main_machine_type' => $main_machine_type])->wherehas('status', function ($q) {
            $q->where('id',3);
        })->count();

        $data['todays_total_pending_complaints'] = Complaint::where(['date' => Carbon::today(), 'main_machine_type' => $main_machine_type])->wherehas('status', function ($q) {
            $q->where('id', 1);
        })->count();

        $data['todays_total_inprogress_complaints'] = Complaint::where(['date' => Carbon::today(), 'main_machine_type' => $main_machine_type])->wherehas('status', function ($q) {
            $q->where('id', 2);
        })->count();

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

        $data['all_present_engineers'] = Attendtl::where('in_date', date('Y-m-d'))
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

        return view('machineservicestates', $data);
    }

    public function machineservicestatesdata($id, Request $request)
    {
        // $main_machine_type = $id;
        // $main_machine_name = 'TEXTALK';
        // $data['main_machine_type'] = $main_machine_type;
        // if($main_machine_type == 1){ $main_machine_name = 'TEXTALK'; }
        // if($main_machine_type == 2){ $main_machine_name = 'ZETA'; }
        // if($main_machine_type == 3){ $main_machine_name = 'RARE'; }
        // if($main_machine_type == 4){ $main_machine_name = 'BEADS'; }
        // if($main_machine_type == 5){ $main_machine_name = 'INK'; }
        // $data['main_machine_name'] = $main_machine_name;
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $data['total_pending_complaints'] = 0;
        $data['total_inprogress_complaints'] = 0;
        $data['total_done_complaints'] = 0;
        $data['total_todays_complaints'] = 0;
        $data['todays_total_done_complaints'] = 0;
        $data['todays_total_pending_complaints'] = 0;
        $data['todays_total_inprogress_complaints'] = 0;

        $data['total_pending_complaints'] = Complaint::where(['status_id' => 1, 'main_machine_type' => $main_machine_type])->count();

        $data['total_inprogress_complaints'] = Complaint::where(['status_id' => 2, 'main_machine_type' => $main_machine_type])->count();

        $data['total_done_complaints'] = Complaint::where(['status_id' => 3, 'main_machine_type' => $main_machine_type])->count();

        $data['total_todays_complaints'] = Complaint::where(['date' => Carbon::today(), 'main_machine_type' => $main_machine_type])->count();

        $data['todays_total_done_complaints'] = Complaint::where(['engineer_out_date' => Carbon::today(), 'main_machine_type' => $main_machine_type])->wherehas('status', function ($q) {
            $q->where('id',3);
        })->count();

        $data['todays_total_pending_complaints'] = Complaint::where(['date' => Carbon::today(), 'main_machine_type' => $main_machine_type])->wherehas('status', function ($q) {
            $q->where('id', 1);
        })->count();

        $data['todays_total_inprogress_complaints'] = Complaint::where(['date' => Carbon::today(), 'main_machine_type' => $main_machine_type])->wherehas('status', function ($q) {
            $q->where('id', 2);
        })->count();

        $data['eng_pending_complaints'] = Complaint::whereNotNull('engineer_id')
            ->where('main_machine_type', $main_machine_type)
            ->whereHas('status', function ($q) {
                $q->where('id', 1);
            })->count();
        // Get the complaints grouped by engineer
        $data['eng_done_complaints'] = Complaint::select('engineer_id', DB::raw('COUNT(*) as engineer_count'))
            ->with('engineer')
            ->whereNotNull('engineer_id')
            ->where(['engineer_out_date' => Carbon::today(), 'main_machine_type' => $main_machine_type])
            ->whereHas('status', function ($q) {
                $q->where('id', 3);
            })
            ->groupBy('engineer_id')
            ->get();

        // Calculate the total of engineer_count
        $data['today_eng_done_complaints'] = $data['eng_done_complaints']->sum('engineer_count');

        $data['pending_assign_complaints'] = Complaint::whereNull('engineer_id')
            ->where('main_machine_type', $main_machine_type)
            ->whereHas('status', function ($q) {
                $q->where('id', '!=', 3);
            })->count();
        
        $data['today_present_engineer'] = Attendtl::where('in_date', date('Y-m-d'))->get()->count();

        // $data['all_present_engineers'] = Attendtl::where('in_date', date('Y-m-d'))
        // ->where('ap', 'P')
        // ->select('in_late', 'in_long', 'in_selfie') // include image field
        // ->get();

        // new
        // $data['all_present_engineers'] = Attendtl::where('in_date', date('Y-m-d'))
        // ->where('ap', 'P')
        // ->join('users', 'attendtl.engineer_id', '=', 'users.id')
        // ->join('userwise_machines', 'users.id', '=', 'userwise_machines.user_id')
        // ->with(['users.roles'])
        // ->select('attendtl.in_late', 'attendtl.in_long', 'attendtl.in_selfie', 'users.name as engineer_name')
        // ->where('userwise_machines.main_machine_type', $main_machine_type)
        // ->get();

        
         
        return view('machineservicestatesdetails', $data);
    }

    public function machinesalesstates($id, Request $request)
    {
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $data['total_pending_leads'] = 0;
        $data['total_inprogress_leads'] = 0;
        $data['total_done_leads'] = 0;
        $data['total_todays_leads'] = 0;
        $data['todays_total_done_leads'] = 0;
        $data['todays_total_pending_leads'] = 0;
        $data['todays_total_inprogress_leads'] = 0;

        $data['total_pending_leads'] = SalesPerson::where(['status_id' => 4, 'main_machine_type'=> $main_machine_type])->count();
        $data['total_inprogress_leads'] = SalesPerson::where([ 'status_id' => 5, 'main_machine_type'=> $main_machine_type])->count();
        $data['total_done_leads'] = SalesPerson::where(['status_id' => 7, 'main_machine_type' => $main_machine_type])->count();

        $data['total_todays_leads'] = SalesPerson::where(['date' => Carbon::today(), 'main_machine_type' => $main_machine_type])->count();
        $data['todays_total_done_leads'] = SalesPerson::where(['status_id' => 7, 'date' => Carbon::today(), 'main_machine_type' => $main_machine_type])->count();
        $data['todays_total_pending_leads'] = SalesPerson::where(['status_id' => 4, 'date' => Carbon::today(), 'main_machine_type' => $main_machine_type])->count();
        $data['todays_total_inprogress_leads'] = SalesPerson::where(['status_id' => 5, 'date' => Carbon::today(), 'main_machine_type' => $main_machine_type])->count();

        $data['all_present_sales_person'] = DB::table('attendtl')
        ->where('attendtl.in_date', date('Y-m-d'))
        ->where('attendtl.ap', 'P')
        ->join('users', 'attendtl.engineer_id', '=', 'users.id')
        ->join('model_has_roles', function ($join) {
            $join->on('users.id', '=', 'model_has_roles.model_id')
                ->where('model_has_roles.model_type', '=', \App\Models\User::class);
        })
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('roles.id', 5) // Filter by specific role
        ->select(
            'attendtl.in_late',
            'attendtl.in_long',
            'attendtl.in_selfie',
            'users.name as engineer_name'
        )
        ->get();
        
        return view('machinesalesstates', $data);
    }

    public function statesAdmin(Request $request)
    {
        $issalesorserv = $request['services'];
        $mainMachineType = $request['main_machine_type'];

        //dd($issalesorserv);
        if(isset($issalesorserv) && $issalesorserv==1)
        {
            $data['total_complaints'] = 0;
            $data['pending_complaints'] = 0;
            $data['in_progress_complaints'] = 0;
            $data['closed_complaints'] = 0;
            //if isset main_machine_type
            if(isset($request['main_machine_type']))
            {
                $data['pending_complaints'] = Complaint::where('status_id', 1)
                ->where('main_machine_type', $mainMachineType)
                ->count();
                $data['in_progress_complaints'] = Complaint::where('status_id', 2)
                ->where('main_machine_type', $mainMachineType)
                ->count();
                 $data['closed_complaints'] = Complaint::where(['status_id' => 3, 'engineer_out_date' => date('Y-m-d'), 'main_machine_type' => $mainMachineType])->count();
                 $data['total_complaints'] = $data['pending_complaints'] + $data['in_progress_complaints'] + $data['closed_complaints'];
            } else {       
                $data['pending_complaints'] = Complaint::where('status_id', 1)->count();
                $data['in_progress_complaints'] = Complaint::where('status_id', 2)->count();
                $data['closed_complaints'] = Complaint::where(['status_id' => 3, 'engineer_out_date' => date('Y-m-d')])->count();
                $data['total_complaints'] = $data['pending_complaints'] + $data['in_progress_complaints'] + $data['closed_complaints'];
            }    
            
        } else {
            $data['total_complaints'] = 0;
            $data['pending_complaints'] = 0;
            $data['in_progress_complaints'] = 0;
            $data['closed_complaints'] = 0;
            if(isset($request['main_machine_type']))
            {
                $data['pending_complaints'] = SalesPerson::where('status_id', 4)
                ->where('main_machine_type', $mainMachineType)
                ->count();
                $data['in_progress_complaints'] = SalesPerson::where('status_id', 5)
                ->where('main_machine_type', $mainMachineType)
                ->count();
                $data['closed_complaints'] = SalesPerson::where(['status_id' => 7, 'closed_date' => date('Y-m-d'), 'main_machine_type' => $mainMachineType])->count();
                $data['total_complaints'] = $data['pending_complaints'] + $data['in_progress_complaints'] + $data['closed_complaints'];
            } else {   
                $data['pending_complaints'] = SalesPerson::where('status_id', 4)->count();
                $data['in_progress_complaints'] = SalesPerson::where('status_id', 5)->count();
                $data['closed_complaints'] = SalesPerson::where(['status_id' => 7, 'closed_date' => date('Y-m-d')])->count();
                $data['total_complaints'] = $data['pending_complaints'] + $data['in_progress_complaints'] + $data['closed_complaints'];
            }    
        }

        return response()->json([
            "success" => true,
            "message" => "Dashboard status",
            "data" => $data,
        ]);
    }

    public function statesManager(Request $request)
    {
        $issalesorserv = $request['services'];
        $mainMachineType = $request['main_machine_type'];
        $lUserid = auth()->user()->id;
        //dd($issalesorserv);
        if(isset($issalesorserv) && $issalesorserv==1)
        {
            $data['total_complaints'] = 0;
            $data['pending_complaints'] = 0;
            $data['in_progress_complaints'] = 0;
            $data['closed_complaints'] = 0;
            
            //if isset main_machine_type
            if(isset($request['main_machine_type']))
            {
                $data['pending_complaints'] = Complaint::where(['status_id' => 1,'main_machine_type' => $mainMachineType])->whereIn('service_type_id', [2, 3])->count();
                $data['in_progress_complaints'] = Complaint::where(['status_id' => 2,'main_machine_type' => $mainMachineType])->whereIn('service_type_id', [2, 3])->count();
                 $data['closed_complaints'] = Complaint::where(['status_id' => 3, 'engineer_out_date' => date('Y-m-d'), 'main_machine_type' => $mainMachineType])->whereIn('service_type_id', [2, 3])->count();
                 $data['total_complaints'] = $data['pending_complaints'] + $data['in_progress_complaints'] + $data['closed_complaints'];
            } else {       
                $data['pending_complaints'] = Complaint::where(['status_id' => 1])->whereIn('service_type_id', [2, 3])->count();
                $data['in_progress_complaints'] = Complaint::where(['status_id' => 2])->whereIn('service_type_id', [2, 3])->count();
                $data['closed_complaints'] = Complaint::where(['status_id' => 3, 'engineer_out_date' => date('Y-m-d')])->whereIn('service_type_id', [2, 3])->count();
                $data['total_complaints'] = $data['pending_complaints'] + $data['in_progress_complaints'] + $data['closed_complaints'];
            }    
            
        } else {
            $data['total_complaints'] = 0;
            $data['pending_complaints'] = 0;
            $data['in_progress_complaints'] = 0;
            $data['closed_complaints'] = 0;
            if(isset($request['main_machine_type']))
            {
                $data['pending_complaints'] = SalesPerson::where(['sale_user_id' => $lUserid, 'status_id' => 4, 'main_machine_type'=> $mainMachineType])->count();
                $data['in_progress_complaints'] = SalesPerson::where(['sale_user_id' => $lUserid, 'status_id' => 5, 'main_machine_type'=> $mainMachineType])->count();
                $data['closed_complaints'] = SalesPerson::where(['sale_user_id' => $lUserid, 'status_id' => 7, 'date' => date('Y-m-d'), 'main_machine_type' => $mainMachineType])->count();
                $data['total_complaints'] = $data['pending_complaints'] + $data['in_progress_complaints'] + $data['closed_complaints'];
            } else {   
                $data['pending_complaints'] = SalesPerson::where(['sale_user_id' => $lUserid, 'status_id' => 4])->count();
                $data['in_progress_complaints'] = SalesPerson::where(['sale_user_id' => $lUserid, 'status_id' => 5])->count();
                $data['closed_complaints'] = SalesPerson::where(['sale_user_id' => $lUserid, 'status_id' => 7, 'date' => date('Y-m-d')])->count();
                $data['total_complaints'] = $data['pending_complaints'] + $data['in_progress_complaints'] + $data['closed_complaints'];
            }    
        }

        return response()->json([
            "success" => true,
            "message" => "Dashboard status",
            "data" => $data,
        ]);
    }

    public function installationStatesManager(Request $request)
    {
        
        $mainMachineType = $request['main_machine_type'];
        $lUserid = auth()->user()->id;
        //dd($issalesorserv);
        
            $data['total_complaints'] = 0;
            $data['pending_complaints'] = 0;
            $data['in_progress_complaints'] = 0;
            $data['closed_complaints'] = 0;
            
            //if isset main_machine_type
            if(isset($request['main_machine_type']))
            {
                $data['pending_complaints'] = MachineSalesEntry::where(['status' => 1,'main_machine_type' => $mainMachineType])->count();
                $data['in_progress_complaints'] = MachineSalesEntry::where(['status' => 2,'main_machine_type' => $mainMachineType])->count();
                 $data['closed_complaints'] = MachineSalesEntry::where(['status' => 3, 'main_machine_type' => $mainMachineType])->count();
                 $data['total_complaints'] = $data['pending_complaints'] + $data['in_progress_complaints'] + $data['closed_complaints'];
            } else {       
                $data['pending_complaints'] = MachineSalesEntry::where(['status' => 1])->count();
                $data['in_progress_complaints'] = MachineSalesEntry::where(['status' => 2])->count();
                $data['closed_complaints'] = MachineSalesEntry::where(['status' => 3, 'date' => date('Y-m-d')])->count();
                $data['total_complaints'] = $data['pending_complaints'] + $data['in_progress_complaints'] + $data['closed_complaints'];
            }    
            
        

        return response()->json([
            "success" => true,
            "message" => "Installation Dashboard states",
            "data" => $data,
        ]);
    }

    public function installationComplaintStatesManager(Request $request)
    {
        
        $mainMachineType = $request['main_machine_type'];
        $lUserid = auth()->user()->id;
        //dd($issalesorserv);
        
            $data['total_complaints'] = 0;
            $data['pending_complaints'] = 0;
            $data['in_progress_complaints'] = 0;
            $data['closed_complaints'] = 0;
            
            //if isset main_machine_type
            if(isset($request['main_machine_type']))
            {
                $data['pending_complaints'] = Complaint::where(['status_id' => 1,'main_machine_type' => $mainMachineType, 'service_type_id' => 4])->count();
                $data['in_progress_complaints'] = Complaint::where(['status_id' => 2,'main_machine_type' => $mainMachineType, 'service_type_id' => 4])->count();
                 $data['closed_complaints'] = Complaint::where(['status_id' => 3, 'engineer_out_date' => date('Y-m-d'), 'main_machine_type' => $mainMachineType, 'service_type_id' => 4])->count();
                 $data['total_complaints'] = $data['pending_complaints'] + $data['in_progress_complaints'] + $data['closed_complaints'];
            } else {       
                $data['pending_complaints'] = Complaint::where(['status_id' => 1,'main_machine_type' => $mainMachineType, 'service_type_id' => 4])->count();
                $data['in_progress_complaints'] = Complaint::where(['status_id' => 2,'main_machine_type' => $mainMachineType, 'service_type_id' => 4])->count();
                 $data['closed_complaints'] = Complaint::where(['status_id' => 3, 'engineer_out_date' => date('Y-m-d'), 'main_machine_type' => $mainMachineType, 'service_type_id' => 4])->count();
                 $data['total_complaints'] = $data['pending_complaints'] + $data['in_progress_complaints'] + $data['closed_complaints'];
            }    
            
        

        return response()->json([
            "success" => true,
            "message" => "Installation Complaint Dashboard states",
            "data" => $data,
        ]);
    }

    public function statesLeader(Request $request)
    {
        $issalesorserv = $request['services'];
        $mainMachineType = $request['main_machine_type'];
        $lUserid = auth()->user()->id;

        //dd($issalesorserv);
        if(isset($issalesorserv) && $issalesorserv==1)
        {
            $data['total_complaints'] = 0;
            $data['pending_complaints'] = 0;
            $data['in_progress_complaints'] = 0;
            $data['closed_complaints'] = 0;
            //if isset main_machine_type
            if(isset($request['main_machine_type']))
            {
                $data['pending_complaints'] = Complaint::where(['status_id' => 1,'main_machine_type' => $mainMachineType])->count();
                $data['in_progress_complaints'] = Complaint::where(['status_id' => 2,'main_machine_type' => $mainMachineType])->count();
                 $data['closed_complaints'] = Complaint::where(['status_id' => 3, 'engineer_out_date' => date('Y-m-d'), 'main_machine_type' => $mainMachineType])->count();
                 $data['total_complaints'] = $data['pending_complaints'] + $data['in_progress_complaints'] + $data['closed_complaints'];
            } else {       
                $data['pending_complaints'] = Complaint::where(['status_id' => 1])->count();
                $data['in_progress_complaints'] = Complaint::where(['status_id' => 2])->count();
                $data['closed_complaints'] = Complaint::where(['status_id' => 3, 'engineer_out_date' => date('Y-m-d')])->count();
                $data['total_complaints'] = $data['pending_complaints'] + $data['in_progress_complaints'] + $data['closed_complaints'];
            }      
        } else {
            $data['total_complaints'] = 0;
            $data['pending_complaints'] = 0;
            $data['in_progress_complaints'] = 0;
            $data['closed_complaints'] = 0;
            if(isset($request['main_machine_type']))
            {
                $data['pending_complaints'] = SalesPerson::where(['sale_user_id' => $lUserid, 'status_id' => 4, 'main_machine_type'=> $mainMachineType])->count();
                $data['in_progress_complaints'] = SalesPerson::where(['sale_user_id' => $lUserid, 'status_id' => 5, 'main_machine_type'=> $mainMachineType])->count();
                $data['closed_complaints'] = SalesPerson::where(['sale_user_id' => $lUserid, 'status_id' => 7, 'date' => date('Y-m-d'), 'main_machine_type' => $mainMachineType])->count();
                $data['total_complaints'] = $data['pending_complaints'] + $data['in_progress_complaints'] + $data['closed_complaints'];
            } else {   
                $data['pending_complaints'] = SalesPerson::where(['sale_user_id' => $lUserid, 'status_id' => 4])->count();
                $data['in_progress_complaints'] = SalesPerson::where(['sale_user_id' => $lUserid, 'status_id' => 5])->count();
                $data['closed_complaints'] = SalesPerson::where(['sale_user_id' => $lUserid, 'status_id' => 7, 'date' => date('Y-m-d')])->count();
                $data['total_complaints'] = $data['pending_complaints'] + $data['in_progress_complaints'] + $data['closed_complaints'];
            } 
        }

        return response()->json([
            "success" => true,
            "message" => "Dashboard status",
            "data" => $data,
        ]);
    }

    public function managerMachineList()
    {
        //$data = UserwiseMachine::where('user_id', auth()->user()->id)->get()->toArray();
        // Before Machine Master
        // $data = UserwiseMachine::where('user_id', auth()->user()->id)
        // ->Join('users', function ($join) {
        //     $join->on('users.id', '=', 'user_id')
        //         ->where(function ($query) {
        //             $query->where('users.is_active', '=', 1); // Active users machine data only
        //         });
        // })
        // ->select('main_machine_type')
        // ->get()
        // ->toArray();

        $user = Auth::user();
               
        $userRole = strtolower(auth()->user()->getRoleNames()->first());
        if($userRole == "admin") {
            //$assignedMachineTypes = array_keys(config('constants.MACHINE_TYPES'));
            $data = Machine::where('status', 1)->get()->toArray();
            //$assignedMachineTypes = array_keys($machineTypes);
        } else if($userRole == "service team leader" || $userRole == "sales team leader" || $userRole == "team leader") {
             //$assignedMachineTypes = UserwiseMachine::where('user_id',$user->id)->select('main_machine_type')->pluck('main_machine_type')->toArray();
            $data =  UserwiseMachine::with('machine')
                                                        ->where('user_id', $user->id)
                                                        ->get()
                                                        ->toArray();

        } else{
            $data =  UserwiseMachine::with('machine')
                                                        ->where('user_id', $user->id)
                                                        ->get()
                                                        ->toArray();
        }
        //dd($assignedMachineTypes);
        //$data['assignedMachineTypes'] = $assignedMachineTypes;

        return response()->json([
            "success" => true,
            "message" => "Machine List Fetched",
            "data" => $data
        ]);
    }

    public function partyMachineList(Request $request)
    {
            //$data = Machine::where('status', 1)->get()->toArray();
             $data =  PartywiseMachine::with('machine')
                      ->where('party_id', $request->party_id)
                      ->get()
                      ->toArray();

            if(isset($data) && !empty($data)) {
                return response()->json([
                    "success" => true,
                    "message" => "Machine List Fetched",
                    "data" => $data
                ]);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "Machine List Not Fetched",
                    "data" => []
                ]);
            }
           
    }

    public function leaderMachineList()
    {
        // $data = UserwiseMachine::where('user_id', auth()->user()->id)
        // ->Join('users', function ($join) {
        //     $join->on('users.id', '=', 'user_id')
        //         ->where(function ($query) {
        //             $query->where('users.is_active', '=', 1) // Active users machine data only
        //                   ->where('users.is_leader', '=', 1); // Leader only
        //         });
        // })
        // ->select('main_machine_type')
        // ->get()
        // ->toArray();

        $user = Auth::user();
               
        $userRole = strtolower(auth()->user()->getRoleNames()->first());
         if($userRole == "service team leader" || $userRole == "sales team leader" || $userRole == "team leader") {
             //$assignedMachineTypes = UserwiseMachine::where('user_id',$user->id)->select('main_machine_type')->pluck('main_machine_type')->toArray();
            $data =  UserwiseMachine::with('machine')
                                                        ->where('user_id', $user->id)
                                                        ->get()
                                                        ->toArray();

        } else {
            $data =  UserwiseMachine::with('machine')
                                                        ->where('user_id', $user->id)
                                                        ->get()
                                                        ->toArray();
        }
        //dd($assignedMachineTypes);
        //$data['assignedMachineTypes'] = $assignedMachineTypes;

        return response()->json([
            "success" => true,
            "message" => "Machine List Fetched",
            "data" => $data
        ]);
    }

    public function dashboardApi(Request $request)
    {
        if (auth()->user()->hasRole('Engineer')) {
            $useridn = Auth::user()->id;
            // Get the complain count enginer wish 
           // $data['total_complaints'] = Complaint::where('engineer_id', auth()->user()->id)->count();
           $data['total_complaints'] = Complaint::where(function ($query) use ($useridn) {
                $query->whereHas('jointcomplaintengs', function ($q) use ($useridn) {
                    $q->where('joint_eng_id', $useridn);
                })->orWhere('engineer_id', $useridn);
                
            })->count();

            //$data['pending_complaints'] = Complaint::where(['engineer_id' => auth()->user()->id, 'status_id' => 1])->count();
            $data['pending_complaints'] = Complaint::where(function ($query) use ($useridn) {
                $query->whereHas('jointcomplaintengs', function ($q) use ($useridn) {
                    $q->where('joint_eng_id', $useridn);
                })->orWhere('engineer_id', $useridn);
                
            })
            ->where('status_id', 1)
            //->where('date', date('Y-m-d'))
            ->count();

            //$data['in_progress_complaints'] = Complaint::where(['engineer_id' => auth()->user()->id, 'status_id' => 2])->count();
            $data['in_progress_complaints'] = Complaint::where(function ($query) use ($useridn) {
                $query->whereHas('jointcomplaintengs', function ($q) use ($useridn) {
                    $q->where('joint_eng_id', $useridn);
                })->orWhere('engineer_id', $useridn);
                
            })
            ->where('status_id', 2)
            //->where('date', date('Y-m-d'))
            ->count();

            // Count only complain closed today
            //$data['closed_complaints'] = Complaint::where(['engineer_id' => auth()->user()->id, 'status_id' => 3, 'engineer_out_date' => date('Y-m-d')])->count();
            $data['closed_complaints'] = Complaint::where(function ($query) use ($useridn) {
                $query->whereHas('jointcomplaintengs', function ($q) use ($useridn) {
                    $q->where('joint_eng_id', $useridn);
                })->orWhere('engineer_id', $useridn);
                
            })
            ->where('status_id', 3)
            ->where('engineer_out_date', date('Y-m-d'))
            ->count();

            return response()->json([
                "success" => true,
                "message" => "Dashboard status",
                "data" => $data,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong",
                "data" => (object)[],
            ]);
        }
    }

    public function salesDashboardApi(Request $request)
    {
        if (auth()->user()->hasRole('Sales Person')) {
            // Get the sales lead count sales person wish 
            $data['total_complaints'] = SalesPerson::where('sale_assign_user_id', auth()->user()->id)->count();

            $data['pending_complaints'] = SalesPerson::where(['sale_assign_user_id' => auth()->user()->id, 'status_id' => 4])->count();

            $data['in_progress_complaints'] = SalesPerson::where(['sale_assign_user_id' => auth()->user()->id, 'status_id' => 5])->count();

            // Count only complain closed today
            $data['closed_complaints'] = SalesPerson::where(['sale_assign_user_id' => auth()->user()->id, 'status_id' => 7, 'date' => date('Y-m-d')])->count();

            // $data['total_complaints'] = $data['pending_complaints'] + $data['in_progress_complaints'] + $data['closed_complaints'];

            return response()->json([
                "success" => true,
                "message" => "Dashboard status",
                "data" => $data,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong",
                "data" => (object)[],
            ]);
        }
    }

    public function salesDashboard()
    {
        return view('sales_dashboard');
    }

    // Get the today report
    public function reportToday(Request $request)
    {
        $report_date = $request->input('date') ?? date('Y-m-d'); // Get the selected date, default to today if not set
        $total_pending_complaints = Complaint::with('party', 'product', 'machineSalesEntry', 'engineer')
            ->whereHas('status', function ($q) {
                $q->where('id', '!=', 3);
            })->get()->toArray();

        $total_todays_complaints = Complaint::with('party', 'product', 'machineSalesEntry', 'engineer')
            ->whereDate('date', $report_date)
            ->get()->toArray();

        $todays_total_dones = Complaint::with('party', 'product', 'machineSalesEntry', 'engineer')
            ->where('engineer_out_date', '=',  $report_date)
            ->whereHas('status', function ($q) {
                $q->where('id', 3);
            })->get()->toArray();

        $data = [
            'title' => 'Today Report',
            'total_pending_complaints' => $total_pending_complaints,
            'total_todays_complaints' => $total_todays_complaints,
            'todays_total_dones' => $todays_total_dones,
            'report_date' => $report_date
        ];

        // Generate the PDF
        $pdf = PDF::loadView('report/report_pdf', $data)->setPaper('a4', 'portrait');

        // Stream the PDF to the browser
        return $pdf->stream('report.pdf');
    }

    public function freeEngineers()
    {
        $data['title'] = 'Free Engineer';
        $data['free_engineers'] = User::role('engineer')
            ->leftJoin('complaints', function ($join) {
                $join->on('users.id', '=', 'complaints.engineer_id')
                    ->where(function ($query) {
                        $query->where('complaints.status_id', '!=', 3)
                            ->orWhereNull('complaints.status_id'); // Handle cases with no complaints
                    });
            })
            ->select('users.id', 'users.name', DB::raw('COUNT(complaints.id) as pending_complaints'))
            ->groupBy('users.id', 'users.name')
            ->orderBy('pending_complaints', 'asc')
            ->get();

        return view('dashboard.free_engineers', $data);
    }

    public function engineerLastVisit()
    {
        $data['title'] = 'Engineer Last Visit Status';
        $data['engineer_last_visit'] = User::role('engineer')
            ->where('is_active', 1)
            ->with(['latestComplaint.party'])
            ->get(['id', 'name']);

        return view('dashboard.engineer_last_visit', $data);
    }

    public function todayPendingComplaint()
    {
        $data['title'] = 'Total Pending Complaints';
        $data['total_pending_complaints'] = Complaint::with('engineer', 'party', 'machineSalesEntry')
            ->whereHas('status', function ($q) {
                $q->where('id', '!=', 3);
            })
            ->orderBy('id', 'desc')
            ->get();
        return view('dashboard.total_pending_complaints', $data);
    }

    public function todayTotalComplaints()
    {
        $data['title'] = 'Total total Complaints';
        $data['total_pending_complaints'] = Complaint::where('created_at', '>=', Carbon::today())->get();
        return view('dashboard.today_total_complaints', $data);
    }

    public function todayPresentEngineer(Request $request)
    {
        $report_date = $request->input('date') ?? date('Y-m-d');
        $data['title'] = 'Today Present Engineers';
        $data['today_present_engineers'] = Attendtl::with('users')->where('in_date', $report_date)->get();
        $data['report_date'] = $report_date;

        return view('dashboard.today_present_engineers', $data);
    }

    public function todayDoneComplaints()
    {
        $data['today_done_complaints'] = Complaint::where('engineer_out_date', '=',  Carbon::today())->wherehas('status', function ($q) {
            $q->where('id', 3);
        })->get();

        $data['title'] = 'Total Done Complaints';
        return view('dashboard.today_done_complaints', $data);
    }

    public function engPendingComplaints()
    {
        $data['eng_pending_complaints'] = Complaint::select('engineer_id', DB::raw('COUNT(*) as engineer_count'))
            ->with('engineer')
            ->whereNotNull('engineer_id')
            ->whereHas('status', function ($q) {
                $q->where('id', '!=', 3);
            })
            ->groupBy('engineer_id')
            ->join('users', 'complaints.engineer_id', '=', 'users.id') // Join with the users table to access engineer names
            ->orderBy('users.name', 'asc') // Order by engineer's name in ascending order
            ->get();

        $data['title'] = 'Engineer Wise Pending + In Progress Complaints';

        return view('eng_pending_complaints', $data);
    }


    public function todayEngDoneComplaints()
    {
        $data['today_eng_done_complaints'] = Complaint::select('engineer_id', DB::raw('COUNT(*) as engineer_count'))
            ->with('engineer')->whereNotNull('engineer_id')
            ->where('engineer_out_date', Carbon::today())
            ->whereHas('status', function ($q) {
                $q->where('id', 3);
            })
            ->groupBy('engineer_id')->get();
        $data['title'] = 'Today Engineer Wise Done Complaints';
        return view('today_eng_done_complaints', $data);
    }

    public function pendingAssignComplaints()
    {
        $data['pending_assign_complaints'] = Complaint::with('party', 'product', 'machineSalesEntry')->whereNull('engineer_id')->whereHas('status', function ($q) {
            $q->where('id', '!=', 3);
        })->get();

        $data['title'] = 'Pending Assign Complaints';
        return view('pending_assign_complaints', $data);
    }

    public function todayExpiryMachine()
    {
        $data['today_expiry_machine'] = MachineSalesEntry::with('product', 'party')->where('is_active', 1)->where('service_expiry_date', date('Y-m-d'))->get();
        $data['title'] = 'Today Expiry Machines';
        return view('today_expiry_machine', $data);
    }
}
