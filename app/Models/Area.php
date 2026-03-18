<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function party()
    {
        return $this->hasMany(Party::class, 'area_id', 'id');
    }

    public function engineers()
    {
        return $this->hasMany(User::class,'area_id', 'id');
    }
}
