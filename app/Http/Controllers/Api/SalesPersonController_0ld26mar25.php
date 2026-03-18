<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesPerson;
use App\Models\SalesPersonsTask;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class SalesPersonController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $sale_assign_user_id = null)
    {
        if (isset($request->date)) {
            $salesPerson = SalesPerson::where('sale_user_id', Auth::user()->id)
                ->where(function ($query) use ($request) {
                    $formattedDate = date('Y-m-d', strtotime($request->date));
                    $query->whereDate('next_reminder_date', $formattedDate)
                        ->orWhere(function ($subQuery) use ($formattedDate) {
                            $subQuery->whereNull('next_reminder_date')
                                ->whereDate('date', $formattedDate);
                        });
                })
                ->orWhere(function ($query) use ($request) {
                    $formattedDate = date('Y-m-d', strtotime($request->date));
                    $query->whereDate('date', $formattedDate)
                        ->orWhereDate('next_reminder_date', $formattedDate);
                })
                ->where('sale_assign_user_id', Auth::user()->id)
                ->with('area', 'product', 'statusDetail', 'salseUserDetail', 'sale_assign_user', 'sale_assign_close_by_user', 'salesPersonTask', 'salesPersonTask.prioritys', 'salesPersonTask.assignUserDetail')
                ->orderBy('id', 'DESC')
                ->get();

            // $salesPerson = SalesPerson::where('sale_user_id', Auth::user()->id)
            //     ->where(function ($query) use ($request) {
            //         $query->whereDate('next_reminder_date', date('Y-m-d', strtotime($request->date)))
            //             ->orWhere(function ($subQuery) use ($request) {
            //                 $subQuery->whereNull('next_reminder_date')
            //                     ->whereDate('date', date('Y-m-d', strtotime($request->date)));
            //             });
            //     })
            //     ->where('sale_assign_user_id', Auth::user()->id)
            //     ->with('area', 'product', 'statusDetail', 'salseUserDetail', 'sale_assign_user', 'sale_assign_close_by_user', 'salesPersonTask', 'salesPersonTask.prioritys', 'salesPersonTask.assignUserDetail')
            //     ->orderBy('id', 'DESC')
            //     ->get();

            // $salesPerson = SalesPerson::where('sale_user_id', Auth::user()->id)
            //     ->whereDate('date', date('Y-m-d', strtotime($request->date)))
            //     ->orWhereDate('next_reminder_date', date('Y-m-d', strtotime($request->date)))
            //     ->orWhere('sale_assign_user_id', Auth::user()->id)
            //     ->with('area', 'product', 'statusDetail', 'salseUserDetail', 'sale_assign_user', 'sale_assign_close_by_user', 'salesPersonTask', 'salesPersonTask.prioritys', 'salesPersonTask.assignUserDetail')
            //     ->orderBy('id', 'DESC')
            //     ->get();
        } else {
            $salesPerson = SalesPerson::where('sale_user_id', Auth::user()->id)
                ->orWhere('sale_assign_user_id', Auth::user()->id)
                ->with('area', 'product', 'statusDetail', 'salseUserDetail', 'sale_assign_user', 'sale_assign_close_by_user', 'salesPersonTask', 'salesPersonTask.prioritys', 'salesPersonTask.assignUserDetail')
                ->orderBy('id', 'DESC')
                ->get();
        }

        // $salesPerson = SalesPerson::where('sale_user_id', Auth::user()->id)
        //     ->with('area', 'product', 'statusDetail', 'salseUserDetail', 'sale_assign_user', 'sale_assign_close_by_user', 'salesPersonTask', 'salesPersonTask.prioritys', 'salesPersonTask.assignUserDetail')
        //     ->whereHas('salesPersonTask', function ($query) {
        //         $query->orWhere('assign_user_id', Auth::user()->id);
        //     })
        //     ->orderBy('id', 'DESC')
        //     ->get();

        return response()->json([
            "success" => true,
            "message" => "Sale Person type fetch successfuly",
            "data" => $salesPerson,
        ]);
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
        $data['tag'] = 1;
        $data['firm_id'] = 1;
        $data['year_id'] = 1;
        $data['date'] = !empty($request->date) ? date('y-m-d', strtotime($request['date'])) : null;
        $data['sale_assign_user_id'] = !empty($request->sale_assign_user_id) ? $request['sale_assign_user_id'] : null;

        $salesPerson = SalesPerson::create($data);

        // Get the last inserted ID
        $lastInsertId = $salesPerson->id;

        if ($request->has('todo_task')) {
            foreach ($request->todo_task as $taskKey => $task) {
                if ($task != "") {
                    $taskArr[$taskKey] = [
                        'todo_id' => $lastInsertId,
                        'date' => !empty($task['date']) ? date('y-m-d', strtotime($task['date'])) : null,
                        'time' => $task['time'],
                        'comment_first' => $task['comment_first'],
                        'comment_second' => $task['comment_second'],
                        'priority_id' => $task['priority_id'],
                        'assign_user_id' => null
                    ];
                }
            }
        }

        // Insert todo task data
        if (isset($taskArr) && !empty($taskArr)) {
            SalesPersonsTask::insert($taskArr);
        }

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

            return response()->json([
                "success" => true,
                "message" => "Sale Person created successfully",
                "data" => $salesPerson,
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $salesPerson = SalesPerson::with('area', 'product', 'prioritys', 'salseUserDetail', 'sale_assign_close_by_user', 'sale_assign_user', 'salesPersonTask', 'salesPersonTask.assignUserDetail', 'salesPersonTask.prioritys')->where('id', $id)->first();
        if (!empty($salesPerson)) {
            return response()->json([
                "success" => true,
                "message" => "Sale Person edit successfully",
                "data" => $salesPerson,
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

    // Update favorite records in salse persone task.
    public function SalsePersonFavorite(Request $request)
    {
        $salesPerson = SalesPersonsTask::where('id', $request->sales_persons_tasks_id)->first();

        if ($salesPerson) {
            $salesPerson->favorite = $salesPerson->favorite == 0 ? 1 : 0;
            $salesPerson->save();

            return response()->json([
                'success' => true,
                'message' => 'Favorite status toggled successfully',
                'data' => $salesPerson
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'SalesPerson not found'
            ], 404);
        }
    }

    // Update favorite records in salse persone task.
    public function assignSalsePersonFavorite(Request $request)
    {
        $salesPerson = SalesPersonsTask::where('id', $request->sales_persons_tasks_id)->first();

        if ($salesPerson) {
            $salesPerson->favorite = $salesPerson->favorite == 0 ? 1 : 0;
            $salesPerson->save();

            return response()->json([
                'success' => true,
                'message' => 'Favorite status toggled successfully',
                'data' => $salesPerson
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'SalesPerson not found'
            ], 404);
        }
    }

    /**
     * Get assign user filter details
     */
    public function assignSalsePersonPriorityFilter(Request $request)
    {
        $status_id = $request->status_id;
        $sale_assign_user_id = $request->sale_assign_user_id;
        $date = $request->date;
        $closed_date = $request->closed_date;
        $favorite = $request->favorite;

        // Fetch todos assigned to the user that match any of the conditions
        $salse = SalesPerson::query()
            ->where(function ($query) use ($sale_assign_user_id, $date, $status_id, $closed_date, $favorite) {
                if ($sale_assign_user_id) {
                    $query->where('sale_assign_user_id', $sale_assign_user_id);
                }
                if ($date) {
                    $formattedDate = date('Y-m-d', strtotime($date));
                    $query->where(function ($subQuery) use ($formattedDate) {
                        $subQuery->where(function ($subQuery2) use ($formattedDate) {
                            $subQuery2->whereNotNull('next_reminder_date')
                                ->whereDate('next_reminder_date', $formattedDate);
                        })
                            ->orWhere(function ($subQuery2) use ($formattedDate) {
                                $subQuery2->whereNull('next_reminder_date')
                                    ->whereDate('date', $formattedDate);
                            });
                    });
                }
                if ($closed_date) {
                    $formattedDate = date('Y-m-d', strtotime($closed_date));
                    $query->whereDate('closed_date', $formattedDate);
                }
                if ($status_id) {
                    $query->where('status_id', $status_id);
                }
                if ($favorite) {
                    $query->where('favorite', $favorite);
                }
            })
            ->with(['salesPersonTask.prioritys', 'area', 'prioritys', 'product', 'sale_assign_user', 'sale_assign_close_by_user', 'salseUserDetail'])
            ->get()
            ->toArray();

        // Return the modified todos as the response
        return response()->json([
            'success' => true,
            'message' => 'Tasks fetched successfully',
            'data' => $salse
        ], 200);
    }



    /**
     * Get assign user filter details
     */
    public function getAssignsalsePersonPriorityFilterDetail(string $sale_assign_user_id = null, string $priority_id = null)
    {
        // Only userId
        if ($sale_assign_user_id != null && $priority_id === "null") {
            // Fetch todos assigned to the user
            $todos = SalesPerson::where('sale_assign_user_id', $sale_assign_user_id)
                ->with(['salesPersonTask.prioritys', 'area', 'prioritys', 'product', 'sale_assign_user', 'sale_assign_close_by_user', 'salseUserDetail'])
                ->get()
                ->toArray();

            // Initialize an empty array to store modified todos
            $modifiedTodos = [];

            // Loop through each todo item
            foreach ($todos as $item) {
                // Get the last task's ID dynamically
                $lastTaskId = end($item['sales_person_task'])['id'];

                // Filter sales_person_task to only include the desired task based on the dynamic ID
                $item['sales_person_task'] = array_values(array_filter($item['sales_person_task'], function ($task) use ($lastTaskId, $priority_id) {
                    return $task['id'] === $lastTaskId; // Keep only the task with the desired ID
                }));

                // Append the modified item to the new array
                $modifiedTodos[] = $item;
            }

            // Check if there are any modified todos
            if (empty($modifiedTodos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tasks found for the given user and priority',
                    'data' => []
                ], 404);
            }

            // Return the modified todos as the response
            return response()->json([
                'success' => true,
                'message' => 'Tasks fetched successfully',
                'data' => $modifiedTodos
            ], 200);
        }

        // Only PriorityId
        if ($sale_assign_user_id === "null" && $priority_id != null) {
            $priority_id = request('priority_id'); // Get priority_id from request

            // Fetch todos assigned to the user
            $todos = SalesPerson::where('sale_assign_user_id', Auth::user()->id)
                ->orWhere('sale_user_id', Auth::user()->id)
                ->whereHas('salesPersonTask', function ($query) use ($priority_id) {
                    $query->where('priority_id', $priority_id); // Apply condition on priority_id
                })
                ->with(['salesPersonTask.prioritys', 'area', 'prioritys', 'product', 'sale_assign_user', 'sale_assign_close_by_user'])
                ->get()
                ->toArray();

            // Initialize an empty array to store modified todos
            $modifiedTodos = [];

            // Loop through each todo item
            foreach ($todos as $item) {
                // Get the last task's ID dynamically
                $lastTaskId = end($item['sales_person_task'])['id'];

                // Filter sales_person_task to only include the desired task based on the dynamic ID
                $item['sales_person_task'] = array_values(array_filter($item['sales_person_task'], function ($task) use ($lastTaskId, $priority_id) {
                    if ($task['priority_id'] == $priority_id) {
                        return $task['id'] === $lastTaskId; // Keep only the task with the desired ID
                    }
                }));

                // Append the modified item to the new array
                $modifiedTodos[] = $item;
            }

            // Check if there are any modified todos
            if (empty($modifiedTodos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tasks found for the given user and priority',
                    'data' => []
                ], 404);
            }

            // Return the modified todos as the response
            return response()->json([
                'success' => true,
                'message' => 'Tasks fetched successfully',
                'data' => $modifiedTodos
            ], 200);
        }

        // Assign userId and priority both
        if ($sale_assign_user_id != null && $priority_id != null) {
            // Fetch todos assigned to the user
            $todos = SalesPerson::where('sale_assign_user_id', $sale_assign_user_id)
                ->with(['salesPersonTask.prioritys', 'area', 'prioritys', 'product', 'sale_assign_user', 'sale_assign_close_by_user'])
                ->get()
                ->toArray();

            // Initialize an empty array to store modified todos
            $modifiedTodos = [];

            // Loop through each todo item
            foreach ($todos as $item) {
                // Get the last task's ID dynamically
                $lastTaskId = end($item['sales_person_task'])['id'];

                // Filter sales_person_task to only include the desired task based on the dynamic ID
                $item['sales_person_task'] = array_values(array_filter($item['sales_person_task'], function ($task) use ($lastTaskId, $priority_id) {
                    if ($task['priority_id'] == $priority_id) {
                        return $task['id'] === $lastTaskId; // Keep only the task with the desired ID
                    }
                }));

                // Append the modified item to the new array
                $modifiedTodos[] = $item;
            }

            // Check if there are any modified todos
            if (empty($modifiedTodos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tasks found for the given user and priority',
                    'data' => []
                ], 404);
            }

            // Return the modified todos as the response
            return response()->json([
                'success' => true,
                'message' => 'Tasks fetched successfully',
                'data' => $modifiedTodos
            ], 200);
        }
    }

    public function updateFavorite(Request $request)
    {
        $salesPerson = SalesPerson::whereId($request->id)->first();

        if (!$salesPerson) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found',
                'data' => []
            ], 404);
        }

        $salesPerson->favorite = $request->favorite == 1 ? 1 : 0;
        $salesPerson->save();

        return response()->json([
            'status' => true,
            'message' => 'Sales person favorite updated successfully',
            'data' => $salesPerson,
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
}
