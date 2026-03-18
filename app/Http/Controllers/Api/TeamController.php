<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamEditeRequest;
use App\Http\Requests\TeamStoreRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class TeamController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all teams with their associated todos
        // $teams = Team::with('todos', 'todos.todoUser', 'todos.todoTasks', 'todos.priority', 'todos.todoTasks.todoTaskUser', 'todos.todoAssignUsers', 'todos.todoAssignUsers.assignUserDetail', 'todos.todoTasks.prioritys', 'todos.todoTasks.status')->get();
        // $teams = Team::with('user','userDetails','todos', 'todos.todoUser', 'todos.todoTasks', 'todos.priority', 'todos.todoTasks.todoTaskUser', 'todos.todoAssignUsers', 'todos.todoAssignUsers.assignUserDetail', 'todos.todoTasks.prioritys', 'todos.todoTasks.status')
        $teams = Team::with('user','userDetails')
                ->whereHas('user', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })
                ->orWhere('create_user_id', Auth::user()->id)
                ->get();

        // If no teams are found, check if the authenticated user is associated with any teams through the pivot table
        if ($teams->isEmpty()) {
            // $teams = Team::with(
            //     'user', 
            //     'userDetails', 
            //     'todos', 
            //     'todos.todoUser', 
            //     'todos.todoTasks', 
            //     'todos.priority', 
            //     'todos.todoTasks.todoTaskUser', 
            //     'todos.todoAssignUsers', 
            //     'todos.todoAssignUsers.assignUserDetail', 
            //     'todos.todoTasks.prioritys', 
            //     'todos.todoTasks.status'
            // )
            $teams = Team::whereHas('user', function ($query) {
                    $query->where('user_id', Auth::user()->id);  // Condition 2: Check if the user is part of the team via pivot table
                })
                ->get();
        }

        // Process todos for each team
        // foreach ($teams as $team) {
        //     $team->todos = $team->todos->map(function ($todo) {
        //         $todo->pdf_url = !empty($todo->pdf) ? url('/todo/pdf/' . $todo->pdf) : '';
        //         $todo->image_url = !empty($todo->image) ? url('/todo/images/' . $todo->image) : '';
        //         $todo->video_url = !empty($todo->video) ? url('/todo/videos/' . $todo->video) : '';
        //         $todo->audio_url = !empty($todo->audio) ? url('/todo/audios/' . $todo->audio) : '';
        //         return $todo;
        //     });
        // }

        // Return the teams and their todos in the response
        return response()->json([
            'success' => true,
            'message' => 'Group fetched successfully',
            'data' => $teams
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(TeamStoreRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeamStoreRequest $request)
    {
        $data = $request->all();
        $data['create_user_id'] = Auth::user()->id;

        // Create a new team
        $team = Team::create($data);

        // if ($request->has('todos')) {
        //     $team->todos()->attach($request->input('todos'));
        // }

        if ($request->has('user')) {
            $team->user()->attach($request->input('user'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Group created successfully',
            'data' => $team,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeamEditeRequest $request, string $id)
    {
        // Find the team by ID
        $team = Team::find($id);

        // If team not found, return an error response
        if (!$team) {
            return response()->json([
                'success' => false,
                'message' => 'Group not found',
                'data' => []
            ], 404);
        }

        // Update the team's name
        $team->update($request->validated());

        // If the request contains a list of todos, update the team-todo relationship
        // if ($request->has('todos')) {
        //     // Sync the todos with the team (attaching new ones and detaching removed ones)
        //     $team->todos()->sync($request->input('todos'));

        //     foreach($request->input('user') as $user) {
        //         $user = User::find($user);
        //         if (!empty($user->device_token)) {
        //             // Send push notification
        //             $title = 'New Todo';
        //             $body = 'You have been assign a new todo. ';
        //             $token = $user->device_token;

        //             if ($title != "" && $body != "" && $token != "") {
        //                 $this->notificationService->sendNotification($title, $body, $token);
        //             }
        //         }
        //     }
        // }

        if ($request->has('user')) {
            $team->user()->sync($request->input('user'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Group updated successfully',
            'data' => $team,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the team by ID
        $team = Team::find($id);

        // If team not found, return an error response
        if (!$team) {
            return response()->json([
                'success' => false,
                'message' => 'Group not found',
                'data' => []
            ], 404);
        }

        // Delete the team-todo relationships (pivot table)
        // $team->todos()->detach();
        $team->user()->detach();

        // Delete the team
        $team->delete();

        return response()->json([
            'success' => true,
            'message' => 'Group deleted successfully',
            'data' => $team,
        ], 200);
    }

    public function groupDetails(Request $request) {
        // $team = Team::with([
        //     'user',
        //     'userDetails',
        //     'todos' => function ($query) {
        //         $query->orderBy('created_at', 'desc');
        //     },
        //     'todos.todoUser',
        //     'todos.todoTasks',
        //     'todos.priority',
        //     'todos.todoTasks.todoTaskUser',
        //     'todos.todoAssignUsers',
        //     'todos.todoAssignUsers.assignUserDetail',
        //     'todos.todoTasks.prioritys',
        //     'todos.todoTasks.status'
        // ])->find($request->id);

        
        // if ($team) {
        //     // Map the todos for the found team
        //     $team->todos = $team->todos->map(function ($todo) {
        //         $todo->pdf_url = !empty($todo->pdf) ? url('/todo/pdf/' . $todo->pdf) : '';
        //         $todo->image_url = !empty($todo->image) ? url('/todo/images/' . $todo->image) : '';
        //         $todo->video_url = !empty($todo->video) ? url('/todo/videos/' . $todo->video) : '';
        //         $todo->audio_url = !empty($todo->audio) ? url('/todo/audios/' . $todo->audio) : '';
        //         return $todo;
        //     });
        
        //     // Return the team and its todos in the response
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Group fetched successfully',
        //         'data' => $team
        //     ], 200);
        // } else {
        //     // Return an error if the team is not found
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Group not found',
        //         'data' => []
        //     ], 404);
        // }
    }

    public function roleWiseGroup(Request $request) {  
        $data = Team::with('user','todos')
                ->where('role_id', $request->role_id)
                ->where('create_user_id', $request->create_user_id)
                ->get();

        if($data) {
            // Return the team and its todos in the response
            return response()->json([
                'success' => true,
                'message' => 'Group fetched successfully',
                'data' => $data
            ], 200);
        } else {
            // Return an error if the team is not found
            return response()->json([
                'success' => false,
                'message' => 'Group not found',
                'data' => []
            ], 404);
        }
    }

    public function groupTodo(Request $request) {
        $data = Team::with([
            'todos' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->whereId($request->id)->first();
        if($data) {
            $todos = $data['todos'];
            if (!empty($todos)) {
                $processedTodos = [];
                foreach ($todos as $item) {
                    $item['pdf_url'] = !empty($item['pdf']) ? url('/todo/pdf/' . $item['pdf']) : '';
                    $item['image_url'] = !empty($item['image']) ? url('/todo/images/' . $item['image']) : '';
                    $item['video_url'] = !empty($item['video']) ? url('/todo/videos/' . $item['video']) : '';
                    $item['audio_url'] = !empty($item['audio']) ? url('/todo/audios/' . $item['audio']) : '';

                    $processedTodos[] = $item;
                }

                $data['todos'] = $processedTodos;
            }


            // Return the team and its todos in the response
            return response()->json([
                'success' => true,
                'message' => 'Group todo fetched successfully',
                'data' => $data
            ], 200);
        } else {
            // Return an error if the team is not found
            return response()->json([
                'success' => false,
                'message' => 'Group not found',
                'data' => []
            ], 404);
        }
    }
}
