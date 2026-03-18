<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImportClass implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(!empty($row['model_no'])) {
            $product = Product::where('name', $row['model_no'])->first();
            if(empty($product)) {
                return new Product([
                    'name' =>  $row['model_no'],
                    'product_group_id' => 1,
                ]);
            }
        }
    }
}

