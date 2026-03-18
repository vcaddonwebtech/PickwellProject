<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TodoTask extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['todo_id', 'date', 'time', 'comment_first', 'comment_second', 'priority_id', 'user_id', 'status_id', 'pdf', 'image', 'video', 'audio', 'remind_type', 'week_day'];

    public function prioritys()
    {
        return $this->belongsTo(Priority::class, 'priority_id');
    }

    public function todoTaskUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function todoDetail()
    {
        return $this->hasOne(Todo::class, 'id', 'todo_id');
    }

    public function todo()
    {
        return $this->hasMany(TodoTask::class, 'user_id', 'todo_id');
    }

    public function todoAssignUsers()
    {
        return $this->hasMany(TodoAssignUser::class);
    }

    public function userDetails()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function status()
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }
}
