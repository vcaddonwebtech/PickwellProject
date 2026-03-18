<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTodoRequest;
use App\Http\Requests\StoreTodoCommentRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\Todo;
use App\Models\TodoAssignUser;
use App\Models\TodoTask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Services\NotificationService;

class TodoController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->is_past_record == '1') {
            //dd(Auth::user()->id);
            $useridn = Auth::user()->id;
            $data = Todo::with([
                'todoUser', 
                'todoTasks', 
                'priority', 
                'todoTasks.todoTaskUser', 
                'todoAssignUsers', 
                'todoAssignUsers.assignUserDetail', 
                'todoTasks.prioritys', 
                'todoTasks.status'
            ])
            //->where('is_group', 0) // removed as per payal suggestion
            ->where('status', 0)
            ->whereDate('assign_date_time', '<', date('Y-m-d'))
            ->where(function ($query) use ($useridn) {
                $query->whereHas('todoAssignUsers', function ($q) use ($useridn) {
                    $q->where('assign_user_id', $useridn);
                })->orWhere('user_id', $useridn);
                
            })
            ->latest()
            ->get()
            ->toArray();

        } else if ($request->is_today_record == '1') {
            $useridn = Auth::user()->id;
            $data = Todo::with([
                'todoUser', 
                'todoTasks', 
                'priority', 
                'todoTasks.todoTaskUser', 
                'todoAssignUsers', 
                'todoAssignUsers.assignUserDetail', 
                'todoTasks.prioritys', 
                'todoTasks.status'
            ])
            //->where('is_group', 0)
            ->where('status', 0)
            ->whereDate('assign_date_time', '>=', date('Y-m-d'))
            ->where(function ($query) use ($useridn) {
                $query->whereHas('todoAssignUsers', function ($q) use ($useridn) {
                    $q->where('assign_user_id', $useridn);
                })->orWhere('user_id', $useridn);
                
            })
            ->latest()
            ->get()
            ->toArray();
        } else if ($request->is_upcoming_record == '1') {
            // $data['todo'] = Todo::where('user_id', Auth::user()->id)
            //     ->where('is_group', 0)
            //     ->where('status', 0)
            //     ->whereDate('assign_date_time', '>', date('Y-m-d'))
            //     ->with([
            //         'todoUser', 
            //         'todoTasksnew', 
            //         'priority', 
            //         'todoTasks.todoTaskUser', 
            //         'todoAssignUsers', 
            //         'todoAssignUsers.assignUserDetail', 
            //         'todoTasks.prioritys', 
            //         'todoTasks.status'
            //     ])
            //     ->latest()->get()->map(function ($item) {
            //         $item['todoTasks'] = $item['todoTasks'] ? collect($item['todoTasks'])->map(function ($task) {
            //             $task['pdf_url'] = !empty($task['pdf']) ? url('/todo/pdf/' . $task['pdf']) : '';
            //             $task['image_url'] = !empty($task['image']) ? url('/todo/images/' . $task['image']) : '';
            //             $task['video_url'] = !empty($task['video']) ? url('/todo/videos/' . $task['video']) : '';
            //             $task['audio_url'] = !empty($task['audio']) ? url('/todo/audios/' . $task['audio']) : '';
            //             return $task;
            //         }) : [];
            //         return $item;
            //     })->toArray();
            // $data['todoAssignUser'] = TodoTask::with('todoDetail.todoUser', 
            // 'todoDetail', 
            // 'todoDetail.todoTasks', 
            // 'todoDetail.priority', 
            // 'todoDetail.todoTasks.todoTaskUser', 
            // 'todoDetail.todoTasks.prioritys', 
            // 'todoDetail.todoTasks.status', 
            // 'todoDetail.todoAssignUsers.assignUserDetail')
            //     ->where('user_id', Auth::user()->id)
            //     ->whereDate('date', '>=', date('Y-m-d'))
            //     ->orderBy('id', 'DESC')
            //     ->get()
            //     ->unique('todo_id')  // Ensure only unique todo_id records are kept
            //     ->values()           // Reset array indices after filtering
            //     ->map(function ($item) {
            //         // Check if todoDetail and todoTasks exist and append URLs
            //         if (isset($item['todoDetail']) && isset($item['todoDetail']['todoTasks'])) {
            //             $item['todoDetail']['todoTasks'] = collect($item['todoDetail']['todoTasks'])->map(function ($task) {
            //                 $task['pdf_url'] = !empty($task['pdf']) ? url('/todo/pdf/' . $task['pdf']) : '';
            //                 $task['image_url'] = !empty($task['image']) ? url('/todo/images/' . $task['image']) : '';
            //                 $task['video_url'] = !empty($task['video']) ? url('/todo/videos/' . $task['video']) : '';
            //                 $task['audio_url'] = !empty($task['audio']) ? url('/todo/audios/' . $task['audio']) : '';
            //                 return $task;
            //             });
            //         }
            //         return $item;
            //     })
            //     ->toArray();
        } else {
            // $data = Todo::join('todo_assign_users as TAS', 'todos.id', '=', 'TAS.todo_id')
            // ->where(function ($query) {
            //     $query->where('todos.user_id', 1)
            //         ->orWhere('TAS.assign_user_id', 1);
            // })
            // ->where('todos.status', 0)
            // ->where('todos.is_group', 0)
            // ->with([
            //     'todoUser', 
            //     'todoTasks', 
            //     'priority', 
            //     'todoTasks.todoTaskUser', 
            //     'todoAssignUsers', 
            //     'todoAssignUsers.assignUserDetail', 
            //     'todoTasks.prioritys',
            //     'todoTasks.status'
            // ])
            // ->orderByDesc('todos.id')
            // ->get();
            // $data['todo'] = Todo::where('user_id', Auth::user()->id)
            //     ->where('is_group', 0)
            //     ->where('status', 0)
            //     ->with([
            //         'todoUser', 
            //         'todoTasks',
            //         'priority', 
            //         'todoTasks.todoTaskUser', 
            //         'todoAssignUsers', 
            //         'todoAssignUsers.assignUserDetail', 
            //         'todoTasks.prioritys', 
            //         'todoTasks.status'
            //     ])
            //     ->latest()->get()->map(function ($item) {
            //         $item['todoTasks'] = $item['todoTasks'] ? collect($item['todoTasks'])->map(function ($task) {
            //             $task['pdf_url'] = !empty($task['pdf']) ? url('/todo/pdf/' . $task['pdf']) : '';
            //             $task['image_url'] = !empty($task['image']) ? url('/todo/images/' . $task['image']) : '';
            //             $task['video_url'] = !empty($task['video']) ? url('/todo/videos/' . $task['video']) : '';
            //             $task['audio_url'] = !empty($task['audio']) ? url('/todo/audios/' . $task['audio']) : '';
            //             return $task;
            //         }) : [];
            //         return $item;
            //     })->toArray();
            // $data['todoAssignUser'] = TodoTask::with('todoDetail.todoUser', 'todoDetail', 'todoDetail.todoTasks', 'todoDetail.priority', 'todoDetail.todoTasks.todoTaskUser', 'todoDetail.todoTasks.prioritys', 'todoDetail.todoTasks.status', 'todoDetail.todoAssignUsers.assignUserDetail')
            //     ->where('user_id', Auth::user()->id)
            //     ->orderBy('id', 'DESC')
            //     ->get()
            //     ->unique('todo_id')  // Ensure only unique todo_id records are kept
            //     ->values()           // Reset array indices after filtering
            //     ->map(function ($item) {
            //         // Check if todoDetail and todoTasks exist and append URLs
            //         if (isset($item['todoDetail']) && isset($item['todoDetail']['todoTasks'])) {
            //             $item['todoDetail']['todoTasks'] = collect($item['todoDetail']['todoTasks'])->map(function ($task) {
            //                 $task['pdf_url'] = !empty($task['pdf']) ? url('/todo/pdf/' . $task['pdf']) : '';
            //                 $task['image_url'] = !empty($task['image']) ? url('/todo/images/' . $task['image']) : '';
            //                 $task['video_url'] = !empty($task['video']) ? url('/todo/videos/' . $task['video']) : '';
            //                 $task['audio_url'] = !empty($task['audio']) ? url('/todo/audios/' . $task['audio']) : '';
            //                 return $task;
            //             });
            //         }
            //         return $item;
            //     })
            //     ->toArray();
        }

        $todos = $data;
        if (!empty($data)) {
            $processedTodos = [];
            foreach ($todos as $item) {
                $item['pdf_url'] = !empty($item['pdf']) ? url('/todo/pdf/' . $item['pdf']) : '';
                $item['image_url'] = !empty($item['image']) ? url('/todo/images/' . $item['image']) : '';
                $item['video_url'] = !empty($item['video']) ? url('/todo/videos/' . $item['video']) : '';
                $item['audio_url'] = !empty($item['audio']) ? url('/todo/audios/' . $item['audio']) : '';

                $processedTodos[] = $item;
            }

            $data = $processedTodos;
        }

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'No priorities found',
                'data' => []
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Todo fetched successfully',
            'data' => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $data = $request->all();

        $folderName = 'todo'; // Replace with your folder name
        $path = public_path($folderName);

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true, true); // Create directory with permissions
        }

        if (isset($data['pdf'])) {
            $folderName = 'pdf'; // Replace with your folder name
            $path = public_path('todo/'.$folderName);

            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true); // Create directory with permissions
            }

            $file = $data['pdf'];
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('todo/pdf'), $filename);
            $data['pdf'] = $filename;
        }

        if (isset($data['image'])) {
            $folderName = 'images'; // Replace with your folder name
            $path = public_path('todo/'.$folderName);

            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true); // Create directory with permissions
            }

            $file = $data['image'];
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('todo/images'), $filename);
            $data['image'] = $filename;
        }

        if (isset($data['video'])) {
            $folderName = 'videos'; // Replace with your folder name
            $path = public_path('todo/'.$folderName);

            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true); // Create directory with permissions
            }

            $file = $data['video'];
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('todo/videos'), $filename);
            $data['video'] = $filename;
        }
        
        if (isset($data['audio'])) {
            $folderName = 'audios'; // Replace with your folder name
            $path = public_path('todo/'.$folderName);

            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true); // Create directory with permissions
            }

            $file = $data['audio'];
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('todo/audios'), $filename);
            $data['audio'] = $filename;
        }
        
        // If validation passes, create the todo
        $todo = Todo::create($data);

        // Get the last inserted ID
        $lastInsertId = $todo->id;

        if ($request->has('todo_task')) {
            foreach ($request->todo_task as $taskKey => $task) {
                if ($task != "") {
                    $date = date('Y-m-d');
                    $time = '00:00:00';
                    $remind_type = null;
                    $week_day = null;
                    if(isset($task['date']) && !empty($task['date'])) {
                        $date = date('Y-m-d', strtotime($task['date']));
                    }
                    if(isset($task['time']) && !empty($task['time'])) {
                        $time = $task['time'];
                    }
                    if(isset($task['remind_type']) && !empty($task['remind_type'])) {
                        $remind_type = $task['remind_type'];
                    }
                    if(isset($task['week_day']) && !empty($task['week_day'])) {
                        $week_day = $task['week_day'];
                    }
                    $taskArr[$taskKey] = [
                        'todo_id' => $lastInsertId,
                        'date' => $date,
                        'time' => $time,
                        'comment_first' => $task['comment_first'],
                        'comment_second' => $task['comment_second'],
                        'priority_id' => $task['priority_id'],
                        'user_id' => $task['user_id'],
                        'status_id' => $task['status_id'],
                        'remind_type' => $remind_type,
                        'week_day' => $week_day
                    ];
                }
                
            }
        }

        // Insert todo task data
        if (isset($taskArr) && !empty($taskArr)) {
            TodoTask::insert($taskArr);
        }

        // Add assign multi user data
        if ($request->has('assign_users')) {
            foreach ($request->assign_users as $userKey => $user) {
                if ($user != "") {
                    $userArr[$userKey] = [
                        'todo_id' => $lastInsertId,
                        'assign_user_id' => $user['assign_user_id']
                    ];
                    if ($user != "" && isset(Auth::user()->id) && Auth::user()->id!='' && $user['assign_user_id']!=Auth::user()->id) {
                        $user = User::find($user['assign_user_id']);
                        if (!empty($user->device_token)) {
                            // Send push notification
                            $id = $lastInsertId;
                            $type = 'Todo';
                            $title = 'New Todo';
                            $body = 'You have a new task. '. $data['title'];
                            $token = $user->device_token;

                            if ($title != "" && $body != "" && $token != "") {
                                // Get the response from the helper function
                                // sendNotification($title, $body, $token);
                                $this->notificationService->sendTodoNotification($title, $body, $token, $type, $id);
                            }
                        }
                    }
                }
            }
        }

        // Insert todo assign user id data
        if (isset($userArr) && !empty($userArr)) {
            TodoAssignUser::insert($userArr);
        }

        $newTodo = Todo::whereId($lastInsertId)
        ->with([
            'todoUser',
            'priority',
            'todoTasks.prioritys',
            'todoTasks.todoTaskUser',
            'todoAssignUsers',
            'todoAssignUsers.assignUserDetail',
            'todoTasks'
        ])->first();

        if (!empty($newTodo)) {
            $newTodo['pdf_url'] = !empty($newTodo['pdf']) ? url('/todo/pdf/' . $newTodo['pdf']) : '';
            $newTodo['image_url'] = !empty($newTodo['image']) ? url('/todo/images/' . $newTodo['image']) : '';
            $newTodo['video_url'] = !empty($newTodo['video']) ? url('/todo/videos/' . $newTodo['video']) : '';
            $newTodo['audio_url'] = !empty($newTodo['audio']) ? url('/todo/audios/' . $newTodo['audio']) : '';
        }
        return response()->json([
            'status' => true,
            'message' => 'Todo created successfully',
            'data' => $newTodo,
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $todo = Todo::whereId($id)->with('todoTasks')->first();
        $todo = Todo::whereId($id)
        ->with([
            'todoUser',
            'priority',
            'todoTasks.prioritys',
            'todoTasks.status',
            'todoTasks.todoTaskUser',
            'todoAssignUsers',
            'todoAssignUsers.assignUserDetail',
            'todoTasks'
        ])->first();

        // if ($todo && $todo->todoTasks) {
        //     $todo->todoTasks = $todo->todoTasks->map(function ($task) {
        //         $task->pdf_url = !empty($task->pdf) ? url('/todo_complete/pdf/' . $task->pdf) : '';
        //         $task->image_url = !empty($task->image) ? url('/todo_complete/images/' . $task->image) : '';
        //         $task->video_url = !empty($task->video) ? url('/todo_complete/videos/' . $task->video) : '';
        //         $task->audio_url = !empty($task->audio) ? url('/todo_complete/audios/' . $task->audio) : '';
        //         return $task;
        //     });
        // }

        if (!empty($todo)) {
            $todo['pdf_url'] = !empty($todo['pdf']) ? url('/todo/pdf/' . $todo['pdf']) : '';
            $todo['image_url'] = !empty($todo['image']) ? url('/todo/images/' . $todo['image']) : '';
            $todo['video_url'] = !empty($todo['video']) ? url('/todo/videos/' . $todo['video']) : '';
            $todo['audio_url'] = !empty($todo['audio']) ? url('/todo/audios/' . $todo['audio']) : '';
        }

        if (!$todo) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Todo fetched successfully',
            'data' => $todo,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $todo = Todo::findOrFail($id);

        if (!$todo) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found',
                'data' => []
            ], 404);
        }

        $data = $request->all();

        // Handle file updates and deletions
        if ($request->hasFile('pdf')) {
            // Delete old file if it exists
            if ($todo->pdf && file_exists(public_path('todo/pdf/'.$todo->pdf))) {
                unlink(public_path('todo/pdf/'.$todo->pdf));
            }

            // Save new PDF file
            $file = $request->file('pdf');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('todo/pdf'), $filename);
            $data['pdf'] = $filename; // Add the new file name to the data array
        }

        if ($request->hasFile('image')) {
            // Delete old file if it exists
            if ($todo->image && file_exists(public_path('todo/images/'.$todo->image))) {
                unlink(public_path('todo/images/'.$todo->image));
            }

            // Save new image file
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('todo/images'), $filename);
            $data['image'] = $filename; // Add the new file name to the data array
        }

        if ($request->hasFile('video')) {
            // Delete old file if it exists
            if ($todo->video && file_exists(public_path('todo/videos/'.$todo->video))) {
                unlink(public_path('todo/videos/'.$todo->video));
            }

            // Save new video file
            $file = $request->file('video');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('todo/videos'), $filename);
            $data['video'] = $filename; // Add the new file name to the data array
        }

        if ($request->hasFile('audio')) {
            // Delete old file if it exists
            if ($todo->audio && file_exists(public_path('todo/audios/'.$todo->audio))) {
                unlink(public_path('todo/audios/'.$todo->audio));
            }

            // Save new audio file
            $file = $request->file('audio');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('todo/audios'), $filename);
            $data['audio'] = $filename; // Add the new file name to the data array
        }

        // Perform the update with the prepared data
        $todo->update($data);

        // Remove todo tasks
        TodoTask::where('todo_id', $todo->id)->delete();
        TodoAssignUser::where('todo_id', $todo->id)->delete();

        // Add new todo task
        if ($request->has('todo_task')) {
            foreach ($request->todo_task as $taskKey => $task) {
                if ($task != "") {
                    $date = date('Y-m-d');
                    $time = '00:00:00';
                    $remind_type = null;
                    $week_day = null;
                    if(isset($task['date']) && !empty($task['date'])) {
                        $date = date('Y-m-d', strtotime($task['date']));
                    }
                    if(isset($task['time']) && !empty($task['time'])) {
                        $time = $task['time'];
                    }
                    if(isset($task['remind_type']) && !empty($task['remind_type'])) {
                        $remind_type = $task['remind_type'];
                    }
                    if(isset($task['week_day']) && !empty($task['week_day'])) {
                        $week_day = $task['week_day'];
                    }
                    $taskArr[$taskKey] = [
                        'todo_id' => $todo->id,
                        'date' => $date,
                        'time' => $time,
                        'comment_first' => $task['comment_first'],
                        'comment_second' => $task['comment_second'],
                        'priority_id' => $task['priority_id'],
                        'user_id' => $task['user_id'],
                        'status_id' => $task['status_id'],
                        'remind_type' => $remind_type,
                        'week_day' => $week_day
                    ];
                }
            }
        }

        // Insert todo task data
        if (isset($taskArr) && !empty($taskArr)) {
            TodoTask::insert($taskArr);
        }

        // Add assign multi user data
        if ($request->has('assign_users')) {
            foreach ($request->assign_users as $userKey => $user) {
                if ($user != "") {
                    $userArr[$userKey] = [
                        'todo_id' => $todo->id,
                        'assign_user_id' => $user['assign_user_id']
                    ];
                }
            }
        }

        // Insert todo assign user id data
        if (isset($userArr) && !empty($userArr)) {
            TodoAssignUser::insert($userArr);
        }

        $newTodo = Todo::whereId($todo->id)
        ->with([
            'todoUser',
            'priority',
            'todoTasks.prioritys',
            'todoTasks.todoTaskUser',
            'todoAssignUsers',
            'todoAssignUsers.assignUserDetail',
            'todoTasks'
        ])->first();

        if (!empty($newTodo)) {
            $newTodo['pdf_url'] = !empty($newTodo['pdf']) ? url('/todo/pdf/' . $newTodo['pdf']) : '';
            $newTodo['image_url'] = !empty($newTodo['image']) ? url('/todo/images/' . $newTodo['image']) : '';
            $newTodo['video_url'] = !empty($newTodo['video']) ? url('/todo/videos/' . $newTodo['video']) : '';
            $newTodo['audio_url'] = !empty($newTodo['audio']) ? url('/todo/audios/' . $newTodo['audio']) : '';
        }

        return response()->json([
            'status' => true,
            'message' => 'Todo updated successfully',
            'data' => $newTodo,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = Todo::whereId($id)->first();

        if (!$todo) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found',
                'data' => []
            ], 404);
        }

        $todo->delete();

        $todo = TodoTask::where('todo_id', $id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Todo deleted successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function getAdminTodos(Request $request, string $userId = null)
    {
        if ($request->is_past_record == '1') {
            if (isset($userId) && $userId != "") {
                $todo = Todo::with(['todoTasks.prioritys', 'todoTasks.status', 'todoTasks.todoTaskUser', 'priority', 'todoAssignUsers', 'todoAssignUsers.assignUserDetail', 'todoTasks' => function ($query) {
                    $query->orderBy('id', 'DESC'); // Order the related todoTasks by id
                }])
                    ->whereDate('assign_date_time', '<', date('Y-m-d'))
                    ->where('user_id', $userId)
                    ->orderBy('id', 'DESC') // Order todos by id
                    ->get()
                    ->toArray();
            } else {
                $todo = Todo::with(['todoTasks.prioritys', 'todoTasks.status', 'todoTasks.todoTaskUser', 'priority', 'todoAssignUsers', 'todoAssignUsers.assignUserDetail', 'todoTasks' => function ($query) {
                    $query->orderBy('id', 'DESC'); // Order the related todoTasks by id
                }])
                    ->whereDate('assign_date_time', '<', date('Y-m-d'))
                    ->orderBy('id', 'DESC') // Order todos by id
                    ->get()
                    ->toArray();
            }
        } else if ($request->is_today_record == '1') {
            if (isset($userId) && $userId != "") {
                $todo = Todo::with(['todoTasks.prioritys', 'todoTasks.status', 'todoTasks.todoTaskUser', 'priority', 'todoAssignUsers', 'todoAssignUsers.assignUserDetail', 'todoTasks' => function ($query) {
                    $query->orderBy('id', 'DESC'); // Order the related todoTasks by id
                }])
                    ->whereDate('assign_date_time', date('Y-m-d'))
                    ->where('user_id', $userId)
                    ->orderBy('id', 'DESC') // Order todos by id
                    ->get()
                    ->toArray();
            } else {
                $todo = Todo::with(['todoTasks.prioritys', 'todoTasks.status', 'todoTasks.todoTaskUser', 'priority', 'todoAssignUsers', 'todoAssignUsers.assignUserDetail', 'todoTasks' => function ($query) {
                    $query->orderBy('id', 'DESC'); // Order the related todoTasks by id
                }])
                    ->whereDate('assign_date_time', date('Y-m-d'))
                    ->orderBy('id', 'DESC') // Order todos by id
                    ->get()
                    ->toArray();
            }
        } else if ($request->is_upcoming_record == '1') {
            if (isset($userId) && $userId != "") {
                $todo = Todo::with(['todoTasks.prioritys', 'todoTasks.status', 'todoTasks.todoTaskUser', 'priority', 'todoAssignUsers', 'todoAssignUsers.assignUserDetail', 'todoTasks' => function ($query) {
                    $query->orderBy('id', 'DESC'); // Order the related todoTasks by id
                }])
                    ->whereDate('assign_date_time', '>', date('Y-m-d'))
                    ->where('user_id', $userId)
                    ->orderBy('id', 'DESC') // Order todos by id
                    ->get()
                    ->toArray();
            } else {
                $todo = Todo::with(['todoTasks.prioritys', 'todoTasks.status', 'todoTasks.todoTaskUser', 'priority', 'todoAssignUsers', 'todoAssignUsers.assignUserDetail', 'todoTasks' => function ($query) {
                    $query->orderBy('id', 'DESC'); // Order the related todoTasks by id
                }])
                    ->whereDate('assign_date_time', '>', date('Y-m-d'))
                    ->orderBy('id', 'DESC') // Order todos by id
                    ->get()
                    ->toArray();
            }
        } else {
            if (isset($userId) && $userId != "") {
                $todo = Todo::with(['todoTasks.prioritys', 'todoTasks.status', 'todoTasks.todoTaskUser', 'priority', 'todoAssignUsers', 'todoAssignUsers.assignUserDetail', 'todoTasks' => function ($query) {
                    $query->orderBy('id', 'DESC'); // Order the related todoTasks by id
                }])
                    ->where('user_id', $userId)
                    ->orderBy('id', 'DESC') // Order todos by id
                    ->get()
                    ->toArray();
            } else {
                $todo = Todo::with(['todoTasks.prioritys', 'todoTasks.status', 'todoTasks.todoTaskUser', 'priority', 'todoAssignUsers', 'todoAssignUsers.assignUserDetail', 'todoTasks' => function ($query) {
                    $query->orderBy('id', 'DESC'); // Order the related todoTasks by id
                }])
                    ->orderBy('id', 'DESC') // Order todos by id
                    ->get()
                    ->toArray();
            }
        }


        if (!$todo) {
            return response()->json([
                'success' => false,
                'message' => 'No priorities found',
                'data' => []
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Todo fetched successfully',
            'data' => $todo
        ], 200);
    }

    /**
     * Get user all todos record details
     */
    public function getAssignUserDetail(string $assign_user_id = null, string $priorityId = null)
    {
        $todo = Todo::where('user_id', Auth::user()->id)
            // ->orWhere('assign_user_id', Auth::user()->id)
            ->with('assignUserDetail', 'priority', 'todoTasks', 'todoTasks.prioritys', 'todoTasks.status')
            ->get()
            ->toArray();

        if (empty($todo)) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'data' => []
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'User fetched successfully',
            'data' => $todo
        ], 200);
    }

    /**
     * Get assign user filter details
     */
    public function getAssignUserFilterDetail(string $assign_user_id = null, string $priorityId = null)
    {
        // Treat empty or '%20' as null
        $assign_user_id = ($assign_user_id === 'null' || $assign_user_id === '' || $assign_user_id === '%20') ? null : $assign_user_id;
        // $priorityId = ($priorityId === 'null' || $priorityId === '' || $priorityId === '%20') ? null : $priorityId;

        $todo = Todo::where('user_id', $assign_user_id)
            // ->orWhere('assign_user_id', $assign_user_id)
            ->with('assignUserDetail', 'priority', 'todoTasks', 'todoTasks.prioritys', 'todoTasks.status')
            ->get()
            ->toArray();

        if (empty($todo)) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'data' => []
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'User fetched successfully',
            'data' => $todo
        ], 200);
    }

    public function todosFilter(Request $request)
    {
        $status = $request->status;
        $priority_id = $request->priority_id;
        $assign_date_time = $request->assign_date_time;
        $assign_user_id = $request->assign_user_id;
        $user_id = $request->user_id;
        //$useridn = Auth::user()->id;


        // $data = Todo::join('todo_assign_users as TAS', 'todos.id', '=', 'TAS.todo_id')
        //                 ->where(function ($query) use($user_id) {
        //                     $query->where('TAS.assign_user_id', $user_id)
        //                     ->orwhere('todos.user_id', $user_id);
        //                 })
                        
        //                 ->where('todos.is_group', 0)
                        
        //                 ->with([
        //                     'todoUser', 
        //                     'todoTasks', 
        //                     'priority', 
        //                     'todoTasks.todoTaskUser', 
        //                     'todoAssignUsers', 
        //                     'todoAssignUsers.assignUserDetail', 
        //                     'todoTasks.prioritys', 
        //                     'todoTasks.status'
        //                 ])

        $data = Todo::with([
            'todoUser', 
            'todoTasks', 
            'priority', 
            'todoTasks.todoTaskUser', 
            'todoAssignUsers', 
            'todoAssignUsers.assignUserDetail', 
            'todoTasks.prioritys', 
            'todoTasks.status'
        ])
        //->where('is_group', 0) // removed group todo on filter to see all assigned todo as sugested by Rakeshbhai
        ->where(function ($query) use ($user_id) {
            $query->whereHas('todoAssignUsers', function ($q) use ($user_id) {
                $q->where('assign_user_id', $user_id);
            })->orWhere('user_id', $user_id);
        })
                       
                        
            ->when($status !== null && $status !== '' && $status == 0, function ($query) use ($status) {
                $todo_pendingstatus = 0;
                $query->where('todos.status', $todo_pendingstatus);
            })
            ->when($status !== null && $status !== '' && ($status == 1), function ($query) use ($status) {
                $query->whereHas('todoTasks', function ($query1) use ($status) {
                     $query1->where('status_id', $status);
                });
            })
            ->when($status !== null && $status !== '' && ($status == 2), function ($query) use ($status) {
                $query->whereHas('todoTasks', function ($query1) use ($status) {
                     $query1->where('status_id', $status);
                });
            })
            ->when($status !== null && $status !== '' && ($status == 3), function ($query) use ($status) {
                $query->whereHas('todoTasks', function ($query1) use ($status) {
                     $query1->where('status_id', $status);
                });
            })
            ->when($status !== null && $status !== '' && $status == 4, function ($query) use ($status) {
                $todo_compstatus = 1;
                $query->where('todos.status', $todo_compstatus);
            })
            ->when($priority_id !== null && $priority_id !== '', function ($query) use ($priority_id) {
                $query->where('todos.priority_id', $priority_id);
            })
            ->when($assign_date_time !== null && $assign_date_time !== '', function ($query) use ($assign_date_time) {
                $query->whereDate('todos.assign_date_time', date('Y-m-d', strtotime($assign_date_time)));
            })
             ->when($assign_user_id !== null && $assign_user_id !== '', function ($query) use ($assign_user_id) {
                 $query->whereHas('todoTasks', function ($query2) use ($assign_user_id) {
                    $query2->where('user_id', $assign_user_id);
               });
             })
            //->when($user_id !== null && $user_id !== '', function ($query) use ($user_id) {
            //    $query->where('user_id', $user_id);
            //})
            //->whereHas('todoAssignUsers', function ($query) use ($assign_user_id) {
            //    $query->where('user_id', $user_id);
            //})
            ->latest()
            ->get()
            ->toArray();
            //dd($data);

        $todos = $data;
        if (!empty($todos)) {
            $processedTodos = [];
            foreach ($todos as $item) {
                $item['pdf_url'] = !empty($item['pdf']) ? url('/todo/pdf/' . $item['pdf']) : '';
                $item['image_url'] = !empty($item['image']) ? url('/todo/images/' . $item['image']) : '';
                $item['video_url'] = !empty($item['video']) ? url('/todo/videos/' . $item['video']) : '';
                $item['audio_url'] = !empty($item['audio']) ? url('/todo/audios/' . $item['audio']) : '';

                $processedTodos[] = $item;
            }

            $data = $processedTodos;
        }

        // if (!empty($data['todoAssignUser'])) {
        //     foreach ($data['todoAssignUser'] as &$task) { // Use reference here
        //         if (!empty($task['todo_detail'])) {
        //             $task['todo_detail']['pdf_url'] = !empty($task['todo_detail']['pdf'])
        //                 ? url('/todo_complete/pdf/' . $task['todo_detail']['pdf'])
        //                 : '';
        //             $task['todo_detail']['image_url'] = !empty($task['todo_detail']['image'])
        //                 ? url('/todo_complete/images/' . $task['todo_detail']['image'])
        //                 : '';
        //             $task['todo_detail']['video_url'] = !empty($task['todo_detail']['video'])
        //                 ? url('/todo_complete/videos/' . $task['todo_detail']['video'])
        //                 : '';
        //             $task['todo_detail']['audio_url'] = !empty($task['todo_detail']['audio'])
        //                 ? url('/todo_complete/audios/' . $task['todo_detail']['audio'])
        //                 : '';
        //         }
        //     }
        //     unset($task);
        // }


        if (!empty($data)) {
            return response()->json([
                'success' => true,
                'message' => 'Todo fetched successfully',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Todo Not fetched successfully',
                'data' => []
            ], 200);
        }
    }

    /**
     * Add new comment details
     */
    public function addNewComment(Request $request)
    {
        // If validation passes, create the todo
        // $todo = TodoTask::create($request->validated());
        //dd($request);
        if (isset($request->id) && !empty($request->id)) {
            $todo = Todo::findOrFail($request->id);
            if ($todo) {
                $data = [];
                // Handle file updates and deletions
                if ($request->hasFile('pdf')) {
                    // Delete old file if it exists
                    if ($todo->pdf && file_exists(public_path('todo/pdf/'.$todo->pdf))) {
                        unlink(public_path('todo/pdf/'.$todo->pdf));
                    }

                    // Save new PDF file
                    $file = $request->file('pdf');
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('todo/pdf'), $filename);
                    $data['pdf'] = $filename; // Add the new file name to the data array
                }

                if ($request->hasFile('image')) {
                    // Delete old file if it exists
                    if ($todo->image && file_exists(public_path('todo/images/'.$todo->image))) {
                        unlink(public_path('todo/images/'.$todo->image));
                    }

                    // Save new image file
                    $file = $request->file('image');
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('todo/images'), $filename);
                    $data['image'] = $filename; // Add the new file name to the data array
                }

                if ($request->hasFile('video')) {
                    // Delete old file if it exists
                    if ($todo->video && file_exists(public_path('todo/videos/'.$todo->video))) {
                        unlink(public_path('todo/videos/'.$todo->video));
                    }

                    // Save new video file
                    $file = $request->file('video');
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('todo/videos'), $filename);
                    $data['video'] = $filename; // Add the new file name to the data array
                }

                if ($request->hasFile('audio')) {
                    // Delete old file if it exists
                    if ($todo->audio && file_exists(public_path('todo/audios/'.$todo->audio))) {
                        unlink(public_path('todo/audios/'.$todo->audio));
                    }

                    // Save new audio file
                    $file = $request->file('audio');
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('todo/audios'), $filename);
                    $data['audio'] = $filename; // Add the new file name to the data array
                }
                // Perform the update with the prepared data
                if($data) {
                    $todo->update($data);
                }
            }
            
        }
        
         //dd($request->todo_id);
        // Add assign multi user task data
        foreach ($request->todo_id as $userKey => $user) {
            if ($user != "" && isset(Auth::user()->id) && Auth::user()->id!='' && $user['user_id']==Auth::user()->id) {
            //if ($user != "") {

                // if (isset($request->status_id) && $request->status_id!='')
                // {
                //     $onestatusforallusers = $request->status_id;
                // } else {
                //     $onestatusforallusers = $user['status_id'];
                // }

                // $folderName = 'todo_complete'; // Replace with your folder name
                // $path = public_path($folderName);

                // if (!File::isDirectory($path)) {
                //     File::makeDirectory($path, 0755, true, true); // Create directory with permissions
                // }

                // if (isset($user['pdf'])) {
                //     $folderName = 'pdf'; // Replace with your folder name
                //     $path = public_path('todo_complete/'.$folderName);
        
                //     if (!File::isDirectory($path)) {
                //         File::makeDirectory($path, 0755, true, true); // Create directory with permissions
                //     }
        
                //     $file = $user['pdf'];
                //     $pdfFileName = time() . '.' . $file->getClientOriginalExtension();
                //     $file->move(public_path('todo_complete/pdf'), $pdfFileName);
                // }
        
                // if (isset($user['image'])) {
                //     $folderName = 'images'; // Replace with your folder name
                //     $path = public_path('todo_complete/'.$folderName);
        
                //     if (!File::isDirectory($path)) {
                //         File::makeDirectory($path, 0755, true, true); // Create directory with permissions
                //     }
        
                //     $file = $user['image'];
                //     $imageFileName = time() . '.' . $file->getClientOriginalExtension();
                //     $file->move(public_path('todo_complete/images'), $imageFileName);
                // }
        
                // if (isset($user['video'])) {
                //     $folderName = 'videos'; // Replace with your folder name
                //     $path = public_path('todo_complete/'.$folderName);
        
                //     if (!File::isDirectory($path)) {
                //         File::makeDirectory($path, 0755, true, true); // Create directory with permissions
                //     }
        
                //     $file = $user['video'];
                //     $videoFileName = time() . '.' . $file->getClientOriginalExtension();
                //     $file->move(public_path('todo_complete/videos'), $videoFileName);
                // }
                
                // if (isset($user['audio'])) {
                //     $folderName = 'audios'; // Replace with your folder name
                //     $path = public_path('todo_complete/'.$folderName);
        
                //     if (!File::isDirectory($path)) {
                //         File::makeDirectory($path, 0755, true, true); // Create directory with permissions
                //     }
        
                //     $file = $user['audio'];
                //     $audioFileName = time() . '.' . $file->getClientOriginalExtension();
                //     $file->move(public_path('todo_complete/audios'), $audioFileName);
                // }
                
                $date = date('Y-m-d');
                $time = '00:00:00';
                $remind_type = null;
                $week_day = null;
                $priority_id = $user['priority_id'];

                if(isset($user['date']) && !empty($user['date'])) {
                    $date = date('Y-m-d', strtotime($user['date']));
                }
                if(isset($user['time']) && !empty($user['time'])) {
                    $time = $user['time'];
                }
                if(isset($user['remind_type']) && !empty($user['remind_type'])) {
                    $remind_type = $user['remind_type'];
                }
                if(isset($user['week_day']) && !empty($user['week_day'])) {
                    $week_day = $user['week_day'];
                }

                    

                    $todoTaskArr[$userKey] = [
                        'todo_id' => $user['todo_id'],
                        'date' => $date,
                        'time' => $time,
                        'comment_first' => $user['comment_first'],
                        'comment_second' => $user['comment_second'],
                        'priority_id' => $user['priority_id'],
                        'user_id' => $user['user_id'],
                        'status_id' => $user['status_id'],
                        //'status_id' => $onestatusforallusers,
                        'remind_type' => $remind_type,
                        'week_day' => $week_day
                        // 'pdf' => $pdfFileName ?? '',
                        // 'image' => $imageFileName ?? '',
                        // 'video' => $videoFileName ?? '',
                        // 'audio' => $audioFileName ?? '',
                    ];
                
            }
        }

        // Insert todo assign user id data
        if (isset($todoTaskArr) && !empty($todoTaskArr)) {
            // Remove todo tasks Before saving todo tasks and assigned users
             //TodoTask::where('user_id', Auth::user()->id)->delete();
            $todoTaskInserted = TodoTask::insert($todoTaskArr);

            if (!$todoTaskInserted) {
                // If insert fails
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to insert todo tasks',
                    'data' => []
                ], 500);
            }
        }

        // Add assign multi user data
        if (isset($request->is_new_member) && $request->is_new_member == 1) { 
            if ($request->has('add_member')) {
                //dd("in ifff");
                $userArrNd = TodoAssignUser::where('todo_id', $request->id)->get();
                //dd($userArrNd);
                foreach ($request->add_member as $userKeyadd => $useradd) {
                    $existsuid = $userArrNd->contains('assign_user_id', $useradd['assign_user_id']);
                    if ($existsuid) {
                        //dd("User ID exists!".$useradd['assign_user_id']);
                    } else {
                        //dd("User ID NOT exists!".$useradd['assign_user_id']);
                        $date = date('Y-m-d');
                        $time = '00:00:00';
                        $remind_type = null;
                        $week_day = null;
                        $comment_firstn = null;
                        $comment_secondn = null;

                
                        if ($useradd != "") {
                            $todoTaskArrn[$userKeyadd] = [
                                'todo_id' => $useradd['assign_todo_id'],
                                'date' => $date,
                                'time' => $time,
                                'comment_first' => $comment_firstn,
                                'comment_second' =>  $comment_secondn,
                                'priority_id' => $priority_id,
                                'user_id' => $useradd['assign_user_id'],
                                'status_id' => $request->status_id,
                                //'status_id' => $onestatusforallusers,
                                'remind_type' => $remind_type,
                                'week_day' => $week_day
                            ];   
                        }
                    }
                    
                }          
            }
        } 

         // Insert todo assign user id data
         if (isset($todoTaskArrn) && !empty($todoTaskArrn)) {
            // Remove todo tasks Before saving todo tasks and assigned users
             //TodoTask::where('user_id', Auth::user()->id)->delete();
            $todoTaskInsertedn = TodoTask::insert($todoTaskArrn);

            if (!$todoTaskInsertedn) {
                // If insert fails
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to insert todo tasks',
                    'data' => []
                ], 500);
            }
        }

        // One Status for All is Nedded
        if (isset($request->id) && !empty($request->id) && isset($request->status_id) && !empty($request->status_id)) {        
            $onestatus = TodoTask::where('todo_id', $request->id)
            ->update(['status_id' => $request->status_id]);
            //dd('in ifff - '.$onestatus);
            if (empty($onestatus) && !isset($onestatus)) {
                // If insert fails
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update todo one status',
                    'data' => []
                ], 500);
            }
        }

        // Add assign multi user data
        if (isset($request->is_new_member) && $request->is_new_member == 1) {
            if ($request->has('add_member')) {
                foreach ($request->add_member as $userKey => $user) {
                    if ($user != "") {
                        $userArr[$userKey] = [
                            'todo_id' => $user['assign_todo_id'],
                            'assign_user_id' => $user['assign_user_id']
                        ];
                    }
                }
            }
            
            // Insert todo assign user id data
            if (isset($userArr) && !empty($userArr)) {
                 // Remove todo tasks Before saving todo tasks and assigned users
                 TodoAssignUser::where('todo_id', $request->id)->delete();
                $todoAssignUserInserted = TodoAssignUser::insert($userArr);

                if (!$todoAssignUserInserted) {
                    // If insert fails
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to insert todo asisgn users',
                        'data' => []
                    ], 500);
                }
            }
        }

        // Send notification while closing the todo
        if (isset($request->status_id) && $request->status_id!='' && $request->status_id==3)
        {
            //dd($request->status_id);
            $userArrNt = TodoAssignUser::where('todo_id', $request->id)->get();
            
            if (isset($userArrNt) && !empty($userArrNt)) {
                //dd('in iff '.$request->status_id);
                foreach ($userArrNt as $userKey => $user) {
                    if ($user != "" && isset(Auth::user()->id) && Auth::user()->id!='' && $user['assign_user_id']!=Auth::user()->id) {
                    
                    //dd($user['assign_user_id']);
                        $user = User::find($user['assign_user_id']);
                        $id = $request->id;
                        $type = 'Todo';
                        if (!empty($user->device_token) && !empty($request->todo_id) && !empty($request->title)) {
                            // Send push notification
                            $title = 'Todo Closed';
                            $body = $request->title.' - Is closed';
                            $token = $user->device_token;

                            if ($title != "" && $body != "" && $token != "") {
                                // Get the response from the helper function
                                // sendNotification($title, $body, $token);
                                $this->notificationService->sendTodoNotification($title, $body, $token, $type, $id);
                            }
                        }
                    }   
                }
            } else {
               // dd('in else '.$request->status_id); 
            }
        } else if(isset($request->status_id) && $request->status_id!='' && ($request->status_id==1 || $request->status_id==2)) {

            //dd($request->status_id);
            $userArrNt = TodoAssignUser::where('todo_id', $request->id)->get();
            if (isset($userArrNt) && !empty($userArrNt)) {
                //dd('in iff '.$request->status_id);
                $id = $request->id;
                $type = 'Todo';
                foreach ($userArrNt as $userKey => $user) {
                    if ($user != "" && isset(Auth::user()->id) && Auth::user()->id!='' && $user['assign_user_id']!=Auth::user()->id) {
                        $user = User::find($user['assign_user_id']);
                        if (!empty($user->device_token) && !empty($request->todo_id) && !empty($request->title)) {
                            // Send push notification
                            $title = 'Todo Updated';
                            $body = $request->title.' - Is Updated';
                            $token = $user->device_token;

                            if ($title != "" && $body != "" && $token != "") {
                                // Get the response from the helper function
                                // sendNotification($title, $body, $token);
                                $this->notificationService->sendTodoNotification($title, $body, $token, $type, $id);
                            }
                        }
                    }
                }
            } else {
               // dd('in else '.$request->status_id); 
            }

         }

        return response()->json([
            'success' => true,
            'message' => 'Comment Added Successfully',
            // 'data' => $todo
        ], 200);
    }

    /**
     * Update todo status
     */
    public function updateTodoStatus(Request $request, string $id)
    {
        $todo = Todo::whereId($request->id)->first();

        if (!$todo) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found',
                'data' => []
            ], 404);
        }

        $todo->status = $request->status;
        $todo->save();

        return response()->json([
            'status' => true,
            'message' => 'Todo status updated successfully',
            'data' => $todo,
        ], 200);
    }

    /**
     * Get assign user filter details
     */
    public function getAssignUserPriorityFilterDetail(string $assign_user_id = null, string $priority_id = null)
    {
        // Fetch todos assigned to the user
        $todos = Todo::where('assign_user_id', $assign_user_id)
            ->with(['assignUserDetail', 'priority', 'todoTasks.prioritys', 'todoTasks.status'])
            ->get()
            ->toArray();

        // Initialize an empty array to store modified todos
        $modifiedTodos = [];

        // Loop through each todo item
        foreach ($todos as $item) {
            // Get the last task's ID dynamically
            $lastTaskId = end($item['todo_tasks'])['id'];

            // Filter todo_tasks to only include the desired task based on the dynamic ID
            $item['todo_tasks'] = array_values(array_filter($item['todo_tasks'], function ($task) use ($lastTaskId, $priority_id) {
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

    public function updateFavorite(Request $request)
    {
        $todo = Todo::whereId($request->id)->first();

        if (!$todo) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found',
                'data' => []
            ], 404);
        }

        $todo->favorite = $request->favorite == 1 ? 1 : 0;
        $todo->save();

        return response()->json([
            'status' => true,
            'message' => 'Todo favorite updated successfully',
            'data' => $todo,
        ], 200);
    }

    public function assignUsersList()
    {
        $assugnUsers = Todo::where('user_id', Auth::user()->id)->get()->toArray();

        dd($assugnUsers);
    }

    public function groupDetails(Request $request) {
        $todo = Todo::with([
            'teamDetail',
            'todoUser', 
            'todoTasks', 
            'priority', 
            'todoTasks.todoTaskUser', 
            'todoAssignUsers', 
            'todoAssignUsers.assignUserDetail', 
            'todoTasks.prioritys',
            'todoTasks.status'
        ])
        ->where('is_group', 1)
        ->where('team_id', $request->id)
        ->get();
        // ->each(function ($item) {
        //     // Loop through each todoTask and append the URL for related fields
        //     $item->todoTasks->each(function ($todoTask) {
        //         // Append full URL for task-related files or resources (adjust based on your fields)
        //         $todoTask->pdf_url = !empty($todoTask->pdf) ? url('/todo_complete/pdf/' . $todoTask->pdf) : '';
        //         $todoTask->image_url = !empty($todoTask->image) ? url('/todo_complete/images/' . $todoTask->image) : '';
        //         $todoTask->video_url = !empty($todoTask->video) ? url('/todo_complete/videos/' . $todoTask->video) : '';
        //         $todoTask->audio_url = !empty($todoTask->audio) ? url('/todo_complete/audios/' . $todoTask->audio) : '';
        //     });
        // });
        $todo = $todo->map(function ($item) {
            $item->pdf_url = !empty($item->pdf) ? url('/todo/pdf/' . $item->pdf) : '';
            $item->image_url = !empty($item->image) ? url('/todo/images/' . $item->image) : '';
            $item->video_url = !empty($item->video) ? url('/todo/videos/' . $item->video) : '';
            $item->audio_url = !empty($item->audio) ? url('/todo/audios/' . $item->audio) : '';
            return $item;
        });

        if($todo) {
            return response()->json([
                'success' => true,
                'message' => 'Group wise todo fetched successfully',
                'data' => $todo
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Group wise todo not found',
                'data' => []
            ], 404);
        }
    }
}
