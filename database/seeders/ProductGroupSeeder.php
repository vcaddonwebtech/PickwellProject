<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("product_groups")->delete();
        DB::table("product_groups")->insert([
            [
                'name' => 'Product Group 1',
            ],
            [
                'name' => 'Product Group 2',
            ],
            [
                'name' => 'Product Group 3',
            ],
        ]);
    }
}
