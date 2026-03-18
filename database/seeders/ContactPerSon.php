<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactPerSon extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("contact_persons")->delete();
        DB::table("contact_persons")->insert([
            [
                'name' => 'om satya person1',
                'phone_no' => '1234567890',
            ],
            [
                'name' => 'om satya person2',
                'phone_no' => '1234567890',
            ],
            [
                'name' => 'om satya person3',
                'phone_no' => '1234567890',
            ],
            [
                'name' => 'om satya person4',
                'phone_no' => '1234567890',
            ],
            [
                'name' => 'om satya person5',
                'phone_no' => '1234567890',
            ]
        ]);
    }
}
