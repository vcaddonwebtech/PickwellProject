<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPerson extends Model
{
    use HasFactory;

    protected $fillable = ['tag', 'firm_id', 'year_id', 'area_id', 'product_id', 'lead_stage_id', 'sale_user_id', 'sale_assign_user_id', 'date', 'time', 'mobile_no', 'partyname', 'address', 'location_Address', 'latitude', 'logitude', 'remarks', 'next_reminder_date', 'next_reminder_time', 'favorite', 'closed_by', 'closed_date', 'image', 'video', 'audio', 'status_id', 'main_machine_type'];

    public $table = "sales_persons";

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function lead_stage()
    {
        return $this->belongsTo(LeadStage::class);
    }

    public function prioritys()
    {
        return $this->hasOne(Priority::class, 'id', 'lead_stage_id');
    }

    public function statusDetail()
    {
        return $this->hasOne(Priority::class, 'id', 'status_id');
    }

    public function sale_assign_user()
    {
        return $this->belongsTo(User::class);
    }

    public function sale_assign_close_by_user()
    {
        return $this->hasOne(User::class, 'id', 'closed_by');
    }

    public function salesPersonTask()
    {
        return $this->hasMany(SalesPersonsTask::class, 'todo_id');
    }

    public function latestSalesPersonTask()
    {
        return $this->hasOne(SalesPersonsTask::class, 'todo_id')->latestOfMany();
    }

    public function salseUserDetail()
    {
        return $this->hasOne(User::class, 'id', 'sale_user_id');
    }

    public function saleAssignUserDetail()
    {
        return $this->hasOne(User::class, 'id', 'sale_assign_user_id');
    }
}
