<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnspotInquiries extends Model
{
    use HasFactory;

    protected $fillable = ['inq_no', 'user_id', 'date', 'company_name', 'customer_name', 'contact_number', 'address', 'qty', 'model', 'rate', 'details', 'final_rate', 'crane_charge', 'duty_gst', 'total_rate', 'remarks', 'status_id', 'main_machine_type'];

    public $table = "onspot_inquiries";

    public function inquiryUserDetail()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
