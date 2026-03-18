<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function machineSales()
    {
        return $this->hasMany(MachineSalesEntry::class, 'service_type_id', 'id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'service_type_id', 'id');
    }
}
