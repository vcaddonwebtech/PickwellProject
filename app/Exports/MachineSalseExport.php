<?php

namespace App\Exports;

use App\Models\MachineSalesEntry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MachineSalseExport implements FromCollection, WithHeadings
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
        $query = $data['machine'] = MachineSalesEntry::with('party', 'product',  'serviceType', 'micFittingEngineer', 'deliveryEngineer')->orderBy('date', 'desc')->latest();

        if (isset($this->requestData['from_date'])) {
            $query->where('date', '>=', $this->requestData['from_date']);
        }

        if (isset($this->requestData['to_date'])) {
            $query->where('date', '<=', $this->requestData['to_date']);
        }

        if (isset($this->requestData['party_id'])) {
            $query->where('party_id', '<=', $this->requestData['party_id']);
        }

        if (isset($this->requestData['area_id'])) {
            $query->whereHas('party', function ($query) {
                $query->where('area_id', $this->requestData['area_id']);
            });

            // $freeServices->whereHas('complaints', function ($query) {
            //     $query->whereNull('engineer_out_date');
            // });
            // $query->where('product_id', '<=', $this->requestData['area_id']);
        }

        if (isset($this->requestData['product_id'])) {
            $query->where('product_id', '<=', $this->requestData['product_id']);
        }

        if (isset($this->requestData['is_active'])) {
            $query->where('is_active', '<=', $this->requestData['is_active']);
        }

        $machineSalseDetail = $query->get()->map(function ($machineSalse) {
            return [
                'Date' => isset($machineSalse->date) ? date('d-m-Y', strtotime($machineSalse->date)) : '',
                'Order No' => $machineSalse->order_no ?? '',
                'Party' => $machineSalse->party->name ?? '',
                'Mobile No' => $machineSalse->party->phone_no ?? '',
                'Address' => $machineSalse->party->address ?? '',
                'Area' => $machineSalse->party->area->name ?? '',
                'Product Name' => $machineSalse->product->name . ' ' . $machineSalse->serial_no . ' ' . $machineSalse->mc_no,
                'Serial No' => $machineSalse->serial_no ?? '',
                'M/c No' => $machineSalse->mc_no ?? '',
                'Install Date' => isset($machineSalse->install_date) ? date('d-m-Y', strtotime($machineSalse->install_date)) : '',
                'Service Expirty Date' => isset($machineSalse->service_expiry_date) ? date('d-m-Y', strtotime($machineSalse->service_expiry_date)) : '',
                'Free Service' => $machineSalse->free_service ?? '',
                'Service Type' => $machineSalse->serviceType->name,
                'Is Active' => isset($machineSalse->is_active) ? 'Active' : 'Not Active',
            ];
        });

        return $machineSalseDetail;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Order No',
            'Party',
            'Mobile No',
            'Address',
            'Area',
            'Product Name',
            'Serial No',
            'M/c No',
            'Install Date',
            'Service Expirty Date',
            'Free Service',
            'Service Type',
            'Is Active',
        ];
    }
}
