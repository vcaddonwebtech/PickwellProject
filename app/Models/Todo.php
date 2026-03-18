<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'user_id', 'assign_user_id', 'assign_date_time', 'favorite', 'priority_id', 'pdf', 'image', 'video', 'audio', 'is_group', 'team_id', 'status', 'main_machine_type'];

    // Get the todo tasks detail
    public function todoTasks()
    {
        return $this->hasMany(TodoTask::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assign_user_id'); // 'assign_user_id' is the foreign key in the todos table
    }

    public function assignUserDetail()
    {
        return $this->hasOne(User::class, 'id', 'assign_user_id');
    }

    public function priority()
    {
        return $this->hasOne(Priority::class, 'id', 'priority_id');
    }

    public function todoAssignUsers()
    {
        return $this->hasMany(TodoAssignUser::class);
    }

    public function userDetails()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function todoUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    // Define the relationship with teams
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_todo', 'todo_id', 'team_id');
    }

    public function teamDetail()
    {
        return $this->hasOne(Team::class, 'id', 'team_id');
    }
}
