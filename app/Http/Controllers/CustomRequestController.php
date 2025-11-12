<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomRequest;

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
        // (Logika validasi, simpan ke DB, dan kirim notifikasi ke Admin)
        // ...
        return redirect()->back()->with('success', 'Permintaan terkirim!');
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
