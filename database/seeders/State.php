<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class State extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            ['id' => 1, 'name' => 'Andaman and Nicobar Islands', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Andhra Pradesh', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Arunachal Pradesh', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Assam', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Bihar', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Chandigarh', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'Chhattisgarh', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'name' => 'Dadra and Nagar Haveli and Daman and Diu', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'name' => 'Delhi', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'name' => 'Goa', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'name' => 'Gujarat', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'name' => 'Haryana', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'name' => 'Himachal Pradesh', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'name' => 'Jammu and Kashmir', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'name' => 'Jharkhand', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'name' => 'Karnataka', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'name' => 'Kerala', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'name' => 'Ladakh', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'name' => 'Lakshadweep', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 20, 'name' => 'Madhya Pradesh', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 21, 'name' => 'Maharashtra', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 22, 'name' => 'Manipur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 23, 'name' => 'Meghalaya', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 24, 'name' => 'Mizoram', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 25, 'name' => 'Nagaland', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 26, 'name' => 'Odisha', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 27, 'name' => 'Puducherry', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 28, 'name' => 'Punjab', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 29, 'name' => 'Rajasthan', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 31, 'name' => 'Sikkim', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 32, 'name' => 'Tamil Nadu', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 33, 'name' => 'Telangana', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 34, 'name' => 'Tripura', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 35, 'name' => 'Uttar Pradesh', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 36, 'name' => 'Uttarakhand', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 37, 'name' => 'West Bengal', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('states')->insert($states);
    }
}
