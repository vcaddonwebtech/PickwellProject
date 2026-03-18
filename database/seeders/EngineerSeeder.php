<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EngineerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('engineers')->delete();
        DB::table('engineers')->insert([
            [
                'name' => 'Engineer1',
                'phone_no' => '1234567890',
                'area_id' => 1
            ],
            [
                'name' => 'Engineer2',
                'phone_no' => '1234567890',
                'area_id' => 2
            ],
            [
                'name' => 'Engineer3',
                'phone_no' => '1234567890',
                'area_id' => 3
            ],
        ]);
    }
}
