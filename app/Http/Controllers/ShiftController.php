<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use App\Http\Requests\ShiftRequest;
use Yajra\DataTables\Facades\DataTables;
use Flasher\Toastr\Laravel\Facade\Toastr;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['title'] = 'Shift List';
        if ($request->ajax()) {
            $collection = Shift::query()->latest()->get();
            return DataTables::of($collection)
                ->addIndexColumn()
                ->addColumn('action', function (Shift $shift) {
                    return "<div class='btn-group table-dropdown m-1'>
                                <a class='btn btn-sm btn-primary' href='" . route('shift.edit', ['shift' => $shift]) . "'><i class='fa fa-edit'></i></a>
                                <a class='btn btn-sm btn-danger' href='javascript:void(0)' onclick='window.deleteParth(" . $shift->id . ")'><i class='fa fa-trash'></i></a>
                            </div>";
                   })
                ->addColumn("title", function ($row) {
                    return $row->title;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('shift.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Create Shift';
        return view('shift.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShiftRequest $request)
    {
        if ($request->validated()) {
            $request['title'] = isset($request->title) ? $request->title : null;

            $checkDate = Shift::where('title', $request->title)->first();

            if(empty($checkDate)) {
                Shift::create($request->all());
                
                return redirect()->route('shift.index')->with('success', 'Shift created successfully');
            } else {
                return redirect()->back()->with('success', 'Shift already created');
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
    public function edit(Shift $shift)
    {
        $data['title'] = 'Edit Shift';
        $data['shift'] = $shift;
        return view('shift.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShiftRequest $request, Shift $shift)
    {
        if ($request->validated()) {
            $request['title'] = $request->title;

            $shift->update($request->all());

            return redirect()->route('shift.index')->with('success', 'Shift updated successfully');
        } else {
            return redirect()->back()->withErrors($request->errors())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shift $shift)
    {
        $shift->delete();
        Toastr::success('Shift deleted successfully');
        return response()->json(['success' => 1, 'message' => 'Shift deleted successfully']);
    }
}

