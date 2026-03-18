<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JointComplaint extends Model
{
    use HasFactory;

    public $table = "joint_complaints";
    protected $fillable = [
        'complaint_id',
        'service_type',
        'visit_id',
        'joint_eng_id',
        'joint_eng_in_date',
        'joint_eng_in_time',
        'joint_eng_out_date',
        'joint_eng_out_time',
        'joint_eng_in_address',
        'joint_eng_out_address',
        'joint_eng_in_lat',
        'joint_eng_in_lng',
        'joint_eng_out_lat',
        'joint_eng_out_lng',
        'joint_eng_status',
        'joint_accp_status',
        'joint_eng_remarks'
    ];

    protected $casts = [
        'joint_complaint_data' => 'array'
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id', 'id');
    }

    public function jntengDetail()
    {
        return $this->hasOne(User::class, 'id', 'joint_eng_id');
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class, 'visit_id', 'id');
    }
}
