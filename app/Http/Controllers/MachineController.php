<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Flasher\Toastr\Laravel\Facade\Toastr;

class MachineController extends Controller
{
    /**
     * Display a listing of machines.
     */
    public function index(Request $request)
    {
        //dd("Nirav test");
        //$machines = Machine::orderBy('id', 'desc')->paginate(10);
        $data['title'] = 'Machine List';
        if ($request->ajax()) {
            $collection = Machine::query()->latest()->get();
            return DataTables::of($collection)
                ->addIndexColumn()
                ->addColumn('action', function (Machine $machine) {
                    return "<div class='btn-group table-dropdown m-1'>
                                <a class='btn btn-sm btn-primary' href='" . route('machines.edit', ['machine' => $machine]) . "'><i class='fa fa-edit'></i></a>
                                <a class='btn btn-sm btn-danger' href='javascript:void(0)' onclick='window.deleteParth(" . $machine->id . ")'><i class='fa fa-trash'></i></a>
                            </div>";
                   })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('machines.index', $data);
    }

    /**
     * Show the form for creating a new machine.
     */
    public function create()
    {
        $data['title'] = 'Create Machine';
        return view('machines.create', $data);
    }

    /**
     * Store a newly created machine.
     */
    public function store(Request $request)
    {
        $request->validate([
            'machine_name' => 'required|string|max:255',
            'machine_specification' => 'nullable|string|max:255',
            'machine_rpm' => 'nullable|string|max:255',
        ]);

        Machine::create($request->all());

        return redirect()->route('machines.index')
            ->with('success', 'Machine created successfully.');
    }

    /**
     * Show the form for editing the specified machine.
     */
    public function edit(Machine $machine)
    {
        $data['title'] = 'Edit Machine';
        $data['machine'] = $machine;
        return view('machines.edit', $data);
    }

    /**
     * Update the specified machine.
     */
    public function update(Request $request, Machine $machine)
    {
        $request->validate([
            'machine_name' => 'required|string|max:255',
            'machine_specification' => 'nullable|string|max:255',
            'machine_rpm' => 'nullable|string|max:255',
            
        ]);

        $machine->update($request->all());

        return redirect()->route('machines.index')
            ->with('success', 'Machine updated successfully.');
    }

    /**
     * Remove the specified machine.
     */
    public function destroy(Machine $machine)
    {
        $machine->delete();

        return redirect()->route('machines.index')
            ->with('success', 'Machine deleted successfully.');
    }
}
