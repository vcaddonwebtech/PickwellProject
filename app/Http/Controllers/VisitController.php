<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\User;
use App\Models\Machine;
use Illuminate\Http\Request;
use App\Models\MachineSalesEntry;
use App\Models\JointComplaint;
use App\Models\Complaint;
use App\Models\Party;
use App\Models\Product;
use App\Models\VisitStepsDone;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;
use Yajra\DataTables\Facades\DataTables;

class VisitController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * List visits
     */
    public function index($mid, $msid, Request $request)
    {
        $main_machine_type = $mid;
        //$msid = $msid;
        $data['main_machine_type'] = $main_machine_type;
        $data['machinesale'] = $msid;
        $main_machine_data = Machine::where('id', $mid)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $data['title'] = $main_machine_name. ' -> Installation Visits';
    
        if ($request->ajax()) {
            //$machineSalse = $data['machine'] = MachineSalesEntry::where('main_machine_type', $main_machine_type)->with('party', 'product',  'serviceType', 'micFittingEngineer', 'deliveryEngineer')->latest();
             $visits = $data['visits'] = Visit::where(['main_machine_type' => $main_machine_type, 'service_id' => $msid])->with(['party', 'product', 'engineer:id,name', 'machine:id,machine_name'])->latest();
            
            if (isset($request->date_range)) {
                [$startDate, $endDate] = explode(' to ', $request->date_range);
                $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
                $visits->whereBetween('date', [$startDate, $endDate]);
            }
            if (isset($request->product_id)) {
                $visits->where('product_id', $request->product_id);
            }
            if (isset($request->party_id)) {
                $visits->where('party_id', $request->party_id);
            }
            $visitsdata = $visits->get();
            //dd($visitsdata);

            return DataTables::of($visitsdata)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($main_machine_type, $msid) {
                    $btn = "<div class='btn-group m-1'><a class='btn btn-sm btn-primary' href='" . route('visits.edit', ['main_machine_type' => $main_machine_type, 'machinesale' => $msid, $row->id]) . "'><i class='fa fa-edit'></i></a>";
                    $btn .= "<a class='btn btn-sm btn-danger' href='javascript:void(0)' onclick='window.deleteParty(" . $row->id . ")'><i class='fa fa-trash'></i></a></div> ";
                    return $btn;
                })
                
                ->addColumn("date", function ($row) {
                    return date('Y-m-d', strtotime($row->date));
                })
                ->addColumn("status", function ($row) {
                    if($row->status == 0 || $row->status == 1){
                        $status="Pending";
                    } else if($row->status == 2) {
                        $status="In Progress";
                    } else if ($row->status == 3){
                        $status="Closed";
                    } else {
                        $status="Pending";
                    }
                    return $status;
                })
                
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('visits.index', $data);
    }

    /**
     * Create form
     */
    public function create($mid, $msid, Request $request)
    {
        $main_machine_type = $mid;
        //$msid = $msid;
        
        $data['main_machine_type'] = $main_machine_type;
        $data['machinesale'] = $msid;
        $main_machine_data = Machine::where('id', $mid)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $data['title'] = $main_machine_name. ' -> Installation('. $msid.') -> Create Visit';
        $currentDate = Carbon::now()->format('d-m-Y');
        $data['today'] = $currentDate;

        $machinesaledata = MachineSalesEntry::where(['id' => $msid, 'main_machine_type' => $main_machine_type])->first();
        $msparty_id = $machinesaledata->party_id;
        $msproduct_id = $machinesaledata->product_id;
        $data['msrno'] = $machinesaledata->serial_no;
        //get parties with machine type
        $data['parties'] = Party::with(['area', 'city'])
        ->where('id', $msparty_id)
        ->whereHas('partywiseMachines', function ($query) use ($main_machine_type) {
            $query->where('main_machine_type', $main_machine_type);
        })->first();

        //get products with machine type
        $data['products'] = Product::where(['id' => $msproduct_id, 'product_group_id' => $mid])->first();

        // get users with machine type
        // $data['engineers'] = User::whereHas('roles', function ($query) {
        //             $query->whereIn('name', ['Engineer']); // show only engineers to managers
        //         })
        //         ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
        //         $query->where('main_machine_type', $main_machine_type);
        //         })->latest()->get();

        $data['engineers'] = User::with('roles')
            ->role('engineer')
            ->where('is_active', 1)
            ->leftJoin('complaints', function ($join) {
                $join->on('users.id', '=', 'complaints.engineer_id')
                    ->where(function ($query) {
                        $query->where('complaints.status_id', '!=', 3)
                            ->orWhereNull('complaints.status_id'); // Handle cases with no complaints
                    });
            })
            ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                $query->where('main_machine_type', $main_machine_type);
                })
            ->select('users.id', 'users.name', DB::raw('COUNT(complaints.id) as pending_complaints'))
            ->groupBy('users.id', 'users.name')
            ->orderBy('users.name')
            ->get();

        return view('visits.create', $data);
    }

    /**
     * Store visit
     */
    public function store(Request $request)
    {
        
        $main_machine_type = $request->main_machine_type;
        $machinesale = $request->machinesale;
        $data['main_machine_type'] = $main_machine_type;
        $data['service_type'] = 2;
        $data['service_id'] = $machinesale;
        $data['product_id'] = $request->product_id;
        $data['party_id'] = $request->party_id;
        $data['engineer_id'] = $request->engineer_id;
        $data['title'] = $request->title;
        $data['date'] = $request->date;
        $data['status'] = $request->status;
        $data['end_date'] = isset($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : null;
        $data['visit_remark'] = $request->visit_remark;

        // Create complaint with visit
        if (isset($request->engineer_id)) {
                $complaint_data['user_id'] = 188;
                $complaint_data['engineer_id'] = $request->engineer_id;
                $complaint_data['is_assigned'] = 1;
                $complaint_data['engineer_assign_date'] = date('Y-m-d');
                $complaint_data['engineer_assign_time'] = date('h:i:s');
                $complaint_data['engineer_in_time'] = null;
                $complaint_data['engineer_out_time'] = null;
                $complaint_data['engineer_in_date'] = null;
                $complaint_data['engineer_out_date'] = null;
                $complaint_data['jointengg'] = isset($request->jointengg) ? implode(",", $request->jointengg) : null;
            } else {
                $complaint_data['engineer_id'] = null;
                $complaint_data['engineer_in_time'] = null;
                $complaint_data['engineer_in_date'] = null;
                $complaint_data['engineer_out_date'] = null;
                $complaint_data['engineer_out_time'] = null;
                $complaint_data['jointengg'] = null;
            }
            $complaint_data['is_urgent'] = 0;
            $complaint_data['is_free_service'] = 0;
            $complaint_data['date'] = isset($request->date) ? date('Y-m-d', strtotime($request->date)) : null;
            $currentTime = Carbon::now()->format('H:i:s');
            $complaint_data['time'] = $currentTime;
            $complaint_data['complaint_no'] = Complaint::whereDate('date', date('Y-m-d'))->count() + 1;
            $complaint_data['firm_id'] = 1;
            $complaint_data['year_id'] = 1;
            $complaint_data['party_id'] = $request->party_id;
            $complaint_data['complaint_type_id'] = 103;
            $complaint_data['sales_entry_id'] = $machinesale;
            $complaint_data['product_id'] = $request->product_id;
            $complaint_data['service_type_id'] = 4;
            $complaint_data['status_id'] = 1;
            $complaint_data['main_machine_type'] = $main_machine_type;

            $complaint = Complaint::create($complaint_data);
            $complaintId = $complaint->id;
            $data['complaint_id'] = $complaintId;
            $visit = Visit::create($data);

            // Steps Done Entry
            if(isset($request->stepsdone)) { 
                $stepsdonearr = $request->stepsdone;
                foreach ($stepsdonearr as $sdid) {
                    $stepsdone_data['visit_id'] = $visit->id;
                    $stepsdone_data['step_id'] = $sdid;
                    $stepsdone_data['status'] = 1;
                    //dd($jengid);
                    $stepsdone = VisitStepsDone::create($stepsdone_data);
                }
            }


            // Send push notification to engineer
            if (isset($request->engineer_id)) {
                $engineer = User::where('id', $request->engineer_id)->first();
            }
            if (!empty($engineer)) {
                $title = 'New Erection';
                $body = 'A new Installation - Erection work been assigned to you.';
                $token = $engineer->device_token;
                $type = 'engineer';

                if ($title != "" && $body != "" && $token != "") {
                    // Get the response from the helper function
                    // sendNotification($title, $body, $token);
                    //$this->notificationService->sendNotification($title, $body, $token);
                     $this->notificationService->sendAlarmNotification($title, $body, $token, $type, $complaint->id);
                }
            }

        // make entries to joint complaint table
            if (isset($request->jointengg)) {

                $joint_complaint_data = [];
                $joint_complaint_data['complaint_id'] = $machinesale;
                $joint_complaint_data['service_type'] = 2;
                $joint_complaint_data['visit_id'] = $visit->id;
                $joint_complaint_data['joint_eng_in_date'] = null;
                $joint_complaint_data['joint_eng_in_time '] = null;
                $joint_complaint_data['joint_eng_out_date'] = null;
                $joint_complaint_data['joint_eng_out_time'] = null;
                $joint_complaint_data['joint_eng_in_address'] = null;
                $joint_complaint_data['joint_eng_out_address'] = null;
                $joint_complaint_data['joint_eng_in_lat'] = null;
                $joint_complaint_data['joint_eng_in_lng'] = null;
                $joint_complaint_data['joint_eng_out_lat'] = null;
                $joint_complaint_data['joint_eng_out_lng'] = null;
                $joint_complaint_data['joint_eng_status'] = 0;
                $joint_complaint_data['joint_eng_remarks'] = null;
                //$request['jointengg'] = isset($request->jointengg) ? implode(",", $request->jointengg) : null;
                //$jointenggarr = explode(",", $request->jointengg);
                //$jointengg = $request->jointengg;
                $jointenggarr = $request->jointengg;
                $Jtoken = [];
                foreach ($jointenggarr as $jengid) {
                    $joint_complaint_data['joint_eng_id'] = $jengid;
                    //dd($jengid);
                    $joint_complaint = JointComplaint::create($joint_complaint_data);
                    if (!empty($jengid)) {
                        // Fetch engineer
                        $user = User::find($jengid);
                        // Collect valid device tokens
                        if (!empty($user?->device_token)) {
                            $Jtoken[] = $user->device_token;
                        }
                    }
                }  
                        $title = 'New Erection';
                        $body = 'A new Installation - Erection work been assigned to you.';
                        $type = 'engineer';
                        $this->notificationService->sendAlarmNotification($title, $body, $Jtoken, $type, $complaint->id);               
            }

        return redirect()->route('visits.index', ['main_machine_type' => $main_machine_type, 'machinesale' => $machinesale])->with('success', 'Visit created successfully');
    }

    /**
     * Edit form
     */
    public function edit($mid, $msid, $vid, Request $request)
    {
        $main_machine_type = $mid;
        //$msid = $msid;
        $data['visit_id'] = $vid;
        $data['main_machine_type'] = $main_machine_type;
        $data['machinesale'] = $msid;
        $main_machine_data = Machine::where('id', $mid)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $data['title'] = $main_machine_name. ' -> Installation('. $msid.') -> Edit Visit';
        $currentDate = Carbon::now()->format('d-m-Y');
        $data['today'] = $currentDate;

        $machinesaledata = MachineSalesEntry::where(['id' => $msid, 'main_machine_type' => $main_machine_type])->first();
        $data['party_id'] = $machinesaledata->party_id;
        $data['product_id'] = $machinesaledata->product_id;
        $data['msrno'] = $machinesaledata->serial_no;

        $visitdata = Visit::where(['id' => $vid, 'main_machine_type' => $main_machine_type])->first();
        $data['visitdata'] = $visitdata;

        $jointengg = JointComplaint::where('visit_id', $vid)
        ->where('service_type', 2)
        ->pluck('joint_eng_id')
        ->toArray();
        $data['jointengg'] = $jointengg;

         $visitdonesteps = VisitStepsDone::where('visit_id', $vid)
        ->where('status', 1)
        ->pluck('step_id')
        ->toArray();
        $data['visitdonesteps'] = $visitdonesteps;
        
        $data['engineers'] = User::with('roles')
            ->role('engineer')
            ->where('is_active', 1)
            ->leftJoin('complaints', function ($join) {
                $join->on('users.id', '=', 'complaints.engineer_id')
                    ->where(function ($query) {
                        $query->where('complaints.status_id', '!=', 3)
                            ->orWhereNull('complaints.status_id'); // Handle cases with no complaints
                    });
            })
            ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                $query->where('main_machine_type', $main_machine_type);
                })
            ->select('users.id', 'users.name', DB::raw('COUNT(complaints.id) as pending_complaints'))
            ->groupBy('users.id', 'users.name')
            ->orderBy('users.name')
            ->get();

        return view('visits.edit', $data);
    }

    /**
     * Update visit
     */
    public function update(Request $request, $mid, $vid)
    {
        //$visit->update($request->all());

        $main_machine_type = $request->main_machine_type;
        $machinesale = $request->machinesale;
        $data['main_machine_type'] = $main_machine_type;
        $data['service_type'] = 2;
        $data['service_id'] = $machinesale;
        $data['product_id'] = $request->product_id;
        $data['party_id'] = $request->party_id;
        $data['visit_id'] = $request->visit_id;
        $data['engineer_id'] = $request->engineer_id;
        $data['title'] = $request->title;
        $data['date'] = $request->date;
        $data['status'] = $request->status;
        $data['end_date'] = isset($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : null;
        $data['visit_remark'] = $request->visit_remark;

        // Create complaint with visit
        if (isset($request->engineer_id)) {
                $complaint_data['user_id'] = 188;
                $complaint_data['engineer_id'] = $request->engineer_id;
                $complaint_data['is_assigned'] = 1;
                $complaint_data['engineer_assign_date'] = date('Y-m-d');
                $complaint_data['engineer_assign_time'] = date('h:i:s');
                $complaint_data['engineer_in_time'] = null;
                $complaint_data['engineer_out_time'] = null;
                $complaint_data['engineer_in_date'] = null;
                $complaint_data['engineer_out_date'] = null;
                $complaint_data['jointengg'] = isset($request->jointengg) ? implode(",", $request->jointengg) : null;
            } else {
                $complaint_data['engineer_id'] = null;
                $complaint_data['engineer_in_time'] = null;
                $complaint_data['engineer_in_date'] = null;
                $complaint_data['engineer_out_date'] = null;
                $complaint_data['engineer_out_time'] = null;
                $complaint_data['jointengg'] = null;
            }
            $complaint_data['is_urgent'] = 0;
            $complaint_data['is_free_service'] = 0;
            $complaint_data['date'] = isset($request->date) ? date('Y-m-d', strtotime($request->date)) : null;
            $currentTime = Carbon::now()->format('H:i:s');
            $complaint_data['time'] = $currentTime;
            $complaint_data['complaint_no'] = Complaint::whereDate('date', date('Y-m-d'))->count() + 1;
            $complaint_data['firm_id'] = 1;
            $complaint_data['year_id'] = 1;
            $complaint_data['party_id'] = $request->party_id;
            $complaint_data['complaint_type_id'] = 103;
            $complaint_data['sales_entry_id'] = $machinesale;
            $complaint_data['product_id'] = $request->product_id;
            $complaint_data['service_type_id'] = 4;
            $complaint_data['status_id'] = 1;
            $complaint_data['main_machine_type'] = $main_machine_type;
            $visitdata = Visit::where('id', $request->visit_id)->first();
            $complaintId = $visitdata->complaint_id;
            $complaintdata = Complaint::where('id', $complaintId)->first();
            $complaintdata->update($complaint_data);
            //$complaintId = $complaint->id;
            $data['complaint_id'] = $complaintId;
            $visitdata->update($data);

            // Steps Done Entry by removing old entries
            VisitStepsDone::where('visit_id', $request->visit_id)->delete();
            if(isset($request->stepsdone)) { 
                $stepsdonearr = $request->stepsdone;
                foreach ($stepsdonearr as $sdid) {
                    $stepsdone_data['visit_id'] = $request->visit_id;
                    $stepsdone_data['step_id'] = $sdid;
                    $stepsdone_data['status'] = 1;
                    //dd($jengid);
                    $stepsdone = VisitStepsDone::create($stepsdone_data);
                }
            }


            // Send push notification to engineer
            if (isset($request->engineer_id)) {
                $engineer = User::where('id', $request->engineer_id)->first();
            }
            if (!empty($engineer)) {
                $title = 'New Erection';
                $body = 'A new Installation - Erection work been assigned to you.';
                $token = $engineer->device_token;
                $type = 'engineer';

                if ($title != "" && $body != "" && $token != "") {
                    // Get the response from the helper function
                    // sendNotification($title, $body, $token);
                    //$this->notificationService->sendNotification($title, $body, $token);
                     $this->notificationService->sendAlarmNotification($title, $body, $token, $type, $complaintdata->id);
                }
            }

        // make entries to joint complaint table
            if (isset($request->jointengg)) {

                $joint_complaint_data = [];
                $joint_complaint_data['complaint_id'] = $machinesale;
                $joint_complaint_data['service_type'] = 2;
                $joint_complaint_data['visit_id'] = $request->visit_id;
                $joint_complaint_data['joint_eng_in_date'] = null;
                $joint_complaint_data['joint_eng_in_time '] = null;
                $joint_complaint_data['joint_eng_out_date'] = null;
                $joint_complaint_data['joint_eng_out_time'] = null;
                $joint_complaint_data['joint_eng_in_address'] = null;
                $joint_complaint_data['joint_eng_out_address'] = null;
                $joint_complaint_data['joint_eng_in_lat'] = null;
                $joint_complaint_data['joint_eng_in_lng'] = null;
                $joint_complaint_data['joint_eng_out_lat'] = null;
                $joint_complaint_data['joint_eng_out_lng'] = null;
                $joint_complaint_data['joint_eng_status'] = 0;
                $joint_complaint_data['joint_eng_remarks'] = null;
                //$request['jointengg'] = isset($request->jointengg) ? implode(",", $request->jointengg) : null;
                //$jointenggarr = explode(",", $request->jointengg);
                //$jointengg = $request->jointengg;
                $jointenggarr = $request->jointengg;
                JointComplaint::where('complaint_id', $complaintId)->delete();
                $Jtoken = [];
                foreach ($jointenggarr as $jengid) {
                    $joint_complaint_data['joint_eng_id'] = $jengid;
                    //dd($jengid);
                    $joint_complaint = JointComplaint::create($joint_complaint_data);
                    if (!empty($jengid)) {
                        // Fetch engineer
                        $user = User::find($jengid);
                        // Collect valid device tokens
                        if (!empty($user?->device_token)) {
                            $Jtoken[] = $user->device_token;
                        }
                    }
                }  
                        $title = 'New Erection';
                        $body = 'A new Installation - Erection work been assigned to you.';
                        $type = 'engineer';
                        $this->notificationService->sendAlarmNotification($title, $body, $Jtoken, $type, $complaintdata->id);               
            }

        return redirect()->route('visits.index', ['main_machine_type' => $main_machine_type, 'machinesale' => $machinesale])->with('success', 'Visit Updated successfully');
    }

    /**
     * Delete visit
     */
    public function destroy(Visit $visit)
    {
        $visit->delete();

        return redirect()->route('visits.index')
            ->with('success', 'Visit deleted successfully');
    }
}
