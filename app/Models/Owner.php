<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $fillable = ['name', 'phone_no'];

    public function parties()
    {
        return $this->hasMany(Party::class);
    }
}
