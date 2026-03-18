<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Party extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'code',
        'name',
        'email',
        'pan_no',
        'address',
        'phone_no',
        'password',
        'city_id',
        'state_id',
        'contact_person',
        'pincode',
        'gst_no',
        'owner_name',
        'area_id',
        'firm_id',
        'other_phone_no',
        'is_active',
        'device_token',
        'otp',
        'location_address'
    ];
    public $table = "parties";

    protected $guarded = ['id'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function partywiseMachines(): HasMany
    {
        return $this->hasMany(PartywiseMachine::class, 'party_id', 'id');
    }

    // public function owner()
    // {
    //     return $this->belongsTo(Owner::class, 'owner_id');
    // }

    // public function contactPerson()
    // {
    //     return $this->belongsTo(ContactPerson::class, 'contact_person_id');
    // }

    /**
     * Get all of the machineSales for the Party
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function machineSales(): HasMany
    {
        return $this->hasMany(MachineSalesEntry::class, 'party_id', 'id');
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'party_id', 'id');
    }

    public function visits()
    {
        return $this->hasMany(Visit::class, 'party_id');
    }

    public function firms()
    {
    return $this->hasMany(PartyFirm::class, 'party_id', 'id');
    }
}
