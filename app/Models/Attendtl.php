<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location;

class Attendtl extends Model
{
    use HasFactory;

    protected $fillable = [
        'firm_id', 'engineer_id', 'year_id', 'in_date', 'in_time', 'out_date', 'out_time', 'ap', 'late_hrs', 'earligoing_hrs', 'working_hrs', 'pdays', 'in_late', 'in_long', 'in_address', 'out_late', 'out_long', 'out_address', 'in_selfie', 'out_selfie'];
    public $table = "attendtl";
 
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'engineer_id', 'id');
    }

    public function users(){
        return $this->hasOne(User::class, 'id', 'engineer_id');
    }

    public function locations()
    {
    return $this->hasMany(Location::class, 'user_id', 'engineer_id')
                ->orderBy('id', 'desc');
    }
}
