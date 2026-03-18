<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Engineer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $fillable = [
        'name', 'phone_no', 'area_id'
    ];
}
