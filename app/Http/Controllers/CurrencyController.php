<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Switch currency (IDR / USD)
     */
    public function switch(Request $request)
    {
        $currency = $request->input('currency', 'IDR');
        
        // Validasi hanya IDR atau USD
        if (!in_array($currency, ['IDR', 'USD'])) {
            $currency = 'IDR';
        }
        
        // Simpan ke session
        session(['currency' => $currency]);
        
        return back();
    }
}