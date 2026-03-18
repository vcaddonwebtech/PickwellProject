<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JointComplaint;
use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\MachineSalesEntry;
use App\Models\Party;
use App\Services\NotificationService;

class ComplaintController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $lUserid = auth()->user()->id;
        if(isset($request->complaint_id) && !empty($request->complaint_id && $request->complaint_id != 0)){
            // Edit Copmlaint
            $complaintData = Complaint::whereId($request->complaint_id)->first();
            $data['user_id'] = $lUserid;
            $data['firm_id'] = 1;
            $data['year_id'] = 1;
            $data['engineer_id'] = $request->engineer_id;
            $data['is_assigned'] = 1;
            $data['engineer_assign_date'] = date('Y-m-d');
            $data['engineer_assign_time'] = date('h:i:s');
            
            //$data['jointengg'] = isset($request->jointengg) ? implode(",", $request->jointengg) : null;
            $complaintupdatedData = $complaintData->update($data);

            // make entries to joint complaint table
            if (isset($request->jointengg)) {

                $joint_complaint_data = [];
                $jointenggarr = []; 
                $joint_complaint_data['complaint_id'] = $request->complaint_id;
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
                $joint_complaint_data['joint_eng_status'] = null;
                $joint_complaint_data['joint_eng_remarks'] = null;
                //$request['jointengg'] = isset($request->jointengg) ? implode(",", $request->jointengg) : null;
                //$jointenggarr = explode(",", $request->jointengg);
                //$jointengg = $request->jointengg;
                $jointenggengs1 = trim($request->jointengg, '"');    
                $jointenggengs2 = trim($jointenggengs1, '[');
                $jointenggengs = trim($jointenggengs2, ']');
                $jointenggarr = explode(',', $jointenggengs);
                // Remove todo tasks
                JointComplaint::where('complaint_id', $request->complaint_id)->delete();
                $Jtoken = [];
                foreach ($jointenggarr as $jengid) {
                    // Skip main assigned engineer
                    if (empty($jengid) || $jengid == $request->engineer_id) {
                        continue;
                    }
                    // Create joint complaint entry
                    JointComplaint::create([
                        'complaint_id' => $request->complaint_id,
                        'joint_eng_id' => $jengid,
                    ]);
                    // Fetch engineer
                    $user = User::find($jengid);
                    // Collect valid device tokens
                    if (!empty($user?->device_token)) {
                        $Jtoken[] = $user->device_token;
                    }
                } 
                $title = 'New Complaint';
                $body = 'A new complaint has been assigned to you. Complaint No: ' . $data['complaint_no'];
                //$type = 'engineer';
                $type = $request->main_machine_type;
                $this->notificationService->sendAlarmNotification($title, $body, $Jtoken, $type, $complaintData->id);              
            }

                // Engineer push notification
                if (isset($request->engineer_id)) {
                    if (isset($complaintData) && !empty($complaintData)) {
                        //$user = User::find($complaintData['engineer_id']);
                        $user = User::find($request->engineer_id);
                        if (!empty($user->device_token)) {
                            // Send push notification
                            $title = 'New Complaint';
                            $body = 'A new complaint has been assigned to you. Complaint No: ' . $data['complaint_no'];
                            $token = $user->device_token;
                            //$type = 'engineer';
                            $type = $request->main_machine_type;

                            if ($title != "" && $body != "" && $token != "") {
                                // Get the response from the helper function
                                // sendNotification($title, $body, $token);
                                $this->notificationService->sendAlarmNotification($title, $body, $token, $type, $complaintData->id);
                            }
                        }
                    }
                }

                // Party Push Notification
                if (isset($complaintData) && !empty($complaintData) && isset($request->engineer_id)) {
                    $party = Party::find($complaintData['party_id']);
                    if (!empty($party->device_token)) {
                        // Send push notification
                        if (isset($request->engineer_id)) {
                            $user = User::find($request->engineer_id);
                            $title = 'Complaint is assigned';
                            $body = 'Your complain has been reassigned to ' . $user->name . ' Complaint No: ' . $complaintData['complaint_no'];
                        } else {
                            $title = 'Complaint Created';
                            $body = 'Your complaint has been created successfully. Complaint No: ' . $complaintData['complaint_no'];
                        }
                        $token = $party->device_token;


                        if ($title != "" && $body != "" && $token != "") {
                            // Get the response from the helper function
                            // sendNotification($title, $body, $token);
                            $this->notificationService->sendNotification($title, $body, $token);
                        }
                    }
                }

            if($complaintupdatedData){
                return response()->json([
                    "success" => true,
                    "message" => "Complaint updated successfully",
                    "data" => $complaintData,
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "Something went wrong",
                    "data" => (object)[],
                ]);
            }
        } else {
            // Create Copmlaint    
            
            //$complaintData = Complaint::where('sales_entry_id', $data['sales_entry_id'])->where('status_id', '!=', 3)->first();
            // if (!empty($complaintData)) {
            //     return response()->json([
            //         "success" => false,
            //         "message" => "Complaint already created. Complaint No is '.$complaintData->complaint_no.'.'",
            //         "data" => (object)[],
            //     ]);
            // }

            // $machine = MachineSalesEntry::select('id', 'product_id', 'is_active', 'order_no')->where('id', $request->sales_entry_id)->first();
            // if ($machine->is_active != 1) {
            //     return response()->json([
            //         "success" => false,
            //         "message" => "Is M/c selles record false... Can not use.. Order no is " . $machine->order_no,
            //         "data" => (object)[],
            //     ]);
            // }

            if (isset($data['image'])) {
                $file = $data['image'];
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('complaint/images'), $filename);
                $data['image'] = $filename;
            }
            if (isset($data['video'])) {
                $file = $data['video'];
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('complaint/videos'), $filename);
                $data['video'] = $filename;
            }
            if (isset($data['audio'])) {
                $file = $data['audio'];
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('complaint/audios'), $filename);
                $data['audio'] = $filename;
            }
            $data['user_id'] = $lUserid;
            $data['firm_id'] = 1;
            $data['year_id'] = 1;
            $data['complaint_no'] = Complaint::whereDate('date', date('Y-m-d'))->count() + 1;
            $complaint = Complaint::create($data);
            if (!empty($complaint)) {
                $complaintData = Complaint::whereId($complaint->id)->first();

                //if more then one enginees - Join engs entry
                if(isset($request->jointengg) && !empty($request->jointengg)) {
                    $jointenggengs1 = trim($request->jointengg, '"');    
                    $jointenggengs2 = trim($jointenggengs1, '[');
                    $jointenggengs = trim($jointenggengs2, ']');
                    //dd($jointenggengs);
                    
                    $jointenggArr = explode(',', $jointenggengs);
                        $Jtoken = [];
                        foreach ($jointenggArr as $jengid) {
                            $joint_complaint_data['joint_eng_id'] = $jengid;
                            //dd($jengid);
                            //$joint_complaint = JointComplaint::create($joint_complaint_data);
                            // Create joint complaint entry
                               $joint_complaint = JointComplaint::create([
                                    'complaint_id' => $complaint->id,
                                    'joint_eng_id' => $jengid,
                                ]);
                            if (!empty($jengid)) {
                                // Fetch engineer
                                $user = User::find($jengid);
                                // Collect valid device tokens
                                if (!empty($user?->device_token)) {
                                    $Jtoken[] = $user->device_token;
                                }
                            }
                        } 
                        $title = 'New Complaint';
                        $body = 'A new complaint has been assigned to you. Complaint No: ' . $data['complaint_no'];
                        //$type = 'engineer';
                        $type = $request->main_machine_type;
                        $this->notificationService->sendAlarmNotification($title, $body, $Jtoken, $type, $complaint->id); 
                } 

                // Engineer push notification
                if (isset($request->engineer_id)) {
                    $complaint->update([
                        'engineer_id' => $request->engineer_id,
                        'is_assigned' => 1,
                        'engineer_assign_date' => date('Y-m-d'),
                        'engineer_assign_time' => date('h:i:s'),
                    ]);

                    if (isset($complaintData) && !empty($complaintData)) {
                        $user = User::find($complaintData['engineer_id']);
                        if (!empty($user->device_token)) {
                            // Send push notification
                            $title = 'New Complaint';
                            $body = 'A new complaint has been assigned to you. Complaint No: ' . $data['complaint_no'];
                            $token = $user->device_token;
                            //$type = 'engineer';
                            $type = $request->main_machine_type;

                            if ($title != "" && $body != "" && $token != "") {
                                // Get the response from the helper function
                                // sendNotification($title, $body, $token);
                                $this->notificationService->sendAlarmNotification($title, $body, $token, $type, $complaintData->id);
                            }
                        }
                    }
                }

                // Party Push Notification
                if (isset($complaintData) && !empty($complaintData)) {
                    $party = Party::find($complaintData['party_id']);
                    if (!empty($party->device_token)) {
                        // Send push notification
                        if (isset($request->engineer_id)) {
                            $user = User::find($request->engineer_id);
                            $title = 'Complaint is assigned';
                            $body = 'Your complain has been assigned to ' . $user->name . ' Complaint No: ' . $complaintData['complaint_no'];
                        } else {
                            $title = 'Complaint Created';
                            $body = 'Your complaint has been created successfully. Complaint No: ' . $complaintData['complaint_no'];
                        }
                        $token = $party->device_token;


                        if ($title != "" && $body != "" && $token != "") {
                            // Get the response from the helper function
                            // sendNotification($title, $body, $token);
                            $this->notificationService->sendNotification($title, $body, $token);
                        }
                    }
                }

                return response()->json([
                    "success" => true,
                    "message" => "Complaint created successfully",
                    "data" => $complaintData,
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


    public function CustomerComplainstore(Request $request)
    {
        $data = $request->all();
        $lUserid = auth()->user()->id;
        //$main_machine_type = $request->main_machine_type;
        $main_machine_type = isset($request->main_machine_type) ? $request->main_machine_type : 1;
        // $complaintData = Complaint::where('sales_entry_id', $data['sales_entry_id'])->where('status_id', '!=', 3)->first();
        // if (!empty($complaintData)) {
        //     return response()->json([
        //         "success" => false,
        //         "message" => "Complaint already created. Complaint No is '.$complaintData->complaint_no.'.'",
        //         "data" => (object)[],
        //     ]);
        // }

        // $machine = MachineSalesEntry::select('id', 'product_id', 'is_active', 'order_no')->where('id', $request->sales_entry_id)->first();
        // if ($machine->is_active != 1) {
        //     return response()->json([
        //         "success" => false,
        //         "message" => "Is M/c selles record false... Can not use.. Order no is " . $machine->order_no,
        //         "data" => (object)[],
        //     ]);
        // }

        if (isset($data['image'])) {
            $file = $data['image'];
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('complaint/images'), $filename);
            $data['image'] = $filename;
        }
        if (isset($data['video'])) {
            $file = $data['video'];
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('complaint/videos'), $filename);
            $data['video'] = $filename;
        }
        if (isset($data['audio'])) {
            $file = $data['audio'];
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('complaint/audios'), $filename);
            $data['audio'] = $filename;
        }
        //$data['user_id'] = $lUserid;
        $user = User::whereHas('roles', fn ($q) =>
                                    $q->where('name', 'Service Team Leader')
                                )
                                ->whereHas('machineTypes', fn ($q) =>
                                    $q->where('main_machine_type', $main_machine_type)
                                )
                                ->where('is_leader', 1)
                                ->where('is_active', 1)
                                ->orderBy('id', 'desc') // or created_at
                                ->first();
        //$data['user_id'] = 181;
        $data['user_id'] = $user->id;
        $data['is_customer_complaint'] = 1;
        $data['firm_id'] = 1;
        $data['year_id'] = 1;
        $data['engineer_id'] = 0;
        $data['complaint_no'] = Complaint::whereDate('date', date('Y-m-d'))->count() + 1;
        $complaint = Complaint::create($data);
        if (!empty($complaint)) {
            $complaintData = Complaint::whereId($complaint->id)->first();
            // Related TL Push Notification
            if (isset($complaintData) && !empty($complaintData)) {
                     // Lerader of the machine type
                     $admin = User::whereHas('roles', fn ($q) =>
                                    $q->where('name', 'Service Team Leader')
                                )
                                ->whereHas('machineTypes', fn ($q) =>
                                    $q->where('main_machine_type', $main_machine_type)
                                )
                                ->where('is_leader', 1)
                                ->where('is_active', 1)
                                ->orderBy('id', 'desc') // or created_at
                                ->first();
                    //$admin = User::find(181);
                // Do not remove admin id 1 from DB
                $party = Party::find($complaintData['party_id']);
                // Get related TL
                // $ServiceTL = User::with('roles')->role('Service Team Leader')
                //     ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                //     $query->where('main_machine_type', $main_machine_type);
                //     })
                //     ->where('is_active', 1)
                //     ->groupBy('users.id', 'users.name')
                //     ->get();

                if (!empty($admin->device_token)) {

                    $title = 'New Complaint';
                    $body = 'New complaint is created by ' . $party->name . ' Complaint No: ' . $complaint->id;
                    $token = $admin->device_token;
                    //$type = 'engineer';
                    $type = $main_machine_type;

                    if ($title != "" && $body != "" && $token != "") {
                        // Get the response from the helper function
                        // sendNotification($title, $body, $token);
                        //$this->notificationService->sendNotification($title, $body, $token);
                        $this->notificationService->sendAlarmNotification($title, $body, $token, $type, $complaint->id);
                    }
                }
            }

            return response()->json([
                "success" => true,
                "message" => "Complaint created successfully",
                "data" => $complaintData,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong",
                "data" => (object)[],
            ]);
        }
    }

    public function CustomerComplainUpdateStatus($pid, Request $request){
        $main_machine_type = $request->main_machine_type;
        $status_id = $request->status_id;
        if(isset($request->status_id) && isset($request->complaint_id)) {
            $complaintsQuery = Complaint::where(['party_id' => $pid, 'main_machine_type' => $main_machine_type, 'id' => $request->complaint_id])->first();
            $data['status_id'] = $status_id;
            $update = Complaint::find($request->complaint_id)->update($data);
            if(isset($update)) {
                return response()->json([
                    "success" => true,
                    "message" => "Complain Updated",
                    "data" => $update,
                ]);

            } else {
                return response()->json([
                    "success" => false,
                    "message" => "Something went wrong",
                    "data" => (object)[],
                ]);
            }
        } else {
            return response()->json([
                    "success" => false,
                    "message" => "status_id and complaint_id is not passed",
                    "data" => (object)[],
                ]);
        }
    }

    // Display list of customer complaints
    public function customerComplaints(Request $request, string $id)
    {
        $main_machine_type = $request['main_machine_type'];
        // Build the query
        $complaintsQuery = Complaint::where(['party_id' => $id, 'main_machine_type' => $main_machine_type, 'status_id' => $request->status_id ? $request->status_id : 1])
            ->whereIn('service_type_id', [2, 3])
            ->with('party', 'serviceType', 'salesEntry', 'status', 'product', 'complaintType', 'engineer', 'feedback')
            ->orderBy('id', 'desc');


        // Apply pagination
        $paginatedComplaints = $complaintsQuery->paginate(10);

        if (!empty($paginatedComplaints)) {
            $data = [];
            foreach ($paginatedComplaints->items() as $item) {
                if (!empty($item->image)) {
                    $item['image_url'] = url('/complaint/images/' . $item->image);
                } else {
                    $item['image_url'] = '';
                }

                if (!empty($item->video)) {
                    $item['video_url'] = url('/complaint/videos/' . $item->video);
                } else {
                    $item['video_url'] = '';
                }

                if (!empty($item->audio)) {
                    $item['audio_url'] = url('/complaint/audios/' . $item->audio);
                } else {
                    $item['audio_url'] = '';
                }

                if (!empty($item->engineer_image)) {
                    $item['engineer_image_url'] = url('/complaint/images/' . $item->engineer_image);
                } else {
                    $item['engineer_image_url'] = '';
                }

                if (!empty($item->engineer_video)) {
                    $item['engineer_video_url'] = url('/complaint/videos/' . $item->engineer_video);
                } else {
                    $item['engineer_video_url'] = '';
                }

                if (!empty($item->engineer_audio)) {
                    $item['engineer_audio_url'] = url('/complaint/audios/' . $item->engineer_audio);
                } else {
                    $item['engineer_audio_url'] = '';
                }

                array_push($data, $item);
            }

            // Customize the response
            return response()->json([
                "success" => true,
                "message" => "Complaints fetched successfully",
                'data' => [
                    'total' => $paginatedComplaints->total(),
                    'per_page' => $paginatedComplaints->perPage(),
                    'current_page' => $paginatedComplaints->currentPage(),
                    'last_page' => $paginatedComplaints->lastPage(),
                    'from' => $paginatedComplaints->firstItem(),
                    'to' => $paginatedComplaints->lastItem(),
                    'data' => $data
                ]
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong",
                "data" => (object)[],
            ]);
        }
    }

    // Count the complains
    public function dashboardComplaintCounter(string $id)
    {
        $data['total_complaints'] = Complaint::where('party_id', $id)->whereIn('service_type_id', [2, 3])->count();
        $data['pending_complaints'] = Complaint::where(['party_id' => $id, 'status_id' => 1])->whereIn('service_type_id', [2, 3])->count();
        $data['in_progress_complaints'] = Complaint::where(['party_id' => $id, 'status_id' => 2])->whereIn('service_type_id', [2, 3])->count();
        $data['closed_complaints'] = Complaint::where(['party_id' => $id, 'status_id' => 3])->whereIn('service_type_id', [2, 3])->count();

        if (!empty($data)) {
            return response()->json([
                "success" => true,
                "message" => "Count complain status",
                "data" => $data,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong",
                "data" => (object)[],
            ]);
        }
    }

    // Count the partywise installations
    public function partyInstallationCounter(string $id)
    {
        $data['total_complaints'] = Complaint::where(['party_id' => $id, 'service_type_id' => 4])->count();
        $data['pending_complaints'] = Complaint::where(['party_id' => $id, 'status_id' => 1, 'service_type_id' => 4])->count();
        $data['in_progress_complaints'] = Complaint::where(['party_id' => $id, 'status_id' => 2, 'service_type_id' => 4])->count();
        $data['closed_complaints'] = Complaint::where(['party_id' => $id, 'status_id' => 3, 'service_type_id' => 4])->count();

        if (!empty($data)) {
            return response()->json([
                "success" => true,
                "message" => "Count installation status",
                "data" => $data,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong",
                "data" => (object)[],
            ]);
        }
    }

    

    // Eng Accept the complaint
    public function acceptComplaint(Request $request)
    {
        //$complaint = Complaint::where('id', $id)->get();
        $cid = $request->id; 
        $complaint = Complaint::find($cid);
        $engineerDetail = User::find($complaint->engineer_id);
        if (!empty($complaint) && isset($request->is_accepted)) {
            $complaint->update(['is_accepted' => 1]);

            // Admin push notification
        if (isset($complaint) && $complaint->engineer_id != "") {
            $admin = User::find('178');
            // do not remove admin id from DB

            if (!empty($admin->device_token)) {
                // Send push notification
                $title = 'Complaint is Accepted';
                $body = $engineerDetail->name . ' has accepted Complaint no ' . $complaint->complaint_no;
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
                "message" => "Complaint accepted",
                "data" => $complaint,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong",
                "data" => (object)[],
            ]);
        }
    }

    // Get the pervious complaints data
    public function previousComplaintsReport(string $id, string $sales_id)
    {
        $data = Complaint::where(['party_id' => $id, 'sales_entry_id' => $sales_id, 'status_id' => 3])
            ->with('party', 'serviceType', 'salesEntry', 'status', 'product', 'complaintType', 'engineer')
            ->orderBy('id', 'asc') // Ensure a consistent order
            ->take(Complaint::where('party_id', $id)->count() - 1) // Get all except the last record
            ->get();

        if (!empty($data)) {
            // Customize the response
            return response()->json([
                "success" => true,
                "message" => "Complaints fetched successfully",
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong",
                "data" => (object)[],
            ]);
        }
    }

    public function complaintNumber()
    {
        $complaint_no = Complaint::where('date', date('Y-m-d'))->count() + 1;
        // $complaint_no = 1;
        // $complaint = Complaint::orderBy('id', 'desc')->first();
        // if (!empty($complaint)) {
        //     $complaint_no = $complaint->complaint_no + 1;
        // }

        if (!empty($complaint_no)) {
            return response()->json([
                "success" => true,
                "message" => "Complain number",
                "data" => $complaint_no,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong",
                "data" => (object)[],
            ]);
        }
    }

    public function getComplaintNo()
    {
        if (auth()->user()->hasRole('Engineer')) {
            $complaint_no = Complaint::select('complaint_no')->where('engineer_id', auth()->user()->id)->get();
            return response()->json([
                "success" => true,
                "message" => "Complain number of engineer",
                "data" => $complaint_no,
            ]);
        } else {
            $complaint_no = Complaint::select('complaint_no')->get();
            return response()->json([
                "success" => true,
                "message" => "Complain number of engineer",
                "data" => $complaint_no,
            ]);
        }
    }

    public function complaintDone(Request $request) {
        $complaint = Complaint::with('party', 'serviceType', 'salesEntry', 'status', 'product', 'complaintType', 'engineer', 'feedback')->where('status_id', 3);
        if(!empty(auth()->user()->roles)) {
            if (auth()->user()->hasRole('Engineer')) {
                $complaint->where('engineer_id', auth()->user()->id);
                if (isset($request->party_id)) {
                    $complaint->where('party_id', $request->party_id);
                }
            } else {
                if (isset($request->engineer_id)) {
                    $complaint->where('engineer_id', $request->engineer_id);
                }
                if (isset($request->party_id)) {
                    $complaint->where('party_id', $request->party_id);
                }
            }
        } else {
            $complaint->where('party_id', auth()->user()->id);
        }

        if (isset($request->date)) {
            $complaint->where('engineer_out_date', $request->date);
        }
        $paginatedComplaints = $complaint->paginate(10);

        $data = [];
            foreach ($paginatedComplaints->items() as $item) {
                if (!empty($item->image)) {
                    $item['image_url'] = url('/complaint/images/' . $item->image);
                } else {
                    $item['image_url'] = '';
                }

                if (!empty($item->video)) {
                    $item['video_url'] = url('/complaint/videos/' . $item->video);
                } else {
                    $item['video_url'] = '';
                }

                if (!empty($item->audio)) {
                    $item['audio_url'] = url('/complaint/audios/' . $item->audio);
                } else {
                    $item['audio_url'] = '';
                }

                if (!empty($item->engineer_image)) {
                    $item['engineer_image_url'] = url('/complaint/images/' . $item->engineer_image);
                } else {
                    $item['engineer_image_url'] = '';
                }

                if (!empty($item->engineer_video)) {
                    $item['engineer_video_url'] = url('/complaint/videos/' . $item->engineer_video);
                } else {
                    $item['engineer_video_url'] = '';
                }

                if (!empty($item->engineer_audio)) {
                    $item['engineer_audio_url'] = url('/complaint/audios/' . $item->engineer_audio);
                } else {
                    $item['engineer_audio_url'] = '';
                }

                array_push($data, $item);
            }

            return response()->json([
                "success" => true,
                "message" => "Complaints fetched successfully",
                'data' => [
                    'total' => $paginatedComplaints->total(),
                    'per_page' => $paginatedComplaints->perPage(),
                    'current_page' => $paginatedComplaints->currentPage(),
                    'last_page' => $paginatedComplaints->lastPage(),
                    'from' => $paginatedComplaints->firstItem(),
                    'to' => $paginatedComplaints->lastItem(),
                    'data' => $data
                ]
            ], 200);
    }

    // Notify TL when location is being off by Engineers
    public function notifyLocationOff(Request $request)
    {
        if(isset($request->id) && !empty($request->id)){
            $luid = $request->id;
        } else {
            $luid = auth()->user()->id;
        }
        
        $users = User::where(['id' => $luid, 'is_active' => 1])->first();
        $admin = User::find(178);
        $users['admin_data'] = $admin;
                // Do not remove admin id 1 from DB
                if (!empty($admin->device_token)) {
                    $title = 'Location is turned off by '.$users->name;
                    $body = $users->name . ' has turned off the device location';
                    $token = $admin->device_token;


                    if ($title != "" && $body != "" && $token != "") {
                        // Get the response from the helper function
                        // sendNotification($title, $body, $token);
                        $nsent = $this->notificationService->sendNotification($title, $body, $token);
                    }
                }
        
        // Return response
        if(!empty($admin->device_token)){
            return response()->json([
                'success' => true,
                'message' => 'Admin notified successfully',
                'data' => $users
            ], 200);    
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Admin Not notified!',
                'data' => []
            ], 404);
        }

    }
}
