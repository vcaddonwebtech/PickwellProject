<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesPerson;
use App\Models\OnspotInquiries;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class OnspotInquiriesController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function onspotSPInquiries(Request $request)
    {
        
        $OnspotInquiries = OnspotInquiries::where('user_id', Auth::user()->id)
        ->orderBy('id', 'DESC')
        ->get();
        
        if (!empty($OnspotInquiries)) {
            return response()->json([
                "success" => true,
                "message" => "Inquires fetched successfuly",
                "data" => $OnspotInquiries,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "No Inquires found",
                "data" => (object)[],
            ]);
        }   
    }

    /**
     * Display a listing of the resource.
     */
    public function salsePersonFilter(string $id, string $priority)
    {
        $salesPerson = SalesPerson::where([
            'sale_user_id' => Auth::user()->id,
            'sale_assign_user_id' => $id
        ])
            ->with(['area', 'product', 'prioritys', 'salseUserDetail', 'sale_assign_close_by_user', 'sale_assign_user', 'salesPersonTask.prioritys' => function ($query) use ($priority) {
                $query->where('id', $priority);
            }])
            ->orderBy('id', 'DESC')
            ->get();

        // Filter out the sales_person_task entries where prioritys is null
        $salesPerson->each(function ($salesPerson) {
            if ($salesPerson->sales_person_task) {
                $salesPerson->sales_person_task = $salesPerson->sales_person_task->filter(function ($task) {
                    return $task->prioritys !== null;  // Keep tasks where prioritys is not null
                });
            }
        });

        return response()->json([
            "success" => true,
            "message" => "Sale Person type fetch successfully",
            "data" => $salesPerson,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
  

        $OnspotInquiries = OnspotInquiries::create($data);

        

        if (!empty($OnspotInquiries)) {

            // Sales assign user push notification
            // if (!empty($salesPerson) && !empty($request->sale_assign_user_id)) {
            //     $salesPerson = SalesPerson::with('salseUserDetail','saleAssignUserDetail')->where('id', $salesPerson->id)->first();
            //     if (!empty($salesPerson->saleAssignUserDetail->device_token)) {
            //         $title = 'Lead assign successfully';
            //         $body = $salesPerson->salseUserDetail->name . ' assign to lead no ' . $salesPerson->id;
            //         $token = $salesPerson->saleAssignUserDetail->device_token;

            //         if ($title != "" && $body != "" && $token != "") {
            //             // Get the response from the helper function
            //             $this->notificationService->sendNotification($title, $body, $token);
            //         }
            //     }
            // }

            return response()->json([
                "success" => true,
                "message" => "Inquiry created successfully",
                "data" => $OnspotInquiries,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong",
                "data" => (object)[],
            ]);
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

    
}
