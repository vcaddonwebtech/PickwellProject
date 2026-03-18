<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
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

        $data['title'] = $main_machine_name. ' -> Product List';

        if ($request->ajax()) {
            $products = Product::where('product_group_id', $id)->latest()->get();
            $datatable =  DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($main_machine_type) {

                    $btn = '<div class="btn-group m-1"><a href="' . route('products.edit', ['main_machine_type' => $main_machine_type, $row->id]) . '" class="edit btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="deleteProduct(' . $row->id . ')" class="edit btn btn-danger btn-sm"><i class="fa fa-trash"></i></a> </div>';
                    // $btn .= '<a href="javascript:void(0)" onclick="ItemAdd(' . $row->id . ')" class="edit btn btn-primary btn-sm"><i class="fa fa-lock"></i></a>';
                    return $btn;
                })
                ->addColumn('product_group', function ($row) {

                    return $row->productGroup->name ?? '-';
                })
                ->rawColumns(['action'])
                ->make(true);
            return $datatable;
        }
        return view('products.index', $data);
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
        $data['title'] = $main_machine_name. ' -> Create Product';

        return view('products.create', $data);
    }
    public function store(Request $request)
    {
        $validataion = Validator::make($request->all(), [
            'name' => 'required|unique:products',
            'product_group_id' => 'required',
        ]);
        if ($validataion->fails()) {
            return redirect()->back()->withErrors($validataion->errors())->withInput();
        }
        $product = Product::create($request->all());
        return redirect()->route('products.index', ['main_machine_type' => $request->main_machine_type])->with('success', 'Product created successfully');
    }

    public function edit($id, Product $product)
    {
        $main_machine_type = $id;
        $data['main_machine_type'] = $main_machine_type;
        $main_machine_data = Machine::where('id', $id)
                            ->where('status', 1)
                            ->first();
        $data['main_machine_name'] = $main_machine_data->machine_name;
        $main_machine_name = $main_machine_data->machine_name;
        $data['title'] = $main_machine_name. ' -> Edit Product';
        $data['product'] = $product;
        
        return view('products.edit', $data);
    }
    public function update(Request $request, Product $product)
    {
        $validataion = Validator::make($request->all(), [
            'name' => 'required|unique:products,name,' . $product->id,
            'product_group_id' => 'required',
        ]);
        if ($validataion->fails()) {
            return redirect()->back()->withErrors($validataion->errors())->withInput();
        }
        $product->update($request->all());
        return redirect()->route('products.index', ['main_machine_type' => $request->main_machine_type])->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        if ($product->machineSales->count() > 0) {
            return response()->json(['success' => 0, 'message' => 'Product can not be deleted'], 400);
        }
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully'], 200);
    }

    public function addProductGroup(Request $request)
    {
        $validataion = Validator::make($request->all(), [
            'name' => 'required|unique:product_groups',
        ]);
        if ($validataion->fails()) {
            return redirect()->back()->withErrors($validataion->errors())->withInput();
        }
        $productGroup = ProductGroup::create(['name' => $request->name]);
        return  redirect()->route('product-groups.index')->with('success', 'Product group created successfully');
    }

    public function productGroupIndex(Request $request)
    {
        $data['title'] = 'Product Group List';
        if ($request->ajax()) {
            $productGroup = ProductGroup::all();
            $datatable =  DataTables::of($productGroup)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group m-1"><a href="' . route('product-groups.edit', $row->id) . '"  class="edit btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="deleteProductGroup(' . $row->id . ')" class="edit btn btn-danger btn-sm"><i class="fa fa-trash"></i></a> </div>';
                    // $btn .= '<a href="javascript:void(0)" onclick="ItemAdd(' . $row->id . ')" class="edit btn btn-primary btn-sm"><i class="fa fa-lock"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            return $datatable;
        }
        return view('product_groups.index', $data);
    }

    public function productGroupCreate()
    {
        $data['title'] = 'Create Product Group';
        return view('product_groups.create', $data);
    }
    public function productGroupStore(Request $request)
    {
        $validataion = Validator::make($request->all(), [
            'name' => 'required|unique:product_groups',
        ]);
        if ($validataion->fails()) {
            return redirect()->back()->withErrors($validataion->errors())->withInput();
        }
        $productGroup = ProductGroup::create(['name' => $request->name]);
        return redirect()->route('product-groups.index')->with('success', 'Product group created successfully');
    }
    public function productGroupEdit(ProductGroup $productGroup)
    {
        $data['title'] = 'Edit Product Group';
        $data['product_group'] = $productGroup;
        return view('product_groups.edit', $data);
        // return response()->json(['productGroup' => $productGroup]);
    }
    public function productGroupUpdate(Request $request, ProductGroup $productGroup)
    {
        $validataion = Validator::make($request->all(), [
            'name' => 'required|unique:product_groups,name,' . $productGroup->id,
        ]);
        if ($validataion->fails()) {
            return redirect()->back()->withErrors($validataion->errors())->withInput();
        }
        // dd($request->all(), $productGroup);
        $productGroup->update(['name' => $request->name]);
        return redirect()->route('product-groups.index')->with('success', 'Product group updated successfully');
    }

    public function productGroupDestroy(ProductGroup $productGroup)
    {
        if ($productGroup->products->count() > 0) {
            return response()->json(['message' => 'Product group can not be deleted It has products'], 422);
        }
        $productGroup->delete();
        return response()->json(['message' => 'Product group deleted successfully'], 200);
    }
}
