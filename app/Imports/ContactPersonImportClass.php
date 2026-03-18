<?php

namespace App\Imports;

use App\Models\ContactPerson;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContactPersonImportClass implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(!empty($row['contact_person'])) {
            $contactPerson = ContactPerson::where('name', $row['contact_person'])->first();
            if(empty($contactPerson)) {
                return new ContactPerson([
                    'name' => $row['contact_person'],
                ]);
            }
        }
    }
}
