<?php

namespace App\Exports;

use App\Models\Complaint;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class ComplaintStatusExport implements FromCollection, WithHeadings
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    { 
        $query = Complaint::select('id', 'date', 'time', 'complaint_no', 'party_id', 'sales_entry_id', 'product_id',  'complaint_type_id', 'service_type_id', 'status_id', 'engineer_id', 'engineer_out_date')
                ->with('party', 'product', 'complaintType', 'serviceType', 'status', 'engineer', 'salesEntry')
                ->orderBy('updated_at', 'desc')->latest();

        $complaintDetail = $query->get()->map(function ($complaint) {
            $date = Carbon::parse($complaint->date);
            return [
                'Date' => date('d-m-Y', strtotime($complaint->date)) ?? '',
                'Engineer Out Date' => date('d-m-Y', strtotime($complaint->engineer_out_date)) ?? '',
                'Time' => $complaint->time ?? '',
                'Complaint No' => $complaint->complaint_no ?? '',
                'Party' => $complaint->party->name ?? '',
                'Address' => $complaint->party->address ?? '',
                'Mobile No.' => $complaint->party->phone_no ?? '',
                'Area' => $complaint->party->area->name ?? '',
                'Product' => $complaint->product->name ?? '',
                'Product Serial' => $complaint->salesEntry->serial_no ?? '',
                'Mc no.' => $complaint->salesEntry->mc_no ?? '',
                'Complaint Type.' => $complaint->complaintType->name ?? '',
                'Service Type.' => $complaint->serviceType->name ?? '',
                'Status' => $complaint->status->name ?? '',
                'Engineer Name' => $complaint->engineer->name ?? '',
                'Days' => $date->diffForHumans() ?? '',
            ];
        });

        return $complaintDetail;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Engineer Out Date',
            'Time',
            'Complaint No',
            'Party',
            'Address',
            'Mobile No.',
            'Area',
            'Product',
            'Product Serial',
            'Mc no.',
            'Complaint Type.',
            'Service Type.',
            'Status',
            'Engineer Name',
            'Days',
        ];
    }
}
