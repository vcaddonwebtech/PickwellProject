<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Shadding extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'name',
        'main_machine_type'
    ];
    
    public $table = "shaddings";

    protected $guarded = ['id'];

}

