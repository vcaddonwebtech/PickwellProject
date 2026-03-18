<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemPartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('item_parts')->insert([
            [
                'name' => 'Item Part 1',
                'product_group_id' => 1,
                'hsn_code' => '78',
                'gst' => '789456123',
                'rate' => 100,
            ],
            [
                'name' => 'Item Part 2',
                'product_group_id' => 1,
                'hsn_code' => '78',
                'gst' => '789456123',
                'rate' => 100,
            ],
            [
                'name' => 'Item Part 3',
                'product_group_id' => 1,
                'hsn_code' => '78',
                'gst' => '789456123',
                'rate' => 100,
            ],
            [
                'name' => 'Item Part 4',
                'product_group_id' => 1,
                'hsn_code' => '78',
                'gst' => '789456123',
                'rate' => 100,
            ],
            [
                'name' => 'Item Part 5',
                'product_group_id' => 1,
                'hsn_code' => '78',
                'gst' => '789456123',
                'rate' => 100,
            ],
        ]);
    }
}
