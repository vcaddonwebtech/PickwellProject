<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class PartyFirm extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'party_id',
        'firm_name',
        'firm_owner',
        'firmowner_phone',
        'otp',
        'firmowner_email',
        'firm_gst',
        'firm_address',
        'device_token',
        'status',
        'is_active' 
    ];
    public $table = "party_firms";

    protected $guarded = ['id'];

    // public function parties()
    // {
    //     return $this->hasMany(Party::class, 'party_id', 'id');
    // }
    public function parties()
    {
    return $this->belongsTo(Party::class, 'party_id', 'id');
    }

    public function machineSales()
    {
        return $this->hasMany(MachineSalesEntry::class, 'firm_id');
    }
}
