<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ImportData;
use App\Imports\PartyImportClass;
use App\Imports\ProductImportClass;
use App\Imports\MachineSalesImportClass;
use App\Imports\ContactPersonImportClass;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Party;
use Illuminate\Support\Facades\Hash;

class ImportDataController extends Controller
{
    public function importData() {
        $data['title'] = 'Import Data';
        return view('import_data.index', $data);
    }

    public function finalApp() {
        $data['title'] = 'Final App Data';
        return view('import_data.finalApp', $data);
    }
    
    public function store(Request $request) {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Get the uploaded file
        $file = $request->file('file');

        // Process the Excel file
        Excel::import(new ImportData, $file);
 
        return redirect()->back()->with('success', 'Excel file imported successfully!');
    }

    public function finalAppPost(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Get the uploaded file
        $filedata = $request->file('file');

        // Process the Excel file
        Excel::import(new PartyImportClass, $filedata);
 
        return redirect()->back()->with('success', 'Excel file imported successfully!');
    }

    public function product(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Get the uploaded file
        $filedata = $request->file('file');

        // Process the Excel file
        Excel::import(new ProductImportClass, $filedata);
 
        return redirect()->back()->with('success', 'Excel file imported successfully!');
    }

    public function machineSales(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Get the uploaded file
        $filedata = $request->file('file');

        // Process the Excel file
        Excel::import(new MachineSalesImportClass, $filedata);
 
        return redirect()->back()->with('success', 'Excel file imported successfully!');
    }

    public function contactPerson(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Get the uploaded file
        $filedata = $request->file('file');

        // Process the Excel file
        Excel::import(new ContactPersonImportClass, $filedata);
 
        return redirect()->back()->with('success', 'Excel file imported successfully!');
    }
}
