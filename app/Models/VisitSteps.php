<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class VisitSteps extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'work',
        'main_machine_type'
    ];
    public $table = "visit_steps";

    protected $guarded = ['id'];
}
