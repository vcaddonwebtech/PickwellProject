<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\HolidayRequest;
use Flasher\Toastr\Laravel\Facade\Toastr;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['title'] = 'Holiday List';
        if ($request->ajax()) {
            $collection = Holiday::query()->latest()->get();
            return DataTables::of($collection)
                ->addIndexColumn()
                ->addColumn('action', function (Holiday $holiday) {
                    return "<div class='btn-group table-dropdown m-1'>
                                <a class='btn btn-sm btn-primary' href='" . route('holiday.edit', ['holiday' => $holiday]) . "'><i class='fa fa-edit'></i></a>
                                <a class='btn btn-sm btn-danger' href='javascript:void(0)' onclick='window.deleteParth(" . $holiday->id . ")'><i class='fa fa-trash'></i></a>
                            </div>";
                   })
                ->addColumn("date", function ($row) {
                    return date('d-m-Y', strtotime($row->date));
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('holiday.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Create Holiday';
        return view('holiday.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HolidayRequest $request)
    {
        if ($request->validated()) {
            $request['date'] = isset($request->date) ? date('Y-m-d', strtotime($request->date)) : null;

            $checkDate = Holiday::where('date',$request['date'])->first();

            if(empty($checkDate)) {
                Holiday::create($request->all());
                
                return redirect()->route('holiday.index')->with('success', 'Holiday created successfully');
            } else {
                return redirect()->back()->with('success', 'Holiday already created');
            }
        } else {
            return redirect()->back()->withErrors($request->errors())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Holiday $holiday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Holiday $holiday)
    {
        $data['title'] = 'Edit Holiday';
        $data['holiday'] = $holiday;
        return view('holiday.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HolidayRequest $request, Holiday $holiday)
    {
        if ($request->validated()) {
            $request['date'] = date('Y-m-d', strtotime($request->date));

            $holiday->update($request->all());

            return redirect()->route('holiday.index')->with('success', 'Holiday updated successfully');
        } else {
            return redirect()->back()->withErrors($request->errors())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        Toastr::success('Parts inventory deleted successfully');
        return response()->json(['success' => 1, 'message' => 'Parts inventory deleted successfully']);
    }
}
