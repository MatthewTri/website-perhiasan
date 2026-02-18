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
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'ai_enabled' => 'required|boolean',
            'ai_quota_limit' => 'required|integer|min:1|max:1000',
        ]);

        // Update semua settings
        Setting::setValue('ai_enabled', $request->ai_enabled);
        Setting::setValue('ai_quota_limit', $request->ai_quota_limit);

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully!');
    }
}
