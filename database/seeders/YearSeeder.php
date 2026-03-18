<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('years')->insert([
            ['name' => 2022, 'from_date' => '2023-01-01', 'to_date' => '2023-12-31'],
            ['name' => 2021, 'from_date' => '2023-01-01', 'to_date' => '2023-12-31'],
            ['name' => 2020, 'from_date' => '2023-01-01', 'to_date' => '2023-12-31'],
            ['name' => 2019, 'from_date' => '2023-01-01', 'to_date' => '2023-12-31'],

        ]);
    }
}
