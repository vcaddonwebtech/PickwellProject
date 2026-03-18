<?php

namespace App\Http\Controllers;

use App\Models\ItemPart;
use Flasher\Toastr\Laravel\Facade\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ItemPartsController extends Controller
{
    public function index(Request $request)
    {
        $data['title'] = 'Item Parts';
        $item_parts = ItemPart::latest()->get();
        if ($request->ajax()) {
            return DataTables::of($item_parts)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group m-1"><a href="' . route('item-parts.edit', ['item_part' => $row->id]) . '" class="edit btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="deleteItemPart(' . $row->id . ')" class="edit btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></div>';
                    // $btn .= '<a href="' . route('item_parts.assignRoles', ['user' => $row->id]) . '" class="edit btn btn-primary btn-sm"><i class="fa fa-lock"></i></a>';
                    return $btn;
                })
                ->addColumn('product_group', function ($row) {

                    return $row->productGroup->name;
                })
                ->rawColumns(['action', 'product_group'])
                ->make(true);
        }
        return view('item-parts.index', $data);
    }

    public function create()
    {
        $data['title'] = 'Create Item Parts';
        return view("item-parts.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|unique:item_parts',
            'product_group_id' => 'required',
            'rate' => 'required|numeric|min:0',
        ]);
        if ($validator->fails()) {
            return redirect()->route('item-parts.create')->with($validator->errors());
        }
        ItemPart::create($data);
        Toastr::success('item_part created successfully');
        return redirect()->route('item-parts.index');
    }


    public function edit($id)
    {
        $item_part = ItemPart::findOrFail($id);
        $data['title'] = 'Edit item_part';
        // dd($item_part);
        $data['item_part'] = $item_part;
        return view("item-parts.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemPart $item_part)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            "name" => "required|unique:item_parts,name," . $item_part->id,
            "product_group_id" => "required",
            "hsn_code" => "required",
            "gst" => "required",
            "rate" => "required",
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with($validator->errors());
        }
        ItemPart::findOrFail($item_part->id)->update($data);
        Toastr::success('item_part updated successfully');
        return redirect()->route('item-parts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemPart $item_part)
    {
        // if ($item_part->machineSales) {
        //     return response()->json(['message' => 'item_part can not be deleted'], 422);
        // }
        $item_part->delete();
        Toastr::success('item_part deleted successfully');
        return response()->json(['success' => 1,'message' => 'item_part deleted successfully']);
    }
}
