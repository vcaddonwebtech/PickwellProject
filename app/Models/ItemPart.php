<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPart extends Model
{
    use HasFactory;
    public $table = "item_parts";
    protected $fillable = [
        'name',
        'product_group_id',
        'hsn_code',
        'gst',
        'rate'
    ];

    public function productGroup()
    {
        return $this->belongsTo(ProductGroup::class);
    }
}
