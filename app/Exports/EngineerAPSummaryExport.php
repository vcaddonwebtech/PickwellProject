<?php

namespace App\Exports;

use App\Models\Attendtl;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class EngineerAPSummaryExport implements FromCollection, WithHeadings
{

    protected $data;

    public function __construct($data)
    {
        //$this->main_machine_type = $data['main_machine_type'];
        $this->fmonth = $data['fmonth'];
        $this->engineer_id = $data['engineer_id'];
       // dd($data['main_machine_type']);
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    { 
        //$main_machine_type = $this->main_machine_type;
        $engineer_id = $this->engineer_id;
        // Mobile sends 0–11 → convert to 1–12
        if($this->fmonth == "9" || $this->fmonth == "11") {
            $month = ((int) $this->fmonth);
        } else {
             $month = ((int) $this->fmonth);
        }
        
        $currentMonth = Carbon::createFromDate(null, $month, 1);
        $startDate = $currentMonth->copy()->startOfMonth();
        $endDate = $currentMonth->copy()->endOfMonth();
        
        $query = Attendtl::query()
            ->join('users', 'attendtl.engineer_id', '=', 'users.id')
            //->join('userwise_machines', 'users.id', '=', 'userwise_machines.user_id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->with(['users.roles'])
            ->selectRaw("
                attendtl.engineer_id,
                attendtl.in_date,
                attendtl.in_time,
                attendtl.out_time,
                attendtl.late_hrs,
                attendtl.working_hrs
            ")
            //->where('userwise_machines.main_machine_type', $main_machine_type)
            ->where('attendtl.engineer_id', $engineer_id)
            ->where(function ($q) {
                $q->where('model_has_roles.role_id', 4)
                ->orWhere('model_has_roles.role_id', 5)
                ->orWhere('model_has_roles.role_id', 6)
                ->orWhere('model_has_roles.role_id', 7)
                ->orWhere('model_has_roles.role_id', 10);
            })
            //->groupBy('attendtl.engineer_id', 'userwise_machines.main_machine_type')
                    ->whereBetween('in_date', [$startDate, $endDate]);

        $userDetail = $query->get()->map(function ($user) {
            return [
                'Name' => $user->users->name ?? '',
                'Role' => $user->users->roles[0]->name ?? '',
                'Date' => (string) ($user->in_date ?? 0),
                'In Time' => (string) ($user->in_time ?? 0),
                'Out Time' => (string) ($user->out_time ?? 0),
                'Late Hrs' => (string) ($user->late_hrs ?? 0),
                'Working Hrs' => (string) ($user->working_hrs ?? 0),
            ];

        });

        return $userDetail;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Role',
            'Date',
            'In Time',
            'Out Time',
            'Late Hrs',
            'Working Hrs'
        ];
    }
}
