<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_id',
        'complaint_id',
        'engineer_id',
        'date',
        'star_rating',
        'remark',
    ];

    public function engineer(){
        return $this->hasOne(User::class, 'id', 'engineer_id');
    }

    public function party(){
        return $this->hasOne(Party::class, 'id', 'party_id');
    }

    public function complaint(){
        return $this->hasOne(Complaint::class, 'id', 'complaint_id');
    }
}
