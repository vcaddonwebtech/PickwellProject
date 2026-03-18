<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $table = 'visits';

    protected $fillable = [
        'main_machine_type',
        'service_type',
        'complaint_id',
        'service_id',
        'product_id',
        'party_id',
        'engineer_id',
        'title',
        'date',
        'end_date',
        'visit_remark',
        'engineer_in_time',
        'engineer_out_time',
        'engineer_in_date',
        'engineer_out_date',
        'engineer_time_duration',
        'engineer_in_address',
        'engineer_out_address',
        'engineer_out_remarks',
        'is_accepted',
        'engineer_video',
        'engineer_audio',
        'engineer_image',
        'status',
    ];

    protected $casts = [
        'date' => 'date'
    ];


    public function engineer()
    {
        return $this->belongsTo(User::class, 'engineer_id');
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class, 'main_machine_type');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id')->with('area');
    }

    public function machineSales()
    {
        return $this->belongsTo(MachineSalesEntry::class, 'service_id', 'id');
    }

    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id', 'id');
    }

    public function jointComplaints()
    {
        return $this->hasMany(JointComplaint::class, 'visit_id', 'id');
    }

    public function visitstepsdone()
    {
        return $this->hasMany(VisitStepsDone::class, 'visit_id', 'id');
    }
}
