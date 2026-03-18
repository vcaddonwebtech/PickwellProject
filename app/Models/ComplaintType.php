<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintType extends Model
{
    use HasFactory;

    protected $fillable = ['main_machine_type', 'machine_type', 'name', 'description'];

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function machineSales()
    {
        return $this->hasMany(MachineSalesEntry::class, 'complaint_type_id');
    }
}
