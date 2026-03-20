<?php
//New controller
namespace App\Http\Controllers;

use App\DataTables\ComplaintDataTable;
use App\Http\Requests\ComplaintRequest;
use App\Models\Complaint;
use App\Models\ComplaintType;
use App\Models\Product;
use App\Models\ProductTypes;
use App\Models\JointComplaint;
use App\Models\ComplaintServicePartsDetail;
use App\Models\Firm;
use App\Models\MachineSalesEntry;
use App\Models\Party;
use App\Models\PartywiseMachine;
use App\Models\User;
use App\Models\UserwiseMachine;
use App\Models\Machine;
use App\Models\UsersPayroll;
use App\Models\Salary;
use App\Models\Attendtl;
use App\Models\CustomerFeedback;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Flasher\Toastr\Laravel\Facade\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use App\Exports\ComplaintPendingExport;
use App\Exports\ComplaintStatusExport;
use App\Exports\ComplaintTypeSummaryExport;
use App\Exports\EngineerDoneSummaryExport;
use App\Exports\EngineerPerformanceExport;
use App\Exports\CustomerFeedbackExport;
use App\Exports\APTodayExport;
use App\Exports\APSummaryExport;
use App\Exports\EngineerAPSummaryExport;
use Maatwebsite\Excel\Facades\Excel;

