<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineSalesEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'firm_id',
        'year_id',
        'date',
        'party_id',
        'product_id',
        'serial_no',
        'mc_no',
        'install_date',
        'service_expiry_date',
        'free_service',
        'free_service_date',
        'order_no',
        'remarks',
        'service_type_id',
        'contenor_no',
        'weft_insertion',
        'width',
        'color',
        'shadding',
        'shadding_option',
        'image3',
        'lat',
        'long',
        'map_url',
        'tag',
        'is_active',
        'mic_fitting_engineer_id',
        'delivery_engineer_id',
        'main_machine_type',
        'status'
    ];
    protected $id = 'id';

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }
    public function year()
    {
        return $this->belongsTo(Year::class);
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
    public function micFittingEngineer()
    {
        return $this->belongsTo(User::class, 'mic_fitting_engineer_id')->role('Engineer');
    }
    public function deliveryEngineer()
    {
        return $this->belongsTo(User::class, 'delivery_engineer_id')->role('Engineer');
    }
    public function salesEntry()
    {
        return $this->belongsTo(MachineSalesEntry::class, 'sales_entry_id')->with('party');
    }
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
    public function complaintType()
    {
        return $this->belongsTo(ComplaintType::class, 'complaint_type_id');
    }
    public function complaints()
    {
        return $this->hasOne(Complaint::class, 'sales_entry_id')->where('is_free_service', 1);
    }
    public function activeComplaints()
    {
        return $this->hasOne(Complaint::class, 'sales_entry_id');
    }
    public function visits()
    {
        return $this->hasMany(Visit::class, 'service_id', 'id');
    }
    public function partyfirms()
    {
        return $this->belongsTo(PartyFirm::class, 'firm_id');
    }
}
