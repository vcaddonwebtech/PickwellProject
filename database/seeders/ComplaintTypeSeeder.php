<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplaintTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("complaint_types")->delete();
        DB::table("complaint_types")->insert([
            [
                'name' => 'auto last + 1',
                'description' => 'Complaint1 desc',
            ],
            [
                'name' => 'Firm Complaint',
                'description' => 'Complaint2 desc',
            ],
            [
                'name' => 'auto last + 2',
                'description' => 'Complaint3 desc',
            ],
        ]);
    }
}
