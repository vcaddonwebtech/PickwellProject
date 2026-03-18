<?php

namespace App\Http\Controllers;

use App\Models\ServiceType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $data['title'] = 'Service Type List';
        if ($request->ajax()) {
            $ServiceTypes = ServiceType::latest()->get();
            return DataTables::of($ServiceTypes)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group m-1"><a href="' . route('service-types.edit', ['service_type' => $row->id]) . '" class="edit btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="deleteServiceType(' . $row->id . ')" class="edit btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('service_types.index', $data);
    }

    public function create()
    {
        $data['title'] = 'Create Service Type';
        return view('service_types.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:service_types',
        ]);
        $ServiceType = new ServiceType();
        $ServiceType->name = $request->name;
        $ServiceType->save();
        return redirect()->route('service-types.index')->with('success', 'Service Type created successfully');
    }
    public function edit(ServiceType $ServiceType)
    {
        $data['title'] = 'Edit Service Type';
        $data['service_type'] = $ServiceType;
        return view('service_types.edit', $data);
    }
    public function update(Request $request, ServiceType $ServiceType)
    {
        $request->validate([
            'name' => 'required|unique:service_types,name,' . $ServiceType->id,
        ]);
        $ServiceType->name = $request->name;
        $ServiceType->save();
        return redirect()->route('service-types.index')->with('success', 'Service Type updated successfully');
    }

    public function destroy(ServiceType $ServiceType)
    {
        if ($ServiceType->machineSales()->exists() || $ServiceType->complaints()->exists()) {
            return response()->json([
                'success' => 0,
                'message' => 'Service Type cannot be deleted as it is associated with machine sales'
            ], 422);
        }
        $ServiceType->delete();
        return response()->json($ServiceType);
    }
}
