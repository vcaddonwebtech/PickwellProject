<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("owners")->delete();
        DB::table("owners")->insert([
            'name' => 'owner1',
            'phone_no' => '1234567890',
        ],
        [
            'name' => 'owner2',
            'phone_no' => '1234567890',
        ],
        [
            'name' => 'owner3',
            'phone_no' => '1234567890',
        ],
        [
            'name' => 'owner4',
            'phone_no' => '1234567890',
        ],
        [
            'name' => 'owner5',
            'phone_no' => '1234567890',
        ]);
    }
}
