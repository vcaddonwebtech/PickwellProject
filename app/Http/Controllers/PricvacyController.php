<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PricvacyController extends Controller
{
    public function index()
    {
        return view('privacypolicy');
    }
}
