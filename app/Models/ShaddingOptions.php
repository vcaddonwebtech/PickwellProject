<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class ShaddingOptions extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'shadding_id',
        'name'
    ];
    
    public $table = "shadding_options";

    protected $guarded = ['id'];

}

