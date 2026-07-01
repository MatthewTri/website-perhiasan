<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Switch currency (IDR / USD)
     */
    public function switch(Request $request)
    {
        $currency = $request->input('currency', 'IDR');

        if (!Setting::isPaypalEnabled()) {
            session(['currency' => 'IDR']);
            return back();
        }
        
        // Validasi hanya IDR atau USD
        if (!in_array($currency, ['IDR', 'USD'])) {
            $currency = 'IDR';
        }
        
        // Simpan ke session
        session(['currency' => $currency]);
        
        return back();
    }
}