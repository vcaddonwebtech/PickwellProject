<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SequenceNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'n',
        'date',
    ];
}
