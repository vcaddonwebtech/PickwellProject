<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ImportData implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $key => $data) {
            if(isset($data['first_name']) && isset($data['last_name']) && isset($data['department']) && isset($data['mobileno'])) {
                $user = User::create([
                    'name' => $data['first_name'].' '.$data['last_name'],
                    'email' => 'addonwebtech'.$key.'@gmail.com',
                    'password' => Hash::make('addonwebtech@gmail.com'),
                    'phone_no' => $data['mobileno'],
                    'role' => 'engineer',
                ]);
                $user->assignRole('engineer');
            }
        }

        return true;
    }
}
