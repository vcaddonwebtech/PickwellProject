<?php

namespace App\Exports;

use App\Models\User;
use App\Models\UserwiseMachine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class APTodayExport implements FromCollection, WithHeadings
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
        $todayDate = date('Y-m-d');
        $main_machine_type = $this->main_machine_type;
        $query = User::leftJoin('attendtl', function ($join) use ($todayDate) {
                $join->on('users.id', '=', 'attendtl.engineer_id')
                    ->where('attendtl.in_date', '=', $todayDate);
            })->whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Manager', 'Service Team Leader', 'Engineer']); // show only engineers to managers
                })
                ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                    $query->where('main_machine_type', $main_machine_type);
                    })
            ->leftJoin('leaves', function ($join) use ($todayDate) {
                $join->on('users.id', '=', 'leaves.user_id')
                    ->where('leaves.is_approved', 1)
                    ->whereDate('leaves.leave_from', '<=', $todayDate)
                    ->whereDate('leaves.leave_till', '>=', $todayDate);
            })
            ->select(
                'users.*',
                'attendtl.engineer_id',
                'attendtl.in_time',
                'attendtl.out_time',
                'attendtl.in_date',
                'attendtl.out_date',
                'attendtl.ap',
                'attendtl.late_hrs',
                'attendtl.earligoing_hrs',
                'attendtl.working_hrs',
                'attendtl.pdays',
                'attendtl.in_address',
                'attendtl.out_address',
                \DB::raw(
                    'CASE 
                        WHEN attendtl.id IS NOT NULL THEN attendtl.ap
                        WHEN leaves.id IS NOT NULL THEN "L"
                        ELSE "A"
                    END as attendance_status'
                )
            )
            ->where('users.is_active', 1)
            ->orderBy('users.name', 'ASC');

        $userDetail = $query->get()->map(function ($user) {
            $pendingComplaintsCount = \DB::table('complaints')
                ->where('engineer_id', $user->id)
                ->where('status_id', 1)
                ->count() ?? 0;

            $inProgressComplaintsCount = \DB::table('complaints')
                ->where('engineer_id', $user->id)
                ->where('status_id', 2)
                ->count() ?? 0;

            $closedComplaintsCount = \DB::table('complaints')
                ->where('engineer_id', $user->id)
                ->where('status_id', 3)
                ->where('engineer_out_date', date('Y-m-d'))
                ->count() ?? 0;

            return [
                'Engineer Name' => $user->name ?? '',
                'Designation' => $user->getRoleNames()->implode(', ') ?? '',
                'AP' => $user->attendance_status ?? '',
                'In Time' => $user->in_time ?? '',
                'Out Time' => $user->out_time ?? '',
                'Pending ' => (string) $pendingComplaintsCount,
                'In Progress ' => (string) $inProgressComplaintsCount,
                'Today Done ' => (string) $closedComplaintsCount,
            ];
        });

        return $userDetail;
    }

    public function headings(): array
    {
        return [
            'Engineer Name',
            'Designation',
            'AP',
            'In Time',
            'Out Time',
            'Pending',
            'In Progress',
            'Today Done'
        ];
    }
}
