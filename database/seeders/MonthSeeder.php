<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('months')->insert([
            [
                'name' => 'January',
                'newid' => 1,
                'tag' => 1,
            ],
            [
                'name' => 'February',
                'newid' => 2,
                'tag' => 1,
            ],
            [
                'name' => 'March',
                'newid' => 3,
                'tag' => 1,
            ],
            [
                'name' => 'April',
                'newid' => 4,
                'tag' => 1,
            ],
            [
                'name' => 'May',
                'newid' => 5,
                'tag' => 1,
            ],
            [
                'name' => 'June',
                'newid' => 6,
                'tag' => 1,
            ],
            [
                'name' => 'July',
                'newid' => 7,
                'tag' => 1,
            ],
            [
                'name' => 'August',
                'newid' => 8,
                'tag' => 1,
            ],
            [
                'name' => 'September',
                'newid' => 9,
                'tag' => 1,
            ],
            [
                'name' => 'October',
                'newid' => 10,
                'tag' => 1,
            ],
            [
                'name' => 'November',
                'newid' => 11,
                'tag' => 1,
            ],
            [
                'name' => 'December',
                'newid' => 12,
                'tag' => 1,
            ],
        ]);
    }
}
