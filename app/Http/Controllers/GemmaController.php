<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GemmaController extends Controller
{
    public function index()
    {
        return view('gemma.index');
    }

    public function generate(Request $request)
    {
        
    }
}