class ComplaintController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index($id, Request $request)
    {
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;

        $data['title'] = $main_machine_name. '-> Complaint List';
        
        $data['main_machine_type'] = $main_machine_type;
        
        $data['todayComplain'] = Complaint::where(['main_machine_type' => $main_machine_type, 'date' => date('Y-m-d')])->count();
        $data['todayDone'] = Complaint::where(['main_machine_type' => $main_machine_type, 'date' => date('Y-m-d')])
            ->whereNotNull('engineer_out_date')
            ->where('status_id', 3)->count();
        $data['tillNotAssign'] = Complaint::whereNull('engineer_id')
            ->where('main_machine_type', $main_machine_type)
            ->whereHas('status', function ($q) {
                $q->where('id', '!=', 3);
            })->count();
        $data['tillInProgress'] = Complaint::where(['main_machine_type' => $main_machine_type, 'status_id' => 2])->whereNotNull('engineer_id')->count();
        $data['tillPending'] = Complaint::where('main_machine_type', $main_machine_type)
            ->wherehas('status', function ($q) {
            $q->where('id', 1);
        })->count();
        $data['tillNotIn'] = Complaint::where('main_machine_type', $main_machine_type)
            ->whereNotNull('engineer_id')->whereNull('engineer_in_date')->wherehas('status', function ($q) {
            $q->where('id', '!=', 3);
        })->count();

        if ($request->ajax()) {
            // $collection = Complaint::query()->with('year', 'complaintType', 'salesEntry', 'product', 'engineer', 'serviceType', 'status', 'party')->latest();
            $collection = Complaint::select('id', 'date', 'time', 'complaint_no', 'complaint_type_id', 'sales_entry_id', 'product_id', 'engineer_id', 'service_type_id', 'status_id', 'party_id')
                ->with(
                    'complaintType:id,name',
                    'salesEntry:id,mc_no',
                    'product:id,name',
                    'engineer:id,name',
                    'serviceType:id,name',
                    'status:id,name',
                    'party:id,code,name,phone_no',
                )
                ->where('main_machine_type', $main_machine_type)
                ->latest();
            if (isset($request->complaint_no)) {
                $collection->where('complaint_no', $request->complaint_no);
            }
            if (isset($request->date_range)) {
                [$startDate, $endDate] = explode(' to ', $request->date_range);
                $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
                $collection->whereBetween('date', [$startDate, $endDate]);
            }
            if (isset($request->party_id)) {
                $collection->where('party_id', $request->party_id);
            }
            if (isset($request->engineer_id)) {
                $collection->where('engineer_id', $request->engineer_id);
            }
            if (isset($request->status)) {
                $collection->where('status_id', $request->status);
            }
            if (isset($request->service_type_id)) {
                $collection->where('service_type_id', $request->service_type_id);
            }
            if ($request->today_complain == 1) {
                $collection->where('date', date('Y-m-d'));
            }
            if ($request->today_done == 1) {
                $collection->where('date', date('Y-m-d'));
                $collection->whereNotNull('engineer_out_date');
                $collection->where('status_id', 3);
            }
            if ($request->till_not_assign == 1) {
                $collection->whereNull('engineer_id')->where('status_id', '!=', 3);
            }
            if ($request->till_in_progress == 1) {
                $collection->where('status_id', 2);
            }
            if ($request->till_pending == 1) {
                $collection->where('status_id', 1);
            }
            if ($request->party_id == 1) {
                $collection->where('party_id', $request->party_id);
            }
            if ($request->till_not_in == 1) {
                $collection->whereNotNull('engineer_id')->whereNull('engineer_in_date')->wherehas('status', function ($q) {
                    $q->where('id', '!=', 3);
                });
            }
            $collection = $collection->get();
            return DataTables::of($collection)
                ->addIndexColumn()
                ->addColumn('action', function (Complaint $complaint) use ($main_machine_type) {
                    return "<div class='btn-group m-1'>
                            <a class='btn btn-sm btn-primary' href='" . route('complaints.edit', ['main_machine_type' => $main_machine_type, 'complaint' => $complaint]) . "'><i class='fa fa-edit'></i></a>
                            <a class='btn btn-sm btn-danger' href='javascript:void(0)' onclick='window.deleteParty(" . $complaint->id . ")'><i class='fa fa-trash'></i></a>
                            <a class='btn btn-sm btn-info' href='" . route('complaints.view', ['complaint' => $complaint]) . "' ><i class='fa fa-eye'></i></a>
                            <a class='btn btn-sm btn-secondary' href='" . route('complaints.pdf', ['complaint' => $complaint]) . "' ><i class='fa fa-download'></i></a>                            
                            </div>";
                })
                ->addColumn("date", function ($row) {
                    return date('d-m-Y', strtotime($row->date));
                })
                ->addColumn('mc_no', function ($row) {
                    //return $row->salesEntry->serial_no . ' - ' . $row->salesEntry->mc_no ?? 'N/A';
                    return $row->salesEntry->mc_no ?? 'N/A';
                })
                ->addColumn('complaint_no', function ($row) {
                    if ($row->complaint_no == 1) {
                        return '<b>' . $row->complaint_no . ' / C</b>';
                    }
                    return $row->complaint_no;
                })
                ->rawColumns(['action', 'complaint_no'])
                ->make(true);
        }
        return view('complaint.index', $data);
    }

    public function create($id, Request $request)
    { 
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $data['title'] = $main_machine_name. '-> Create Complaint';
        
        $data['main_machine_type'] = $main_machine_type;
        $complaint_no = Complaint::whereDate('created_at', date('Y-m-d'))->count() + 1;
        // $complaint_no = 1;
        // $complaint = Complaint::orderBy('id', 'desc')->first();
        // if (!empty($complaint)) {
        //     $complaint_no = $complaint->complaint_no + 1;
        // }

        $data['complaint_no'] = $complaint_no;
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

       
        // $data['all_present_engineers'] = Attendtl::where('in_date', date('Y-m-d'))
        // ->where('ap', 'P')
        // ->join('users', 'attendtl.engineer_id', '=', 'users.id')
        // ->select('attendtl.in_late', 'attendtl.in_long', 'attendtl.in_selfie', 'users.name as engineer_name')
        // ->get();    

        //New with live location
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

        $data['parties'] = Party::with(['area', 'city'])
        ->whereHas('partywiseMachines', function ($query) use ($main_machine_type) {
            $query->where('main_machine_type', $main_machine_type);
        })
        ->latest()
        ->get();

        $complaintTypes = ComplaintType::where('main_machine_type', $main_machine_type)->latest()->get();
        $data['complaint_types'] = $complaintTypes;

        return view('complaint.create', $data);
    }

    public function store(ComplaintRequest $request)
    {
        if ($request->validated()) {
            // Skipping the creatyed complaints check, can create any number of complaints of a party
            // $complaintData = Complaint::where('sales_entry_id', $request->sales_entry_id)->where('status_id', '!=', 3)->first();
            // if (!empty($complaintData)) {
            //     Toastr::error('Complaint already created');
            //     return redirect()->back()->withErrors('Complaint already created. Complaint No is ' . $complaintData->complaint_no . '.')->withInput();
            // }
            $main_machine_type = $request['main_machine_type'];
            $machine = MachineSalesEntry::select('product_id', 'is_active', 'order_no')->where('id', $request->sales_entry_id)->first();
            if ($machine->is_active != 1) {
                Toastr::error('Is M/c selles record false... Can not use.. Order no is ' . $machine->order_no);
                return redirect()->back()->withErrors('Is M/c selles record false... Can not use.. Order no is ' . $machine->order_no)->withInput();
            }
            $request['product_id'] = $machine->product_id;

            if (isset($request->engineer_id)) {
                $request['is_assigned'] = 1;
                $request['engineer_assign_date'] = date('Y-m-d');
                $request['engineer_assign_time'] = date('h:i:s');
                $request['engineer_in_time'] = isset($request->engineer_in_time) ? $request->engineer_in_time : null;
                $request['engineer_out_time'] = isset($request->engineer_out_time) ? $request->engineer_out_time : null;
                $request['engineer_in_date'] = isset($request->engineer_in_date) ? date('Y-m-d', strtotime($request->engineer_in_date)) : null;
                $request['engineer_out_date'] = isset($request->engineer_out_date) ? date('Y-m-d', strtotime($request->engineer_out_date)) : null;
                $request['jointengg'] = isset($request->jointengg) ? implode(",", $request->jointengg) : null;
            } else {
                $request['engineer_id'] = null;
                $request['engineer_in_time'] = null;
                $request['engineer_in_date'] = null;
                $request['engineer_out_date'] = null;
                $request['engineer_out_time'] = null;
                $request['jointengg'] = null;
            }

            if (!isset($request->is_urgent)) {
                $request['is_urgent'] = 0;
            }

            if (!isset($request->is_free_service)) {
                $request['is_free_service'] = 0;
            }

            $request['date'] = isset($request->date) ? date('Y-m-d', strtotime($request->date)) : null;

            $request['complaint_no'] = Complaint::whereDate('date', date('Y-m-d'))->count() + 1;

            $complaint = Complaint::create($request->all());

            //dd($request->jointengg);

            // make entries to joint complaint table
            if (isset($request->jointengg)) {

                $joint_complaint_data = [];
                $joint_complaint_data['complaint_id'] = $complaint->id;
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
                $joint_complaint_data['joint_eng_status'] = $request->status_id;
                $joint_complaint_data['joint_eng_remarks'] = $request->remark;
                //$request['jointengg'] = isset($request->jointengg) ? implode(",", $request->jointengg) : null;
                $jointenggarr = explode(",", $request->jointengg);
                //$jointengg = $request->jointengg;
                $Jtoken = [];
                foreach ($jointenggarr as $jengid) {
                    $joint_complaint_data['joint_eng_id'] = $jengid;
                    $joint_complaint = JointComplaint::create($joint_complaint_data);

                    // Fetch engineer
                    $user = User::find($jengid);
                    // Collect valid device tokens
                    if (!empty($user?->device_token)) {
                        $Jtoken[] = $user->device_token;
                    }
                } 
                        $title = 'New Complaint';
                        $body = 'A new complaint has been assigned to you. Complaint No: ' . $request['complaint_no'];
                        $type = 'engineer';
                        $this->notificationService->sendAlarmNotification($title, $body, $Jtoken, $type, $complaint->id);               
            }

            // $complaint_no = $complaint->id . $complaint->firm_id . $complaint->tag . $complaint->year->name;
            // $complaint->update(['complaint_no' => $complaint_no]);

            // Send push notification to party
            if (isset($request->party_id)) {
                $party = Party::select('device_token')->where('id', $request->party_id)->first();
            }
            if (isset($party)) {
                $title = 'Complaint Created';
                $body = 'Your complaint has been created successfully.';
                $token = $party->device_token;

                if ($title != "" && $body != "" && $token != "") {
                    // Get the response from the helper function
                    // sendNotification($title, $body, $token);
                    $this->notificationService->sendNotification($title, $body, $token);
                }
            }

            // Send push notification to engineer
            if (isset($request->engineer_id)) {
                $engineer = User::where('id', $request->engineer_id)->first();
            }
            if (!empty($engineer)) {
                $title = 'New Complain';
                $body = 'A new complaint has been assigned to you. Complaint No: ' . $complaint->complaint_no;
                $token = $engineer->device_token;
                $type = 'engineer';

                if ($title != "" && $body != "" && $token != "") {
                    // Get the response from the helper function
                    // sendNotification($title, $body, $token);
                    //$this->notificationService->sendNotification($title, $body, $token);
                     $this->notificationService->sendAlarmNotification($title, $body, $token, $type, $complaint->id);
                }
            }

            return redirect()->route('machineservicedata', ['main_machine_type' => $main_machine_type])->with('success', 'Complaint created successfully');
        } else {
            return redirect()->back()->withErrors($request->errors())->withInput();
        }
    }

    public function edit($id, Complaint $complaint)
    {
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $data['title'] = 'Edit Complaint';
        //$jointengg1 = trim('[', $complaint->jointengg);
        //$jointengg2 = trim(']', $jointengg1);
        //$complaint['jointengg'] = $jointengg2; 
        //$jointEnggList = implode(',', $complaint->jointengg);
        $jointEnggClean = str_replace(['[', ']'], '', $complaint->jointengg);
        //$jointEnggList = explode(',', $jointEnggClean);
        $complaint['jointengg'] = $jointEnggClean; 
        $data['complaint'] = $complaint;
        $data['sales_entries'] = MachineSalesEntry::where('party_id', $complaint->party_id)->with('product')->get();
        // dd($data);
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

        return view('complaint.edit', $data);
    }

    public function update(ComplaintRequest $request, Complaint $complaint)
    {

        if ($request->validated()) {
            $main_machine_type = $request['main_machine_type'];
            // $complaintData = Complaint::where('sales_entry_id', $request->sales_entry_id)->where('status_id', '!=', 3)->where('id', '!=', $complaint->id)->first();
            // if (!empty($complaintData)) {
            //     Toastr::error('Complaint already created');
            //     return redirect()->back()->withErrors('Complaint already created. Complaint No is ' . $complaintData->complaint_no . '.')->withInput();
            // }

            // $machine = MachineSalesEntry::select('product_id', 'is_active', 'order_no')->where('id', $request->sales_entry_id)->first();
            // if ($machine->is_active != 1) {
            //     Toastr::error('Is M/c selles record false... Can not use.. Order no is ' . $machine->order_no);
            //     return redirect()->back()->withErrors('Is M/c selles record false... Can not use.. Order no is ' . $machine->order_no)->withInput();
            // }

            if (isset($request->engineer_id)) {
                $request['is_assigned'] = 1;
                $request['engineer_assign_date'] = date('Y-m-d');
                $request['engineer_assign_time'] = date('h:i:s');
                $request['engineer_in_time'] = isset($request->engineer_in_time) ? $request->engineer_in_time : null;
                $request['engineer_out_time'] = isset($request->engineer_out_time) ? $request->engineer_out_time : null;
                $request['engineer_in_date'] = isset($request->engineer_in_date) ? date('Y-m-d', strtotime($request->engineer_in_date)) : null;
                $request['engineer_out_date'] = isset($request->engineer_out_date) ? date('Y-m-d', strtotime($request->engineer_out_date)) : null;
                $request['jointengg'] = isset($request->jointengg) ? implode(",", $request->jointengg) : null;
            } else {
                $request['engineer_id'] = null;
                $request['engineer_in_time'] = null;
                $request['engineer_in_date'] = null;
                $request['engineer_out_date'] = null;
                $request['engineer_out_time'] = null;
                $request['jointengg'] = null;
            }

            if (!isset($request->is_urgent)) {
                $request['is_urgent'] = 0;
            }

            if (!isset($request->is_free_service)) {
                $request['is_free_service'] = 0;
            }

            $request['date'] = date('Y-m-d', strtotime($request->date));

            $complaint->update($request->all());

            // Send push notification to engineer
            if (isset($request->engineer_id)) {
                $engineer = User::where('id', $request->engineer_id)->first();
            }
            if (!empty($engineer)) {
                $title = 'New Complain';
                $body = 'You has been assign for a new complain.';
                $token = $engineer->device_token;
                $type = 'engineer';

                if ($title != "" && $body != "" && $token != "") {
                    // Get the response from the helper function
                    // sendNotification($title, $body, $token);
                    //$this->notificationService->sendNotification($title, $body, $token);
                    $this->notificationService->sendAlarmNotification($title, $body, $token, $type, $complaint->id);
                }
            }

            return redirect()->route('complaints.index', ['main_machine_type' => $main_machine_type])->with('success', 'Complaint updated successfully');
        } else {
            return redirect()->back()->withErrors($request->errors())->withInput();
        }
    }

    public function destroy(Complaint $complaint)
    {
        $complaint->delete();
        Toastr::success('Complaint deleted successfully');
        return response()->json(['success' => 1, 'message' => 'Complaint deleted successfully']);
    }

    public function AddComplaintItemPart(Request $request)
    {
        // dd($request->all());
        $complaintItemPart = new ComplaintServicePartsDetail();
        $complaintItemPart->create($request->all());
        return redirect()->route('complaints.index')->with('success', 'Complaint Item Part added successfully');
    }

    public function partyProducts(Request $request)
    {
        $main_machine_type = $request->main_machine_type;
        $machines = MachineSalesEntry::where(['party_id' => $request->id, 'is_active' => 1, 'main_machine_type' => $main_machine_type])->with('product')->get();
        //$machines = MachineSalesEntry::where(['party_id' => $request->id, 'is_active' => 1])->with('product')->get();
        // $machinesData = [];
        // foreach ($machines as $item) {
        //     $expiryDate = Carbon::parse($item->service_expiry_date);
        //     $todayDate = Carbon::today();
        //     if ($expiryDate->greaterThan($todayDate)) {
        //         array_push($machinesData, $item);
        //     }
        // }
        $data['partyProducts'] = $machines;
        // $data['party'] = Party::where('id', $request->id)->with('area', 'owner', 'city', 'contactPerson')->first();
        $data['party'] = Party::where('id', $request->id)
                        ->with([
                            'area',
                            'city',
                            'complaints' => function ($query) {
                                $query->orderBy('id', 'DESC')->limit(1);
                            }
                        ])
                        ->first();

        return response()->json($data, 200);
    }

    public function machineEntry(Request $request, Complaint $complaint)
    {
        // dd($request->all());
        $machine_entry = MachineSalesEntry::where('id', $request->id)->first();
        return response()->json($machine_entry, 200);
    }

    public function machineCType(Request $request)
    {
        // dd($request->all());
        
        $MachineSalesdata = MachineSalesEntry::where('id', $request->id)->first();
        $products = Product::where('id', $MachineSalesdata->product_id)->first();
        $machine_type = ProductTypes::where('id', $products->product_type_id)->first();
        return response()->json($machine_type, 200);
    }
    
    public function getcomplaintType($id, Request $request)
    {
        // dd($request->all());
        $complaint_type = ComplaintType::where(['main_machine_type' => $id, 'machine_type' => $request->mid])->get();
        return response()->json($complaint_type, 200);
    }

    public function report(Request $request)
    {
        $data['title'] = 'Complaint Status Report';
        $data['columns'] = [
            'sr_no' => 'Sr. No.',
            'date' => 'Date',
            'time' => 'Time',
            'complaint_no' => 'Complaint No',
            'party_id' => 'Party',
            'address' => 'Address',
            'mobile_no' => 'Mobile No',
            'area' => 'Area',
            'product' => 'Product',
            'product_serial' => 'Product Serial',
            'mc_no' => 'Machine No',
            'complain_type' => 'Complain Type',
            'service_type' => 'Service Type',
            'status' => 'Status',
            'engineer' => 'Engineer',
            'days' => 'Days',

        ];
        $complaints = Complaint::select('id', 'date', 'time', 'complaint_no', 'party_id', 'sales_entry_id', 'product_id',  'complaint_type_id', 'service_type_id', 'status_id', 'engineer_id', 'engineer_out_date')
            ->with('party', 'product', 'complaintType', 'serviceType', 'status', 'engineer', 'salesEntry')
            ->orderBy('updated_at', 'desc');

        if (isset($request->date_range)) {
            [$startDate, $endDate] = explode(' to ', $request->date_range);
            $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
            $complaints->whereBetween('date', [$startDate, $endDate])->where('status_id', 3);
        }
        if (isset($request->phone_no)) {
            $complaints->whereHas('party', function ($q) use ($request) {
                $q->where('phone_no', 'like', '%' . $request->phone_no . '%');
            });
        }
        if (isset($request->owner_id)) {
            $complaints->whereHas('party', function ($q) use ($request) {
                $q->where('owner_id', $request->owner_id);
            });
        }
        if (isset($request->party_id)) {
            $complaints->where('party_id', $request->party_id);
        }
        if (isset($request->engineer_id)) {
            $complaints->where('engineer_id', $request->engineer_id);
        }
        if (isset($request->status_id)) {
            $complaints->where('status_id', $request->status_id);
        }
        if (isset($request->service_type_id)) {
            $complaints->where('service_type_id', $request->service_type_id);
        }
        if (isset($request->complaint_type_id)) {
            $complaints->where('complaint_type_id', $request->complaint_type_id);
        }
        if (isset($request->complaint_no)) {
            $complaints->where('complaint_no', $request->complaint_no);
        }
        $complaints = $complaints->get();
        if ($request->ajax()) {
            return DataTables::of($complaints)
                // ->smart(false)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('engineer_name'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains($row['engineer'], $request->get('engineer_name')) ? true : false;
                        });
                    }
                    if (!empty($request->get('from_date')) && !empty($request->get('to_date'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return $row['date'] >= $request->get('from_date') && $row['date'] <= $request->get('to_date') ? true : false;
                        });
                    }
                    if (!empty($request->get('status'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains($row['status'], $request->get('status')) ? true : false;
                        });
                    }
                    // if(!empty($request->get('search_key'))){
                    //     $instance->collection = $instance->collection->filter(function ($row) use ($request) {

                    //     })
                    // }
                })
                ->addColumn("date", function ($row) {
                    return date('d-m-Y', strtotime($row->date));
                })
                ->addColumn("engineer_out_date", function ($row) {
                    return isset($row->engineer_out_date) ? date('d-m-Y', strtotime($row->engineer_out_date)) : "";
                })
                ->addColumn('party', function ($row) {
                    return $row->party->name ?? 'N/A';
                })
                ->addColumn('address', function ($row) {
                    return $row->party->address ?? 'N/A';
                })
                ->addColumn('mobile_no', function ($row) {
                    return $row->party->phone_no ?? 'N/A';
                })
                ->addColumn('area', function ($row) {
                    return $row->party->area->name ?? 'N/A';
                })
                ->addColumn('product', function ($row) {
                    return $row->product->name ?? 'N/A';
                })
                ->addColumn('product_serial', function ($row) {
                    return $row->salesEntry->serial_no ?? 'N/A';
                })
                ->addColumn('mc_no', function ($row) {
                    return $row->salesEntry->mc_no ?? 'N/A';
                })
                ->addColumn('complaint_type', function ($row) {
                    return $row->complaintType->name ?? 'N/A';
                })
                ->addColumn('service_type', function ($row) {
                    return $row->serviceType->name ?? 'N/A';
                })
                ->addColumn('engineer', function ($row) {
                    return $row->engineer->name ?? 'N/A';
                })
                ->addColumn('status', function ($row) {
                    return $row->status->name ?? 'N/A';
                })
                ->addColumn('days', function ($row) {
                    $date = Carbon::parse($row->date);
                    return  $date->diffForHumans();
                })
                ->addColumn('complaint_no', function ($row) {
                    return $row->complaint_no ?? 'N/A';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('complaint.report', $data);
    }

    public function data(Request $request)
    {
        dd($request->all());
        $columns = array_merge($request->default_columns, $request->optional_columns);
        $complaints = Complaint::select($columns);

        return DataTables::of($complaints)->make(true);
    }

    public function view(Complaint $complaint)
    {
        $data['title'] = 'View Complaint';
        $data['complaint'] = $complaint;
        if (isset($data['complaint'])) {
            return view('complaint.show', $data);
        } else {
            return redirect()->route('complaints.index');
        }
    }

    public function downloadPDF(Complaint $complaint)
    {
        $data['title'] = 'Complaint PDF';
        $data['complaint'] = $complaint;
        // Load the view and pass in the data
        $pdf = PDF::loadView('complaint/pdf', $data)->setPaper('a4', 'portrait');
        // Stream the PDF
        // return $pdf->stream();
        // Download the PDF
        return $pdf->download('Complaint.pdf');
    }

    public function downloadSalarySlip($mid, $uid, Request $request)
    {
        //$midanduid = $mid.'-'.$uid;
        //dd($midanduid);
        $scurrentDate = Carbon::now(); // Example: June (6)
        $previousMonth = $scurrentDate->subMonth()->month;
        $monthName = Carbon::createFromDate(null, $previousMonth, 1)->format('F');
        $data['monthName'] = $monthName;
        $year = Carbon::now()->year;
        $data['year'] = $year;
        $data['title'] = 'Salary Slip';
        $userData = User::where(['id' => $uid, 'is_active' => 1])->with('roles')->first();
        //dd($userData->roles->name);
        $userPayroll = UsersPayroll::where('user_id', $userData->id)->first();
        $userSalary = Salary::where(['emp_id' => $userData->id, 'month_id' => $previousMonth, 'main_machine_type' => $mid])->first();
        $data['userData'] = $userData;
        $data['userPayroll'] = $userPayroll;
        $data['userSalary'] = $userSalary;
        // Load the view and pass in the data
        $pdf = PDF::loadView('users/salaryslippdf', $data)->setPaper('a4', 'portrait');
        // Stream the PDF
        // return $pdf->stream();
        // Download the PDF
        return $pdf->download('salaryslip.pdf');
    }

    public function todayReport($id, Request $request)
    {
        $data['title'] = 'Today Report';
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;

        $todayDate = isset($request->date_from) ? $request->date_from : date('Y-m-d');

        if ($request->ajax()) {
            // Initialize the base query
            $usersWithAttendance = User::leftJoin('attendtl', function ($join) use ($todayDate) {
                $join->on('users.id', '=', 'attendtl.engineer_id')
                    ->where('attendtl.in_date', '=', $todayDate);
            })
            ->whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Manager', 'Service Team Leader', 'Engineer']); // show only engineers to managers
                })
                ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                    $query->where('main_machine_type', $main_machine_type);
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

            // Apply the 'attn' filter if set
            if ($request->has('attn') && !empty($request->attn)) {
                $usersWithAttendance->where('attendtl.ap', $request->attn);
            }

            // Apply the 'role_id' filter if set
            if ($request->has('role_id') && !empty($request->role_id)) {
                $usersWithAttendance->whereHas('roles', function ($q) use ($request) {
                    $q->where('id', $request->role_id);
                });
            }

            // Execute the query
            $usersWithAttendance = $usersWithAttendance->get();

            // Return DataTables response
            return DataTables::of($usersWithAttendance)
                ->addIndexColumn()
                ->addColumn('designation', function ($row) {
                    return $row->getRoleNames()->implode(', ');
                })
                ->addColumn('pendingComplaintsCount', function ($row) {
                    return DB::table('complaints')
                        ->where('engineer_id', $row->id)
                        ->where('status_id', 1)
                        ->count();
                })
                ->addColumn('inProgressComplaintsCount', function ($row) {
                    return DB::table('complaints')
                        ->where('engineer_id', $row->id)
                        ->where('status_id', 2)
                        ->count();
                })
                ->addColumn('closedComplaintsCount', function ($row) use ($todayDate) {
                    return DB::table('complaints')
                        ->where('engineer_id', $row->id)
                        ->where('status_id', 3)
                        ->where('engineer_out_date', $todayDate)
                        ->count();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('complaint.today_report', $data);
    }

    public function attendapTodayReport(Request $request)
    {
        $data['title'] = 'Today Attendence Report';
        $todayDate = isset($request->date_from) ? $request->date_from : date('Y-m-d');
        $totalstaff = User::where('is_active', 1)->count();
        $present = User::join('attendtl', function ($join) use ($todayDate) {
            $join->on('users.id', '=', 'attendtl.engineer_id')
            ->where('attendtl.in_date', $todayDate)
            ->where('attendtl.is_approved', 1);
           //->where('attendtl.ap', 'P');
            })->count();

        $pendingforapproval = User::join('attendtl', function ($join) use ($todayDate) {
            $join->on('users.id', '=', 'attendtl.engineer_id')
            ->where('attendtl.in_date', $todayDate)
            ->where('attendtl.is_approved', 0)
            ->where('attendtl.ap', 'P');
            })->count();    

        $absent = User::where('is_active', 1)
            ->leftJoin('attendtl', function ($join) use ($todayDate) {
                $join->on('users.id', '=', 'attendtl.engineer_id')
                    ->where('attendtl.in_date', $todayDate);
            })
            ->whereNull('attendtl.id')
            ->count();  

        $leaveCount = User::where('is_active', 1)
        ->join('leaves', function ($join) use ($todayDate) {
            $join->on('users.id', '=', 'leaves.user_id')
                ->where('leaves.is_approved', 1)
                ->whereDate('leaves.leave_from', '<=', $todayDate)
                ->whereDate('leaves.leave_till', '>=', $todayDate);
        })
        ->distinct('users.id')
        ->count('users.id');    
        $data['totalstaff'] = $totalstaff;
        $data['present'] = $present;
        $data['absent'] = $absent;
        $data['leave'] = $leaveCount;
        $data['pendingforapproval'] = $pendingforapproval;

        if ($request->ajax()) {
            // Initialize the base query
            $usersWithAttendance = User::with('department','shift')->leftJoin('attendtl', function ($join) use ($todayDate) {
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
                    'attendtl.in_selfie',
                    DB::raw(
                        'CASE 
                            WHEN attendtl.id IS NOT NULL THEN attendtl.ap
                            WHEN leaves.id IS NOT NULL THEN "L"
                            ELSE "A"
                        END as attendance_status'
                    )
                )
                ->where('users.is_active', 1)
                //->where('attendtl.ap', 'P')
                //->orderBy('users.name', 'ASC');
                ->orderBy('attendtl.created_at', 'DESC');

            // Apply the 'attn' filter if set
            if ($request->has('attn') && !empty($request->attn)) {
                $usersWithAttendance->where('attendtl.ap', $request->attn);
            }

            // Apply the 'attn' filter if set
            if ($request->has('department') && !empty($request->department)) {
                $usersWithAttendance->where('deparment_id', $request->department);
            }

            // Apply the 'role_id' filter if set
            if ($request->has('role_id') && !empty($request->role_id)) {
                $usersWithAttendance->whereHas('roles', function ($q) use ($request) {
                    $q->where('id', $request->role_id);
                });
            }

            // Execute the query
            $usersWithAttendance = $usersWithAttendance->get();

            // Return DataTables response
            return DataTables::of($usersWithAttendance)
                ->addIndexColumn()
                ->addColumn('designation', function ($row) {
                    return $row->getRoleNames()->implode(', ');
                })
                ->addColumn('pendingComplaintsCount', function ($row) {
                    return DB::table('complaints')
                        ->where('engineer_id', $row->id)
                        ->where('status_id', 1)
                        ->count();
                })
                ->addColumn('inProgressComplaintsCount', function ($row) {
                    return DB::table('complaints')
                        ->where('engineer_id', $row->id)
                        ->where('status_id', 2)
                        ->count();
                })
                ->addColumn('closedComplaintsCount', function ($row) use ($todayDate) {
                    return DB::table('complaints')
                        ->where('engineer_id', $row->id)
                        ->where('status_id', 3)
                        ->where('engineer_out_date', $todayDate)
                        ->count();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('complaint.attendap_today_report', $data);
    }
    
public function pendingApproval(Request $request)
{
    $data['title'] = 'Pending Approval';
    $todayDate = isset($request->date_from) ? $request->date_from : date('Y-m-d');
   

    if ($request->ajax()) {
        // Initialize the base query
        $usersWithAttendance = User::with('department','shift')->leftJoin('attendtl', function ($join) use ($todayDate) {
            $join->on('users.id', '=', 'attendtl.engineer_id')
                ->where('attendtl.in_date', '=', $todayDate)
                ->where('attendtl.is_approved', 0); // Only show unapproved records
        })
            ->leftJoin('leaves', function ($join) use ($todayDate) {
                $join->on('users.id', '=', 'leaves.user_id')
                    ->where('leaves.is_approved', 1)
                    ->whereDate('leaves.leave_from', '<=', $todayDate)
                    ->whereDate('leaves.leave_till', '>=', $todayDate);
            })
            ->select(
                'users.*',
                'attendtl.id as attendance_id',
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
                'attendtl.in_selfie',
                DB::raw(
                    'CASE 
                        WHEN attendtl.id IS NOT NULL THEN attendtl.ap
                        WHEN leaves.id IS NOT NULL THEN "L"
                        ELSE "A"
                    END as attendance_status'
                )
            )
            ->where('users.is_active', 1)
            ->whereNotNull('attendtl.id') // Only show records that have attendance entries
            //->where('attendtl.ap', 'P')
            //->orderBy('users.name', 'ASC');
            ->orderBy('attendtl.created_at', 'DESC');

        // Apply the 'attn' filter if set
        if ($request->has('attn') && !empty($request->attn)) {
            $usersWithAttendance->where('attendtl.ap', $request->attn);
        }

        // Apply the 'department' filter if set
        if ($request->has('department') && !empty($request->department)) {
            $usersWithAttendance->where('deparment_id', $request->department);
        }

        // Apply the 'role_id' filter if set
        if ($request->has('role_id') && !empty($request->role_id)) {
            $usersWithAttendance->whereHas('roles', function ($q) use ($request) {
                $q->where('id', $request->role_id);
            });
        }

        // Execute the query
        $usersWithAttendance = $usersWithAttendance->get();

        // Return DataTables response
        return DataTables::of($usersWithAttendance)
            ->addIndexColumn()
            ->addColumn('designation', function ($row) {
                return $row->getRoleNames()->implode(', ');
            })
            ->addColumn('pendingComplaintsCount', function ($row) {
                return DB::table('complaints')
                    ->where('engineer_id', $row->id)
                    ->where('status_id', 1)
                    ->count();
            })
            ->addColumn('inProgressComplaintsCount', function ($row) {
                return DB::table('complaints')
                    ->where('engineer_id', $row->id)
                    ->where('status_id', 2)
                    ->count();
            })
            ->addColumn('closedComplaintsCount', function ($row) use ($todayDate) {
                return DB::table('complaints')
                    ->where('engineer_id', $row->id)
                    ->where('status_id', 3)
                    ->where('engineer_out_date', $todayDate)
                    ->count();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('complaint.pending-approval', $data);
}
    
public function approveAttendance(Request $request)
{
    try {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:attendtl,id',
        ]);

        $updated = Attendtl::whereIn('id', $request->ids)
            ->where('is_approved', 0)
            ->update(['is_approved' => 1]);

        return response()->json([
            'success' => true,
            'message' => $updated . ' record(s) approved successfully.',
            'updated' => $updated,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(), // ← shows real error in browser
        ], 500);
    }
}

    public function userMonthlyAttendenceList($id, Request $request)
    {
        $data['title'] = 'Monthly Attendence List';
        $data['user'] = $id;
        

        // Total Working days
        $currentDate = Carbon::now();

        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth   = $currentDate->copy()->endOfMonth();

        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);

        $workingDays = collect($period)->filter(function ($date) {
            return $date->dayOfWeek !== Carbon::SUNDAY;
        })->count();
        $totalstaff = $workingDays;
        $present = User::join('attendtl', function ($join) use ($id) {
            $join->on('users.id', '=', 'attendtl.engineer_id')
            ->where('attendtl.engineer_id', $id)
            ->where('attendtl.ap', 'P');
            })->count();

        $absent =  ($workingDays - $present);

        $leaveCount = User::where('is_active', 1)
        ->join('leaves', function ($join) use ($id) {
            $join->on('users.id', '=', 'leaves.user_id')
                ->where('leaves.user_id', $id)
                ->where('leaves.is_approved', 1);
        })
        ->distinct('users.id')
        ->count('users.id');    
        $data['totalstaff'] = $totalstaff;
        $data['present'] = $present;
        $data['absent'] = $absent;
        $data['leave'] = $leaveCount;
        $usersWithAttendance = Attendtl::with('user')->where('engineer_id', $id);
        
        // Execute the query
        //$todayDate = isset($request->date_from) ? $request->date_from : date('Y-m-d');
        // if(isset($request->date)) {
        //     $usersWithAttendance = $usersWithAttendance->where('in_date', $request->date)->latest()->get();
        // } else {
        //     $currentDate = Carbon::now();
        //     $startDate = $currentDate->copy()->startOfMonth();
        //     $endDate = $currentDate->copy()->endOfMonth();
        //     $usersWithAttendance = $usersWithAttendance->whereBetween('in_date', [$startDate, $endDate])->latest()->get();
        // }

        if ($request->has('month') && !empty($request->month) && $request->month!=0) {
                $month = $request->month;
                //dd($month);
                $currentYear = Carbon::now()->year;
                $startDate = Carbon::createFromDate($currentYear, $month, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($currentYear, $month, 1)->endOfMonth();
                $usersWithAttendance = $usersWithAttendance->whereBetween('in_date', [$startDate, $endDate])->latest()->get();
            } else {
            $currentDate = Carbon::now();
            $startDate = $currentDate->copy()->startOfMonth();
            $endDate = $currentDate->copy()->endOfMonth();
            $usersWithAttendance = $usersWithAttendance->whereBetween('in_date', [$startDate, $endDate])->latest()->get();
        }
        //dd($usersWithAttendance);
        $data['userAttendance'] = $usersWithAttendance;

        if ($request->ajax()) {
            // Return DataTables response
            return DataTables::of($usersWithAttendance)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('complaint.user_monthly_attendence_list', $data);
    }

    public function userDailyAttendenceDetails($id, $date, Request $request)
    {
        $data['title'] = 'Daily Attendence Details';
        $data['user'] = $id;
        $usersWithAttendance = Attendtl::with('user')->where('engineer_id', $id);
        
        // Execute the query
        $todayDate = isset($request->date) ? $request->date : $date;
        if(isset($request->date)) {
            $usersWithAttendance = $usersWithAttendance->where('in_date', $request->date)->get();
            //$date = $request->date;
        } else {
            $currentDate = Carbon::now();
            $startDate = $currentDate->copy()->startOfMonth();
            $endDate = $currentDate->copy()->endOfMonth();
            $usersWithAttendance = $usersWithAttendance->where('in_date', $date)->get();
        }
        $data['date'] = $date;
        $data['userAttendance'] = $usersWithAttendance;
        $data['today'] = $todayDate;
       

        return view('complaint.user_daily_attendence_details', $data);
    }

    public function saleTodayReport($id, Request $request)
    {
        $data['title'] = 'Today Report';
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;

        $todayDate = isset($request->date_from) ? $request->date_from : date('Y-m-d');

        if ($request->ajax()) {
            // Initialize the base query
            $usersWithAttendance = User::leftJoin('attendtl', function ($join) use ($todayDate) {
                $join->on('users.id', '=', 'attendtl.engineer_id')
                    ->where('attendtl.in_date', '=', $todayDate);
            })->whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Sales Team Leader', 'Sales Person']); // show only sales persopns
                })
                ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                    $query->where('main_machine_type', $main_machine_type);
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

            // Apply the 'attn' filter if set
            if ($request->has('attn') && !empty($request->attn)) {
                $usersWithAttendance->where('attendtl.ap', $request->attn);
            }

            // Apply the 'role_id' filter if set
            if ($request->has('role_id') && !empty($request->role_id)) {
                $usersWithAttendance->whereHas('roles', function ($q) use ($request) {
                    $q->where('id', $request->role_id);
                });
            }

            // Execute the query
            $usersWithAttendance = $usersWithAttendance->get();

            // Return DataTables response
            return DataTables::of($usersWithAttendance)
                ->addIndexColumn()
                ->addColumn('designation', function ($row) {
                    return $row->getRoleNames()->implode(', ');
                })
                ->addColumn('pendingComplaintsCount', function ($row) {
                    return DB::table('complaints')
                        ->where('engineer_id', $row->id)
                        ->where('status_id', 1)
                        ->count();
                })
                ->addColumn('inProgressComplaintsCount', function ($row) {
                    return DB::table('complaints')
                        ->where('engineer_id', $row->id)
                        ->where('status_id', 2)
                        ->count();
                })
                ->addColumn('closedComplaintsCount', function ($row) use ($todayDate) {
                    return DB::table('complaints')
                        ->where('engineer_id', $row->id)
                        ->where('status_id', 3)
                        ->where('engineer_out_date', $todayDate)
                        ->count();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('complaint.saletoday_report', $data);
    }

    public function apSummary($id, Request $request)
    {
        $data['title'] = 'A/P Summary';
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;

        if ($request->ajax()) {
            // $usersWithAttendance = Attendtl::query()
            //     ->with(['users.roles'])
            //     ->selectRaw("
            //                         attendtl.engineer_id,
            //                         SUM(CASE WHEN ap = 'p' THEN 1 ELSE 0 END) as count_p,
            //                         SUM(CASE WHEN ap = 'l' THEN 1 ELSE 0 END) as count_l,
            //                         SUM(CASE WHEN ap = 'a' THEN 1 ELSE 0 END) as count_a,
            //                         SUM(CASE WHEN ap = 'h' THEN 1 ELSE 0 END) as count_h,
            //                         SUM(pdays) as total_pdays
            //                     ")
            //     ->groupBy('attendtl.engineer_id');

            // $usersWithAttendance = Attendtl::query()
            // ->join('users', 'attendtl.engineer_id', '=', 'users.id')
            // ->join('userwise_machines', 'users.id', '=', 'userwise_machines.user_id')
            // ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            // ->with(['users.roles'])
            // ->selectRaw("
            //     attendtl.engineer_id,
            //     userwise_machines.main_machine_type,
            //     SUM(CASE WHEN ap = 'p' THEN 1 ELSE 0 END) as count_p,
            //     SUM(CASE WHEN ap = 'l' THEN 1 ELSE 0 END) as count_l,
            //     SUM(CASE WHEN ap = 'a' THEN 1 ELSE 0 END) as count_a,
            //     SUM(CASE WHEN ap = 'h' THEN 1 ELSE 0 END) as count_h,
            //     SUM(pdays) as total_pdays
            // ")
            // ->where('userwise_machines.main_machine_type', $main_machine_type)
            // ->where('model_has_roles.role_id', 4)
            // ->orwhere('model_has_roles.role_id', 7)
            // ->groupBy('attendtl.engineer_id', 'userwise_machines.main_machine_type');

            $usersWithAttendance = Attendtl::query()
            ->join('users', 'attendtl.engineer_id', '=', 'users.id')
            ->join('userwise_machines', 'users.id', '=', 'userwise_machines.user_id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->with(['users.roles'])
            ->selectRaw("
                attendtl.engineer_id,
                userwise_machines.main_machine_type,
                SUM(CASE WHEN ap = 'p' THEN 1 ELSE 0 END) as count_p,
                SUM(CASE WHEN ap = 'l' THEN 1 ELSE 0 END) as count_l,
                SUM(CASE WHEN ap = 'a' THEN 1 ELSE 0 END) as count_a,
                SUM(CASE WHEN ap = 'h' THEN 1 ELSE 0 END) as count_h,
                SUM(pdays) as total_pdays
            ")
            ->where('userwise_machines.main_machine_type', $main_machine_type)
            ->where(function ($q) {
                $q->where('model_has_roles.role_id', 4)
                ->orWhere('model_has_roles.role_id', 7);
            })
            ->groupBy('attendtl.engineer_id', 'userwise_machines.main_machine_type');

            if ($request->has('month') && !empty($request->month)) {
                $month = $request->month;
                $currentYear = Carbon::now()->year;
                $startDate = Carbon::createFromDate($currentYear, $month, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($currentYear, $month, 1)->endOfMonth();
            } else {
                $currentDate = Carbon::now();
                $startDate = $currentDate->copy()->startOfMonth();
                $endDate = $currentDate->copy()->endOfMonth();
            }

            // Apply the 'role_id' filter if set
            if ($request->has('role_id') && !empty($request->role_id)) {
                $usersWithAttendance->whereHas('users.roles', function ($q) use ($request) {
                    $q->where('id', $request->role_id);
                });
            }

            // Apply the 'engineer_id' filter if set
            if ($request->has('engineer_id') && !empty($request->engineer_id)) {
                $usersWithAttendance->where('engineer_id', $request->engineer_id);
            }

            // Execute the query
            $usersWithAttendance = $usersWithAttendance->whereBetween('in_date', [$startDate, $endDate])->get();
            $todayDate = date('Y-m-d');
            // Return DataTables response
            return DataTables::of($usersWithAttendance)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->users ? $row->users->name : '';
                })
                ->addColumn('role', function ($row) {
                    return $row->users->roles ? $row->users->roles[0]->name : '';
                })
                ->addColumn('total_p', function ($row) {
                    return $row->count_p ? $row->count_p : '0';
                })
                ->addColumn('total_l', function ($row) {
                    return $row->count_l ? $row->count_l : '0';
                })
                ->addColumn('total_a', function ($row) {
                    return $row->count_a ? $row->count_a : '0';
                })
                ->addColumn('total_h', function ($row) {
                    return $row->count_h ? $row->count_h : '0';
                })
                // ->addColumn('total_pdays', function ($row) {
                //     return $row->total_pdays ? $row->total_pdays : '0';
                // })
                ->addColumn('pendingComplaintsCount', function ($row) {
                    return DB::table('complaints')
                        ->where('engineer_id', $row->engineer_id)
                        ->where('status_id', 1)
                        ->count();
                })
                ->addColumn('inProgressComplaintsCount', function ($row) {
                    return DB::table('complaints')
                        ->where('engineer_id', $row->engineer_id)
                        ->where('status_id', 2)
                        ->count();
                })
                ->addColumn('closedComplaintsCount', function ($row) use ($todayDate) {
                    return DB::table('complaints')
                        ->where('engineer_id', $row->engineer_id)
                        ->where('status_id', 3)
                        ->where('engineer_out_date', $todayDate)
                        ->count();
                })
                ->addColumn('action', function ($row) use ($main_machine_type) {
                    return '<div class="btn-group m-1"><a href="' . route('ap_detail', ['main_machine_type' => $main_machine_type, 'engineer' => $row->engineer_id]) . '" class="edit btn btn-primary btn-sm" style="padding: 2px 5px;">A/P Details</a></div>';
                })
                ->make(true);
        }
        return view('report.ap_summary', $data);
    }

    public function saleapSummary($id, Request $request)
    {
        $data['title'] = 'A/P Summary';
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;

        if ($request->ajax()) {
            // $usersWithAttendance = Attendtl::query()
            //     ->with(['users.roles'])
            //     ->selectRaw("
            //                         attendtl.engineer_id,
            //                         SUM(CASE WHEN ap = 'p' THEN 1 ELSE 0 END) as count_p,
            //                         SUM(CASE WHEN ap = 'l' THEN 1 ELSE 0 END) as count_l,
            //                         SUM(CASE WHEN ap = 'a' THEN 1 ELSE 0 END) as count_a,
            //                         SUM(CASE WHEN ap = 'h' THEN 1 ELSE 0 END) as count_h,
            //                         SUM(pdays) as total_pdays
            //                     ")
            //     ->groupBy('attendtl.engineer_id');

            $usersWithAttendance = Attendtl::query()
            ->join('users', 'attendtl.engineer_id', '=', 'users.id')
            ->join('userwise_machines', 'users.id', '=', 'userwise_machines.user_id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->with(['users.roles'])
            ->selectRaw("
                attendtl.engineer_id,
                userwise_machines.main_machine_type,
                SUM(CASE WHEN ap = 'p' THEN 1 ELSE 0 END) as count_p,
                SUM(CASE WHEN ap = 'l' THEN 1 ELSE 0 END) as count_l,
                SUM(CASE WHEN ap = 'a' THEN 1 ELSE 0 END) as count_a,
                SUM(CASE WHEN ap = 'h' THEN 1 ELSE 0 END) as count_h,
                SUM(pdays) as total_pdays
            ")
            ->where('userwise_machines.main_machine_type', $main_machine_type)
            ->where('model_has_roles.role_id', 5)
            ->orwhere('model_has_roles.role_id', 8)
            ->groupBy('attendtl.engineer_id', 'userwise_machines.main_machine_type');

            if ($request->has('month') && !empty($request->month)) {
                $month = $request->month;
                $currentYear = Carbon::now()->year;
                $startDate = Carbon::createFromDate($currentYear, $month, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($currentYear, $month, 1)->endOfMonth();
            } else {
                $currentDate = Carbon::now();
                $startDate = $currentDate->copy()->startOfMonth();
                $endDate = $currentDate->copy()->endOfMonth();
            }

            // Apply the 'role_id' filter if set
            if ($request->has('role_id') && !empty($request->role_id)) {
                $usersWithAttendance->whereHas('users.roles', function ($q) use ($request) {
                    $q->where('id', $request->role_id);
                });
            }

            // Apply the 'engineer_id' filter if set
            if ($request->has('engineer_id') && !empty($request->engineer_id)) {
                $usersWithAttendance->where('engineer_id', $request->engineer_id);
            }

            // Execute the query
            $usersWithAttendance = $usersWithAttendance->whereBetween('in_date', [$startDate, $endDate])->get();
            $todayDate = date('Y-m-d');
            // Return DataTables response
            return DataTables::of($usersWithAttendance)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->users ? $row->users->name : '';
                })
                ->addColumn('role', function ($row) {
                    return $row->users->roles ? $row->users->roles[0]->name : '';
                })
                ->addColumn('total_p', function ($row) {
                    return $row->count_p ? $row->count_p : '0';
                })
                ->addColumn('total_l', function ($row) {
                    return $row->count_l ? $row->count_l : '0';
                })
                ->addColumn('total_a', function ($row) {
                    return $row->count_a ? $row->count_a : '0';
                })
                ->addColumn('total_h', function ($row) {
                    return $row->count_h ? $row->count_h : '0';
                })
                // ->addColumn('total_pdays', function ($row) {
                //     return $row->total_pdays ? $row->total_pdays : '0';
                // })
                ->addColumn('pendingComplaintsCount', function ($row) {
                    return DB::table('complaints')
                        ->where('engineer_id', $row->engineer_id)
                        ->where('status_id', 1)
                        ->count();
                })
                ->addColumn('inProgressComplaintsCount', function ($row) {
                    return DB::table('complaints')
                        ->where('engineer_id', $row->engineer_id)
                        ->where('status_id', 2)
                        ->count();
                })
                ->addColumn('closedComplaintsCount', function ($row) use ($todayDate) {
                    return DB::table('complaints')
                        ->where('engineer_id', $row->engineer_id)
                        ->where('status_id', 3)
                        ->where('engineer_out_date', $todayDate)
                        ->count();
                })
                ->addColumn('action', function ($row) use ($main_machine_type) {
                    return '<div class="btn-group m-1"><a href="' . route('ap_detail', ['main_machine_type' => $main_machine_type, 'engineer' => $row->engineer_id]) . '" class="edit btn btn-primary btn-sm" style="padding: 2px 5px;">A/P Details</a></div>';
                })
                ->make(true);
        }
        return view('report.saleap_summary', $data);
    }

    public function apDetails($mid, $id, Request $request)
    {
        $data['title'] = 'A/P Details';
        $main_machine_type = $mid;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $mid)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $data['enginner_id'] = $id;
        $data['attendtl'] = Attendtl::with(['users.roles'])->where('engineer_id', $id)->first();
        if ($request->ajax()) {


            $usersWithAttendance = Attendtl::where('engineer_id', $id);

            // Apply the 'attn' filter if set
            if ($request->has('attn') && !empty($request->attn)) {
                if ($request->attn == "P") {
                    $usersWithAttendance->whereNotIn('ap', ['A', 'L']);
                } elseif ($request->attn == "A") {
                    $usersWithAttendance->whereIn('ap', ['A', 'L']);
                }
            }

            if ($request->has('month') && !empty($request->month)) {
                $month = $request->month;
                $currentYear = Carbon::now()->year;
                $startDate = Carbon::createFromDate($currentYear, $month, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($currentYear, $month, 1)->endOfMonth();
            } else {
                $currentDate = Carbon::now();
                $startDate = $currentDate->copy()->startOfMonth();
                $endDate = $currentDate->copy()->endOfMonth();
            }
            // Execute the query
            $usersWithAttendance = $usersWithAttendance->whereBetween('in_date', [$startDate, $endDate])->get();

            // Return DataTables response
            return DataTables::of($usersWithAttendance)
                ->addIndexColumn()
                ->addColumn('in_date', function ($row) {
                    return $row->in_date ? date('d-m-Y', strtotime($row->in_date)) : '';
                })
                ->addColumn('day', function ($row) {
                    return $row->in_date ? date('l', strtotime($row->in_date)) : '';
                })

                ->make(true);
        }
        return view('report.ap_details', $data);
    }

    public function salaries($mid, Request $request) 
    {
        //$data['title'] = 'Employee Salary Details';
        $main_machine_type = $mid;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $mid)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        if ($request->has('month') && !empty($request->month)) {
            $month = $request->month;
            $monthName = Carbon::createFromDate(null, $month, 1)->format('F');
            $data['monthName'] = $monthName;
        } else {
            $scurrentDate = Carbon::now(); // Example: June (6)
            $previousMonth = $scurrentDate->subMonth()->month;
            $monthName = Carbon::createFromDate(null, $previousMonth, 1)->format('F');
            $data['monthName'] = $monthName;
        }
        $data['title'] = 'Salary Details of '.$monthName;
        //dd($previousMonth);
        //$usersWithAttendance = Salary::with(['users.roles'])->where('1month_id', $previousMonth)->get();
        //dd($usersWithAttendance);
        if ($request->ajax()) {
            //$usersWithAttendance = Salary::with(['users.roles'])->where(['month_id' => $previousMonth, 'main_machine_type' => $main_machine_type]);
            if ($request->has('month') && !empty($request->month)) {
                $month = $request->month;
                //$monthName = Carbon::create()->month($month)->format('F');
                $usersWithAttendance = Salary::with(['users.roles'])->where(['month_id' => $month, 'main_machine_type' => $main_machine_type]);
            } else {
                $usersWithAttendance = Salary::with(['users.roles'])->where(['month_id' => $previousMonth, 'main_machine_type' => $main_machine_type]);
            }

            // Apply the 'role_id' filter if set
            if ($request->has('role_id') && !empty($request->role_id)) {
                $usersWithAttendance->whereHas('users.roles', function ($q) use ($request) {
                    $q->where('id', $request->role_id);
                });
            }

            // Apply the 'engineer_id' filter if set
            if ($request->has('engineer_id') && !empty($request->engineer_id)) {
                $usersWithAttendance->where('emp_id', $request->engineer_id);
            }

            // Execute the query
            $usersWithAttendance = $usersWithAttendance->get();
            //dd($usersWithAttendance);
           
            // Return DataTables response
            return DataTables::of($usersWithAttendance)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->users ? $row->users->name : '';
                })
                ->addColumn('role', function ($row) {
                    return $row->users->roles ? $row->users->roles[0]->name : '';
                })
                ->addColumn('total_p', function ($row) {
                    return $row->pdays ? $row->pdays : '0';
                })
                ->addColumn('w_days', function ($row) {
                    return $row->working_days ? $row->working_days : '0';
                })
                ->addColumn('pd_sal', function ($row) {
                    return $row->perday_sal ? $row->perday_sal : '0';
                })
                ->addColumn('t_sal', function ($row) {
                    return $row->total_salary ? $row->total_salary : '0';
                })
                ->addColumn('action', function ($row) use ($main_machine_type) {
                    return '<div class="btn-group m-1"><a href="' . route('download-salesalaryslip', ['main_machine_type' => $main_machine_type, 'id' => $row->emp_id]) . '" class="edit btn btn-primary btn-sm" style="padding: 2px 5px;">Salary Slip</a></div>';
                })
                ->make(true);
        }

        return view('report.salaries', $data);
    }

    public function saleSalaries($mid, Request $request) 
    {
        $data['title'] = 'Employee Salary Details';
        $main_machine_type = $mid;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $mid)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $scurrentDate = Carbon::now(); // Example: June (6)
        $previousMonth = $scurrentDate->subMonth()->month;
        //dd($previousMonth);
        //$usersWithAttendance = Salary::with(['users.roles'])->where('1month_id', $previousMonth)->get();
        //dd($usersWithAttendance);
        if ($request->ajax()) {
            //$usersWithAttendance = Salary::with(['users.roles'])->where(['month_id' => $previousMonth, 'main_machine_type' => $main_machine_type]);
            if ($request->has('month') && !empty($request->month)) {
                $month = $request->month;
                $usersWithAttendance = Salary::with(['users.roles'])
                ->whereHas('users.roles', function ($q) use ($request) {
                    $q->whereIn('name', ['Sales Team Leader', 'Sales Person']);
                })
                ->where(['month_id' => $month, 'main_machine_type' => $main_machine_type]);
            } else {
                $usersWithAttendance = Salary::with(['users.roles'])
                ->whereHas('users.roles', function ($q) use ($request) {
                    $q->whereIn('name', ['Sales Team Leader', 'Sales Person']);
                })
                ->where(['month_id' => $previousMonth, 'main_machine_type' => $main_machine_type]);
            }

            // Apply the 'role_id' filter if set
            // if ($request->has('role_id') && !empty($request->role_id)) {
            //     $usersWithAttendance->whereHas('users.roles', function ($q) use ($request) {
            //         $q->where('id', $request->role_id);
            //     });
            // }

            // Apply the 'engineer_id' filter if set
            // if ($request->has('engineer_id') && !empty($request->engineer_id)) {
            //     $usersWithAttendance->where('emp_id', $request->engineer_id);
            // }

            // Execute the query
            $usersWithAttendance = $usersWithAttendance->get();
            //dd($usersWithAttendance);
           
            // Return DataTables response
            return DataTables::of($usersWithAttendance)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->users ? $row->users->name : '';
                })
                ->addColumn('role', function ($row) {
                    return $row->users->roles ? $row->users->roles[0]->name : '';
                })
                ->addColumn('total_p', function ($row) {
                    return $row->pdays ? $row->pdays : '0';
                })
                ->addColumn('w_days', function ($row) {
                    return $row->working_days ? $row->working_days : '0';
                })
                ->addColumn('pd_sal', function ($row) {
                    return $row->perday_sal ? $row->perday_sal : '0';
                })
                ->addColumn('t_sal', function ($row) {
                    return $row->total_salary ? $row->total_salary : '0';
                })
                ->addColumn('action', function ($row) use ($main_machine_type) {
                    return '<div class="btn-group m-1"><a href="' . route('ap_detail', ['main_machine_type' => $main_machine_type, 'engineer' => $row->emp_id]) . '" class="edit btn btn-primary btn-sm" style="padding: 2px 5px;">A/P Details</a></div>';
                })
                ->make(true);
        }

        return view('report.salesalaries', $data);
    }

    public function generateSalaries($mid, Request $request)
    {
        $main_machine_type = $mid;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $mid)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $data['title'] = 'Generate Salaries';
        
        return view('report.generatesalaries', $data);
    }

    public function generateSaleSalaries($mid, Request $request)
    {
        $main_machine_type = $mid;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $mid)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $data['title'] = 'Generate Salaries';
        
        return view('report.generatesalesalaries', $data);
    }

    public function processSalaries(Request $request)
    {
        //dd($request->main_machine_type);
        $main_machine_type = $request->main_machine_type;
        $month = $request->month;
        if(isset($request->is_salesalary) && $request->is_salesalary == 1)
        {
            $is_salesalary = $request->is_salesalary;

            //$month = 6;
            $currentYear = Carbon::now()->year;
            $startDate = Carbon::createFromDate($currentYear, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($currentYear, $month, 1)->endOfMonth();
            // echo $startDate;
            // echo $endDate;
            // exit;
            $usersWithAttendance = Attendtl::query()
                ->join('users', 'attendtl.engineer_id', '=', 'users.id')
                ->join('userwise_machines', 'users.id', '=', 'userwise_machines.user_id')
                //->with(['users.roles'])
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->whereIn('name', ['Sales Team Leader', 'Sales Person']) // filter only Service Team
                ->selectRaw("
                    attendtl.engineer_id,
                    userwise_machines.main_machine_type,
                    SUM(CASE WHEN ap = 'p' THEN 1 ELSE 0 END) as count_p,
                    SUM(CASE WHEN ap = 'l' THEN 1 ELSE 0 END) as count_l,
                    SUM(CASE WHEN ap = 'a' THEN 1 ELSE 0 END) as count_a,
                    SUM(CASE WHEN ap = 'h' THEN 1 ELSE 0 END) as count_h,
                    SUM(pdays) as total_pdays")
                ->where('userwise_machines.main_machine_type', $main_machine_type)
                ->groupBy('attendtl.engineer_id', 'userwise_machines.main_machine_type');
            // Execute the query
            $usersWithAttendance = $usersWithAttendance->whereBetween('in_date', [$startDate, $endDate])->get();

        } else {
        
            //$month = 6;
            $currentYear = Carbon::now()->year;
            $startDate = Carbon::createFromDate($currentYear, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($currentYear, $month, 1)->endOfMonth();
            // echo $startDate;
            // echo $endDate;
            // exit;
            $usersWithAttendance = Attendtl::query()
                ->join('users', 'attendtl.engineer_id', '=', 'users.id')
                ->join('userwise_machines', 'users.id', '=', 'userwise_machines.user_id')
                //->with(['users.roles'])
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->whereIn('name', ['Service Team Leader', 'Engineer']) // filter only Service Team
                ->selectRaw("
                    attendtl.engineer_id,
                    userwise_machines.main_machine_type,
                    SUM(CASE WHEN ap = 'p' THEN 1 ELSE 0 END) as count_p,
                    SUM(CASE WHEN ap = 'l' THEN 1 ELSE 0 END) as count_l,
                    SUM(CASE WHEN ap = 'a' THEN 1 ELSE 0 END) as count_a,
                    SUM(CASE WHEN ap = 'h' THEN 1 ELSE 0 END) as count_h,
                    SUM(pdays) as total_pdays")
                ->where('userwise_machines.main_machine_type', $main_machine_type)
                ->groupBy('attendtl.engineer_id', 'userwise_machines.main_machine_type');
            // Execute the query
            $usersWithAttendance = $usersWithAttendance->whereBetween('in_date', [$startDate, $endDate])->get();
        }
        //dd($usersWithAttendance);
        //$totalDaysInMonth = Carbon::now()->daysInMonth;
        $totalDaysInMonth = Carbon::createFromDate($currentYear, $month, 1)->daysInMonth;
        //$employeeSalaries = [];
        //dd($usersWithAttendance);
        $count = 0;
        foreach ($usersWithAttendance as $userAttendance) {
            
            //echo $userAttendance->engineer_id;
            //echo "<br>";
                // Get employee payroll record
                $payroll[$count] = UsersPayroll::where('user_id', $userAttendance->engineer_id)->first();

                if ($payroll[$count]) {
                    // Calculate per day salary
                    //$perDaySalary = $payroll->monthly_salary / $totalDaysInMonth;
                    $perDaySalary = $payroll[$count]->perday_sal;

                    // Calculate payable salary
                    $calculatedSalary = ($perDaySalary * $userAttendance->total_pdays);

                    
                    $main_machine_type = $request->main_machine_type;
                    //Store or Update Salary data in to the Salaries table
                    $usersWiseSalaryData[$count] = Salary::where(['emp_id' => $userAttendance->engineer_id, 'month_id' => $month, 'main_machine_type' => $main_machine_type])->first();
                    
                    if($usersWiseSalaryData[$count]){
                            $usersWiseSalaryDatau[$count] = $usersWiseSalaryData[$count]->update([
                            'emp_id' => $userAttendance->engineer_id,
                            'month_id' => $month,
                            'pdays' => $userAttendance->total_pdays,
                            'hdays' => $userAttendance->count_h,
                            'working_days' => $totalDaysInMonth,
                            'perday_sal' => $perDaySalary,
                            'total_salary' => round($calculatedSalary, 2), // rounding to 2 decimal places
                            'main_machine_type' => $main_machine_type,
                            ]);
                    } else {
                            $usersWiseSalaryDatau[$count] = Salary::create([
                            'emp_id' => $userAttendance->engineer_id,
                            'month_id' => $month,
                            'pdays' => $userAttendance->total_pdays,
                            'hdays' => $userAttendance->count_h,
                            'working_days' => $totalDaysInMonth,
                            'perday_sal' => $perDaySalary,
                            'total_salary' => round($calculatedSalary, 2), // rounding to 2 decimal places
                            'main_machine_type' => $main_machine_type,
                            ]);
                    }
                    //dd($usersWiseSalaryDatau[$count]);
                    // $salary = Salary::updateOrCreate(
                    //     [
                    //         'emp_id' => $userAttendance->engineer_id, 
                    //         'main_machine_type' => $main_machine_type,
                    //     ],
                    //     [
                    //         'emp_id' => $userAttendance->engineer_id,
                    //         'month_id' => $month,
                    //         'pdays' => $userAttendance->total_pdays,
                    //         'hdays' => $userAttendance->count_h,
                    //         'working_days' => $totalDaysInMonth,
                    //         'perday_sal' => $perDaySalary,
                    //         'total_salary' => round($calculatedSalary, 2), // rounding to 2 decimal places
                    //         'main_machine_type' => $main_machine_type,
                    //     ]
                    // );
                    if($usersWiseSalaryDatau[$count]){
                      //dd('Salary Stored');    
                       $count++;
                    } else {
                        //return redirect()->back()->withErrors($request->errors())->withInput();
                    }
                } else {
                    // Optionally handle if no payroll is found
                    //dd($userAttendance->engineer_id);
                    $employeeSalaries[] = [
                        'emp_id' => $userAttendance->engineer_id,
                        'id' => $userAttendance->id,
                        'error' => 'Payroll not found'
                    ];
                }
        }
            //exit;
            //return view('report.salaries', $data);
            return redirect()->route('salaries', ['main_machine_type' => $main_machine_type])->with('success', 'Salaries generated successfully');  
    }

    public function reassignComplaint($id) {
        $complaintNumber = Complaint::whereDate('created_at', date('Y-m-d'))->count();
        if($complaintNumber == 0) {
            $complaintNumber = $complaintNumber + 1;
        }
        $complaintNo = $complaintNumber + 1;
        $complaint = Complaint::where('id', $id)->first();
        $data = [
            'tag' => $complaint->tag,
            'user_id' => $complaint->user_id,
            'firm_id' => $complaint->firm_id,
            'year_id' => $complaint->year_id,
            'product_id' => $complaint->product_id,
            'party_id' => $complaint->party_id,
            'date' => $complaint->date,
            'time' => $complaint->time,
            'complaint_type_id' => $complaint->complaint_type_id,
            'sales_entry_id' => $complaint->sales_entry_id,
            'service_type_id' => $complaint->service_type_id,
            'status_id' => 1,
            'remarks' => 'Reassign from '.$complaintNumber,
            'complaint_no' => $complaintNo,
            'from_complaint_id' => $complaint->id,
        ];

        Complaint::create($data);

        $complaint->is_reassign = 1;
        $complaint->save();
        return response()->json(['success' => 1, 'message' => 'Complaint reassign successfully. Date is ' . date('d-m-Y') . '. Complaint number is ' . $complaintNo. '.']);
    }

    public function customerFeedback(Request $request) {
        $data['title'] = 'Customer Feedback Report';
        if ($request->ajax()) {
            $customerFeedback = CustomerFeedback::with('party','engineer')->latest();

            if (isset($request->date_range)) {
                [$startDate, $endDate] = explode(' to ', $request->date_range);
                $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
                $customerFeedback->whereBetween('date', [$startDate, $endDate]);
            }
            if (isset($request->party_id)) {
                $customerFeedback->where('party_id', $request->party_id);
            }
            if (isset($request->engineer_id)) {
                $customerFeedback->where('engineer_id', $request->engineer_id);
            }
            if (isset($request->star_rating)) {
                $customerFeedback->where('star_rating', $request->star_rating);
            }
            $customerFeedback = $customerFeedback->get();
            return DataTables::of($customerFeedback)
                ->addIndexColumn()
                ->addColumn("star_rating", function ($row) {
                    return $row->star_rating. ' ★';
                })
                ->addColumn("date", function ($row) {
                    return $row->date ? date('d-m-Y', strtotime($row->date)) : '';
                })
                ->addColumn("party", function ($row) {
                    return $row->party ? $row->party->name : '';
                })
                ->addColumn("engineer", function ($row) {
                    return $row->engineer ? $row->engineer->name : '';
                })
                ->make(true);
        }
        return view('report.customer_feedback', $data);
    }

    public function exportComplaintPending()
    {
        return Excel::download(new ComplaintPendingExport, 'Complaint Pending.xlsx');
    }

    public function exportComplaintStatus()
    {
        return Excel::download(new ComplaintStatusExport, 'Complaint Status.xlsx');
    }

    public function exportComplaintTypeSummary()
    {
        return Excel::download(new ComplaintTypeSummaryExport, 'Complaint Type Summary.xlsx');
    }

    public function exportEngineerDoneSummary()
    {
        return Excel::download(new EngineerDoneSummaryExport, 'Engineer Done Summary.xlsx');
    }

    public function exportEngineerPerformance()
    {
        return Excel::download(new EngineerPerformanceExport, 'Engineer Performance Details.xlsx');
    }

    public function exportCustomerFeedback()
    {
        return Excel::download(new CustomerFeedbackExport, 'Customer Feedback.xlsx');
    }

    public function exportAPToday(Request $request)
    {
        $main_machine_type = $request->main_machine_type;
        return Excel::download(new APTodayExport($main_machine_type), 'AP Today.xlsx');
    }

    public function exportAPSummary(Request $request)
    {
        $main_machine_type = $request->main_machine_type;
        return Excel::download(new APSummaryExport($main_machine_type), 'AP Summary.xlsx');
    }

    public function exportEngineerAPSummary(Request $request)
    {
        //$main_machine_type = $request->main_machine_type;
        //$fmonth = $month;
        //$data['main_machine_type'] = $main_machine_type;
        $data['fmonth'] = $request->month;
        $data['engineer_id'] = $request->engineer_id;
        //dd($data['engineer_id']);
        return Excel::download(new EngineerAPSummaryExport($data), 'Engineer AP Summary.xlsx');
    }
}
