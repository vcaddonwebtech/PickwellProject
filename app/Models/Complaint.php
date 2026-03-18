<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'firm_id',
        'year_id',
        'party_id',
        'date',
        'time',
        'complaint_type_id',
        'sales_entry_id',
        'product_id',
        'remarks',
        'image',
        'video',
        'audio',
        'engineer_id',
        'is_urgent',
        'engineer_assign_date',
        'engineer_assign_time',
        'engineer_complaint_id',
        'engineer_video',
        'engineer_audio',
        'engineer_image',
        'jointengg',
        'service_type_id',
        'status_id',
        'complaint_no',
        'engineer_in_time',
        'engineer_out_time',
        'engineer_in_date',
        'engineer_out_date',
        'engineer_time_duration',
        'is_assigned',
        'engineer_in_address',
        'engineer_out_address',
        'engineer_out_remarks',
        'factory_person',
        'fac_per_mobile',
        'fac_per_designation',
        'fac_per_dsignature',
        'is_free_service',
        'is_customer_complaint',
        'from_complaint_id',
        'is_reassign',
        'is_accepted',
        'not_resolved_by',
        'main_machine_type'
    ];

    protected $casts = [
        'jointengg' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class)->with('area');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function salesEntry()
    {
        //return $this->belongsTo(MachineSalesEntry::class, 'sales_entry_id')->with('party');
        return $this->belongsTo(MachineSalesEntry::class, 'sales_entry_id');
    }

    public function machineSalesEntry()
    {
        return $this->belongsTo(MachineSalesEntry::class, 'sales_entry_id');
    }

    public function complaintType()
    {
        return $this->belongsTo(ComplaintType::class, 'complaint_type_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    public function engineer()
    {
        return $this->belongsTo(User::class)->role('Engineer');
    }

    public function engineerDetail()
    {
        return $this->hasOne(User::class, 'id', 'engineer_id');
    }

    public function feedback()
    {
        return $this->hasMany(CustomerFeedback::class, 'complaint_id', 'id');
    }
    
    public function jointcomplaintengs()
    {
    return $this->hasMany(JointComplaint::class, 'complaint_id');
    }

    public function visits()
    {
    return $this->hasMany(Visit::class, 'complaint_id');
    }
}
