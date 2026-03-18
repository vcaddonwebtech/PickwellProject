<?php

namespace App\Exports;

use App\Models\Complaint;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ComplaintTypeSummaryExport implements FromCollection, WithHeadings
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    { 
        $query = Complaint::select('complaints.complaint_type_id', 'complaint_types.name as complaint_type_name', \DB::raw('count(*) as count'))
                ->join('complaint_types', 'complaints.complaint_type_id', '=', 'complaint_types.id')
                ->groupBy('complaints.complaint_type_id', 'complaint_types.name');

        $complaintDetail = $query->get()->map(function ($complaint) {
            return [
                'Complaint Type' => $complaint->complaint_type_name ?? '',
                'Count' => $complaint->count ?? '',
            ];
        });

        return $complaintDetail;
    }

    public function headings(): array
    {
        return [
            'Complaint Type',
            'Count',
        ];
    }
}
