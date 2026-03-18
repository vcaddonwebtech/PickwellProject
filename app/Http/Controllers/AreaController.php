<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AreaController extends Controller
{
    //

    public function index(Request $request)
    {
        $data['title'] = 'Area List';
        if ($request->ajax()) {
            $areas = Area::latest()->get();
            return DataTables::of($areas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group m-1"><a href="' . route('areas.edit', $row->id) . '" class="edit btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="deleteArea(' . $row->id . ')" class="edit btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></div>';
                    // $btn .= '<a href="' . route('users.assignRoles', ['user' => $row->id]) . '" class="edit btn btn-primary btn-sm"><i class="fa fa-lock"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('area.index', $data);
    }

    public function create()
    {
        $data['title'] = 'Create Area';
        return view('area.create', $data);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // dd($request->all());
        $area = Area::create(['name' => $request->name]);
        return redirect()->route('areas.index')->with('success', 'Area created successfully');
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Area';
        $data['area'] = Area::find($id);
        return view('area.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        $area = Area::find($id);
        $area->update(['name' => $request->name]);
        return redirect()->route('areas.index')->with('success', 'Area updated successfully');
        return response()->json($area);
    }

    public function destroy($id)
    {
        $area = Area::find($id);
        if($area->engineers->count() > 0 || $area->party->count() > 0) {
            return response()->json(['success' => 0,'message' => 'Area can not be deleted, it is in use'], 422);
        }
        $area->delete();
        return response()->json(['message' => 'Area deleted successfully'], 200);
    }
}
