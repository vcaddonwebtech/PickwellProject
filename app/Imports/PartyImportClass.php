<?php

namespace App\Imports;

use App\Models\Party;
use App\Models\ContactPerson;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class PartyImportClass implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(!empty($row['customer_no'])) {
            $party = Party::where('code', $row['customer_no'])->first();
            if(empty($party)) {
                $contactPerson = ContactPerson::where('name', $row['contact_person'])->first();
                return new Party([
                    'code' => $row['customer_no'],
                    'name' =>  preg_replace('/\[.*?\]/', '', $row['comapany_name']),
                    'address' => $row['address'],
                    'city_id' => 1,
                    'state_id' => 11,
                    'phone_no' => !empty($row['mobile_no']) ? $row['mobile_no'] : 0000000000,
                    'other_phone_no' => $row['other_mobile_no'],
                    'password' => Hash::make(!empty($row['mobile_no']) ? $row['mobile_no'] : 0000000000),
                    'contact_person_id' => !empty($contactPerson) ? $contactPerson->id : 2,
                    'owner_id' => 1,
                    'area_id' => 1,
                    'firm_id' => 1
                ]);
            }
        }
    }
}
