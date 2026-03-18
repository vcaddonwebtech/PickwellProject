<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $table = 'machines';

    protected $fillable = [
        'machine_name',
        'machine_specification',
        'machine_rpm',
        'machine_category',
        'machine_image',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * A machine can be assigned to many userwise machines
     */
    public function userwiseMachines()
    {
        return $this->hasMany(
            UserwiseMachine::class,
            'main_machine_type', // foreign key in userwise_machines
            'id'                 // local key in machines
        );
    }

    /**
     * A machine can be assigned to many userwise machines
     */
    public function partywiseMachines()
    {
        return $this->hasMany(
            PartywiseMachine::class,
            'main_machine_type', // foreign key in userwise_machines
            'id'                 // local key in machines
        );
    }
}