<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'published_at', 'roles_id'];

    public function advertisementImage()
    {
        return $this->hasMany(AdvertisementImage::class, 'advertisement_id', 'id');
    }
}
