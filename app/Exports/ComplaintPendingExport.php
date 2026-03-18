<?php

namespace App\Exports;

use App\Models\Complaint;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ComplaintPendingExport implements FromCollection, WithHeadings
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    { 
        $query = Complaint::select('id', 'date', 'time', 'complaint_no', 'party_id', 'sales_entry_id', 'product_id',  'complaint_type_id', 'service_type_id', 'status_id', 'engineer_id', 'engineer_out_date', 'engineer_time_duration')
                ->where('status_id', '!=', '3')
                ->orderBy('id', 'desc')
                ->with('party', 'product', 'complaintType', 'serviceType', 'status', 'engineer', 'salesEntry')->latest();

        $complaintDetail = $query->get()->map(function ($complaint) {
            return [
                'Complain Date' => date('d-m-Y', strtotime($complaint->date)) ?? '',
                'Complaint No' => $complaint->complaint_no ?? '',
                'Party Code' => $complaint->party->code ?? '',
                'Party Name' => $complaint->party->name ?? '',
                'Party Phone' => $complaint->party->phone_no ?? '',
                'Engineer Name' => $complaint->engineer->name ?? '',
                'Area' => $complaint->party->area->name ?? '',
                'Complaint Type' => $complaint->complaintType->name ?? '',
                'Status' => $complaint->status->name ?? '',
            ];
        });

        return $complaintDetail;
    }

    public function headings(): array
    {
        return [
            'Complain Date',
            'Complaint No',
            'Party Code',
            'Party Name',
            'Party Phone',
            'Engineer Name',
            'Area',
            'Complaint Type',
            'Status'
        ];
    }
}
