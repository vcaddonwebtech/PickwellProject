<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            ['name' => 'North'], ['name' => 'South'], ['name' => 'East'], ['name' => 'West'], ['name' => 'Central'], ['name' => 'North-East'],
            ['name' => 'North-West'], ['name' => 'South-East'], ['name' => 'South-West'], ['name' => 'North-Central'], ['name' => 'South-Central'],
            ['name' => 'East-Central'], ['name' => 'West-Central']
        ];
        DB::table("areas")->insert($areas);
    }
}
