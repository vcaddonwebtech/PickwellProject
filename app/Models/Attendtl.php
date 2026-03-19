<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location;

class Attendtl extends Model
{
    protected $table = 'attendtl';
 
    protected $fillable = [
        'firm_id',
        'engineer_id',
        'year_id',
        'in_date',
        'in_time',
        'out_date',
        'out_time',
        'ap',
        'late_hrs',
        'earligoing_hrs',
        'working_hrs',
        'pdays',
        'in_late',
        'in_long',
        'in_address',
        'out_late',
        'out_long',
        'out_address',
        'in_selfie',
        'out_selfie',
        'is_approved',
    ];
 
    protected $casts = [
        'in_date'  => 'date',
        'out_date' => 'date',
    ];
 
    /**
     * attendtl.engineer_id → users.id
     */
    public function engineer()
    {
        return $this->belongsTo(User::class, 'engineer_id', 'id');
    }
}