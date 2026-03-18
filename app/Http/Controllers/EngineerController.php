<?php

namespace App\Http\Controllers;

use App\Models\MachineSalesEntry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class EngineerController extends Controller
{

    public function index(Request $request)
    {
        $data['title'] = 'Engineer List';
        $data['engineers'] = User::role('engineer')->latest()->get();
        if ($request->ajax()) {
            return DataTables::of($data['engineers'])
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group m-1"><a href="' . route('engineers.edit', ['engineer' => $row->id]) . '" class="edit btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="deleteEngineer(' . $row->id . ')" class="edit btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></div>';
                    // $btn .= '<a href="' . route('users.assignRoles', ['user' => $row->id]) . '" class="edit btn btn-primary btn-sm"><i class="fa fa-lock"></i></a>';
                    return $btn;
                })
                ->addColumn('area', function ($row) {
                    return $row->area->name;
                })
                ->addColumn('is_active', function ($row) {
                    $active = '<span class="badge' . ($row->is_active == 1 ? ' bg-success' : ' bg-danger') . '"> ' . ($row->is_active == 1 ? 'Active' : 'Inactive') . '</span>';
                    return $active;
                })
                ->rawColumns(['action', 'roles', 'is_active'])
                ->make(true);
        }
        return view('engineers.index', $data);
    }

    public function create()
    {
        $data['title'] = 'Create Engineer';
        return view('engineers.create', $data);
    }

    public function store(Request $request)
    {
        $data['title'] = 'Create Engineer';
        $validator = Validator::make($request->all(), [
            'name' => ['required', Rule::unique('users')->where('phone_no', '!=', $request->get('phone_no'))],
            'email' => 'required|email|unique:users',
            'password' => 'required|unique:users',
            'confirm_password' => 'required|same:password',
            'phone_no' => 'required|max:10|min:10|regex:/^([0-9\s\-\+\(\)]*)$/|unique:users,phone_no',
            'area_id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $request['password'] = bcrypt($request->password);
        $request['role'] = 'engineer';
        $user = User::create($request->all());
        $user->assignRole('engineer');
        toastr()->success('Engineer created successfully');
        return redirect()->route('engineers.index')->with('success', 'Engineer created successfully');
    }
    public function edit(User $engineer)
    {
        $data['title'] = 'Edit Engineer';
        $data['engineer'] = $engineer;
        return view('engineers.edit', $data);
    }

    public function update(Request $request, User $engineer)
    {
        $data['title'] = 'Edit Engineer';
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $engineer->id,
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if (!isset($request->is_active)) {
            $request['is_active'] = 0;
        }
        if (!empty($request->password)) {
            $request['password'] = bcrypt($request->password);
        } else {
            unset($request['password']);
            unset($request['confirm_password']);
        }
        $engineer->update($request->all());
        $engineer->assignRole('engineer');
        toastr()->success('Engineer updated successfully');
        return redirect()->route('engineers.index')->with('success', 'Engineer updated successfully');
    }

    public function destroy(User $engineer)
    {
        if($engineer->complaints()->count() > 0 || MachineSalesEntry::where('mic_fitting_engineer_id', $engineer->id)->orWhere('delivery_engineer_id', $engineer->id)->count() > 0) {
            return response()->json([
                'error' => 'Engineer can not be deleted its associated with complaints or machine sales entry',
            ], 422);
        }
        $engineer->delete();
        toastr()->success('Engineer deleted successfully');
        return redirect()->route('engineers.index')->with('success', 'Engineer deleted successfully');
    }
}
