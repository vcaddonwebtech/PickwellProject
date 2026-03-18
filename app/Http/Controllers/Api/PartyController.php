<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\PartyFirm;
use App\Models\Complaint;

class PartyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //$party = Party::where('is_active', 1)->get();
        $main_machine_type = $request->main_machine_type;
        $party = Party::with('firms')->whereHas('partywiseMachines', function ($query) use ($main_machine_type) {
            $query->where('main_machine_type', $main_machine_type);
        })->where('is_active', 1)->get();

        if (!empty($party)) {
            return response()->json([
                "success" => true,
                "message" => "Party fetch successfuly",
                "data" => $party,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong",
                "data" => (object)[],
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $party = Party::whereId($id)->get();

        if (!empty($party)) {
            return response()->json([
                "success" => true,
                "message" => "Party fetch successfuly",
                "data" => $party,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong",
                "data" => (object)[],
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $party = Party::find($id);
        $party->location_address = $request->location_address;
        $party->save();

        return response()->json([
            "success" => true,
            "message" => "Party update successfuly",
            "data" => $party,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Display the specified party details.
     */
    public function partyDetailByPartyCode(string $id)
    {
        $party = Party::where('code', $id)->first();

        if (!empty($party)) {
            return response()->json([
                "success" => true,
                "message" => "Party fetch successfuly",
                "data" => $party,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong",
                "data" => (object)[],
            ]);
        }
    }

    /**
     * Display the machines detail by party id.
     */
    public function machineDetailByPartId(string $id, Request $request)
    {
        //dd($request->main_machine_type);
        $main_machine_type = $request->main_machine_type;
        // $party = Party::where('id', $id)
        //     ->whereHas('partywiseMachines', function ($query) use ($main_machine_type) {
        //         $query->where('main_machine_type', $main_machine_type);
        //         })
        //     ->with('machineSales', 'machineSales.product')
        //     ->get();
        $party = Party::where('id', $id)
        ->whereHas('machineSales', function ($query) use ($main_machine_type) {
            $query->where(['main_machine_type' => $main_machine_type, 'status' => 3]);
            //$query->where('main_machine_type', $main_machine_type);
        })
        ->with(['machineSales' => function ($query)  use ($main_machine_type) {
            $query->where(['main_machine_type' => $main_machine_type, 'status' => 3])->with('product');
            //$query->where('main_machine_type', $main_machine_type)->with('product');
        }])
        ->get();

        foreach ($party[0]->machineSales as $item) {
            $complaintData = Complaint::where('sales_entry_id', $item->id)->where('status_id', '!=', 3)->first();
            if (!empty($complaintData)) {
                $item['c_message'] = $complaintData->complaint_no;
            } else {
                $item['c_message'] = '';
            }
        }

        if (!empty($party)) {
            return response()->json([
                "success" => true,
                "message" => "Party fetch successfuly",
                "data" => $party,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong",
                "data" => (object)[],
            ]);
        }
    }

    public function partyFirms(Request $request) {

        $partyFirms = PartyFirm::where('party_id', $request->party_id)
                            ->where('is_active', 1)
                            ->get();
        //return response()->json(['firms' => $partyFirms]);
        if (!empty($partyFirms)) {
            return response()->json([
                "success" => true,
                "message" => "Party firm fetch successfuly",
                "data" => $partyFirms,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Party firm not fetched",
                "data" => (object)[],
            ]);
        }
    }
}
