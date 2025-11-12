<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewConsultationRequestMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomRequestController extends Controller
{
    /**
     * TUGAS USER: Menampilkan form "Book a Consultation".
     */
    public function create()
    {
        // (Logika untuk menampilkan view form konsultasi)
        return view('consultation.create');
    }

    /**
     * TUGAS USER: Menyimpan data dari form konsultasi.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255',
            'country' => 'required|string|max:255',
            'notes' => 'nullable|string|max:2000',
        ]);

        // Tambahkan user_id jika login
        $validated['user_id'] = Auth::id();

        // Simpan ke database
        $customRequest = CustomRequest::create($validated);

        // Kirim email ke admin
        try {
            Mail::to(config('mail.admin_email', 'admin@yoursite.com'))
                ->send(new NewConsultationRequestMail($customRequest));
        } catch (\Exception $e) {
            // Log error tapi tetap lanjut
            Log::error('Failed to send consultation email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Your consultation request has been submitted successfully! We will contact you within 24-48 hours.');
    }

    /**
     * TUGAS ADMIN: Menampilkan daftar semua permintaan konsultasi.
     */
    public function index()
    {
        // (Hanya untuk admin - pastikan ada middleware 'admin' di rute ini)
        $requests = CustomRequest::latest()->get();
        return view('admin.consultation.index', compact('requests'));
    }

    /**
     * TUGAS ADMIN: Menampilkan detail satu permintaan (termasuk form invoice).
     */
    public function show(CustomRequest $customRequest)
    {
        // (Hanya untuk admin)
        // (Tampilkan gambar, notes, dan form untuk 'createInvoice')
        return view('admin.consultation.show', compact('customRequest'));
    }

    /**
     * TUGAS ADMIN: Memproses form "Buat Invoice".
     */
    public function createInvoice(Request $request, CustomRequest $customRequest)
    {
        // (Hanya untuk admin)
        // (Logika validasi, panggil API Xendit/PayPal, dan UPDATE $customRequest)
        // ...
        return redirect()->back()->with('success', 'Invoice berhasil dibuat!');
    }

    /**
     * TUGAS ADMIN: Menghapus permintaan konsultasi (misal: spam).
     */
    public function destroy(CustomRequest $customRequest)
    {
        // (Hanya untuk admin)
        $customRequest->delete();
        return redirect()->back()->with('success', 'Request dihapus.');
    }
}
