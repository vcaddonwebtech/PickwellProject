<?php

namespace App\Exports;

use App\Models\SalesPerson;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesLeadExport implements FromCollection, WithHeadings
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    { 
        $query = SalesPerson::with(['salseUserDetail', 'saleAssignUserDetail', 'prioritys', 'latestSalesPersonTask']);

        $salesPersonDetail = $query->get()->map(function ($salesPerson) {
            return [
                'Party Name' => $salesPerson->partyname ?? '',
                'Mobile' => $salesPerson->mobile_no ?? '',
                'Address' => $salesPerson->address ?? '',
                'Lead User' => $salesPerson->salseUserDetail->name ?? '',
                'Lead Assign User' => $salesPerson->saleAssignUserDetail->name ?? '',
                'Lead Date & Time' => date('d-m-Y', strtotime($salesPerson->date)) . ' ' . $salesPerson->time ?? '',
                'Remind Date & Time' => date('d-m-Y', strtotime($salesPerson->next_reminder_date)) . ' ' . ($salesPerson->next_reminder_time ?? '') ?? '',
                'Status' => $salesPerson->prioritys->priority ?? '',
                'Comments' => $salesPerson->latestSalesPersonTask->comment_first ?? '',
            ];
        });

        return $salesPersonDetail;
    }

    public function headings(): array
    {
        return [
            'Party Name',
            'Mobile',
            'Address',
            'Lead User',
            'Lead Assign User',
            'Lead Date & Time',
            'Remind Date & Time',
            'Status',
            'Comments'
        ];
    }
}
