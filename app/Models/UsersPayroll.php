<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersPayroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'basic_sal',
        'hra',
        'da',
        'pt',
        'msc_allow',
        'ptrl_allow',
        'earning',
        'deduction',
        'perday_sal',
        'total_act_sal_monthly',
        'total_ctc_yearly',
        'aadhar_front',
        'aadhar_back',
        'aadhar_pdf',
        'pan_front',
        'pan_pdf',
        'dl',
        'aadharno',
        'panno',
        'bank_name',
        'ifsc',
        'ahname',
        'account_no',
        'upi_id'

    ];

   public $table = "users_payroll";

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
