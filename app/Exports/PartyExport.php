<?php

namespace App\Exports;

use App\Models\Party;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PartyExport implements FromCollection, WithHeadings
{

    protected $main_machine_type;

    // 👇 Accept the variable in constructor
    public function __construct($main_machine_type)
    {
        $this->main_machine_type = $main_machine_type;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    { 
        //$query = Party::latest(); // $this->main_machine_type
        $main_machine_type = $this->main_machine_type;
        $query = Party::whereHas('partywiseMachines', function ($query) use ($main_machine_type) {
            $query->where('main_machine_type', $main_machine_type);
        })->latest();

        $partyDetail = $query->get()->map(function ($party) {
            return [
                'Code' => $party->code ?? '',
                'Name' => $party->name ?? '',
                'Address' => $party->address ?? '',
                'Mobile No' => $party->phone_no ?? '',
                'Contact Person' => $party->contact_person ?? '',
                'Owner' => $party->owner_name ?? '',
                'Id' => $party->id ?? '',
            ];
        });

        return $partyDetail;
    }

    public function headings(): array
    {
        return [
            'Code',
            'Name',
            'Address',
            'Mobile No',
            'Contact Person',
            'Owner',
            'Id',
        ];
    }
}
