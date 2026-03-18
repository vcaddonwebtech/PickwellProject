<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Months extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'newid', 'tag'
     ];
    public $table = "months";
 
    protected $guarded = ['id'];
}
