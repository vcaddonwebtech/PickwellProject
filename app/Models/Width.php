<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Width extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'width'
    ];
    public $table = "width_master";

    protected $guarded = ['id'];

}

