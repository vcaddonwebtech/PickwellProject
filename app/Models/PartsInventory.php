<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartsInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'in_engineer_id',
        'vou_no',
        'date',
        'time',
        'in_party_id',
        'product_id',
        'card_no',
        'quntity',
        'remarks',
        'repair_out_date',
        'repair_out_time',
        'repair_out_party_code',
        'repair_out_party_id',
        'repair_out_remarks',
        'expexted_required_date',
        'repair_in_date',
        'repair_in_time',
        'repair_in_remarks',
        'issue_date',
        'issue_time',
        'issue_engineer_id',
        'issue_remarks',
        'repair_status'
    ];

    public function in_engineer()
    {
        return $this->belongsTo(User::class)->role('Engineer');
    }

    public function issue_engineer()
    {
        return $this->belongsTo(User::class)->role('Engineer');
    }

    public function in_party()
    {
        return $this->belongsTo(Party::class);
    }

    public function repair_out_party()
    {
        return $this->belongsTo(Party::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->with('machineSales');
    }
}
