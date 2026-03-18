<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserwiseMachine extends Model
{
    protected $fillable = ['user_id', 'main_machine_type'];
    public $table = "userwise_machines";

    /**
     * Each userwise machine belongs to a machine
     */
    public function machine()
    {
        return $this->belongsTo(
            Machine::class, 
            'main_machine_type', // foreign key in this table
            'id'                 // referenced key in machines
        );
    }
}

