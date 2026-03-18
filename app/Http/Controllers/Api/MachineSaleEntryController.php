<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MachineSalesEntry;
use App\Models\Party;
use App\Models\PartywiseMachine;
use App\Models\PartyFirm;
use App\Models\VisitSteps;
use App\Models\VisitStepsDone;
use App\Models\User;
use App\Models\Complaint;
use App\Models\JointComplaint;
use App\Models\Product;
use App\Models\Visit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\NotificationService;
use Illuminate\Support\Str;

class MachineSaleEntryController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function machineNumber(Request $request)
    {
        //$machineSalesEntry = MachineSalesEntry::where('party_id', $request->party_id)->get('mc_no');
        $machineSalesEntry = MachineSalesEntry::where('party_id', $request->party_id)->get(['mc_no', 'order_no', 'serial_no']);
        if (count($machineSalesEntry) > 0) {
            return response()->json([
                "success" => true,
                "message" => "Machine number get successfully",
                "data" => $machineSalesEntry,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Machine number not found",
                "data" => [],
            ]);
        }
    }

    public function customerMachines($id, $machine_no = null)
    {
        $party = MachineSalesEntry::where(['party_id' => $id, 'is_active' => 1])->with('party', 'product', 'serviceType');
        if ($machine_no) {
            $party->where('mc_no', $machine_no);
        }
        $paginatedMachines = $party->paginate(10);

        $data = [];
        foreach ($paginatedMachines->items() as $item) {
            $complaintData = Complaint::where('sales_entry_id', $item->id)->where('status_id', '!=', 3)->first();
            if (!empty($complaintData)) {
                $item['c_message'] = $complaintData->complaint_no;
            } else {
                $item['c_message'] = '';
            }
            array_push($data, $item);
        }

        return response()->json([
            "success" => true,
            "message" => "All customer machines",
            'data' => [
                'total' => $paginatedMachines->total(),
                'per_page' => $paginatedMachines->perPage(),
                'current_page' => $paginatedMachines->currentPage(),
                'last_page' => $paginatedMachines->lastPage(),
                'from' => $paginatedMachines->firstItem(),
                'to' => $paginatedMachines->lastItem(),
                'data' => $data
            ]
        ], 200);
    }

    // Get the machine installation or sold machine list
    public function machineInstallationlist(Request $request)
    {
        $main_machine_type = $request['main_machine_type'];
        
        // $machineInstallationlist = MachineSalesEntry::where(['main_machine_type' => $main_machine_type, 'is_active' => 1])->with('party', 'product');
        // if(isset($request->date)) {
        //    $machineInstallationlist = MachineSalesEntry::where(['date'=> $request->date, 'main_machine_type' => $main_machine_type, 'is_active' => 1])->with('party', 'product'); 
        // }
        // if(isset($request['party_id']) && $request['party_id'] !="") {
        //    $party_id = $request['party_id'];
        //    $machineInstallationlist = MachineSalesEntry::where(['party_id' => $party_id, 'main_machine_type' => $main_machine_type, 'is_active' => 1])->with('party', 'product'); 
        // }
        // if(isset($request->status)) {
        //     if($request->status == 0) {
        //         $machineInstallationlist = MachineSalesEntry::where(['main_machine_type' => $main_machine_type, 'is_active' => 1])->with('party', 'product');
        //     } else {
        //         $machineInstallationlist = MachineSalesEntry::where(['status'=> $request->status, 'main_machine_type' => $main_machine_type, 'is_active' => 1])->with('party', 'product'); 
        //    }
        // }
        // if(isset($request->product_id)) {
        //    $machineInstallationlist = MachineSalesEntry::where(['product_id'=> $request->product_id, 'main_machine_type' => $main_machine_type, 'is_active' => 1])->with('party', 'product'); 
        // }
        // $machineInstallationlist = $machineInstallationlist->latest()->paginate(10);

        $machineInstallationlist = MachineSalesEntry::with('party', 'product')
        ->where('main_machine_type', $main_machine_type)
        ->where('is_active', 1)
        ->when($request->date, fn ($q) =>
            $q->whereDate('date', $request->date)
        )
        ->when($request->party_id, fn ($q) =>
            $q->where('party_id', $request->party_id)
        )
        ->when(isset($request->status) && $request->status != 0, fn ($q) =>
            $q->where('status', $request->status)
        )
        ->when($request->product_id, fn ($q) =>
            $q->where('product_id', $request->product_id)
        )
        ->latest()
        ->paginate(10);

        if(isset($machineInstallationlist))
        {
            return response()->json([
                "success" => true,
                "message" => "All Sold Machines",
                'data' => $machineInstallationlist, 
                'total' => $machineInstallationlist->total(), 
            ], 200);

        } else {
            return response()->json([
                "success" => false,
                "message" => "All Sold Machines not fetches",
                'data' => [],
            ], 500);
        } 
    }

    // Get the machine installation or sold machine list
    public function partyMachineInstallationlist(Request $request)
    {
        $main_machine_type = $request['main_machine_type'];
        $party_id = $request['party_id'];
        
        $machineInstallationlist = MachineSalesEntry::with('party', 'product')
        ->where('party_id', $party_id)
        ->where('main_machine_type', $main_machine_type)
        ->where('is_active', 1)
        ->when($request->date, fn ($q) =>
            $q->whereDate('date', $request->date)
        )
        ->when(isset($request->status) && $request->status != 0, fn ($q) =>
            $q->where('status', $request->status)
        )
        ->when($request->product_id, fn ($q) =>
            $q->where('product_id', $request->product_id)
        )
        ->latest()
        ->paginate(10);

        if(isset($machineInstallationlist))
        {
            return response()->json([
                "success" => true,
                "message" => "All Sold Machines",
                'data' => $machineInstallationlist, 
                'total' => $machineInstallationlist->total(), 
            ], 200);

        } else {
            return response()->json([
                "success" => false,
                "message" => "All Sold Machines not fetches",
                'data' => [],
            ], 500);
        } 
    }

    // Get the machine installation or sold machine by id
    public function getMachineInsbyId($mid, Request $request)
    {
        $main_machine_type = $request['main_machine_type'];
        
        $machineInstallationlist = MachineSalesEntry::where(['id' => $mid, 'main_machine_type' => $main_machine_type, 'is_active' => 1])->with('party', 'product')->first();

        if(isset($machineInstallationlist))
        {
            return response()->json([
                "success" => true,
                "message" => "Sold Machines Details Fetched",
                'data' => $machineInstallationlist,  
            ], 200);

        } else {
            return response()->json([
                "success" => false,
                "message" => "Sold Machines Details not fetched",
                'data' => [],
            ], 500);
        } 
    }

    // Update Machine sales data
    public function updateMachineInsbyId($mid, Request $request)
    {
        $main_machine_type = $request['main_machine_type'];
        
        $data = $request->all();
        $data['firm_id'] = isset($request->firm_id) ? $request->firm_id : 0;
        $data['year_id'] = 1;
        $data['is_active'] = 1;
        $data['mic_fitting_engineer_id'] = 0;
        //$data['free_service'] = $data['free_service'] != "" ? $data['free_service'] : 0;
        $data['date'] = isset($request->date) ? $this->changeDateFormate($request->date) : null;
        $data['install_date'] = isset($request->install_date) ? $this->changeDateFormate($request->install_date) : null;
        $data['service_expiry_date'] = isset($request->service_expiry_date) ? $this->changeDateFormate($request->service_expiry_date) : null;
        $data['free_service_date'] = isset($request->free_service_date) ? $this->changeDateFormate($request->free_service_date) : null;
        $machineInstallationlist = MachineSalesEntry::find($mid)->update($data);

        if(isset($machineInstallationlist))
        {
            return response()->json([
                "success" => true,
                "message" => "Sold Machines Details Updated",
                'data' => $machineInstallationlist,  
            ], 200);

        } else {
            return response()->json([
                "success" => false,
                "message" => "Sold Machines Details not Updated",
                'data' => [],
            ], 500);
        }
    }

    //Store new machine sales entry
    public function storeMachineIns(Request $request)
    {
        $main_machine_type = $request['main_machine_type'];
        
        $data = $request->all();
        $data['firm_id'] = 1;
        $data['year_id'] = 1;
        $data['is_active'] = 1;
        $data['mic_fitting_engineer_id'] = 0;
        $letters = strtoupper(Str::random(2)); // e.g. "AB"
        $numbers = rand(100, 999);              // e.g. "34"
        $uniqueCode = "PCW". $letters . $numbers;    // "AB34"
        //$data['order_no'] = $uniqueCode;
        $data['mc_no'] = $uniqueCode;
        //$data['free_service'] = $data['free_service'] != "" ? $data['free_service'] : 0;
        $data['date'] = $this->changeDateFormate($request->date);
        $data['install_date'] = $this->changeDateFormate($request->install_date);
        $data['service_expiry_date'] = $this->changeDateFormate($request->service_expiry_date);
        $data['free_service_date'] = $this->changeDateFormate($request->free_service_date);
        $machineInstallationlist = MachineSalesEntry::create($data);

        if(isset($machineInstallationlist))
        {
            return response()->json([
                "success" => true,
                "message" => "Sold Machines Details Stored",
                'data' => $machineInstallationlist,  
            ], 200);

        } else {
            return response()->json([
                "success" => false,
                "message" => "Sold Machines Details not Stored",
                'data' => [],
            ], 500);
        }
    }

    //Store new machine sales entry with parties
    public function storeMachineInsWithPartyFirm(Request $request)
    {
        $main_machine_type = $request['main_machine_type'];
        //dd($request->activetab);
        
        $data = $request->all();
        //dd($data->firm_data);
        //dd(json_decode($request));
        // return response()->json([
        //                 "success" => true,
        //                 "message" => "Customer and Machine data",
        //                 'data' => $request['activetab'],
        //             ], 200); 

                    
        if(isset($request->activetab) && $request->activetab == 0) {

            // return response()->json([
            //             "success" => true,
            //             "message" => "Customer and Machine data",
            //             'data' => "if activetab is 0",
            //         ], 200);

            // dd("if activetab is 0");
        
            // if(!isset($request->is_active)) {
            //     $data['is_active'] = 0;
            // }

            // Add Partywise Machine entry if not there for a party
            $pwmodata = Party::where('phone_no', $request->phone_no)->first();
            //dd(count($pwmdata));        
            if ($pwmodata) {
                return response()->json([
                        "success" => false,
                        "message" => "Phone is already there",
                        'data' => [],
                    ], 500);
                
            } else {
               //echo "In else".$request->main_machine_type;
               $letters = strtoupper(Str::random(2)); // e.g. "AB"
               $numbers = rand(100, 999);              // e.g. "34"
               $uniqueCode = $letters . $numbers;    // "AB34"
               $data['code'] = $uniqueCode;
               $data['firm_id'] = 1;
               $data['is_active'] = 1;
               $data['name'] = $request->owner_name;
               $party = Party::create($data);
               $pfdata = [];
               $pfdata['party'] = $party;
               PartywiseMachine::create(['party_id' => $party->id, 'main_machine_type' => $request->main_machine_type]);

               //Party Firms Entry
               $pfdata['party_id'] = $party->id;
        
               if (!empty($request->firm_data)) {
                        //$raw = $request->firm_data; // string
                        // Convert JS-style object to valid JSON
                        //$json = preg_replace('/(\w+):/', '"$1":', $raw);

                        // Wrap values in quotes where missing (simple case)
                        //$json = str_replace("'", '"', $json);

                        $firm_namearr = $request->firm_data;

                        //print_r($firm_namearr);
                        //dd($firm_namearr);

                        // if (json_last_error() !== JSON_ERROR_NONE) {
                        //     return response()->json(['error' => 'Invalid firm data'], 400);
                        // }
                    foreach ($firm_namearr as $index => $firm) {
                        
                            // Skip empty rows (important)
                            if (empty($firm)) {
                                continue;
                            }
                            $pfdata = [
                                'party_id'   => $party->id,   // parent party id
                                'firm_name'  => $firm['firm_name'],
                                'firm_owner' => $firm['firm_owner'] ?? null,
                                'firmowner_phone' => $firm['firmowner_phone'] ?? null,
                                'firm_gst'     => $firm['firm_gst'] ?? null,
                                'firm_address'    => $firm['firm_address'] ?? null,
                            ];
                            //dd($pfdata);
                            PartyFirm::create($pfdata);
                    }
                }
               //Toastr::success('Customer and Firms created successfully');
            }
            
            //$data = [];
                if(isset($pfdata))
                {
                    return response()->json([
                        "success" => true,
                        "message" => "Customer and firms saved",
                        'data' => $pfdata,  
                    ], 200);

                } else {
                    return response()->json([
                        "success" => false,
                        "message" => "Customer not savved",
                        'data' => [],
                    ], 500);
                }
            
        } else if (isset($request->activetab) && $request->activetab == 1) {
            $data = [];
            //$data = $request->all();
            // Machine Sale Entry
            //dd("Machine Sale Start");
               if (!empty($request->machine_data)) {

                        //$raw = $request->width; // string
                        // Convert JS-style object to valid JSON
                        //$json = preg_replace('/(\w+):/', '"$1":', $raw);

                        // Wrap values in quotes where missing (simple case)
                        //$json = str_replace("'", '"', $json);

                        $width_arr = $request->machine_data;

                        //print_r($firm_namearr);
                        //dd($firm_namearr);

                    foreach ($width_arr as $index => $width) {
                        // Skip empty rows (important)
                        if (empty($width)) {
                            continue;
                        }
                        //$data['party_id'] =  $request->party_id;
                        $letters = strtoupper(Str::random(2)); // e.g. "AB"
                        $numbers = rand(100, 999);              // e.g. "34"
                        $uniqueCode = "PCW". $letters . $numbers;    // "AB34"
                        $data = [
                            'firm_id' => $request->firm_id,
                            'mc_no' => $uniqueCode,
                            'status' => $width['status'],
                            'party_id' => $request->party_id,
                            //'product_id' => $request->product_id,
                            'product_id' => $width['product_id'] ?? 1,
                            'weft_insertion'  => $width['weft_insertion'] ?? null,
                            'width'  => $width['width'] ?? null,
                            'color' => $width['color'] ?? null,
                            'shadding' => $width['shadding'] ?? null,
                            'shadding_option' => $width['shadding_option'] ?? null,
                            'order_no'     => $width['order_no'] ?? null,
                            'serial_no'    => $width['seralNo'] ?? null,
                            'contenor_no' => $request->contenor_no,
                            'main_machine_type' => $request->main_machine_type,
                            'date' => Carbon::now()->format('Y-m-d'),
                            'install_date' => Carbon::now()->format('Y-m-d'),
                            'service_expiry_date' => Carbon::now()->addYear()->format('Y-m-d'),
                            'free_service_date' => Carbon::now()->format('Y-m-d'),
                        ];
                        $pfdata = MachineSalesEntry::create($data);
                    }
                }

                if(isset($pfdata))
                {
                    return response()->json([
                        "success" => true,
                        "message" => "Machine of cust with firm saved",
                        'data' => $pfdata,  
                    ], 200);

                } else {
                    return response()->json([
                        "success" => false,
                        "message" => "Machine of cust with firm not savved",
                        'data' => [],
                    ], 500);
                }            
        } else {
                    return response()->json([
                        "success" => false,
                        "message" => "Customer and Machine not savved",
                        'data' => [],
                    ], 500); 
        }  
    }

    // To Chanage date formate
    public function changeDateFormate($date)
    {
        $newFormate = Carbon::parse($date);
        return $newFormate->format('Y-m-d');
    }

    // Get the Machine Installation Visits List
    public function machineInsVisitlist($id, Request $request)
    {
        $main_machine_type = $request['main_machine_type'];
        $machinesale = $id;
        // $machineInstallationlist = Visit::where(['service_id' => $machinesale,'main_machine_type' => $main_machine_type])->with('party', 'product', 'machineSales');
        // if(isset($request->date)) {
        //    $machineInstallationlist = Visit::where(['date'=> $request->date, 'main_machine_type' => $main_machine_type])->with('party', 'product'); 
        // }
        // if(isset($request->party_id)) {
        //    $machineInstallationlist = Visit::where(['party_id'=> $request->party_id, 'main_machine_type' => $main_machine_type])->with('party', 'product', 'machineSales'); 
        // }
        // if(isset($request->status)) {
        //     if($request->status == 0) {
        //         $machineInstallationlist = Visit::where(['main_machine_type' => $main_machine_type])->with('party', 'product', 'machineSales');
        //     } else {
        //         $machineInstallationlist = Visit::where(['status'=> $request->status, 'main_machine_type' => $main_machine_type])->with('party', 'product', 'machineSales'); 
        //    }
        // }
        // if(isset($request->product_id)) {
        //    $machineInstallationlist = Visit::where(['product_id'=> $request->product_id, 'main_machine_type' => $main_machine_type])->with('party', 'product', 'machineSales'); 
        // }
        // $machineInstallationlist = $machineInstallationlist->latest()->paginate(10);

        $machineInstallationlist = Visit::with('party', 'product', 'machineSales', 'jointComplaints.jntengDetail', 'visitstepsdone')
        ->where(['service_id' => $machinesale, 'main_machine_type' => $main_machine_type])
        ->when($request->date, fn ($q) =>
            $q->whereDate('date', $request->date)
        )
        ->when($request->party_id, fn ($q) =>
            $q->where('party_id', $request->party_id)
        )
        ->when(isset($request->status) && $request->status != 0, fn ($q) =>
            $q->where('status', $request->status)
        )
        ->when($request->product_id, fn ($q) =>
            $q->where('product_id', $request->product_id)
        )
        ->latest()
        ->paginate(10);

        if(isset($machineInstallationlist))
        {
            return response()->json([
                "success" => true,
                "message" => "All Machine visti list fetched",
                'data' =>  $machineInstallationlist,
                'total' => $machineInstallationlist->total(), 
            ], 200);

        } else {
            return response()->json([
                "success" => false,
                "message" => "No Machine visit list fetched",
                'data' => [],
            ], 500);
        } 
    }

    // Create the Machine Installation Visit
    public function CreateMachineInsVisit($id, Request $request)
    {
        $machinesale = $id;
        $main_machine_type = $request->main_machine_type;
        $data['main_machine_type'] = $main_machine_type;
        $data['service_type'] = 2;
        $data['service_id'] = $machinesale;
        $data['product_id'] = $request->product_id;
        $data['party_id'] = $request->party_id;
        $data['engineer_id'] = $request->engineer_id;
        $data['title'] = $request->title;
        $data['date'] = $request->date;
        $data['end_date'] = $request->end_date;
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
                //$complaint_data['jointengg'] = isset($request->jointengg) ? implode(",", $request->jointengg) : null;
                $complaint_data['jointengg'] = $request->jointengg;
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
            if(isset($request->stepsdone) ) { 
                $stepsdone1 = trim($request->stepsdone, '"');    
                $stepsdone2 = trim($stepsdone1, '[');
                $stepsdone3 = trim($stepsdone2, ']');
                $stepsdonearr = explode(',', $stepsdone3);
                //$stepsdonearr = $request->stepsdone;
                if(count($stepsdonearr) > 1)
                {
                    foreach ($stepsdonearr as $sdid) {
                        $stepsdone_data['visit_id'] = $visit->id;
                        $stepsdone_data['step_id'] = $sdid;
                        $stepsdone_data['status'] = 1;
                        //dd($jengid);
                        $stepsdone = VisitStepsDone::create($stepsdone_data);
                    }
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
                $type = $main_machine_type;

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
                $request['jointengg'] = $request->jointengg;
                //$jointenggarr = explode(",", $request->jointengg);
                //$jointengg = $request->jointengg;
                //$jointenggarr = $request->jointengg;
                $jointenggengs1 = trim($request->jointengg, '"');    
                $jointenggengs2 = trim($jointenggengs1, '[');
                $jointenggengs = trim($jointenggengs2, ']');
                $jointenggarr = explode(',', $jointenggengs);
                $Jtoken = [];
                foreach ($jointenggarr as $jengid) {
                    $joint_complaint_data['complaint_id'] = $complaint->id;
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
                        $type = $main_machine_type;
                        $this->notificationService->sendAlarmNotification($title, $body, $Jtoken, $type, $complaint->id);          
            }

        if(isset($complaint) && isset($visit))
        {
            return response()->json([
                "success" => true,
                "message" => "Machine installation visit created",
                'data' =>  $complaint,
                
            ], 200);

        } else {
            return response()->json([
                "success" => false,
                "message" => "Machine installation visit NOT created",
                'data' => [],
            ], 500);
        } 
    }

    // Update the Machine Installation Visit
    public function UpdateMachineInsVisit($id, $vid, Request $request)
    {
        $machinesale = $id;
        $visit_data = Visit::where('id', $vid)->first();
        //dd($visit_data->complaint_id);
        $main_machine_type = $request->main_machine_type;
        $data['main_machine_type'] = $main_machine_type;
        $data['service_type'] = 2;
        $data['service_id'] = $machinesale;
        $data['product_id'] = $request->product_id;
        $data['party_id'] = $request->party_id;
        $data['engineer_id'] = $request->engineer_id;
        $data['title'] = $request->title;
        $data['date'] = $request->date;
        $data['end_date'] = $request->end_date;
        $data['visit_remark'] = $request->visit_remark;
        $data['status'] = $request->status;

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
                //$complaint_data['jointengg'] = isset($request->jointengg) ? implode(",", $request->jointengg) : null;
                $complaint_data['jointengg'] = $request->jointengg;
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

            //$complaint = Complaint::create($complaint_data);
            $complaint = Complaint::find($visit_data->complaint_id)->update($complaint_data);
            //$complaintId = $complaint->id;
            $data['complaint_id'] = $visit_data->complaint_id;
            //$visit = visit::create($data);
            $visit = Visit::find($vid)->update($data);

            // Steps Done Entry by removing old entries
            if(isset($request->stepsdone)) { 
                VisitStepsDone::where('visit_id', $vid)->delete();
                //$stepsdonearr = $request->stepsdone;
                $stepsdone1 = trim($request->stepsdone, '"');    
                $stepsdone2 = trim($stepsdone1, '[');
                $stepsdone3 = trim($stepsdone2, ']');
                $stepsdonearr = explode(',', $stepsdone3);
                if(count($stepsdonearr) > 1) {
                    foreach ($stepsdonearr as $sdid) {
                        $stepsdone_data['visit_id'] = $vid;
                        $stepsdone_data['step_id'] = $sdid;
                        $stepsdone_data['status'] = 1;
                        //dd($jengid);
                        $stepsdone = VisitStepsDone::create($stepsdone_data);
                    }
                }    
            }

            // Send push notification to engineer
            if (isset($request->engineer_id)) {
                $engineer = User::where('id', $request->engineer_id)->first();
            }
            if (!empty($engineer)) {
                $title = 'New Erection';
                $body = 'A new Installation Erection work been assigned to you.';
                $token = $engineer->device_token;
                $type = $main_machine_type;

                if ($title != "" && $body != "" && $token != "") {
                    // Get the response from the helper function
                    // sendNotification($title, $body, $token);
                    //$this->notificationService->sendNotification($title, $body, $token);
                     $this->notificationService->sendAlarmNotification($title, $body, $token, $type, $visit_data->complaint_id);
                }
            }

        // make entries to joint complaint table
            if (isset($request->jointengg)) {

                $joint_complaint_data = [];
                $joint_complaint_data['complaint_id'] = $machinesale;
                $joint_complaint_data['service_type'] = 2;
                $joint_complaint_data['visit_id'] = $vid;
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
                $request['jointengg'] = $request->jointengg;
                //$jointenggarr = explode(",", $request->jointengg);
                //$jointengg = $request->jointengg;
                //$jointenggarr = $request->jointengg;
                $jointenggengs1 = trim($request->jointengg, '"');    
                $jointenggengs2 = trim($jointenggengs1, '[');
                $jointenggengs = trim($jointenggengs2, ']');
                $jointenggarr = explode(',', $jointenggengs);
                JointComplaint::where('complaint_id', $visit_data->complaint_id)->delete();
                $Jtoken = [];
                foreach ($jointenggarr as $jengid) {
                    $joint_complaint_data['complaint_id'] = $visit_data->complaint_id;
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
                        $type = $main_machine_type;
                        $this->notificationService->sendAlarmNotification($title, $body, $Jtoken, $type, $visit_data->complaint_id);          
            }

        if(isset($complaint) && isset($visit))
        {
            return response()->json([
                "success" => true,
                "message" => "Machine installation visit updated",
                'data' =>  $complaint,
                
            ], 200);

        } else {
            return response()->json([
                "success" => false,
                "message" => "Machine installation visit NOT updated",
                'data' => [],
            ], 500);
        } 
    }

    
    public function visitSteps(Request $request) {

        $visitSteps = VisitSteps::get();
        //return response()->json(['firms' => $partyFirms]);
        if (!empty($visitSteps)) {
            return response()->json([
                "success" => true,
                "message" => "Visit Steps fetch successfuly",
                "data" => $visitSteps,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Visit Steps not fetched",
                "data" => (object)[],
            ]);
        }
    }

    // Get the machine expiry information
    public function machineExpiry(string $partyId)
    {
        $machinesExpiryQuery = MachineSalesEntry::with('party', 'product', 'serviceType')->where('party_id', $partyId)->where('service_expiry_date', '<=', Carbon::now()->addDays(15))->get();
        $data = [];
        foreach ($machinesExpiryQuery as $item) {
            $expiryDate = Carbon::parse($item->service_expiry_date);

            $todayDate = Carbon::today();
            if ($expiryDate->lessThan($todayDate)) {
                $item['is_expired'] = "Expired";
            } else {
                $daysLeft = $todayDate->diffInDays($expiryDate);
                if ($daysLeft < 16) {
                    $item['is_expired'] = $daysLeft . " days left";
                }
            }
            array_push($data, $item);
        }

        return response()->json([
            "success" => True,
            "message" => "Machine Expied get successfully",
            "data" => $data,
        ], 200);
    }
}
