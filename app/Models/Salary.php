<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_id',
        'month_id',
        'pdays',
        'hdays',
        'working_days',
        'perday_sal',
        'leaves',
        'earning',
        'deduction',
        'total_salary',
        'main_machine_type'
    ];

   public $table = "salaries";

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'emp_id');
    }
}
