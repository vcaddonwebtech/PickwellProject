<?php

namespace App\Exports;

use App\Models\Attendtl;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class APSummaryExport implements FromCollection, WithHeadings
{

    protected $main_machine_type;

    public function __construct($main_machine_type)
    {
        $this->main_machine_type = $main_machine_type;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    { 
        $currentDate = Carbon::now();
        $startDate = $currentDate->copy()->startOfMonth();
        $endDate = $currentDate->copy()->endOfMonth();
        $main_machine_type = $this->main_machine_type;
        // $query = Attendtl::with(['users.roles'])
        //             ->selectRaw("
        //                 attendtl.engineer_id,
        //                 SUM(CASE WHEN ap = 'p' THEN 1 ELSE 0 END) as count_p,
        //                 SUM(CASE WHEN ap = 'l' THEN 1 ELSE 0 END) as count_l,
        //                 SUM(CASE WHEN ap = 'a' THEN 1 ELSE 0 END) as count_a,
        //                 SUM(CASE WHEN ap = 'h' THEN 1 ELSE 0 END) as count_h,
        //                 SUM(pdays) as total_pdays
        //             ")
        //             ->groupBy('attendtl.engineer_id')
        //             ->whereBetween('in_date', [$startDate, $endDate]);

        $query = Attendtl::query()
            ->join('users', 'attendtl.engineer_id', '=', 'users.id')
            ->join('userwise_machines', 'users.id', '=', 'userwise_machines.user_id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->with(['users.roles'])
            ->selectRaw("
                attendtl.engineer_id,
                userwise_machines.main_machine_type,
                SUM(CASE WHEN ap = 'p' THEN 1 ELSE 0 END) as count_p,
                SUM(CASE WHEN ap = 'l' THEN 1 ELSE 0 END) as count_l,
                SUM(CASE WHEN ap = 'a' THEN 1 ELSE 0 END) as count_a,
                SUM(CASE WHEN ap = 'h' THEN 1 ELSE 0 END) as count_h,
                SUM(pdays) as total_pdays
            ")
            ->where('userwise_machines.main_machine_type', $main_machine_type)
            ->where(function ($q) {
                $q->where('model_has_roles.role_id', 4)
                ->orWhere('model_has_roles.role_id', 7);
            })
            ->groupBy('attendtl.engineer_id', 'userwise_machines.main_machine_type')
                    ->whereBetween('in_date', [$startDate, $endDate]);

        $userDetail = $query->get()->map(function ($user) {
            $pendingComplaintsCount = \DB::table('complaints')
                ->where('engineer_id', $user->engineer_id)
                ->where('status_id', 1)
                ->count() ?? 0; // Ensure 0

            $inProgressComplaintsCount = \DB::table('complaints')
                ->where('engineer_id', $user->engineer_id)
                ->where('status_id', 2)
                ->count() ?? 0; // Ensure 0

            $closedComplaintsCount = \DB::table('complaints')
                ->where('engineer_id', $user->engineer_id)
                ->where('status_id', 3)
                ->where('engineer_out_date', date('Y-m-d'))
                ->count() ?? 0; // Ensure 0

            return [
                'Name' => $user->users->name ?? '',
                'Role' => $user->users->roles[0]->name ?? '',
                'Total Present' => (string) ($user->count_p ?? 0),
                'Total Half Day' => (string) ($user->count_h ?? 0),
                'Total Leaves' => (string) ($user->count_l ?? 0),
                'Total Absent' => (string) ($user->count_a ?? 0),
                'Pending Complaints' => (string) $pendingComplaintsCount,
                'Inprogress Complaints' => (string) $inProgressComplaintsCount,
                'Today Done Complaints' => (string) $closedComplaintsCount,
            ];

        });

        return $userDetail;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Role',
            'Total Present',
            'Total Half Day',
            'Total Leaves',
            'Total Absent',
            'Pending Complaints',
            'Inprogress Complaints',
            'Today Done Complaints'
        ];
    }
}
