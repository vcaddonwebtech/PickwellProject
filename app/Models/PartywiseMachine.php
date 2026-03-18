<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartywiseMachine extends Model
{
    protected $fillable = ['party_id', 'main_machine_type'];
    public $table = "partywise_machines";

    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id', 'id');
    }

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