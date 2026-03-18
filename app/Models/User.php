<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Attribute;
use Dompdf\Css\Content\Attr;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'area_id',
        'phone_no',
        'doj',
        'is_active',
        'shift_id',
        'duty_start',
        'duty_end',
        'duty_break',
        'duty_hours',
        'device_token',
        'otp',
        'is_salse_emb',
        'is_salse_cir',
        'profile',
        'aadhar_card',
        'pan_card',
        'is_leader',
        'leader_id',
        'deparment_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // /**
    //  * Get the roles associated with the User
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\HasOne
    //  */
    // public function roles(): HasOne
    // {
    //     return $this->hasOne(Role::class, 'foreign_key', 'local_key');
    // }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'engineer_id', 'id')->with('party', 'serviceType', 'salesEntry', 'status', 'product');
    }

    public function inOutTime()
    {
        return $this->hasOne(Attendtl::class, 'engineer_id', 'id')
            ->where('in_date', date('Y-m-d'));
    }

    public function attendtl()
    {
        return $this->hasMany(Attendtl::class, 'engineer_id', 'id');
    }

    public function isPresent()
    {
        return $this->hasOne(Attendtl::class, 'engineer_id', 'id')->where('in_date', date('Y-m-d'));
    }

    public function leave()
    {
        return $this->hasOne(Leave::class, 'user_id', 'id')->latest('created_at');
    }

    public function hasLeaveOn()
    {
        return $this->leave()->where('leave_from', '<=', date('Y-m-d'))
            ->where('leave_till', '>=', date('Y-m-d'))
            ->where('is_approved', 1)
            ->exists();
    }

    // Define the relationship with teams
    // public function teams()
    // {
    //     return $this->belongsToMany(Team::class, 'team_todo');
    // }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_users', 'user_id', 'team_id');
    }

    public function latestComplaint()
    {
        return $this->hasOne(Complaint::class, 'engineer_id')
            ->select('complaints.id', 'complaints.engineer_id', 'complaints.date', 'complaints.complaint_no', 'complaints.engineer_in_address', 'complaints.engineer_out_address', 'complaints.party_id')
            ->with('party')
            ->latestOfMany('date');
    }
    
    public function machineTypes()
    {
        return $this->hasMany(UserwiseMachine::class, 'user_id');
    }

    public function userwiseMachines()
    {
        return $this->hasMany(UserwiseMachine::class, 'user_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'deparment_id', 'id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'id');
    }
}
