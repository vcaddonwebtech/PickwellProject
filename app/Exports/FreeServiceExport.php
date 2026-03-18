<?php

namespace App\Exports;

use App\Models\MachineSalesEntry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FreeServiceExport implements FromCollection, WithHeadings
{
    private $requestData;

    public function __construct($requestData)
    {
        $this->requestData = $requestData;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = MachineSalesEntry::where(['is_active' => 1])->where('free_service_date', '!=', null)
            ->with('party', 'product', 'complaints');
        
        if (isset($this->requestData['free_service_date_from'])) {
            $query->where('free_service_date', '>=', $this->requestData['free_service_date_from']);
        }

        if (isset($this->requestData['free_service_date_to'])) {
            $query->where('free_service_date', '<=', $this->requestData['free_service_date_to']);
        }

        $freeServices = $query->get()->map(function ($freeService) {
            return [
                'Free Service Date' => isset($freeService->free_service_date) ? date('d-m-Y', strtotime($freeService->free_service_date)) : '',
                'Complaint Date' => isset($freeService->complaints->date) ? date('d-m-Y', strtotime($freeService->complaints->date)) : '',
                'Complaint No' => $freeService->complaints->complaint_no ?? '',
                'Party' => $freeService->party->name ?? '',
                'Mobile No' => $freeService->party->phone_no ?? '',
                'Address' => $freeService->party->address ?? '',
                'Product' => $freeService->product->name . ' ' . $freeService->serial_no . ' ' . $freeService->mc_no,
                'Eng Out Date' => isset($freeService->complaints->engineer_out_date) ? date('d-m-Y', strtotime($freeService->complaints->engineer_out_date)) : '',
                'Status' => isset($freeService->complaints->engineer_out_date) ? 'Closed' : 'Pending',
            ];
        });

        return $freeServices;
    }

    public function headings(): array
    {
        return [
            'Free Service Date',
            'Complaint Date',
            'Complaint No',
            'Party',
            'Mobile No',
            'Address',
            'Product',
            'Eng Out Date',
            'Status',
        ];
    }
}
