<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class GemmaController extends Controller
{
    public function index()
    {
        // Cek apakah fitur AI aktif dari settings
        $aiEnabled = Setting::getBoolValue('ai_enabled', false);
        if (!$aiEnabled) {
            return redirect()->back()->with('error', 'AI feature is currently disabled.');
        }

        return view('gemma.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:500'
        ]);

        // TODO: Implementasi call ke Node.js API di port 3001
        // Untuk sekarang return dummy response
        return response()->json([
            'success' => true,
            'message' => 'Image generated successfully!',
            'image_url' => asset('img/jewel.png')
        ]);
    }
}
