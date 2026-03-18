<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Department extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'name'
    ];
    public $table = "departments";

    protected $guarded = ['id'];

    public function users()
    {
        return $this->hasMany(User::class, 'deparment_id', 'id');
    }

}


