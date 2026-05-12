<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ChatHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class GemmaController extends Controller
{
    private function imageBytesFromAiPayload(array $data): string
    {
        // Preferred: explicit base64 payload
        if (!empty($data['image_b64'])) {
            $bytes = base64_decode((string) $data['image_b64'], true);
            if ($bytes === false) {
                throw new \RuntimeException('Failed to decode image_b64 from AI service');
            }
            return $bytes;
        }

        $imageUrl = (string) ($data['image_url'] ?? '');
        if ($imageUrl === '') {
            throw new \RuntimeException('No image_url/image_b64 returned by AI service');
        }

        // Support data URLs: data:image/png;base64,....
        if (preg_match('/^data:image\/(png|jpe?g|webp);base64,(.+)$/is', $imageUrl, $m)) {
            $bytes = base64_decode($m[2], true);
            if ($bytes === false) {
                throw new \RuntimeException('Failed to decode base64 data URL from AI service');
            }
            return $bytes;
        }

        // Treat as remote URL
        $imageResponse = Http::timeout(30)->get($imageUrl);
        if (!$imageResponse->successful()) {
            throw new \RuntimeException('Failed to download image from AI service URL');
        }
        return $imageResponse->body();
    }

    public function index()
    {
        $aiEnabled = Setting::getBoolValue('ai_enabled', false);
        if (!$aiEnabled) {
            return redirect()->back()->with('error', 'AI feature is currently disabled.');
        }

        $history  = collect();
        $isGuest  = !Auth::check();
        $quotaInfo = null;

        if (Auth::check()) {
            $user       = Auth::user();
            $quotaLimit = Setting::getNumericValue('ai_quota_limit', 20);

            // Reset quota jika hari baru
            $today = now()->toDateString();
            if ($user->last_prompt_date !== $today) {
                $user->update([
                    'ai_prompt_count'  => 0,
                    'last_prompt_date' => $today,
                ]);
                $user->refresh();
            }

            $history   = $user->chatHistory;
            $quotaInfo = [
                'used'        => $user->ai_prompt_count,
                'limit'       => $quotaLimit,
                'remaining'   => $quotaLimit - $user->ai_prompt_count,
                'is_exceeded' => $user->ai_prompt_count >= $quotaLimit,
            ];
        }

        return view('gemma.index', compact('history', 'isGuest', 'quotaInfo'));
    }

    // ─── GENERATE IMAGE ──────────────────────────────────────────────────────────
    public function generate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'error'   => 'You must be logged in to generate images.',
            ], 401);
        }

        $user       = Auth::user();
        $quotaLimit = Setting::getNumericValue('ai_quota_limit', 20);

        // Reset quota jika hari baru
        $today = now()->toDateString();
        if ($user->last_prompt_date !== $today) {
            $user->update([
                'ai_prompt_count'  => 0,
                'last_prompt_date' => $today,
            ]);
            $user->refresh();
        }

        if ($user->ai_prompt_count >= $quotaLimit) {
            $used = (int) $user->ai_prompt_count;
            $remaining = max($quotaLimit - $used, 0);

            return response()->json([
                'success'     => false,
                'error'       => 'quota_exceeded',
                'quota_limit' => $quotaLimit,
                'quota'       => [
                    'used'        => $used,
                    'limit'       => (int) $quotaLimit,
                    'remaining'   => (int) $remaining,
                    'is_exceeded' => true,
                ],
                'message'     => "You have reached your daily limit of {$quotaLimit} AI generations. Please try again tomorrow.",
            ], 403);
        }

        try {
            $aiUrl  = config('services.ai.url', env('AI_SERVICE_URL'));
            $apiKey = env('INTERNAL_AI_KEY');

            $response = Http::withHeaders(['x-api-key' => $apiKey])
                ->timeout(120)
                ->post($aiUrl . '/generate-image', [
                    'prompt' => $request->prompt,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                // Download / decode & simpan gambar ke storage
                $imageBytes = $this->imageBytesFromAiPayload(is_array($data) ? $data : []);

                if (!Storage::disk('public')->exists('ai_generations')) {
                    Storage::disk('public')->makeDirectory('ai_generations');
                }

                $fileName = 'ai_generations/' . Str::uuid() . '.png';
                Storage::disk('public')->put($fileName, $imageBytes);
                $permanentUrl = asset('storage/' . $fileName);

                $chatHistory = ChatHistory::create([
                    'user_id'           => Auth::id(),
                    'prompt'            => $request->prompt,
                    'image_url'         => $permanentUrl,
                    'original_filename' => $fileName,
                ]);

                $user->increment('ai_prompt_count');
                $user->refresh();

                $used = (int) $user->ai_prompt_count;
                $remaining = max($quotaLimit - $used, 0);

                return response()->json([
                    'success'         => true,
                    'message'         => 'Image generated successfully!',
                    'image_url'       => $permanentUrl,
                    'chat_id'         => $chatHistory->id,
                    'remaining_quota' => $quotaLimit - $user->ai_prompt_count,
                    'quota'           => [
                        'used'        => $used,
                        'limit'       => (int) $quotaLimit,
                        'remaining'   => (int) $remaining,
                        'is_exceeded' => $used >= $quotaLimit,
                    ],
                ]);

            } else {
                $errorData = $response->json();
                return response()->json([
                    'success'     => false,
                    'error'       => $errorData['error'] ?? 'Unknown error from AI service',
                    'status_code' => $response->status(),
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('GemmaController@generate failed', [
                'message' => $e->getMessage(),
            ]);
            if (str_contains($e->getMessage(), 'Connection refused') ||
                str_contains($e->getMessage(), 'cURL error 7')) {
                return response()->json([
                    'success' => false,
                    'error'   => 'AI service is not available. Please make sure the Node.js server is running.',
                ], 503);
            }

            return response()->json([
                'success' => false,
                'error'   => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    // ─── EDIT IMAGE ───────────────────────────────────────────────────────────────
    // Catatan: Node.js sekarang pakai strategi GPT merge + DALL-E 3 regenerate.
    // Controller ini TIDAK perlu kirim gambar asli lagi — cukup kirim JSON:
    //   { chat_id, instruction }
    // Node.js akan baca original_prompt dari ChatHistory dan merge sendiri.
    // ─────────────────────────────────────────────────────────────────────────────
    public function edit(Request $request)
    {
        $request->validate([
            'chat_id'     => 'required|integer',
            'instruction' => 'required|string|max:500',
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'error'   => 'You must be logged in to edit images.',
            ], 401);
        }

        $user       = Auth::user();
        $quotaLimit = Setting::getNumericValue('ai_quota_limit', 20);

        // Reset quota jika hari baru
        $today = now()->toDateString();
        if ($user->last_prompt_date !== $today) {
            $user->update([
                'ai_prompt_count'  => 0,
                'last_prompt_date' => $today,
            ]);
            $user->refresh();
        }

        if ($user->ai_prompt_count >= $quotaLimit) {
            $used = (int) $user->ai_prompt_count;
            $remaining = max($quotaLimit - $used, 0);

            return response()->json([
                'success'     => false,
                'error'       => 'quota_exceeded',
                'quota_limit' => $quotaLimit,
                'quota'       => [
                    'used'        => $used,
                    'limit'       => (int) $quotaLimit,
                    'remaining'   => (int) $remaining,
                    'is_exceeded' => true,
                ],
                'message'     => "You have reached your daily limit of {$quotaLimit} AI generations. Please try again tomorrow.",
            ], 403);
        }

        // Ambil chat history milik user ini
        $chat = ChatHistory::where('id', $request->chat_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$chat) {
            return response()->json([
                'success' => false,
                'error'   => 'Design not found.',
            ], 404);
        }

        try {
            $aiUrl  = config('services.ai.url', env('AI_SERVICE_URL'));
            $apiKey = env('INTERNAL_AI_KEY');

            // ✅ Cukup kirim JSON — tidak perlu upload gambar lagi
            // Node.js akan merge original_prompt + instruction via GPT,
            // lalu generate ulang dengan DALL-E 3
            $response = Http::withHeaders(['x-api-key' => $apiKey])
                ->timeout(120)
                ->post($aiUrl . '/edit-image', [
                    'instruction'     => $request->instruction,
                    'original_prompt' => $chat->prompt, // berisi riwayat semua modifikasi
                ]);

            if ($response->successful()) {
                $data = $response->json();

                // Node.js bisa return image_url (remote URL / data URL) atau image_b64
                $imageContents = $this->imageBytesFromAiPayload(is_array($data) ? $data : []);

                // Simpan gambar hasil edit
                if (!Storage::disk('public')->exists('ai_generations')) {
                    Storage::disk('public')->makeDirectory('ai_generations');
                }

                $fileName = 'ai_generations/' . Str::uuid() . '.png';
                Storage::disk('public')->put($fileName, $imageContents);
                $permanentUrl = asset('storage/' . $fileName);

                // Prompt baru = prompt lama + modifikasi baru (untuk riwayat kumulatif)
                $newPrompt = $chat->prompt . ' | Modification: ' . $request->instruction;

                $chatHistory = ChatHistory::create([
                    'user_id'           => Auth::id(),
                    'prompt'            => $newPrompt,
                    'image_url'         => $permanentUrl,
                    'original_filename' => $fileName,
                ]);

                $user->increment('ai_prompt_count');
                $user->refresh();

                $used = (int) $user->ai_prompt_count;
                $remaining = max($quotaLimit - $used, 0);

                return response()->json([
                    'success'         => true,
                    'message'         => 'Image edited successfully!',
                    'image_url'       => $permanentUrl,
                    'chat_id'         => $chatHistory->id,
                    'remaining_quota' => $quotaLimit - $user->ai_prompt_count,
                    'quota'           => [
                        'used'        => $used,
                        'limit'       => (int) $quotaLimit,
                        'remaining'   => (int) $remaining,
                        'is_exceeded' => $used >= $quotaLimit,
                    ],
                ]);

            } else {
                $errorData = $response->json();
                return response()->json([
                    'success'     => false,
                    'error'       => $errorData['error'] ?? 'Unknown error from AI service',
                    'status_code' => $response->status(),
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('GemmaController@edit failed', [
                'message' => $e->getMessage(),
            ]);
            if (str_contains($e->getMessage(), 'Connection refused') ||
                str_contains($e->getMessage(), 'cURL error 7')) {
                return response()->json([
                    'success' => false,
                    'error'   => 'AI service is not available. Please make sure the Node.js server is running.',
                ], 503);
            }

            return response()->json([
                'success' => false,
                'error'   => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    // ─── DELETE HISTORY ──────────────────────────────────────────────────────────
    public function deleteHistory($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false], 401);
        }

        $chatHistory = ChatHistory::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($chatHistory) {
            if (Storage::disk('public')->exists($chatHistory->original_filename)) {
                Storage::disk('public')->delete($chatHistory->original_filename);
            }
            $chatHistory->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}