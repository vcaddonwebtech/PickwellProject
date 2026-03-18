<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model
{
    use HasFactory;
    public $table = 'contact_persons';

    protected $fillable = [
        'name',
        'phone_no',
    ];

    public function parties()
    {
        return $this->hasMany(Party::class);
    }
}
