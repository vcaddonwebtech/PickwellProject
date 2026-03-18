<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Product 1',
                'product_group_id' => 1,
            ],
            [
                'name' => 'Product 2',
                'product_group_id' => 1,
            ],
            [
                'name' => 'Product 3',
                'product_group_id' => 2,
            ],
            [
                'name' => 'Product 4',
                'product_group_id' => 2,
            ],
        ]);
    }
}
