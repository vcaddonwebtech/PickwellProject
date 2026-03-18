<?php
namespace App\Http\Controllers;

//use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesPerson;
use App\Models\SalesPersonsTask;
use App\Models\User;
use App\Models\Machine;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;
use Yajra\DataTables\Facades\DataTables;

class SalesPersonController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function salesleadcreate($id, Request $request)
    {
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $main_machine_name = $main_machine_data->machine_name;                    
        $data['main_machine_name'] = $main_machine_name;
        $data['title'] = $main_machine_name. '-> Create Sales Lead';

        //$data['complaint_no'] = $complaint_no;

        $data['salespersons'] = User::with('roles')
            ->role('sales person')
            ->leftJoin('sales_persons', function ($join) {
                $join->on('users.id', '=', 'sales_persons.sale_assign_user_id')
                    ->where(function ($query) {
                        $query->where('sales_persons.status_id', '!=', 7)
                            ->orWhereNull('sales_persons.status_id'); // Handle cases with no complaints
                    });
            })
            ->select('users.id', 'users.name', DB::raw('COUNT(sales_persons.id) as pending_sales'))
            ->groupBy('users.id', 'users.name')
            ->orderBy('users.name')
            ->get();

            //dd($data['salespersons']);
        
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

        return view('leads.salesleadcreate', $data);
    }

    public function store(Request $request)
    {
        //dd($request);
                
        $data = $request->all();
        $data['tag'] = 1;
        $data['favorite'] = 0;
        $data['area_id'] = 6;
        $data['lead_stage_id'] = 4;
        
        //$data['sale_assign_user_id'] = !empty($request->sale_assign_user_id) ? $request['sale_assign_user_id'] : null;

        $salesPerson = SalesPerson::create($data);

        // Get the last inserted ID
        $lastInsertId = $salesPerson->id;

        if (!empty($salesPerson)) {

            // Sales assign user push notification
            if (!empty($salesPerson) && !empty($request->sale_assign_user_id)) {
                $salesPerson = SalesPerson::with('salseUserDetail','saleAssignUserDetail')->where('id', $salesPerson->id)->first();
                if (!empty($salesPerson->saleAssignUserDetail->device_token)) {
                    $title = 'Lead assign successfully';
                    $body = $salesPerson->salseUserDetail->name . ' assign to lead no ' . $salesPerson->id;
                    $token = $salesPerson->saleAssignUserDetail->device_token;

                    if ($title != "" && $body != "" && $token != "") {
                        // Get the response from the helper function
                        $this->notificationService->sendNotification($title, $body, $token);
                    }
                }
            }

            return redirect()->route('machinesalesdata', ['main_machine_type' => $request->main_machine_type])->with('success', 'Lead created successfully');
        } else {
            return redirect()->route('machinesalesdata', ['main_machine_type' => $request->main_machine_type])->with('danger', 'Lead Not created successfully');
        } 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $salesPerson = SalesPerson::find($id);
        $salesPerson->area_id = $request->area_id;
        $salesPerson->product_id = $request->product_id;
        $salesPerson->lead_stage_id = $request->lead_stage_id;
        $salesPerson->sale_user_id = $request->sale_user_id;
        $salesPerson->date = !empty($request->date) ? date('y-m-d', strtotime($request->date)) : null;
        $salesPerson->time = $request->time;
        $salesPerson->mobile_no = $request->mobile_no;
        $salesPerson->partyname = $request->partyname;
        $salesPerson->address = isset($request->address) ? $request->address : $salesPerson->address;
        $salesPerson->location_Address = isset($request->location_Address) ? $request->location_Address : $salesPerson->location_Address;
        $salesPerson->out_address = isset($request->out_address) ? $request->out_address : $salesPerson->out_address;
        $salesPerson->in_address = isset($request->in_address) ? $request->in_address : $salesPerson->in_address;
        $salesPerson->out_date_time = isset($request->out_date_time) ? $request->out_date_time : $salesPerson->out_date_time;
        $salesPerson->in_date_time = isset($request->in_date_time) ? $request->in_date_time : $salesPerson->in_date_time;
        $salesPerson->latitude = isset($request->latitude) ? $request->latitude : $salesPerson->latitude;
        $salesPerson->logitude = isset($request->logitude) ? $request->logitude : $salesPerson->logitude;
        $salesPerson->remarks = isset($request->remarks) ? $request->remarks : $salesPerson->remarks;

        if(isset($request->next_reminder_date) && isset($request->next_reminder_time)) {
            if($salesPerson->next_reminder_date != $request->next_reminder_date && $salesPerson->next_reminder_time != $request->next_reminder_time) {
                $salesPerson->out_address = null;
                $salesPerson->in_address = null;
                $salesPerson->out_date_time = null;
                $salesPerson->in_date_time = null;
                $salesPerson->time_duration = null;
            }

            if($salesPerson->next_reminder_date == $request->next_reminder_date && $salesPerson->next_reminder_time != $request->next_reminder_time) {
                $salesPerson->out_address = null;
                $salesPerson->in_address = null;
                $salesPerson->out_date_time = null;
                $salesPerson->in_date_time = null;
                $salesPerson->time_duration = null;
            }
        }

        if(isset($request->sale_assign_user_id) && $salesPerson->sale_assign_user_id != $request->sale_assign_user_id) {
            $salesPerson->out_address = null;
            $salesPerson->in_address = null;
            $salesPerson->out_date_time = null;
            $salesPerson->in_date_time = null;
            $salesPerson->time_duration = null;
        }

        $salesPerson->sale_assign_user_id = $request->sale_assign_user_id;
        $salesPerson->next_reminder_date = isset($request->next_reminder_date) ? $request->next_reminder_date : $salesPerson->next_reminder_date;
        $salesPerson->next_reminder_time = isset($request->next_reminder_time) ? $request->next_reminder_time : $salesPerson->next_reminder_time;
        $salesPerson->status_id = isset($request->status_id) ? $request->status_id : $salesPerson->status_id;
        $salesPerson->closed_by = isset($request->closed_by) ? $request->closed_by : $salesPerson->closed_by;
        $salesPerson->closed_date = !empty($request->closed_date) ? date('y-m-d', strtotime($request->closed_date)) : null;
        $salesPerson->save();

        // Remove todo tasks
        SalesPersonsTask::where('todo_id', $salesPerson->id)->delete();

        // Add new todo task
        if ($request->has('todo_task')) {
            foreach ($request->todo_task as $taskKey => $task) {
                if ($task != "") {
                    $taskArr[$taskKey] = [
                        'todo_id' => $salesPerson->id,
                        'date' => !empty($task['date']) ? date('y-m-d', strtotime($task['date'])) : null,
                        'time' => $task['time'],
                        'comment_first' => $task['comment_first'],
                        'comment_second' => $task['comment_second'],
                        'priority_id' => $task['priority_id'],
                        'assign_user_id' => null,
                    ];
                }
            }
        }

        // Insert todo task data
        if (isset($taskArr) && !empty($taskArr)) {
            SalesPersonsTask::insert($taskArr);
        }

        return response()->json([
            "success" => true,
            "message" => "Sale Person update successfully",
            "data" => $salesPerson,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $salesPerson = SalesPerson::find($id);

        if (!$salesPerson) {
            return response()->json([
                'success' => false,
                'message' => 'No record found',
                'data' => []
            ], 404);
        }

        // Delete
        $salesPerson->delete();

        return response()->json([
            "success" => true,
            "message" => "Sale Person deleted successfully",
        ], 200);
    }


    public function updateSalesPerson(Request $request) {
        if(isset($request->id)) {
            $salesPerson = SalesPerson::with('salseUserDetail','saleAssignUserDetail')->where('id', $request->id)->first();
            if(!empty($salesPerson)) {
                $salesPerson->out_address = isset($request->out_address) ? $request->out_address : $salesPerson->out_address;
                $salesPerson->in_address = isset($request->in_address) ? $request->in_address : $salesPerson->in_address;
                $salesPerson->out_date_time = isset($request->out_date_time) ? $request->out_date_time : $salesPerson->out_date_time;
                $salesPerson->in_date_time = isset($request->in_date_time) ? $request->in_date_time : $salesPerson->in_date_time;
                $salesPerson->time_duration = isset($request->time_duration) ? $request->time_duration : $salesPerson->time_duration;
                $salesPerson->status_id = isset($request->status_id) ? $request->status_id : $salesPerson->status_id;
                $salesPerson->save();

                if(isset($request->out_address) && isset($request->out_date_time)) {
                    // Remove todo tasks
                    SalesPersonsTask::where('todo_id', $salesPerson->id)->delete();
    
                    // Add new todo task
                    if ($request->has('todo_task')) {
                        foreach ($request->todo_task as $taskKey => $task) {
                            if ($task != "") {
                                $taskArr[$taskKey] = [
                                    'todo_id' => $salesPerson->id,
                                    'date' => !empty($task['date']) ? date('y-m-d', strtotime($task['date'])) : null,
                                    'time' => $task['time'],
                                    'comment_first' => $task['comment_first'],
                                    'comment_second' => $task['comment_second'],
                                    'priority_id' => $task['priority_id'],
                                    'assign_user_id' => null,
                                ];
                            }
                        }
                    }
    
                    // Insert todo task data
                    if (isset($taskArr) && !empty($taskArr)) {
                        SalesPersonsTask::insert($taskArr);
                    }
                }

                // Sales user push notification
                if (!empty($salesPerson) && $salesPerson->sale_user_id != "") {
                    if (!empty($salesPerson->salseUserDetail->device_token)) {
                        // Send push notification
                        if(isset($request->in_address) && isset($request->in_date_time)) {
                            $title = 'Sales in successfully';
                            $body = $salesPerson->saleAssignUserDetail->name . ' check in sales lead no ' . $salesPerson->id;
                        } else {
                            $title = 'Sales out successfully';
                            $body = $salesPerson->saleAssignUserDetail->name . ' check out sales lead no ' . $salesPerson->id;
                        }
                        $token = $salesPerson->salseUserDetail->device_token;

                        if ($title != "" && $body != "" && $token != "") {
                            // Get the response from the helper function
                            $this->notificationService->sendNotification($title, $body, $token);
                        }
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Sales person updated successfully',
                    'data' => $salesPerson,
                ], 200);
            } else {    
                return response()->json([
                    'success' => false,
                    'message' => 'Sales person data not found',
                    'data' => []
                ], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sales person id not found',
                'data' => []
            ], 404);
        }
    }

    public function workUpdateList(Request $request)
    {
        //$salesPerson = SalesPerson::find($id);
        $data['title'] = 'Salse Work Report';
        
        if ($request->ajax()) {
            if (isset($request->date_range)) {
                [$startDate, $endDate] = explode(' to ', $request->date_range);
                try {
                    $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                    $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    dd('Invalid date range format', $request->date_range);
                }
            } else {
                $startDate = $endDate = null;
            }
            
            $salsePersone = SalesPerson::with([
                'salseUserDetail', 
                'saleAssignUserDetail', 
                'prioritys', 
                'latestSalesPersonTask'
            ]);

            if ($startDate && $endDate) {
                $salsePersone->whereBetween('next_reminder_date', [$startDate, $endDate]);
            }
            
            if (isset($request->next_reminder_date)) {
                $salsePersone->where('next_reminder_date', date('Y-m-d', strtotime($request->next_reminder_date)));
            }
            if (isset($request->status_id)) {
                $salsePersone->where('status_id', $request->status_id);
            }
            if (isset($request->sale_user_id)) {
                $salsePersone->where('sale_user_id', $request->sale_user_id);
            }
            if (isset($request->sale_assign_user_id)) {
                $salsePersone->where('sale_assign_user_id', $request->sale_assign_user_id);
            }
            $salsePersone = $salsePersone->latest()->get();

            if ($salsePersone->isEmpty() && $startDate && $endDate) {
                $salsePersone = SalesPerson::with([
                    'salseUserDetail', 
                    'saleAssignUserDetail', 
                    'prioritys', 
                    'latestSalesPersonTask'
                ]);
    
                if ($startDate && $endDate) {
                    $salsePersone->whereBetween('date', [$startDate, $endDate]);
                }
                
                if (isset($request->next_reminder_date)) {
                    $salsePersone->where('next_reminder_date', date('Y-m-d', strtotime($request->next_reminder_date)));
                }
                if (isset($request->status_id)) {
                    $salsePersone->where('status_id', $request->status_id);
                }
                if (isset($request->sale_user_id)) {
                    $salsePersone->where('sale_user_id', $request->sale_user_id);
                }
                if (isset($request->sale_assign_user_id)) {
                    $salsePersone->where('sale_assign_user_id', $request->sale_assign_user_id);
                }
                $salsePersone = $salsePersone->latest()->get();
            }
            return DataTables::of($salsePersone)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = "<div class='btn-group m-1'><a class='btn btn-sm btn-primary'href='" . route('edit-work', ['lead' => $row->id]) . "'><i class='fa fa-edit'></i></a>";
                    $btn .= "<a class='btn btn-sm btn-danger' href='javascript:void(0)' onclick=''><i class='fa fa-trash'></i></a></div>";
                    return $btn;
                })
                ->addColumn("date", function ($row) {
                    return date('d-m-Y', strtotime($row->date)) . ' ' . $row->time;
                })
                ->addColumn("next_reminder_date", function ($row) {
                    return $row->next_reminder_date
                        ? date('d-m-Y', strtotime($row->next_reminder_date)) . ' ' . ($row->next_reminder_time ?? '')
                        : '';
                })
                ->addColumn("sale_user", function ($row) {
                    return $row->salseUserDetail ? $row->salseUserDetail->name : '';
                })
                ->addColumn("sale_assign_user", function ($row) {
                    return $row->saleAssignUserDetail ? $row->saleAssignUserDetail->name : '';
                })
                ->addColumn("comments", function ($row) {
                    return $row->latestSalesPersonTask ? $row->latestSalesPersonTask->comment_first : '';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('report.work_update_report', $data);  
    }

    public function editWork(Request $request, SalesPerson $lead)
    {
        $data['title'] = 'Show / Edit Sales Work';
        //dd($lead);
        // $salsePersone = SalesPerson::with([
        //         'salseUserDetail', 
        //         'saleAssignUserDetail', 
        //         'prioritys', 
        //         'latestSalesPersonTask'
        //     ])->where('id', $lead)->first();
        //     dd($salsePersone);
        $data['lead'] = $lead;   
        $data['salespersons'] = User::with('roles')
            ->role('sales person')
            ->leftJoin('sales_persons', function ($join) {
                $join->on('users.id', '=', 'sales_persons.sale_assign_user_id')
                    ->where(function ($query) {
                        $query->where('sales_persons.status_id', '!=', 7)
                            ->orWhereNull('sales_persons.status_id'); // Handle cases with no complaints
                    });
            })
            ->select('users.id', 'users.name', DB::raw('COUNT(sales_persons.id) as pending_sales'))
            ->groupBy('users.id', 'users.name')
            ->orderBy('users.name')
            ->get();

            //dd($data['salespersons']);
        
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

        return view('leads.salesleadedit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateSwork(Request $request, SalesPerson $lead)
    {
        //dd($lead);
        $salesPerson = SalesPerson::find($lead->id);
        $salesPerson->area_id = $request->area_id;
        $salesPerson->product_id = $request->product_id;
        $salesPerson->lead_stage_id = $request->lead_stage_id;
        $salesPerson->sale_user_id = $request->sale_user_id;
        $salesPerson->date = !empty($request->date) ? date('y-m-d', strtotime($request->date)) : null;
        $salesPerson->time = $request->time;
        $salesPerson->mobile_no = $request->mobile_no;
        $salesPerson->partyname = $request->partyname;
        $salesPerson->address = isset($request->address) ? $request->address : $lead->address;
        //$salesPerson->location_Address = isset($request->location_Address) ? $request->location_Address : $salesPerson->location_Address;
        //$salesPerson->out_address = isset($request->out_address) ? $request->out_address : $salesPerson->out_address;
        //$salesPerson->in_address = isset($request->in_address) ? $request->in_address : $salesPerson->in_address;
        //$salesPerson->out_date_time = isset($request->out_date_time) ? $request->out_date_time : $salesPerson->out_date_time;
        //$salesPerson->in_date_time = isset($request->in_date_time) ? $request->in_date_time : $salesPerson->in_date_time;
        //$salesPerson->latitude = isset($request->latitude) ? $request->latitude : $salesPerson->latitude;
        //$salesPerson->logitude = isset($request->logitude) ? $request->logitude : $salesPerson->logitude;
        $salesPerson->remarks = isset($request->remarks) ? $request->remarks : $lead->remarks;

        if(isset($request->next_reminder_date) && isset($request->next_reminder_time)) {
            if($salesPerson->next_reminder_date != $request->next_reminder_date && $lead->next_reminder_time != $request->next_reminder_time) {
                $salesPerson->out_address = null;
                $salesPerson->in_address = null;
                $salesPerson->out_date_time = null;
                $salesPerson->in_date_time = null;
                $salesPerson->time_duration = null;
            }

            if($salesPerson->next_reminder_date == $request->next_reminder_date && $lead->next_reminder_time != $request->next_reminder_time) {
                $salesPerson->out_address = null;
                $salesPerson->in_address = null;
                $salesPerson->out_date_time = null;
                $salesPerson->in_date_time = null;
                $salesPerson->time_duration = null;
            }
        }

        if(isset($request->sale_assign_user_id) && $lead->sale_assign_user_id != $request->sale_assign_user_id) {
            $salesPerson->out_address = null;
            $salesPerson->in_address = null;
            $salesPerson->out_date_time = null;
            $salesPerson->in_date_time = null;
            $salesPerson->time_duration = null;
        }

        $salesPerson->sale_assign_user_id = $request->sale_assign_user_id;
        $salesPerson->next_reminder_date = isset($request->next_reminder_date) ? $request->next_reminder_date : $lead->next_reminder_date;
        $salesPerson->next_reminder_time = isset($request->next_reminder_time) ? $request->next_reminder_time : $lead->next_reminder_time;
        $salesPerson->status_id = isset($request->status_id) ? $request->status_id : $lead->status_id;
        $salesPerson->closed_by = isset($request->closed_by) ? $request->closed_by : $lead->closed_by;
        $salesPerson->closed_date = !empty($request->closed_date) ? date('y-m-d', strtotime($request->closed_date)) : null;
        $salesPerson->save();

        // Remove todo tasks
        SalesPersonsTask::where('todo_id', $salesPerson->id)->delete();

        // Add new todo task
        if ($request->has('todo_task')) {
            foreach ($request->todo_task as $taskKey => $task) {
                if ($task != "") {
                    $taskArr[$taskKey] = [
                        'todo_id' => $salesPerson->id,
                        'date' => !empty($task['date']) ? date('y-m-d', strtotime($task['date'])) : null,
                        'time' => $task['time'],
                        'comment_first' => $task['comment_first'],
                        'comment_second' => $task['comment_second'],
                        'priority_id' => $task['priority_id'],
                        'assign_user_id' => null,
                    ];
                }
            }
        }

        // Insert todo task data
        if (isset($taskArr) && !empty($taskArr)) {
            SalesPersonsTask::insert($taskArr);
        }

        return redirect()->route('work-update-list')->with('success', 'Work updated successfully');
    }
}
