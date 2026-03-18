<?php

namespace App\Exports;

use App\Models\Complaint;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EngineerDoneSummaryExport implements FromCollection, WithHeadings
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    { 
        $query = Complaint::selectRaw('engineer_id, CONCAT(MONTHNAME(engineer_out_date), " - ", YEAR(engineer_out_date)) as month_year, COUNT(*) as done_count')
                            ->where('status_id', 3)
                            ->whereNotNull('engineer_out_date')
                            ->groupBy('engineer_id', 'month_year')
                            ->with('engineerDetail');

        $complaintDetail = $query->get()->map(function ($complaint) {
            return [
                'Engineer Name' => $complaint->engineerDetail->name ?? '',
                'Month - Year' => $complaint->month_year ?? '',
                'Count' => $complaint->done_count ?? '',
            ];
        });

        return $complaintDetail;
    }

    public function headings(): array
    {
        return [
            'Engineer Name',
            'Month - Year',
            'Count',
        ];
    }
}
