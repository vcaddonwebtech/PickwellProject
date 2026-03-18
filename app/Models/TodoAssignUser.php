<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoAssignUser extends Model
{
    use HasFactory;

    protected $fillable = ['todo_id', 'assign_user_id'];

    public function assignUserDetail()
    {
        return $this->hasOne(User::class, 'id', 'assign_user_id');
    }

    public function todoDetail(){
        return $this->hasOne(Todo::class, 'id', 'todo_id');
    }

}
