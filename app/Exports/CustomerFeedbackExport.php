<?php

namespace App\Exports;

use App\Models\CustomerFeedback;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class CustomerFeedbackExport implements FromCollection, WithHeadings
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    { 
        
        $query = CustomerFeedback::with('party','engineer')->latest();

        $machineSaleExpiryDetail = $query->get()->map(function ($feedback) {
            return [
                'Party' => $feedback->party->name ?? '',
                'Engineer' => $feedback->engineer->name ?? '',
                'Date' => date('d-m-Y', strtotime($feedback->date)) ?? '',
                'Rating' => $feedback->star_rating. ' ★' ?? '',
                'Remark' => $feedback->remark ?? '',
            ];
        });

        return $machineSaleExpiryDetail;
    }

    public function headings(): array
    {
        return [
            'Party',
            'Engineer',
            'Date',
            'Rating',
            'Remark',
        ];
    }
}
