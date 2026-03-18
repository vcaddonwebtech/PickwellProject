<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPersonsTask extends Model
{
    use HasFactory;

    protected $fillable = ['todo_id', 'date', 'time', 'comment_first', 'comment_second', 'priority_id', 'assign_user_id'];

    public function salseTodo()
    {
        return $this->hasOne(SalesPerson::class, 'id', 'todo_id');
    }

    public function prioritys()
    {
        return $this->belongsTo(Priority::class, 'priority_id');
    }

    public function assignUserDetail()
    {
        return $this->hasOne(User::class, 'id', 'assign_user_id');
    }
}
