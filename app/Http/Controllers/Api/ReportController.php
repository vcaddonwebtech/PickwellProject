<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\MachineSalesEntry;
use App\Models\User;
use App\Models\SalesPerson;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Get the today report
    public function reportToday($date = null)
    {
        $report_date = isset($date) ? $date : date('Y-m-d');

        $total_pending_complaints = Complaint::with('party', 'product', 'machineSalesEntry', 'engineer')->wherehas('status', function ($q) {
            $q->where('id', '!=', 3);
        })->get()->toArray();
        $total_todays_complaints = Complaint::with('party', 'product', 'machineSalesEntry', 'engineer')->whereDate('date', $report_date)->get()->toArray();
        $todays_total_dones = Complaint::with('party', 'product', 'machineSalesEntry', 'engineer')->where('engineer_out_date', '=',  $report_date)->wherehas('status', function ($q) {
            $q->where('id', 3);
        })->get()->toArray();

        $data = [
            'title' => 'Today Report',
            'total_pending_complaints' => $total_pending_complaints,
            'total_todays_complaints' => $total_todays_complaints,
            'todays_total_dones' => $todays_total_dones,
            'report_date' => $report_date
        ];

        return response()->json([
            "success" => true,
            "message" => "Dashboard status",
            "data" => $data,
        ], 200);
    }

    public function todayExpiry(Request $request)
    {
        $machineSales = MachineSalesEntry::with('party', 'product', 'serviceType')->where('is_active', 1)->where('service_expiry_date', date('Y-m-d'));
        if (!empty($request->party_id)) {
            $machineSales = $machineSales->where('party_id', $request->party_id);
        }
        $machineSales = $machineSales->get();

        if (count($machineSales) > 0) {
            return response()->json([
                "success" => true,
                "message" => "Machine sales get successfully",
                "data" => $machineSales,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Machine sales not found",
                "data" => [],
            ], 200);
        }
    }

    public function freeEngineers()
    {
        $freeEngineers = User::role('engineer')
            ->leftJoin('complaints', function ($join) {
                $join->on('users.id', '=', 'complaints.engineer_id')
                    ->where(function ($query) {
                        $query->where('complaints.status_id', '!=', 3)
                            ->orWhereNull('complaints.status_id'); // Handle cases with no complaints
                    });
            })
            ->whereHas('isPresent', function($query) {
                $query->where('in_date', date('Y-m-d'))
                    ->where('ap', 'P');
            })
            ->select('users.id', 'users.name', DB::raw('COUNT(complaints.id) as pending_complaints'))
            ->where('users.is_active', 1) 
            ->groupBy('users.id', 'users.name')
            ->orderBy('pending_complaints', 'asc')
            ->get();

        if (count($freeEngineers) > 0) {
            return response()->json([
                "success" => true,
                "message" => "Free engineers get successfully",
                "data" => $freeEngineers,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Free engineers not found",
                "data" => [],
            ], 200);
        }
    }

    public function tlwiseFreeEngineers(Request $request)
    {
        $main_machine_type = $request->main_machine_type;
        $freeEngineers = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Engineer']); // show only engineers to managers
                    })
                ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                $query->where('main_machine_type', $main_machine_type);
                })->leftJoin('complaints', function ($join) {
                $join->on('users.id', '=', 'complaints.engineer_id')
                    ->where(function ($query) {
                        $query->where('complaints.status_id', '!=', 3)
                            ->orWhereNull('complaints.status_id'); // Handle cases with no complaints
                    });
            })
            ->whereHas('isPresent', function($query) {
                $query->where('in_date', date('Y-m-d'))
                    ->where('ap', 'P');
            })
            ->select('users.id', 'users.name', DB::raw('COUNT(complaints.id) as pending_complaints'))
            ->where('users.is_active', 1) 
            ->groupBy('users.id', 'users.name')
            ->orderBy('pending_complaints', 'asc')
            ->get();

        if (count($freeEngineers) > 0) {
            return response()->json([
                "success" => true,
                "message" => "Free engineers get successfully",
                "data" => $freeEngineers,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Free engineers not found",
                "data" => [],
            ], 200);
        }
    }

    public function reportSalesSummary() {
        $salesPerson = SalesPerson::with('saleAssignUserDetail')
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

        foreach ($salesPerson as $sale) {
            $totalDurationInSeconds = $sale->total_time_duration_seconds;
            $hours = floor($totalDurationInSeconds / 3600);
            $minutes = floor(($totalDurationInSeconds % 3600) / 60);
            $seconds = $totalDurationInSeconds % 60;
            $sale->total_time_duration = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
        }

        if (count($salesPerson) > 0) {
            return response()->json([
                "success" => true,
                "message" => "Sales Summary get successfully",
                "data" => $salesPerson,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Sales Summary not found",
                "data" => [],
            ], 200);
        }
    }
}
