<?php

namespace App\Http\Controllers;

use App\Models\PartsInventory;
use App\Models\Party;
use App\Models\User;
use App\Models\MachineSalesEntry;
use Illuminate\Http\Request;
use App\Http\Requests\PartsInventoryRequest;
use App\Http\Requests\RepairOutRequest;
use App\Http\Requests\RepairInRequest;
use App\Http\Requests\IssueToCustomerRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Flasher\Toastr\Laravel\Facade\Toastr;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use File;

class PartsInventoryController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['title'] = 'Parts Inventory List';
        if ($request->ajax()) {
            $collection = PartsInventory::with('in_engineer','issue_engineer','in_party','repair_out_party','product');

            if (isset($request->date_range)) {
                [$startDate, $endDate] = explode(' to ', $request->date_range);
                $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
                $collection->whereBetween('date', [$startDate, $endDate]);
            }

            $collection = $collection->latest()->get();

            return DataTables::of($collection)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    // if (!empty($request->get('from_date')) && !empty($request->get('to_date'))) {
                    //     $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    //         return $row['date'] >= $request->get('from_date') && $row['date'] <= $request->get('to_date') ? true : false;
                    //     });
                    // }
                    if (!empty($request->get('repair_status'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains($row['repair_status'], $request->get('repair_status')) ? true : false;
                        });
                    }
                    if (!empty($request->get('in_party_id'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains($row['in_party_id'], $request->get('in_party_id')) ? true : false;
                        });
                    }
                    if (!empty($request->get('repair_out_party_id'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains($row['repair_out_party_id'], $request->get('repair_out_party_id')) ? true : false;
                        });
                    }
                })
                ->addColumn('action', function (PartsInventory $partsInventory) {
                    return "<div class='btn-group table-dropdown m-1'>
                            <a class='btn btn-sm btn-primary' href='" . route('parts_inventory.edit', ['parts_inventory' => $partsInventory]) . "'><i class='fa fa-edit'></i></a>
                            <a class='btn btn-sm btn-danger' href='javascript:void(0)' onclick='window.deleteParth(" . $partsInventory->id . ")'><i class='fa fa-trash'></i></a>
                            <a class='btn btn-sm btn-info' href='javscript:void(0);' data-bs-toggle='dropdown'><i class='las la-download'></i></a>
                            <ul class='dropdown-menu dropdown-content'>
                                <li><a class='dropdown-item' href='" . route('parts_inventory.pdf', ['parts_inventory' => $partsInventory->id, 'repair_status' => 1]) . "''>Receive Parts</a></li>
                                <li><a class='dropdown-item' href='" . route('parts_inventory.pdf', ['parts_inventory' => $partsInventory->id, 'repair_status' => 2]) . "''>Repair Out</a></li>
                                <li><a class='dropdown-item' href='" . route('parts_inventory.pdf', ['parts_inventory' => $partsInventory->id, 'repair_status' => 3]) . "''>Repair In</a></li>
                                <li><a class='dropdown-item' href='" . route('parts_inventory.pdf', ['parts_inventory' => $partsInventory->id, 'repair_status' => 4]) . "''>Issue to Party</a></li>
                            </ul>
                            
                            </div>";
                    // <button class='btn btn-sm btn-info btnjjp' onclick='window.complainPrint(" . $complaint->id . ")'><i class='fa fa-download'></i></button>
                    // <a class='btn btn-sm btn-info' href='javascript:void(0)' onclick='window.addItemPart(" . $complaint->id . ")'><i class='fa fa-product-hunt'></i></a>
                })
                ->addColumn("date", function ($row) {
                    return date('d-m-Y', strtotime($row->date));
                })
                ->addColumn("repair_out_date", function ($row) {
                    return $row->repair_out_date ? date('d-m-Y', strtotime($row->repair_out_date)) : '';
                })
                ->addColumn("repair_in_date", function ($row) {
                    return $row->repair_in_date ? date('d-m-Y', strtotime($row->repair_in_date)) : '';
                })
                ->addColumn("issue_date", function ($row) {
                    return $row->issue_date ? date('d-m-Y', strtotime($row->issue_date)) : '';
                })
                ->addColumn("engineer_name", function ($row) {
                    return $row->in_engineer ? $row->in_engineer->name : '';
                })
                ->addColumn("in_party_name", function ($row) {
                    return $row->in_party ? $row->in_party->name : '';
                })
                ->addColumn("mc_no", function ($row) {
                    return $row->product->machineSales ? $row->product->machineSales[0]->mc_no : '';
                })
                ->addColumn("repair_out_party", function ($row) {
                    return $row->repair_out_party ? $row->repair_out_party->name : '';
                })
                ->addColumn('status', function ($row) {
                    if ($row->repair_status == 1) {
                        $repair_status = 'Receive Parts';
                    } elseif ($row->repair_status == 2) {
                        $repair_status = 'Repair Out';
                    } elseif ($row->repair_status == 3) {
                        $repair_status = 'Repair In';
                    } else {
                        $repair_status = 'Issue to Party';
                    }
                    return $repair_status;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('parts_inventory.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Create Parts Inventory';
        $vou_no = 1;
        $partsInventory = PartsInventory::orderBy('id', 'desc')->first();
        if (!empty($partsInventory)) {
            $vou_no = $partsInventory->vou_no + 1;
        }
        $data['vou_no'] = $vou_no;
        return view('parts_inventory.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PartsInventoryRequest $request)
    {
        if ($request->validated()) {
            $request['date'] = isset($request->date) ? date('Y-m-d', strtotime($request->date)) : null;
            $partsData = PartsInventory::create($request->all());

            $partsInventoryData = PartsInventory::with('product','in_party')->where('id',$partsData->id)->first();
            // Send push notification to party
            if (isset($request->in_party_id)) {
                $party = Party::select('device_token')->where('id', $request->in_party_id)->first();
            }
            if (!empty($party)) {
                $title = 'Your '.$request->card_no.' Receive';
                $body = 'Date : '.date('d-m-Y', strtotime($partsInventoryData->date)).'. M/C No : '.$partsInventoryData->product->machineSales[0]->mc_no;
                $token = $party->device_token;

                if ($title != "" && $body != "" && $token != "") {
                    // Get the response from the helper function
                    // sendNotification($title, $body, $token);
                    $this->notificationService->sendNotification($title, $body, $token);
                }
            }

            // Send push notification to engineer
            if (isset($request->in_engineer_id)) {
                $engineer = User::where('id', $request->in_engineer_id)->first();
            }
            if (!empty($engineer)) {
                $title = $partsInventoryData->in_party->name.' '.$request->card_no.' Receive';
                $body = 'Date : '.date('d-m-Y', strtotime($partsInventoryData->date)).'. M/C No : '.$partsInventoryData->product->machineSales[0]->mc_no;
                $token = $engineer->device_token;

                if ($title != "" && $body != "" && $token != "") {
                    // Get the response from the helper function
                    // sendNotification($title, $body, $token);
                    $this->notificationService->sendNotification($title, $body, $token);
                }
            }

            return redirect()->route('parts_inventory.index')->with('success', 'Parts inventory created successfully');
        } else {
            return redirect()->back()->withErrors($request->errors())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PartsInventory $partsInventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PartsInventory $partsInventory)
    {
        $data['title'] = 'Edit Parts Inventory';
        $data['partsInventory'] = $partsInventory;
        $data['sales_entries'] = MachineSalesEntry::where('party_id', $partsInventory->in_party_id)->with('product')->get();
        return view('parts_inventory.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PartsInventoryRequest $request, PartsInventory $partsInventory)
    {
        if ($request->validated()) {
            $request['date'] = date('Y-m-d', strtotime($request->date));

            $partsInventory->update($request->all());

            return redirect()->route('parts_inventory.index')->with('success', 'Parts inventory updated successfully');
        } else {
            return redirect()->back()->withErrors($request->errors())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PartsInventory $partsInventory)
    {
        $partsInventory->delete();
        Toastr::success('Parts inventory deleted successfully');
        return response()->json(['success' => 1, 'message' => 'Parts inventory deleted successfully']);
    }

    public function repairOutUpdate(RepairOutRequest $request) {
        if ($request->validated()) {
            $partsInventory = PartsInventory::with('product','in_party')->findOrFail($request->parts_inventory_id);
            $partsInventory->repair_out_date = isset($request->repair_out_date) ? date('Y-m-d', strtotime($request->repair_out_date)) : '';
            $partsInventory->repair_out_time = isset($request->repair_out_time) ? $request->repair_out_time : '';
            $partsInventory->repair_out_party_code = isset($request->repair_out_party_code) ? $request->repair_out_party_code : '';
            $partsInventory->repair_out_party_id  = isset($request->repair_out_party_id) ? $request->repair_out_party_id : '';
            $partsInventory->repair_out_remarks  = isset($request->repair_out_remarks) ? $request->repair_out_remarks : '';
            $partsInventory->expexted_required_date = isset($request->expexted_required_date) ? date('Y-m-d', strtotime($request->expexted_required_date)) : '';
            $partsInventory->repair_status = 2;
            $partsInventory->save();
            
            // Admin Push Notification
            if (isset($partsInventory) && !empty($partsInventory)) {
                $admin = User::find(1);
                // Do not remove admin id 1 from DB
                if(!empty($admin->device_token)) {
                    $title = $partsInventory->in_party->name.' '.$partsInventory->card_no.' Receive';
                    $body = 'Date : '.date('d-m-Y', strtotime($request->repair_out_date)).'. M/C No : '.$partsInventory->product->machineSales[0]->mc_no;
                    $token = $admin->device_token;
    
                    if ($title != "" && $body != "" && $token != "") {
                        // Get the response from the helper function
                        // sendNotification($title, $body, $token);
                        $this->notificationService->sendNotification($title, $body, $token);
                    }
                }
            }

            // Send push notification to engineer
            if (isset($partsInventory->in_engineer_id)) {
                $engineer = User::where('id', $partsInventory->in_engineer_id)->first();
            }
            if (!empty($engineer)) {
                $title = $partsInventory->in_party->name.' '.$partsInventory->card_no.' Receive';
                $body = 'Date : '.date('d-m-Y', strtotime($request->repair_out_date)).'. M/C No : '.$partsInventory->product->machineSales[0]->mc_no;
                $token = $admin->device_token;
                if ($title != "" && $body != "" && $token != "") {
                    // Get the response from the helper function
                    // sendNotification($title, $body, $token);
                    $this->notificationService->sendNotification($title, $body, $token);
                }
            }
            return redirect()->route('parts_inventory.edit', ['parts_inventory' => $partsInventory])->with('success', 'Repair out updated successfully');
        } else {
            return redirect()->back()->withErrors($request->errors())->withInput();
        }
    }

    public function repairInUpdate(RepairInRequest $request) {
        if ($request->validated()) {
            $partsInventory = PartsInventory::with('product','in_party')->findOrFail($request->parts_inventory_id);
            $partsInventory->repair_in_date = isset($request->repair_in_date) ? date('Y-m-d', strtotime($request->repair_in_date)) : '';
            $partsInventory->repair_in_time = isset($request->repair_in_time) ? $request->repair_in_time : '';
            $partsInventory->repair_in_remarks  = isset($request->repair_in_remarks) ? $request->repair_in_remarks : '';
            $partsInventory->repair_status = 3;
            $partsInventory->save();

            // Admin Push Notification
            if (isset($partsInventory) && !empty($partsInventory)) {
                $admin = User::find(1);
                // Do not remove admin id 1 from DB
                if(!empty($admin->device_token)) {
                    $title = $partsInventory->in_party->name.' '.$partsInventory->card_no.' Receive';
                    $body = 'Date : '.date('d-m-Y', strtotime($request->repair_in_date)).'. M/C No : '.$partsInventory->product->machineSales[0]->mc_no;
                    $token = $admin->device_token;
    
    
                    if ($title != "" && $body != "" && $token != "") {
                        // Get the response from the helper function
                        // sendNotification($title, $body, $token);
                        $this->notificationService->sendNotification($title, $body, $token);
                    }
                }
            }

            // Send push notification to engineer
            if (isset($partsInventory->in_engineer_id)) {
                $engineer = User::where('id', $partsInventory->in_engineer_id)->first();
            }
            if (!empty($engineer)) {
                $title = $partsInventory->in_party->name.' '.$partsInventory->card_no.' Receive';
                $body = 'Date : '.date('d-m-Y', strtotime($request->repair_in_date)).'. M/C No : '.$partsInventory->product->machineSales[0]->mc_no;
                $token = $engineer->device_token;

                if ($title != "" && $body != "" && $token != "") {
                    // Get the response from the helper function
                    // sendNotification($title, $body, $token);
                    $this->notificationService->sendNotification($title, $body, $token);
                }
            }

            return redirect()->route('parts_inventory.edit', ['parts_inventory' => $partsInventory])->with('success', 'Repair in updated successfully');
        } else {
            return redirect()->back()->withErrors($request->errors())->withInput();
        }
    }

    public function issueToPartyUpdate(IssueToCustomerRequest $request) {
        if ($request->validated()) {
            $partsInventory = PartsInventory::with('product','in_party')->findOrFail($request->parts_inventory_id);
            $partsInventory->issue_date = isset($request->issue_date) ? date('Y-m-d', strtotime($request->issue_date)) : '';
            $partsInventory->issue_time = isset($request->issue_time) ? $request->issue_time : '';
            $partsInventory->issue_engineer_id  = isset($request->issue_engineer_id) ? $request->issue_engineer_id : '';
            $partsInventory->issue_remarks  = isset($request->issue_remarks) ? $request->issue_remarks : '';
            $partsInventory->repair_status = 4;
            $partsInventory->save();

            // Admin Push Notification
            if (isset($partsInventory) && !empty($partsInventory)) {
                $admin = User::find(1);
                // Do not remove admin id 1 from DB
                if(!empty($admin->device_token)) {
                    $title = $partsInventory->in_party->name.' '.$partsInventory->card_no.' Receive';
                    $body = 'Date : '.date('d-m-Y', strtotime($request->issue_date)).'. M/C No : '.$partsInventory->product->machineSales[0]->mc_no;
                    $token = $admin->device_token;
    
    
                    if ($title != "" && $body != "" && $token != "") {
                        // Get the response from the helper function
                        // sendNotification($title, $body, $token);
                        $this->notificationService->sendNotification($title, $body, $token);
                    }
                }
            }

            // Send push notification to party
            if (isset($partsInventory->in_engineer_id)) {
                $engineer = User::where('id', $partsInventory->in_engineer_id)->first();
            }
            if (!empty($engineer)) {
                $title = $partsInventory->in_party->name.' '.$partsInventory->card_no.' Receive';
                $body = 'Date : '.date('d-m-Y', strtotime($request->issue_date)).'. M/C No : '.$partsInventory->product->machineSales[0]->mc_no;
                $token = $engineer->device_token;

                if ($title != "" && $body != "" && $token != "") {
                    // Get the response from the helper function
                    // sendNotification($title, $body, $token);
                    $this->notificationService->sendNotification($title, $body, $token);
                }
            }


            return redirect()->route('parts_inventory.edit', ['parts_inventory' => $partsInventory])->with('success', 'Repair in updated successfully');
        } else {
            return redirect()->back()->withErrors($request->errors())->withInput();
        }
    }

    public function downloadPDF($parts_inventory, $repair_status) {
        $partsInventory = PartsInventory::with('in_engineer','in_party','product','repair_out_party','issue_engineer')->where('id',$parts_inventory)->first();
        if($repair_status == 1) {
            $data['title'] = 'Receive Parts';
            $data['partsInventory'] = $partsInventory;
            $pdf = PDF::loadView('parts_inventory/record_in', $data)->setPaper('a4', 'portrait');
            return $pdf->download($partsInventory->vou_no.' Receive Parts.pdf');
        } else if ($repair_status == 2 && !empty($partsInventory->repair_out_date)) {
            $data['title'] = 'Repair Out';
            $data['partsInventory'] = $partsInventory;
            $pdf = PDF::loadView('parts_inventory/repair_out', $data)->setPaper('a4', 'portrait');
            return $pdf->download($partsInventory->vou_no.' Repair Out.pdf');
        } else if ($repair_status == 3 && !empty($partsInventory->repair_in_date)) {
            $data['title'] = 'Repair In';
            $data['partsInventory'] = $partsInventory;
            $pdf = PDF::loadView('parts_inventory/repair_in', $data)->setPaper('a4', 'portrait');
            return $pdf->download($partsInventory->vou_no.' Repair In.pdf');
        } else if ($repair_status == 4 && !empty($partsInventory->issue_date)) {
            $data['title'] = 'Issue to Party';
            $data['partsInventory'] = $partsInventory;
            $pdf = PDF::loadView('parts_inventory/issue_to_party', $data)->setPaper('a4', 'portrait');
            return $pdf->download($partsInventory->vou_no.' Issue to Party.pdf');
        } else {
            Toastr::error('Record not found');
            return redirect()->route('parts_inventory.index');
        }
    }
}
