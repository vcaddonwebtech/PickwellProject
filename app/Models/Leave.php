<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = ['firm_id','year_id','user_id','date_time','leave_from','leave_till','leave_type','leave_duration','total_leave','reason','is_approved'];

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
