<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Requests\DepartmentRequest;
use Yajra\DataTables\Facades\DataTables;
use Flasher\Toastr\Laravel\Facade\Toastr;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['title'] = 'Department List';
        if ($request->ajax()) {
            $collection = Department::query()->latest()->get();
            return DataTables::of($collection)
                ->addIndexColumn()
                ->addColumn('action', function (Department $department) {
                    return "<div class='btn-group table-dropdown m-1'>
                                <a class='btn btn-sm btn-primary' href='" . route('department.edit', ['department' => $department]) . "'><i class='fa fa-edit'></i></a>
                                <a class='btn btn-sm btn-danger' href='javascript:void(0)' onclick='window.deleteParth(" . $department->id . ")'><i class='fa fa-trash'></i></a>
                            </div>";
                   })
                ->addColumn("name", function ($row) {
                    return $row->name;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('department.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Create Department';
        return view('department.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentRequest $request)
    {
        if ($request->validated()) {
            $request['date'] = isset($request->name) ? $request->name : null;

            $checkDate = Department::where('name', $request->name)->first();

            if(empty($checkDate)) {
                Department::create($request->all());
                
                return redirect()->route('department.index')->with('success', 'Department created successfully');
            } else {
                return redirect()->back()->with('success', 'Department already created');
            }
        } else {
            return redirect()->back()->withErrors($request->errors())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        $data['title'] = 'Edit Department';
        $data['department'] = $department;
        return view('department.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentRequest $request, Department $department)
    {
        if ($request->validated()) {
            $request['name'] = $request->name;

            $department->update($request->all());

            return redirect()->route('department.index')->with('success', 'Department updated successfully');
        } else {
            return redirect()->back()->withErrors($request->errors())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();
        Toastr::success('Department deleted successfully');
        return response()->json(['success' => 1, 'message' => 'Department deleted successfully']);
    }
}
