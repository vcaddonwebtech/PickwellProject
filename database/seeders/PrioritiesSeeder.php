<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrioritiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priorities = [
            ['priority' => 'Low'],
            ['priority' => 'Medium'],
            ['priority' => 'High'],
            ['priority' => 'Cancel'],
            ['priority' => 'Delivered'],
            ['priority' => 'Booked'],
            ['priority' => 'Under Process'],
            ['priority' => 'Done']
        ];

        DB::table('priorities')->insert($priorities);
    }
}
