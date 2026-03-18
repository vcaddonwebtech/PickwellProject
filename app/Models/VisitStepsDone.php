<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class VisitStepsDone extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'visit_id',
        'step_id',
        'status'
    ];
    public $table = "visit_steps_done";

    protected $guarded = ['id'];

    public function visits()
    {
        return $this->hasMany(Visit::class, 'visit_id');
    }

    public function steps()
    {
        return $this->hasMany(VisitSteps::class, 'step_id');
    }

}
