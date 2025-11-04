<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        // Ambil semua settings yang diperlukan
        $settings = [
            // AI Settings
            'ai_enabled' => Setting::getBoolValue('ai_enabled', false),
            'ai_quota_limit' => Setting::getNumericValue('ai_quota_limit', 20),
            
            // Financial Settings
            'tax_percentage' => Setting::getNumericValue('tax_percentage', 11),
            
            // Shipping Settings
            'shipping_domestic' => Setting::getNumericValue('shipping_domestic', 20000),
            'shipping_international' => Setting::getNumericValue('shipping_international', 350000),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'ai_enabled' => 'required|boolean',
            'ai_quota_limit' => 'required|integer|min:1|max:1000',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'shipping_domestic' => 'required|integer|min:0',
            'shipping_international' => 'required|integer|min:0',
        ]);

        // Update semua settings
        Setting::setValue('ai_enabled', $request->ai_enabled);
        Setting::setValue('ai_quota_limit', $request->ai_quota_limit);
        Setting::setValue('tax_percentage', $request->tax_percentage);
        Setting::setValue('shipping_domestic', $request->shipping_domestic);
        Setting::setValue('shipping_international', $request->shipping_international);

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully!');
    }
}
