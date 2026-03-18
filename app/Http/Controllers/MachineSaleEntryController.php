<?php

namespace App\Http\Controllers;

use App\DataTables\MachineSalesEntryDataTable;
use App\Http\Requests\MachineSalesEntryRequest;
use App\Models\MachineSalesEntry;
use App\Models\Machine;
use App\Models\Width;
use App\Models\Color;
use App\Models\Shadding;
use App\Models\ShaddingOptions;
use App\Models\Party;
use App\Models\PartyFirm;
use App\Models\PartywiseMachine;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Flasher\Toastr\Laravel\Facade\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use App\Exports\MachineSalesExpiryExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MachineSaleEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id, Request $request)
    {
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $data['title'] = $main_machine_name. '-> installations';
    
        if ($request->ajax()) {
            // $machineSalse = $data['machine'] = MachineSalesEntry::where('main_machine_type', $main_machine_type)->with('party', 'product',  'serviceType', 'micFittingEngineer', 'deliveryEngineer', 'partyfirms')->latest();

            $query = MachineSalesEntry::query()
            ->select(
                'party_id',
                DB::raw('COUNT(id) as total_machines')
            )
            ->where('main_machine_type', $main_machine_type);

        /* APPLY FILTERS BEFORE GROUPING */
        if ($request->filled('party_id')) {
            $query->where('party_id', $request->party_id);
        }

        if ($request->filled('date_range')) {
            [$start, $end] = explode(' to ', $request->date_range);
            $query->whereBetween('date', [
                Carbon::createFromFormat('d-m-Y', $start)->format('Y-m-d'),
                Carbon::createFromFormat('d-m-Y', $end)->format('Y-m-d'),
            ]);
        }

        /* GROUP AFTER FILTERING */
        $query->groupBy('party_id')
              ->with('party:id,name,phone_no');

        return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($main_machine_type) {
                    return "<a href='" .
                    route('MachineSales.party-machines', [
                        'main_machine_type' => $main_machine_type,
                        'party' => $row->party_id
                    ]) .
                    "' class='btn btn-sm btn-primary'>View Machines</a>";
                })
                
                ->addColumn("date", function ($row) {
                    return date('d-m-Y', strtotime($row->date));
                })
                
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('MachineSalesEntry.index', $data);
    }

    public function partyMachines($id, $pid, Request $request)
    {
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $data['title'] = $main_machine_name. '-> installations';
        $data['party'] = $pid;
    
        if ($request->ajax()) {
            $machineSalse = $data['machine'] = MachineSalesEntry::where(['main_machine_type' => $main_machine_type, 'party_id' => $pid])->with('party', 'product',  'serviceType', 'micFittingEngineer', 'deliveryEngineer', 'partyfirms')->latest();

            if (isset($request->date_range)) {
                [$startDate, $endDate] = explode(' to ', $request->date_range);
                $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
                $machineSalse->whereBetween('date', [$startDate, $endDate]);
            }
            if (isset($request->product_id)) {
                $machineSalse->where('product_id', $request->product_id);
            }
            
            $machineSalse = $machineSalse->get();
            return DataTables::of($machineSalse)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($main_machine_type) {
                    $btn = "<div class='btn-group m-1'><a class='btn btn-sm btn-primary' href='" . route('visits.index', ['main_machine_type' => $main_machine_type, 'machinesale' => $row->id]) . "'> VISITS </a> &nbsp; <a class='btn btn-sm btn-primary' href='" . route('MachineSales.edit', ['main_machine_type' => $main_machine_type, $row->id]) . "'><i class='fa fa-edit'></i></a>";
                    $btn .= "<a class='btn btn-sm btn-danger' href='javascript:void(0)' onclick='window.deleteParty(" . $row->id . ")'><i class='fa fa-trash'></i></a></div> ";
                    return $btn;
                })
                
                ->addColumn("date", function ($row) {
                    return date('d-m-Y', strtotime($row->date));
                })
                
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('MachineSalesEntry.party_machines', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id, Request $request)
    {
        $main_machine_type = $id;

        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $data['title'] = $main_machine_name. '-> Create installation';
        $currentDate = Carbon::now()->format('d-m-Y');
        $data['today'] = $currentDate;

        //get parties with machine type
        $data['parties'] = Party::with(['area', 'city'])
        ->whereHas('partywiseMachines', function ($query) use ($main_machine_type) {
            $query->where('main_machine_type', $main_machine_type);
        })->latest()->get();

        //get products with machine type
        $data['products'] = Product::where('product_group_id', $id)->latest()->get();

        // get users with machine type
        $data['engineers'] = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Engineer']); // show only engineers to managers
                })
                ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                $query->where('main_machine_type', $main_machine_type);
                })->latest()->get();

        return view('MachineSalesEntry.create', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function newCreate($id, Request $request)
    {
        $main_machine_type = $id;
        $activetab = 0;
        $data['activetab'] = $activetab;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $data['title'] = $main_machine_name. '-> New Customer';
        $currentDate = Carbon::now()->format('d-m-Y');
        $data['today'] = $currentDate;

        //get parties with machine type
        $data['parties'] = Party::with(['area', 'city'])
        ->whereHas('partywiseMachines', function ($query) use ($main_machine_type) {
            $query->where('main_machine_type', $main_machine_type);
        })->latest()->get();

        //get products with machine type
        $data['products'] = Product::where('product_group_id', $id)->latest()->get();

        // get users with machine type
        $data['engineers'] = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Engineer']); // show only engineers to managers
                })
                ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                $query->where('main_machine_type', $main_machine_type);
                })->latest()->get();

        return view('MachineSalesEntry.new-create', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function addMachinesForOldCust($id, Request $request)
    {
        $main_machine_type = $id;
        $activetab = 0;
        $data['activetab'] = $activetab;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $data['title'] = $main_machine_name. '-> Add Machine Sale Entry';
        $currentDate = Carbon::now()->format('d-m-Y');
        $data['today'] = $currentDate;

        //get parties with machine type
        $data['parties'] = Party::with(['area', 'city'])
        ->whereHas('partywiseMachines', function ($query) use ($main_machine_type) {
            $query->where('main_machine_type', $main_machine_type);
        })->latest()->get();

        //get products with machine type
        $data['products'] = Product::where('product_group_id', $id)->latest()->get();

        //get width
        $data['width'] = Width::get();

        //get color
        $data['color'] = Color::get();

        //get Shadding
        $data['shadding'] = Shadding::get();

        //get Shadding
        $data['shadding_options'] = ShaddingOptions::get();

        return view('MachineSalesEntry.addmachine-oldcust', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MachineSalesEntryRequest $request)
    {
        // Check the machine serial is unique for the same party with the same order number
        $isUniqueSerialNo = MachineSalesEntry::where(['serial_no' => $request->serial_no, 'party_id' => $request->party_id, 'order_no' => $request->order_no])
            ->get();

        if ($isUniqueSerialNo->isNotEmpty()) {
            Toastr::error('Serial no already used');
            return redirect()->back()->withErrors('serial No already use for the same party')->withInput();
        }

        $data['title'] = 'Create Machine Sales Entry';
        $data = $request->all();
        $data['date'] = $this->changeDateFormate($request->date);
        $data['install_date'] = $this->changeDateFormate($request->install_date);
        $data['service_expiry_date'] = $this->changeDateFormate($request->service_expiry_date);
        $data['free_service_date'] = $this->changeDateFormate($request->free_service_date);

        $folderName = 'machine_sales'; // Replace with your folder name
        $path = public_path($folderName);

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true, true); // Create directory with permissions
        }

        if ($request->image) {
            $folderName = 'image'; // Replace with your folder name
            $path = public_path('machine_sales/'.$folderName);

            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true); // Create directory with permissions
            }

            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('machine_sales/image'), $filename);
            $data['image'] = $filename;
        }
        if ($request->image1) {
            $folderName = 'image1'; // Replace with your folder name
            $path = public_path('machine_sales/'.$folderName);

            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true); // Create directory with permissions
            }

            $file = $request->file('image1');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('machine_sales/image1'), $filename);
            $data['image1'] = $filename;
        }
        if ($request->image2) {
            $folderName = 'image2'; // Replace with your folder name
            $path = public_path('machine_sales/'.$folderName);

            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true); // Create directory with permissions
            }

            $file = $request->file('image2');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('machine_sales/image2'), $filename);
            $data['image2'] = $filename;
        }
        if ($request->image3) {
            $folderName = 'image3'; // Replace with your folder name
            $path = public_path('machine_sales/'.$folderName);

            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true); // Create directory with permissions
            }

            $file = $request->file('image3');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('machine_sales/image3'), $filename);
            $data['image3'] = $filename;
        }
        if (!isset($request->is_active)) {
            $data['is_active'] = 0;
        }
        $letters = strtoupper(Str::random(2)); // e.g. "AB"
        $numbers = rand(100, 999);              // e.g. "34"
        $uniqueCode = "PCW". $letters . $numbers;    // "AB34"
        //$data['order_no'] = $uniqueCode;
        $data['mc_no'] = $uniqueCode;
        $data['status'] = 1;
        MachineSalesEntry::create($data);
        Toastr::success('Machine Sales Entry Added Successfully');
        return redirect()->route('MachineSales.index', ['main_machine_type' => $request->main_machine_type]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function newStore(Request $request)
    {
        $data = $request->all();
        $main_machine_type = $request->main_machine_type;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $main_machine_type)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        //$data['title'] = $main_machine_name. '-> Machine Sale';

        
        if(isset($request->activetab) && $request->activetab == 0) {
        
            // if(!isset($request->is_active)) {
            //     $data['is_active'] = 0;
            // }

            // Add Partywise Machine entry if not there for a party
            $pwmodata = Party::where('phone_no', $request->phone_no)->first();
            //dd(count($pwmdata));        
            if ($pwmodata) {
                 $activetab = 0;
                //echo "In ifff".$request->main_machine_type;
                //PartywiseMachine::create(['party_id' => $pwmodata->id, 'main_machine_type' => $request->main_machine_type]);
                //return redirect()->route('parties.create', ['main_machine_type' => $request->main_machine_type])->with($request->errors());
                return redirect()->route('MachineSales.new-create', ['main_machine_type' => $request->main_machine_type])->withErrors(['custom_error' => 'Customer is already there with same Mobile Number']);
                
            } else {
               //echo "In else".$request->main_machine_type;
               $letters = strtoupper(Str::random(2)); // e.g. "AB"
               $numbers = rand(100, 999);              // e.g. "34"
               $uniqueCode = $letters . $numbers;    // "AB34"
               $data['code'] = $uniqueCode;
               $data['firm_id'] = 1;
               $data['is_active'] = 1;
               $data['name'] = $request->owner_name;
               $party = Party::create($data);
               PartywiseMachine::create(['party_id' => $party->id,'main_machine_type' => $request->main_machine_type]);

               //Party Firms Entry
               $pfdata['party_id'] = $party->id;
               if (!empty($request->firm_name)) {
                    foreach ($request->firm_name as $index => $firmName) {
                        // Skip empty rows (important)
                        if (empty($firmName)) {
                            continue;
                        }
                        $pfdata = [
                            'party_id'   => $party->id,   // parent party id
                            'firm_name'  => $firmName,
                            'firm_owner' => $request->firm_owner[$index] ?? null,
                            'firmowner_phone' => $request->firmowner_phone[$index] ?? null,
                            'firm_gst'     => $request->firm_gst[$index] ?? null,
                            'firm_address'    => $request->firm_address[$index] ?? null,
                        ];

                        PartyFirm::create($pfdata);
                    }
                }
               Toastr::success('Customer and Firms created successfully');
            }
            $activetab = 1;
            $data = [];
            $main_machine_type = $request->main_machine_type;
            $data['main_machine_type'] = $main_machine_type;
            $main_machine_data = Machine::where('id', $main_machine_type)
                                ->where('status', 1)
                                ->first();
            $data['main_machine_name'] = $main_machine_data->machine_name;
            $data['activetab'] = $activetab;
            $data['title'] = $main_machine_name. '-> Machine Sale';
            $data['party_id'] =  $party->id;
            $partyfirms = PartyFirm::where('party_id', $party->id)
                                ->where('is_active', 1)
                                ->get();
            $data['partyfirms'] =  $partyfirms;
            //get products with machine type
            $data['products'] = Product::where('product_group_id', $main_machine_type)->latest()->get();

            //get width
            $data['width'] = Width::get();

            //get color
            $data['color'] = Color::get();

            //get Shadding
            $data['shadding'] = Shadding::get();

            //get Shadding
            $data['shadding_options'] = ShaddingOptions::get();
                            
            return view('MachineSalesEntry.new-create', $data);
        } else if (isset($request->activetab) && $request->activetab == 1) {
            //$data = $request->all();
            // Machine Sale Entry
            //dd("Machine Sale Start");
               if (!empty($request->width)) {
                    foreach ($request->width as $index => $width) {
                        // Skip empty rows (important)
                        if (empty($width)) {
                            continue;
                        }
                        //$data['party_id'] =  $request->party_id;
                        $letters = strtoupper(Str::random(2)); // e.g. "AB"
                        $numbers = rand(100, 999);              // e.g. "34"
                        $uniqueCode = "PCW". $letters . $numbers;    // "AB34"
                        $data = [
                            'firm_id' => $request->firm_id,
                            'mc_no' => $uniqueCode,
                            'status' => $request->status[$index],
                            'party_id' => $request->party_id,
                            'product_id' => $request->product_id[$index],
                            'weft_insertion'  => $request->weft_insertion[$index],
                            'width'  => $request->width[$index],
                            'color' => $request->color[$index] ?? null,
                            'shadding' => $request->shadding[$index] ?? null,
                            'shadding_option' => $request->extra_shadding[$index] ?? null,
                            'order_no'     => $request->order_no[$index] ?? null,
                            'serial_no'    => $request->serial_no[$index] ?? null,
                            'contenor_no' => $request->contenor_no,
                            'main_machine_type' => $request->main_machine_type,
                            'date' => Carbon::now()->format('Y-m-d'),
                            'install_date' => Carbon::now()->format('Y-m-d'),
                            'service_expiry_date' => Carbon::now()->addYear()->format('Y-m-d'),
                            'free_service_date' => Carbon::now()->format('Y-m-d'),
                        ];
                        MachineSalesEntry::create($data);
                    }
                }
            
            Toastr::success('Machine Sales Entry Added Successfully');
            $data = [];
            $activetab = 1;
            $data['party_id'] =  $request->party_id;
            $data['activetab'] = $activetab;
            $partyfirms = PartyFirm::where('party_id', $request->party_id)
                                ->where('is_active', 1)
                                ->get();
            $data['partyfirms'] =  $partyfirms;
            //get products with machine type
            $data['products'] = Product::where('product_group_id', $main_machine_type)->latest()->get();
            $main_machine_type = $request->main_machine_type;
            $data['main_machine_type'] = $main_machine_type;
            $main_machine_data = Machine::where('id', $main_machine_type)
                            ->where('status', 1)
                            ->first();
            $data['main_machine_name'] = $main_machine_data->machine_name;
            $main_machine_name = $main_machine_data->machine_name;
            $data['title'] = $main_machine_name. '-> Machine Sale';
            //get width
            $data['width'] = Width::get();

            //get color
            $data['color'] = Color::get();

            //get Shadding
            $data['shadding'] = Shadding::get();

            //get Shadding
            $data['shadding_options'] = ShaddingOptions::get();
            
            if(isset($request->oldcust) && $request->oldcust==1) {
                //get parties with machine type
                $data['parties'] = Party::with(['area', 'city'])
                ->whereHas('partywiseMachines', function ($query) use ($main_machine_type) {
                    $query->where('main_machine_type', $main_machine_type);
                })->latest()->get();
                return view('MachineSalesEntry.addmachine-oldcust', $data);
            } else {
                return view('MachineSalesEntry.new-create', $data);
            }
            
        } else {
          //return view('MachineSalesEntry.new-create', $data);  
        }   
        //return redirect()->route('MachineSales.new-create', ['main_machine_type' => $request->main_machine_type]);
    }

    public function changeDateFormate($date)
    {
        $newFormate = Carbon::parse($date);
        return $newFormate->format('Y-m-d');
    }

    /**
     * Display the specified resource.
     */
    public function show(MachineSalesEntry $machineSalesEntry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, $machineSalesEntry)
    {
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $data['title'] = $main_machine_name. '-> Edit installation';

        $machinedata = MachineSalesEntry::with('party')->find($machineSalesEntry);
        $data['machine'] = $machinedata;

        //get parties with machine type
        $data['parties'] = Party::with(['area', 'city'])
        ->where('id', $machinedata->party_id)
        ->whereHas('partywiseMachines', function ($query) use ($main_machine_type) {
            $query->where('main_machine_type', $main_machine_type);
        })->latest()->get();

        $partyfirms = PartyFirm::where('party_id', $machinedata->party_id)
                                ->where('is_active', 1)
                                ->get();
        $data['partyfirms'] =  $partyfirms;

        //get products with machine type
        $data['products'] = Product::where('id', $machinedata->product_id)->latest()->get();        
        return view('MachineSalesEntry.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        Validator::make($data, [
            "party_id" => "required"
        ]);
  
         $data = [
         'firm_id' => $request->firm_id,
         'status' => $request->status,
         'party_id' => $request->party_id,
         'product_id' => $request->product_id,
         'width'  => $request->width,
         'color' => $request->color,
         'shadding' => $request->shadding,
         'order_no'     => $request->order_no,
         'serial_no'    => $request->serial_no,
         'contenor_no' => $request->contenor_no,
         'main_machine_type' => $request->main_machine_type,
         'remarks' => $request->remarks,
         'is_active' => 1,
        ];
        $update = MachineSalesEntry::find($id)->update($data);
        if ($update) {
            Toastr::success('Machine Sales Entry Updated Successfully');
            return redirect()->route('MachineSales.index', ['main_machine_type' => $request->main_machine_type]);
        } else {
            Toastr::error('Machine Sales Entry Not Updated Successfully');
            return redirect()->route('MachineSales.edit', ['main_machine_type' => $request->main_machine_type, $id])->withInput();
        }
        return redirect()->route('MachineSales.index', ['main_machine_type' => $request->main_machine_type]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $machineSalesEntry = MachineSalesEntry::with('activeComplaints')->where('id', $id)->first();
        if (!empty($machineSalesEntry->activeComplaints)) {
            return response()->json(['success' => 0, 'message' => 'Machine Sales Entry has some Complaints']);
        } else {
            $machineSalesEntry->delete();
            $delete = true;
            if ($delete) {
                Toastr::success('Machine Sales Entry Deleted Successfully');
                return response()->json(['success' => 'Machine Sales Entry Deleted Successfully']);
            }
        }
    }

    public function expiryReport(Request $request)
    {
        $data['title'] = 'Machine Sales Expiry Report';
        if ($request->ajax()) {
            $machineSales = MachineSalesEntry::where('is_active', 1);

            if (isset($request->date_range)) {
                [$startDate, $endDate] = explode(' to ', $request->date_range);

                $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
                $machineSales->whereBetween('service_expiry_date', [$startDate, $endDate]);
            }
            
            $datsMachineSales = $machineSales->get();
            $data['machines'] = $datsMachineSales->sortBy(function ($row) {
                $startDate = Carbon::today();
                $endDate = Carbon::parse($row->service_expiry_date);
                return $startDate->diffInDays($endDate);
            });

            return DataTables::of($data['machines'])
                ->addIndexColumn()
                ->addColumn('product', function ($row) {
                    return $row->product->name . ' - ' . $row->serial_no . ' - ' . $row->mc_no;
                })
                ->addColumn('expiry_date', function ($row) {
                    return date('d-m-Y', strtotime($row->service_expiry_date));
                })
                ->addColumn('party', function ($row) {
                    return $row->party->name;
                })
                ->addColumn('mobile_no', function ($row) {
                    return  $row->party->phone_no;
                })
                ->addColumn('address', function ($row) {
                    return  $row->party->address;
                })
                ->addColumn('expiry_day', function ($row) {
                    $startDate = Carbon::today();
                    $endDate = Carbon::parse($row->service_expiry_date);

                    // Get the day count between the two dates
                    return $startDate->diffInDays($endDate);
                    // $date = Carbon::parse($row->service_expiry_date);
                    // return  $date->diffForHumans();
                })
                ->rawColumns(['product', 'expiry_date', 'expiry_day'])
                ->make(true);
        }
        return view('MachineSalesEntry.expiry_report', $data);
    }

    public function machineNumber(Request $request)
    {
        //$machineSalesEntry = MachineSalesEntry::where('party_id', $request->party_id)->get('mc_no');
        $machineSalesEntry = MachineSalesEntry::where('party_id', $request->party_id)->get('order_no');
        if (count($machineSalesEntry) > 0) {
            return response()->json([
                "success" => true,
                "message" => "Machine number get successfully",
                "data" => $machineSalesEntry,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Machine number not found",
                "data" => [],
            ]);
        }
    }

    public function exportMachineSalesExpiry()
    {
        return Excel::download(new MachineSalesExpiryExport, 'Machine Sales Expiry.xlsx');
    }
}
