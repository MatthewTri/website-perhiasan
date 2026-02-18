<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ChatHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GemmaController extends Controller
{
    public function index()
    {
    // Cek apakah fitur AI aktif dari settings
        $aiEnabled = Setting::getBoolValue('ai_enabled', false);
        if (!$aiEnabled) {
            return redirect()->back()->with('error', 'AI feature is currently disabled.');
        }

        // Ambil chat history untuk user yang login
        $history = collect(); // Default empty collection untuk guest
        $isGuest = !Auth::check();
        
        if (Auth::check()) {
            $history = Auth::user()->chatHistory;
        }

        return view('gemma.index', compact('history', 'isGuest'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:500'
        ]);

        // Cek apakah user login (untuk save history)
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'error' => 'You must be logged in to generate images.'
            ], 401);
        }

        try {
            // Call ke Node.js API di port 3001
            $response = Http::timeout(60)->post('http://localhost:3001/generate-image', [
                'prompt' => $request->prompt
            ]);

            if ($response->successful()) {
                // Success - dapat gambar
                $data = $response->json();
                
                if (!isset($data['image_url'])) {
                    return response()->json([
                        'success' => false,
                        'error' => 'No image URL received from AI service.'
                    ], 500);
                }

                $tempUrl = $data['image_url'];

                // Download gambar dari Node.js
                $imageResponse = Http::timeout(30)->get($tempUrl);
                
                if (!$imageResponse->successful()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Failed to download generated image.'
                    ], 500);
                }

                $imageContents = $imageResponse->body();

                // Buat direktori jika belum ada
                if (!Storage::disk('public')->exists('ai_generations')) {
                    Storage::disk('public')->makeDirectory('ai_generations');
                }

                // Buat nama file unik
                $fileName = 'ai_generations/' . Str::uuid() . '.png';

                // Simpan ke storage/app/public/ai_generations
                Storage::disk('public')->put($fileName, $imageContents);

                // URL permanen
                $permanentUrl = asset('storage/' . $fileName);

                // Simpan ke chat history
                $chatHistory = ChatHistory::create([
                    'user_id' => Auth::id(),
                    'prompt' => $request->prompt,
                    'image_url' => $permanentUrl,
                    'original_filename' => $fileName
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Image generated successfully!',
                    'image_url' => $permanentUrl,
                    'chat_id' => $chatHistory->id
                ]);

            } else {
                // Error dari microservice (400, 500, dll)
                $errorData = $response->json();
                return response()->json([
                    'success' => false,
                    'error' => $errorData['error'] ?? 'Unknown error from AI service',
                    'status_code' => $response->status()
                ], $response->status());
            }

        } catch (\Exception $e) {
            
            // Handle specific connection errors
            if (str_contains($e->getMessage(), 'Connection refused') || 
                str_contains($e->getMessage(), 'cURL error 7')) {
                return response()->json([
                    'success' => false,
                    'error' => 'AI service is not available. Please make sure the Node.js server is running on port 3001.'
                ], 503);
            }
            
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    // Method untuk hapus chat history
    public function deleteHistory($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false], 401);
        }

        $chatHistory = ChatHistory::where('id', $id)
                                 ->where('user_id', Auth::id())
                                 ->first();

        if ($chatHistory) {
            // Hapus file gambar juga
            if (Storage::disk('public')->exists($chatHistory->original_filename)) {
                Storage::disk('public')->delete($chatHistory->original_filename);
            }
            
            $chatHistory->delete();
            
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
