<?php

namespace App\Http\Controllers;

use App\DataTables\partiesDataTable;
use App\DataTables\PartyDataTable;
use App\Http\Requests\PartyCreateRequest;
use App\Models\Party;
use App\Models\PartywiseMachine;
use App\Models\PartyFirm;
use App\Models\Machine;
use Flasher\Toastr\Laravel\Facade\Toastr;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Exports\PartyExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class PartyController extends Controller
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
        $data['title'] = $main_machine_name. '-> Party List';

        //$data['parties'] = Party::with('area', 'city')->latest()->get();
         $data['parties'] = Party::with(['area', 'city'])
        ->whereHas('partywiseMachines', function ($query) use ($main_machine_type) {
            $query->where('main_machine_type', $main_machine_type);
        })
        ->latest()
        ->get();
        if( $request->ajax() )
        {
            return DataTables::of($data['parties'])
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($main_machine_type) {
                    // dd($row);
                    $btn = "<div class='btn-group m-1'><a class='btn btn-sm btn-primary' href='" . route('parties.edit', ['main_machine_type' => $main_machine_type, $row->id]) . "'><i class='fa fa-edit'></i></a> ";
                    $btn .= "<a class='btn btn-sm btn-danger' href='javascript:void(0)' onclick='window.deleteParty(" . $row->id . ")'><i class='fa fa-trash'></i></a></div>";
                    return $btn;
                })
                ->addColumn('city_name', function($row) {
                    // dd($row->city->name);
                    // return 'rr';
                    return $row->city->name ?? '-';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('party.index', $data);
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
        $data['title'] = $main_machine_name. '-> Create Party';
        
        return view("party.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PartyCreateRequest $request)
    {
        $data = $request->all();
        if ($request->validated()) {
            $data['password'] = Hash::make($request->phone_no);
            if(!isset($request->is_active)) {
                $data['is_active'] = 0;
            }

            // Add Partywise Machine entry if not there for a party
            $pwmodata = Party::where('phone_no', $request->phone_no)->first();
            //dd(count($pwmdata));        
            if ($pwmodata) {
                //echo "In ifff".$request->main_machine_type;
                PartywiseMachine::create(['party_id' => $pwmodata->id, 'main_machine_type' => $request->main_machine_type]);
                //return redirect()->route('parties.create', ['main_machine_type' => $request->main_machine_type])->with($request->errors());
                return redirect()->route('parties.create', ['main_machine_type' => $request->main_machine_type])->withErrors(['custom_error' => 'Customer is already there with same Mobile Number']);
                
            } else {
               //echo "In else".$request->main_machine_type;
               $letters = strtoupper(Str::random(2)); // e.g. "AB"
               $numbers = rand(100, 999);              // e.g. "34"
               $uniqueCode = $letters . $numbers;    // "AB34"
               $data['code'] = $uniqueCode;
               $data['owner_name'] = $request->firm_name[0];
               $party = Party::create($data);
               PartywiseMachine::create(['party_id' => $party->id,'main_machine_type' => $request->main_machine_type]);

               // Party Firms Entry 
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

               Toastr::success('Party created successfully');
               return redirect()->route('parties.index', ['main_machine_type' => $request->main_machine_type]);
            }
        } else {
            return redirect()->route('parties.create', ['main_machine_type' => $request->main_machine_type])->with($request->errors());
        }
    }

    public function edit($id, Party $party)
    { 
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $data['title'] = $main_machine_name. '-> Edit Party';
        $data['party'] = $party;

        $data['party_firm_data'] = PartyFirm::where('party_id', $party->id)
                            ->where('is_active', 1)
                            ->get();
        
        return view("party.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PartyCreateRequest $request, Party $party)
    {
        $data = $request->all();
        if ($request->validated()) {
            $data['password'] = Hash::make($request->phone_no);
            if(!isset($request->is_active)) {
                $data['is_active'] = 0;
            }
            // Add Partywise Machine entry if not there for a party
            $pwmdata = PartywiseMachine::where(['party_id' => $party->id, 'main_machine_type' => $request->main_machine_type])->get();
            //dd(count($pwmdata));        
            if ($pwmdata->isEmpty()) {
               PartywiseMachine::create(['party_id' => $party->id,'main_machine_type' => $request->main_machine_type]);
            } else {
               //echo "In else".$request->main_machine_type;
            }
            $party->update($data);
            // Party Firms Entry 
               if (!empty($request->firm_name)) {
                    foreach ($request->firm_name as $index => $firmName) {
                        $pfirm = PartyFirm::where('id', $index)->first();
                        // Skip empty rows (important)
                        if (empty($firmName)) {
                            continue;
                        }
                        $pfdata = [
                            'firm_name'  => $request->firm_name[$index],
                            'firm_owner' => $request->firm_owner[$index] ?? null,
                            'firmowner_phone' => $request->firmowner_phone[$index] ?? null,
                            'firm_gst'     => $request->firm_gst[$index] ?? null,
                            'firm_address'    => $request->firm_address[$index] ?? null,
                        ];
                        $pfirm->update($pfdata);
                    }
                }

                // New Party Firms Entry 
               if (!empty($request->firm_namen)) {
                    foreach ($request->firm_namen as $index => $nfirmName) {
                        // Skip empty rows (important)
                        if (empty($nfirmName)) {
                            continue;
                        }
                        $pfdata = [
                            'party_id'   => $party->id,   // parent party id
                            'firm_name'  => $nfirmName,
                            'firm_owner' => $request->firm_ownern[$index] ?? null,
                            'firmowner_phone' => $request->firmowner_phonen[$index] ?? null,
                            'firm_gst'     => $request->firm_gstn[$index] ?? null,
                            'firm_address'    => $request->firm_addressn[$index] ?? null,
                        ];

                        PartyFirm::create($pfdata);
                    }
                }

            Toastr::success('Party updated successfully');
            return redirect()->route('parties.index', ['main_machine_type' => $request->main_machine_type]);
        } else {
            return redirect()->route('parties.edit', ['main_machine_type' => $request->main_machine_type, $party])->with($request->errors());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Party $party)
    {
        // if ($party->machineSales->count() > 0 || $party->complaints->count() > 0) {
        //     return response()->json(['message' => 'Party can not be deleted'], 422);
        // }
        $party->delete();
        Toastr::success('Party deleted successfully');
        return response()->json(['success' => 1,'message' => 'Party deleted successfully']);
    }

    public function partyFirms(Request $request) {

        $partyFirms = PartyFirm::where('party_id', $request->id)
                            ->where('is_active', 1)
                            ->get();
        return response()->json(['firms' => $partyFirms]);
    }

    public function partyCode(Request $request) {
        $party = Party::with('area', 'city')->where('code', $request->party_code)->first();
        return response()->json(['party' => $party]);
    }

    public function exportParty($id)
    {
        $main_machine_type = $id;
        return Excel::download(new PartyExport($main_machine_type), 'Party.xlsx');
    }
}
