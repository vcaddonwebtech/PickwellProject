<?php

namespace App\Exports;

use App\Models\SalesPerson;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesSummaryExport implements FromCollection, WithHeadings
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    { 
        $query = SalesPerson::with(['saleAssignUserDetail'])
        ->selectRaw('
            partyname,
            sale_assign_user_id,
            count(*) as party_count,
            SUM(TIME_TO_SEC(time_duration)) as total_time_duration_seconds,
            MAX(created_at) as latest_created_at
        ')
        ->groupBy('partyname', 'sale_assign_user_id')
        ->orderBy('latest_created_at', 'desc')
        ->get();
        
        $complaintDetail = $query->sortBy(function ($complaint) {
            return $complaint->saleAssignUserDetail->name ?? '';
        })->map(function ($complaint) {
            $totalDurationInSeconds = $complaint->total_time_duration_seconds;
            $hours = floor($totalDurationInSeconds / 3600);
            $minutes = floor(($totalDurationInSeconds % 3600) / 60);
            $seconds = $totalDurationInSeconds % 60;
            $complaint->total_time_duration = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
        
            return [
                'User' => $complaint->saleAssignUserDetail->name ?? '',
                'Attend Party' => $complaint->party_count ?? '',
                'Time' => $complaint->total_time_duration ?? '',
            ];
        });
        
        return $complaintDetail;
    }

    public function headings(): array
    {
        return [
            'User',
            'Attend Party',
            'Time',
        ];
    }
}
