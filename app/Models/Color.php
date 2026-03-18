<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Color extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'color'
    ];
    
    public $table = "color_master";

    protected $guarded = ['id'];

}

