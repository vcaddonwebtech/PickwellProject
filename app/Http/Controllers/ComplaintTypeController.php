<?php

namespace App\Http\Controllers;

use App\Models\ComplaintType;
use App\Models\Machine;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ComplaintTypeController extends Controller
{
    public function index($id, Request $request)
    {
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $data['title'] = $main_machine_name. '-> Complaint Types';
        
        $data['main_machine_type'] = $main_machine_type;
        if ($request->ajax()) {
            $complaintTypes = ComplaintType::where('main_machine_type', $main_machine_type)->latest()->get();
            return DataTables::of($complaintTypes)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($main_machine_type) {
                    $btn = '<div class="btn-group m-1"><a href="' . route('complaint-types.edit', ['main_machine_type' => $main_machine_type, 'complaint_type' => $row->id]) . '" class="edit btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="deleteComplaintType(' . $row->id . ')" class="edit btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('complaint_types.index', $data);
    }

    public function create($id, Request $request)
    {
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $data['title'] = $main_machine_name. '-> Create Complaint Type';
        $data['main_machine_type'] = $main_machine_type;
        return view('complaint_types.create', $data);
    }

    public function store(Request $request)
    {
        $main_machine_type = $request->main_machine_type;
        if($main_machine_type == 1) {
            $request->validate([
                'name' => 'required',
            ]);
        } else {
            $request->validate([
                'name' => 'required|unique:complaint_types',
            ]); 
        }
        $complaint = ComplaintType::create($request->all());
        return redirect()->route('complaint-types.index', ['main_machine_type' => $main_machine_type])->with('success', 'Complaint Type created successfully');
    }
    public function edit($id, ComplaintType $complaintType)
    {
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $data['title'] = $main_machine_name. '-> Edit Complaint Type';
        $data['complaint_type'] = $complaintType;
        return view('complaint_types.edit', $data);
    }
    public function update(Request $request, ComplaintType $complaintType)
    {
        $main_machine_type = $request->main_machine_type;
        if($main_machine_type == 1) {
            $request->validate([
                'name' => 'required',
            ]);
        } else {
            $request->validate([
            'name' => 'required|unique:complaint_types,name,' . $complaintType->id,
            ]); 
        }
        $complaintType->update($request->all());
        return redirect()->route('complaint-types.index', ['main_machine_type' => $main_machine_type])->with('success', 'Complaint type updated successfully');
        //return response()->json($complaintType);
    }

    public function destroy(ComplaintType $complaintType)
    {
        if($complaintType->complaints->count() > 0) {
            return response()->json(['success' => 0,'message' => 'Complaint Type can not be deleted'], 422);
        }
        $complaintType->delete();
        return response()->json(['message' => 'Complaint Type deleted successfully'], 200);
    }
}
