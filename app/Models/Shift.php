<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Shift extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'title',
        'shift_start',
        'shift_end',
        'is_active'
    ];
    public $table = "shifts";

    protected $guarded = ['id'];

    public function users()
    {
        return $this->hasMany(User::class, 'shift_id', 'id');
    }

}



