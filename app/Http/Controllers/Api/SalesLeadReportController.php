<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SalesPerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesLeadReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $date = isset($request->date) ? date('Y-m-d', strtotime($request->date)) : today();

        // Fetch all sales leads for today
        $salesLeadCount = SalesPerson::whereDate('date', $date)->count();

        return response()->json([
            'status' => $salesLeadCount > 0 ? true : false,
            'data' => $salesLeadCount,
            'total_records' => $salesLeadCount,
        ], $salesLeadCount > 0 ? 200 : 404);
    }

    // salse lead group wise
    public function groupWiseSalesLead(Request $request)
    {
        $date = isset($request->date) ? date('Y-m-d', strtotime($request->date)) : today();

        // Fetch sales leads grouped by sales person
        $salesLeads = SalesPerson::with('salseUserDetail')->select('sale_user_id', DB::raw('COUNT(*) as total_sales'))
            ->whereDate('date', $date)
            ->groupBy('sale_user_id')
            ->get();

        return response()->json([
            'status' => $salesLeads->count() > 0 ? true : false,
            'data' => $salesLeads,
            'total_records' => $salesLeads->count(),
        ], $salesLeads->count() > 0 ? 200 : 404);
    }

    // salse lead user wise
    public function userWiseSalesLead(Request $request)
    {
        $date = isset($request->date) ? date('Y-m-d', strtotime($request->date)) : today();

        // Fetch sales leads grouped by sales person
        $salesLeads = SalesPerson::with('salseUserDetail', 'saleAssignUserDetail', 'product', 'statusDetail', 'salesPersonTask', 'salesPersonTask.prioritys', 'salesPersonTask.assignUserDetail')
            ->whereDate('date', $date)
            ->where('sale_user_id', $request->sale_user_id)
            ->get();

        return response()->json([
            'status' => $salesLeads->count() > 0 ? true : false,
            'data' => $salesLeads
        ], $salesLeads->count() > 0 ? 200 : 404);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
