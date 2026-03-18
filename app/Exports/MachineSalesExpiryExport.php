<?php

namespace App\Exports;

use App\Models\MachineSalesEntry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class MachineSalesExpiryExport implements FromCollection, WithHeadings
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    { 
        
        $query = MachineSalesEntry::with('party', 'product')->get();

        $machines = $query->sortBy(function ($row) {
            $startDate = Carbon::today();
            $endDate = Carbon::parse($row->service_expiry_date);
            return $startDate->diffInDays($endDate);
        });

        $machineSaleExpiryDetail = $machines->map(function ($machineSales) {
            $startDate = Carbon::today();
            $endDate = Carbon::parse($machineSales->service_expiry_date);
            return [
                'Expire Date' => date('d-m-Y', strtotime($machineSales->service_expiry_date)) ?? '',
                'Party' => $machineSales->party->name ?? '',
                'Mobile No.' => $machineSales->party->phone_no ?? '',
                'Address' => $machineSales->party->address ?? '',
                'Product' => $machineSales->product->name . ' - ' . $machineSales->serial_no . ' - ' . $machineSales->mc_no ?? '',
                'Expriy Day' => $startDate->diffInDays($endDate) ?? '',
            ];
        });

        return $machineSaleExpiryDetail;
    }

    public function headings(): array
    {
        return [
            'Expire Date',
            'Party',
            'Mobile No.',
            'Address',
            'Product',
            'Expriy Day',
        ];
    }
}
