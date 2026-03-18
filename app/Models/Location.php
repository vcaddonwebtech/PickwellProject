<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['user_id', 'lat', 'lng', 'created_at'];

    // public function Userlocation()
    // {
    //     return $this->hasMany(User::class, 'id', 'user_id');
    // }
}