<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // protected $guarded = ['id'];
    protected $fillable = ['name', 'product_group_id', 'product_type_id'];

    public function productGroup()
    {
        return $this->belongsTo(ProductGroup::class);
    }

    public function machineSales()
    {
        return $this->hasMany(MachineSalesEntry::class, 'product_id')->with('party');
    }

    public function productType()
    {
        return $this->hasOne(ProductTypes::class, 'id', 'product_type_id');
    }

    public function visits()
    {
        return $this->hasMany(Visit::class, 'product_id');
    }
}
