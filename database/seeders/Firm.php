<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Firm extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('firms')->insert([
            'name' => 'om satya enterprise',
            'sh_code' => 'ome',
            'address' => 'G-18 To G-20, A.P.M.C. Market, Krushi Bazar, Sahara Darwaja, Surat',
            'city_id' => '8',
            'state_id' => '11',
            'pincode' => '395002',
            'phone_no' => '02612532507',
            'gst_no' => 'eddfsdtff4w34rsdwe3',
            'pan_no' => 'gfeswtc4s',
            'reg_no' => 'sdrf5sdfsdvf',
            'bank_name' => 'HDFC Bank',
            'branch_name' => 'udhana',
            'bank_account_no' => '0201457520022',
            'bank_ifsc_code' => 'WERFW745045',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
