<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementImage extends Model
{
    use HasFactory;

    public $table = "advertisement_images";
    
    protected $fillable = ['advertisement_id', 'image'];
}
