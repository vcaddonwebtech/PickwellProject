<?php

namespace App\Http\Controllers;

use App\Exports\ComplaintExport;
use App\Exports\EngineerComplainDoneExport;
use App\Exports\FreeServiceExport;
use App\Exports\MachineSalseExport;
use App\Exports\SalesLeadExport;
use App\Exports\SalesSummaryExport;
use App\Exports\ToDoExport;
use App\Models\Complaint;
use App\Models\MachineSalesEntry;
use App\Models\Machine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\SalesPerson;
use App\Models\Todo;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    // Complain report
    public function reportComplaint(Request $request)
    {
        $data['title'] = 'Complain Report';

        if ($request->ajax()) {
            
            $complaints = Complaint::select('id', 'date', 'time', 'complaint_no', 'party_id', 'sales_entry_id', 'product_id',  'complaint_type_id', 'service_type_id', 'status_id', 'engineer_id', 'engineer_out_date')
                ->orderBy('id', 'desc')
                ->with('party', 'product', 'complaintType', 'serviceType', 'status', 'engineer', 'salesEntry');

            if (isset($request->date_range)) {
                [$startDate, $endDate] = explode(' to ', $request->date_range);
                $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
                $complaints->whereBetween('date', [$startDate, $endDate]);
            }

            if (isset($request->status_id)) {
                $complaints->where('status_id', $request->status_id);
            }

            if (isset($request->engineer_id)) {
                $complaints->where('engineer_id', $request->engineer_id);
            }

            if (isset($request->party_id)) {
                $complaints->where('party_id', $request->party_id);
            }

            $complaints = $complaints->get();

            return DataTables::of($complaints)
                ->addIndexColumn()
                ->addColumn("date", function ($row) {
                    return date('d-m-Y', strtotime($row->date));
                })
                ->addColumn('complaint_no', function ($row) {
                    return $row->complaint_no ?? 'N/A';
                })
                ->addColumn('party_code', function ($row) {
                    return $row->party->code ?? 'N/A';
                })
                ->addColumn('party_name', function ($row) {
                    return $row->party->name ?? 'N/A';
                })
                ->addColumn('party_phone', function ($row) {
                    return $row->party->phone_no ?? 'N/A';
                })
                ->addColumn('complaint_type', function ($row) {
                    return $row->complaintType->name ?? 'N/A';
                })
                ->addColumn('complaint_status', function ($row) {
                    return $row->status->name ?? 'N/A';
                })
                ->addColumn('complaint_area', function ($row) {
                    return $row->party->area->name ?? 'N/A';
                })
                ->addColumn('engineer_name', function ($row) {
                    return $row->engineer->name ?? 'N/A';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('report.complaint_report', $data);
    }

    // Pending complaints report
    public function reportComplaintPending(Request $request)
    {
        $data['title'] = 'Complain Pending Report';

        if ($request->ajax()) {
            $complaints = Complaint::select('id', 'date', 'time', 'complaint_no', 'party_id', 'sales_entry_id', 'product_id',  'complaint_type_id', 'service_type_id', 'status_id', 'engineer_id', 'engineer_out_date', 'engineer_time_duration')
                ->where('status_id', '!=', '3')
                ->orderBy('id', 'desc')
                ->with('party', 'product', 'complaintType', 'serviceType', 'status', 'engineer', 'salesEntry');

            if (isset($request->date_range)) {
                [$startDate, $endDate] = explode(' to ', $request->date_range);
                $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
                $complaints->whereBetween('date', [$startDate, $endDate]);
            }

            if (isset($request->party_id)) {
                $complaints->where('party_id', $request->party_id);
            }

            if (isset($request->engineer_id)) {
                $complaints->where('engineer_id', $request->engineer_id);
            }

            if (isset($request->status_id)) {
                $complaints->where('status_id', $request->status_id);
            }

            $complaints = $complaints->get();

            return DataTables::of($complaints)
                ->addIndexColumn()
                ->addColumn("date", function ($row) {
                    return date('d-m-Y', strtotime($row->date));
                })
                ->addColumn('complaint_no', function ($row) {
                    return $row->complaint_no ?? 'N/A';
                })
                ->addColumn('party_code', function ($row) {
                    return $row->party->code ?? 'N/A';
                })
                ->addColumn('party_name', function ($row) {
                    return $row->party->name ?? 'N/A';
                })
                ->addColumn('party_phone', function ($row) {
                    return $row->party->phone_no ?? 'N/A';
                })
                ->addColumn('complaint_type', function ($row) {
                    return $row->complaintType->name ?? 'N/A';
                })
                ->addColumn('complaint_status', function ($row) {
                    return $row->status->name ?? 'N/A';
                })
                ->addColumn('complaint_area', function ($row) {
                    return $row->party->area->name ?? 'N/A';
                })
                ->addColumn('engineer_name', function ($row) {
                    return $row->engineer->name ?? 'N/A';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('report.complaint_pending', $data);
    }

    // Engineer visit report
    public function reportEngineerVisit(Request $request)
    {
        $data['title'] = 'Engineer Complaints Done Report';

        if ($request->ajax()) {
            $complaints = Complaint::select('id', 'date', 'time', 'complaint_no', 'party_id', 'sales_entry_id', 'product_id',  'complaint_type_id', 'service_type_id', 'status_id', 'engineer_id', 'engineer_out_date', 'engineer_time_duration')
                ->where(['status_id' => '3'])
                ->orderBy('id', 'desc')
                ->with('party', 'product', 'complaintType', 'serviceType', 'status', 'engineer', 'salesEntry');

            if (isset($request->date_range)) {
                [$startDate, $endDate] = explode(' to ', $request->date_range);
                $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
                $complaints->whereBetween('engineer_out_date', [$startDate, $endDate]);
            }

            if (isset($request->party_id)) {
                $complaints->where('party_id', $request->party_id);
            }

            if (isset($request->engineer_id)) {
                $complaints->where('engineer_id', $request->engineer_id);
            }

            $complaints = $complaints->get();

            return DataTables::of($complaints)
                ->addIndexColumn()
                ->addColumn("date", function ($row) {
                    return date('d-m-Y', strtotime($row->date));
                })
                ->addColumn("engineer_out_date", function ($row) {
                    return isset($row->engineer_out_date) ? date('d-m-Y', strtotime($row->engineer_out_date)) : "";
                })
                ->addColumn('complaint_no', function ($row) {
                    return $row->complaint_no ?? 'N/A';
                })
                ->addColumn('party_code', function ($row) {
                    return $row->party->code ?? 'N/A';
                })
                ->addColumn('party_name', function ($row) {
                    return $row->party->name ?? 'N/A';
                })
                ->addColumn('party_phone', function ($row) {
                    return $row->party->phone_no ?? 'N/A';
                })
                ->addColumn('complaint_type', function ($row) {
                    return $row->complaintType->name ?? 'N/A';
                })
                ->addColumn('engineer_name', function ($row) {
                    return $row->engineer->name ?? 'N/A';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('report.engineer_visit_report', $data);
    }

    // Free serice report
    public function freeServiceReport(Request $request)
    {
        $data['title'] = 'Machine Free Service Report';
        if ($request->ajax()) {
            $freeServices = MachineSalesEntry::where(['is_active' => 1])->where('free_service_date', '!=', null)
                ->with('party', 'product', 'complaints');

            if (isset($request->date_range)) {
                [$startDate, $endDate] = explode(' to ', $request->date_range);
                $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
                $freeServices->whereBetween('free_service_date', [$startDate, $endDate]);
            }

            $freeServices = $freeServices->get();
            return DataTables::of($freeServices)
                ->addIndexColumn()
                ->addColumn('complaint_date', function ($row) {
                    return $row->complaints ? date('d-m-Y', strtotime($row->complaints->date)) : null;
                })
                ->addColumn('complaint_no', function ($row) {
                    return $row->complaints ? $row->complaints->complaint_no : null; // Check for null
                })
                ->addColumn('product', function ($row) {
                    return $row->product ? $row->product->name . ' - ' . $row->serial_no . ' - ' . $row->mc_no : null; // Check for null
                })
                ->addColumn("free_service_date", function ($row) {
                    return date('d-m-Y', strtotime($row->free_service_date));
                })
                ->addColumn('party', function ($row) {
                    return $row->party ? $row->party->name : null; // Check for null
                })
                ->addColumn('mobile_no', function ($row) {
                    return $row->party ? $row->party->phone_no : null; // Check for null
                })
                ->addColumn('address', function ($row) {
                    return $row->party ? $row->party->address : null; // Check for null
                })
                ->addColumn('engineer_out_date', function ($row) {
                    return $row->complaints && $row->complaints->engineer_out_date != null
                        ? date('d-m-Y', strtotime($row->complaints->engineer_out_date))
                        : null; // Check for null
                })
                // ->rawColumns(['product', 'free_service_date'])
                ->make(true);
        }
        return view('report.machine_free_service_report', $data);
    }

    public function machineSalseReport(Request $request)
    {
        $data['title'] = 'Machine Salse Report';
        if ($request->ajax()) {    
            $machineSalse = $data['machine'] = MachineSalesEntry::with('party', 'product',  'serviceType', 'micFittingEngineer', 'deliveryEngineer')->orderBy('date', 'desc')->latest();

            if (isset($request->date_range)) {
                [$startDate, $endDate] = explode(' to ', $request->date_range);
                $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
                $machineSalse->whereBetween('date', [$startDate, $endDate]);
            }

            if (isset($request->party_id)) {
                $machineSalse->where('party_id', $request->party_id);
            }
            if (isset($request->product_id)) {
                $machineSalse->where('product_id', $request->product_id);
            }
            if (isset($request->is_active)) {
                $machineSalse->where('is_active', $request->is_active);
            }
            if (isset($request->area_id)) {
                $machineSalse->whereHas('party', function ($query) use ($request) {
                    $query->where('area_id', $request->area_id);
                });
            }
            $machineSalse = $machineSalse->get();
            return DataTables::of($machineSalse)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = "<div class='btn-group m-1'><a class='btn btn-sm btn-primary' href='" . route('MachineSales.edit', $row->id) . "'><i class='fa fa-edit'></i></a>";
                    $btn .= "<a class='btn btn-sm btn-danger' href='javascript:void(0)' onclick='window.deleteParty(" . $row->id . ")'><i class='fa fa-trash'></i></a></div> ";
                    return $btn;
                })
                ->addColumn("install_date", function ($row) {
                    return date('d-m-Y', strtotime($row->install_date));
                })
                ->addColumn("service_expiry_date", function ($row) {
                    return date('d-m-Y', strtotime($row->service_expiry_date));
                })
                ->addColumn("date", function ($row) {
                    return date('d-m-Y', strtotime($row->date));
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('report.machine_salse_report', $data);
    }

    public function salseLeadReport($id, Request $request)
    {
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $data['title'] = 'Salse Lead Report';
        
        if ($request->ajax()) {
            if (isset($request->date_range)) {
                [$startDate, $endDate] = explode(' to ', $request->date_range);
                try {
                    $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                    $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    dd('Invalid date range format', $request->date_range);
                }
            } else {
                $startDate = $endDate = null;
            }
            
            $salsePersone = SalesPerson::where('main_machine_type', $main_machine_type)->with([
                'salseUserDetail', 
                'saleAssignUserDetail', 
                'prioritys', 
                'latestSalesPersonTask'
            ]);

            if ($startDate && $endDate) {
                $salsePersone->whereBetween('next_reminder_date', [$startDate, $endDate]);
            }
            
            if (isset($request->next_reminder_date)) {
                $salsePersone->where('next_reminder_date', date('Y-m-d', strtotime($request->next_reminder_date)));
            }
            if (isset($request->status_id)) {
                $salsePersone->where('status_id', $request->status_id);
            }
            if (isset($request->sale_user_id)) {
                $salsePersone->where('sale_user_id', $request->sale_user_id);
            }
            if (isset($request->sale_assign_user_id)) {
                $salsePersone->where('sale_assign_user_id', $request->sale_assign_user_id);
            }
            $salsePersone = $salsePersone->latest()->get();

            if ($salsePersone->isEmpty() && $startDate && $endDate) {
                $salsePersone = SalesPerson::with([
                    'salseUserDetail', 
                    'saleAssignUserDetail', 
                    'prioritys', 
                    'latestSalesPersonTask'
                ]);
    
                if ($startDate && $endDate) {
                    $salsePersone->whereBetween('date', [$startDate, $endDate]);
                }
                
                if (isset($request->next_reminder_date)) {
                    $salsePersone->where('next_reminder_date', date('Y-m-d', strtotime($request->next_reminder_date)));
                }
                if (isset($request->status_id)) {
                    $salsePersone->where('status_id', $request->status_id);
                }
                if (isset($request->sale_user_id)) {
                    $salsePersone->where('sale_user_id', $request->sale_user_id);
                }
                if (isset($request->sale_assign_user_id)) {
                    $salsePersone->where('sale_assign_user_id', $request->sale_assign_user_id);
                }
                $salsePersone = $salsePersone->latest()->get();
            }
            return DataTables::of($salsePersone)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = "<div class='btn-group m-1'><a class='btn btn-sm btn-primary' href='" . route('edit-work', ['lead' => $row->id]) . "'><i class='fa fa-edit'></i></a>";
                    $btn .= "<a class='btn btn-sm btn-danger' href='javascript:void(0)' onclick=''><i class='fa fa-trash'></i></a></div>";
                    return $btn;
                })
                ->addColumn("date", function ($row) {
                    return date('d-m-Y', strtotime($row->date)) . ' ' . $row->time;
                })
                ->addColumn("next_reminder_date", function ($row) {
                    return $row->next_reminder_date
                        ? date('d-m-Y', strtotime($row->next_reminder_date)) . ' ' . ($row->next_reminder_time ?? '')
                        : '';
                })
                ->addColumn("sale_user", function ($row) {
                    return $row->salseUserDetail ? $row->salseUserDetail->name : '';
                })
                ->addColumn("sale_assign_user", function ($row) {
                    return $row->saleAssignUserDetail ? $row->saleAssignUserDetail->name : '';
                })
                ->addColumn("comments", function ($row) {
                    return $row->latestSalesPersonTask ? $row->latestSalesPersonTask->comment_first : '';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('report.salse_lead_report', $data);
    }

    public function changeDateFormate($date)
    {
        $newFormate = Carbon::parse($date);
        return $newFormate->format('Y-m-d');
    }

    // Export excel file
    public function exportEngineerComplaints(Request $request)
    {
        $data = $request->only(['engineer_visit_from', 'engineer_visit_to', 'party_id', 'engineer_id']);
        return Excel::download(new EngineerComplainDoneExport($data), 'Engineer_done_complaint.xlsx');
    }

    // Export complaint to excel
    public function exportComplaints(Request $request)
    {
        $data = $request->only(['complaint_pending_from', 'complaint_pending_to', 'party_id', 'engineer_id', 'status_id']);
        return Excel::download(new ComplaintExport($data), 'complaint.xlsx');
    }

    // Export complaint to excel
    public function exportFreeService(Request $request)
    {
        $data = $request->only(['free_service_date_from', 'free_service_date_to', 'status_id']);
        return Excel::download(new FreeServiceExport($data), 'free_service.xlsx');
    }

    // Export Machine salse to excel
    public function exportMachineSalse(Request $request)
    {
        $data = $request->only(['from_date', 'to_date', 'party_id', 'product_id', 'area_id', 'is_active']);
        return Excel::download(new MachineSalseExport($data), 'Machine_salse.xlsx');
    }

    public function reportTodo(Request $request)
    {
        $data['title'] = 'To Do Report';
        if ($request->ajax()) {
            if (isset($request->date_range)) {
                [$startDate, $endDate] = explode(' to ', $request->date_range);
                try {
                    $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                    $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    dd('Invalid date range format', $request->date_range);
                }
            } else {
                $startDate = $endDate = null;
            }
            
            $todoQuery = Todo::with([
                'todoUser',
                'todoAssignUsers.assignUserDetail',
                'todoTasks' => function ($query) {
                    $query->orderBy('date', 'desc');
                },
                'todoTasks.prioritys'
            ])->latest();
            
            if ($startDate && $endDate) {
                $todoQuery->whereRaw('DATE(assign_date_time) BETWEEN ? AND ?', [$startDate, $endDate]);
            }

            if (isset($request->priority_id)) {
                $todoQuery->whereHas('todoTasks', function ($query) use ($request) {
                    $query->whereNotNull('priority_id')->where('priority_id', $request->priority_id)
                          ->whereRaw('id = (SELECT MAX(id) FROM todo_tasks WHERE todo_tasks.todo_id = todos.id)');
                });
            }
            
            if (isset($request->user_id)) {
                $todoQuery->where('user_id', $request->user_id);
            }
            
            if (isset($request->assign_user_id)) {
                $todoQuery->whereHas('todoAssignUsers', function ($query) use ($request) {
                    $query->where('assign_user_id', $request->assign_user_id);
                });
            }
            
            $todos = $todoQuery->get();

            if ($todos->isEmpty() && $startDate && $endDate) {
                $todoQuery = Todo::with([
                    'todoUser',
                    'todoAssignUsers.assignUserDetail',
                    'todoTasks' => function ($query) {
                        $query->orderBy('date', 'desc');
                    },
                    'todoTasks.prioritys'
                ])->latest();
            
                $todoQuery->whereHas('todoTasks', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                });

                if (isset($request->priority_id)) {
                    $todoQuery->whereHas('todoTasks', function ($query) use ($request) {
                        $query->whereNotNull('priority_id')->where('priority_id', $request->priority_id)
                              ->whereRaw('id = (SELECT MAX(id) FROM todo_tasks WHERE todo_tasks.todo_id = todos.id)');
                    });
                }
                
                if (isset($request->user_id)) {
                    $todoQuery->where('user_id', $request->user_id);
                }
                
                if (isset($request->assign_user_id)) {
                    $todoQuery->whereHas('todoAssignUsers', function ($query) use ($request) {
                        $query->where('assign_user_id', $request->assign_user_id);
                    });
                }
            
                $todos = $todoQuery->get();
            }
            
            return DataTables::of($todos)
                ->addIndexColumn()
                ->addColumn("status", function ($row) {
                    return $row->status != 1 ? 'In Progress' : 'Done';
                })
                ->addColumn("user_name", function ($row) {
                    return $row->todoUser ? $row->todoUser->name : '';
                })
                ->addColumn("assign_date_time", function ($row) {
                    return $row->assign_date_time ? date('d-m-Y H:i:s', strtotime($row->assign_date_time)) : '';
                })
                ->addColumn("reminder_date", function ($row) {
                    $taskWithDate = $row->todoTasks->firstWhere('date', '!=', null);
                    return $taskWithDate ? date('d-m-Y', strtotime($taskWithDate->date)) : '';
                })
                ->addColumn("comment", function ($row) {
                    $latestTask = $row->todoTasks->sortByDesc('date')->first(); // Get the latest task
                    return $latestTask ? $latestTask->comment_first : '';
                })
                ->addColumn("priority", function ($row) {
                    $latestTask = $row->todoTasks->sortByDesc('id')->first(); // Get the latest task
                    return $latestTask && $latestTask->prioritys ? $latestTask->prioritys->priority : '';
                })
                ->addColumn("assign_users", function ($row) {
                    return $row->todoAssignUsers->isNotEmpty() 
                        ? $row->todoAssignUsers->map(function($todoAssignUser) {
                            return $todoAssignUser->assignUserDetail ? $todoAssignUser->assignUserDetail->name : null;
                        })->filter()->implode(', ') 
                        : '';
                })
                ->make(true);
        }
        
        return view('report.todo', $data);
    }

    public function reportSales(Request $request)
    {
        $data['title'] = 'Sales Summary';
        if ($request->ajax()) {
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
            
            return DataTables::of($salesPerson)
                ->addIndexColumn()
                ->addColumn("user_name", function ($row) {
                    return $row->saleAssignUserDetail ? $row->saleAssignUserDetail->name : '';
                })
                ->make(true);
        }
        return view('report.sales_summary', $data);
    }

    public function complaintTypeReport(Request $request) {
        $data['title'] = 'Complaint Type Summary';
        if ($request->ajax()) {
            $complaints = Complaint::select('complaints.complaint_type_id', 'complaint_types.name as complaint_type_name', \DB::raw('count(*) as count'))
                        ->join('complaint_types', 'complaints.complaint_type_id', '=', 'complaint_types.id')
                        ->groupBy('complaints.complaint_type_id', 'complaint_types.name')
                        ->get();

            return DataTables::of($complaints)
                ->addIndexColumn()
                ->make(true);
        }
        return view('report.complaint_type', $data);
    }

    public function reportEngDoneSummary(Request $request) {
        $data['title'] = 'Engineer Done Summary Report';
        if ($request->ajax()) {
            $complaintQuery = Complaint::query();

            $complaintQuery->whereNotNull('engineer_out_date');

            if (isset($request->date_range)) {
                [$startDate, $endDate] = explode(' to ', $request->date_range);
                $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
                $complaintQuery->whereBetween('engineer_out_date', [$startDate, $endDate]);
            }
            if (isset($request->engineer_id)) {
                $complaintQuery->where('engineer_id', $request->engineer_id);
            }

            $complaint = $complaintQuery
                ->selectRaw('engineer_id, CONCAT(MONTHNAME(engineer_out_date), " - ", YEAR(engineer_out_date)) as month_year, COUNT(*) as done_count')
                ->where('status_id', 3)
                ->groupBy('engineer_id', 'month_year')
                ->with('engineerDetail')
                ->get();

            return DataTables::of($complaint)
                ->addIndexColumn()
                ->addColumn("engineer_name", function ($row) {
                    return $row->engineerDetail ? $row->engineerDetail->name : '';
                })
                ->make(true);
        }
        return view('report.eng_done_summary', $data);
    }

    public function reporEngineerPerformance(Request $request) {
        $data['title'] = 'Engineer Performance Details';
        if ($request->ajax()) {
            $complaintQuery = Complaint::query();
            $complaintQuery->whereNotNull('engineer_out_date');

            if (isset($request->date_range)) {
                [$startDate, $endDate] = explode(' to ', $request->date_range);
                $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
                $complaintQuery->whereBetween('engineer_out_date', [$startDate, $endDate]);
            }

            if (!isset($request->from_date) && !isset($request->end_date)) {
                $complaintQuery->whereDate('engineer_out_date', Carbon::today());
            }

            if (isset($request->engineer_id)) {
                $complaintQuery->where('engineer_id', $request->engineer_id);
            }

            $complaintQuery->orderByDesc('engineer_out_date');

            $complaint = $complaintQuery->selectRaw('
                                    engineer_id,
                                    engineer_out_date,
                                    COUNT(party_id) as party_count,
                                    SUM(TIME_TO_SEC(engineer_time_duration)) as total_time_duration_seconds,
                                    COUNT(id) as total_complaints
                                ')
                                ->groupBy('engineer_id', 'engineer_out_date')
                                ->with('engineerDetail')
                                ->get();

            foreach ($complaint as $sale) {
                $totalDurationInSeconds = $sale->total_time_duration_seconds;
                $hours = floor($totalDurationInSeconds / 3600);
                $minutes = floor(($totalDurationInSeconds % 3600) / 60);
                $seconds = $totalDurationInSeconds % 60;
                $sale->total_time_duration = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
            }

            return DataTables::of($complaint)
                ->addIndexColumn()
                ->addColumn("engineer_out_date", function ($row) {
                    return $row->engineer_out_date ? date('d-m-Y', strtotime($row->engineer_out_date)) : '';
                })
                ->addColumn("engineer_name", function ($row) {
                    return $row->engineerDetail ? $row->engineerDetail->name : '';
                })
                ->addColumn("duty_start", function ($row) {
                    return $row->engineerDetail ? $row->engineerDetail->duty_start : '';
                })
                ->addColumn("duty_end", function ($row) {
                    return $row->engineerDetail ? $row->engineerDetail->duty_end : '';
                })
                ->addColumn("duty_hours", function ($row) {
                    return $row->engineerDetail ? $row->engineerDetail->duty_hours : '';
                })
                ->make(true);
        }
        return view('report.engineer_performance', $data);
    }

    public function exportSalesLead()
    {
        return Excel::download(new SalesLeadExport, 'Sales Lead.xlsx');
    }

    public function exportSalesSummary()
    {
        return Excel::download(new SalesSummaryExport, 'Sales Summary.xlsx');
    }

    public function exportTodo()
    {
        return Excel::download(new ToDoExport, 'To Do.xlsx');
    }
}
