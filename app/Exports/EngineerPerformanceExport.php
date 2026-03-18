<?php

namespace App\Exports;

use App\Models\Complaint;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EngineerPerformanceExport implements FromCollection, WithHeadings
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    { 
        $query = Complaint::selectRaw('engineer_id,engineer_out_date,COUNT(party_id) as party_count,SUM(TIME_TO_SEC(engineer_time_duration)) as total_time_duration_seconds,COUNT(id) as total_complaints')
                    ->groupBy('engineer_id', 'engineer_out_date')
                    ->whereNotNull('engineer_out_date')
                    ->with('engineerDetail')
                    ->orderByDesc('engineer_out_date');

        $complaintDetail = $query->get()->map(function ($complaint) {
            $totalDurationInSeconds = $complaint->total_time_duration_seconds;
            $hours = floor($totalDurationInSeconds / 3600);
            $minutes = floor(($totalDurationInSeconds % 3600) / 60);
            $seconds = $totalDurationInSeconds % 60;
            $complaint->total_time_duration = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
            

            return [
                'Out Date' => date('d-m-Y', strtotime($complaint->engineer_out_date)) ?? '',
                'Engineer Name' => $complaint->engineerDetail->name ?? '',
                'Attend Party' => $complaint->done_count ?? '',
                'Work Duration Hrs' => $complaint->total_time_duration ?? '',
                'Att In Time' => $complaint->engineerDetail->duty_start ?? '',
                'Att Out Time' => $complaint->engineerDetail->duty_end ?? '',
                'Att Duration Hrs' => $complaint->engineerDetail->duty_hours ?? '',
            ];
        });

        return $complaintDetail;
    }

    public function headings(): array
    {
        return [
            'Out Date',
            'Engineer Name',
            'Attend Party',
            'Work Duration Hrs',
            'Att In Time',
            'Att Out Time',
            'Att Duration Hrs',
        ];
    }
}
