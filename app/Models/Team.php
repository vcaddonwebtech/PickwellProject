<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['create_user_id','name','role_id'];

    // Disable timestamps
    public $timestamps = true;

    // Define the relationship with users
    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'team_user');
    // }


    // Define the relationship with todo
    public function todos()
    {
        return $this->belongsToMany(Todo::class, 'team_todo', 'team_id', 'todo_id')->with('todoUser', 'todoTasks', 'priority', 'todoTasks.todoTaskUser', 'todoAssignUsers', 'todoAssignUsers.assignUserDetail', 'todoTasks.prioritys','todoTasks.status');
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'team_users', 'team_id', 'user_id');
    }

    public function userDetails()
    {
        return $this->hasOne(User::class, 'id', 'create_user_id');
    }
}
