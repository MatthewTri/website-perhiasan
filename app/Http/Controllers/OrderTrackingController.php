<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;

class OrderTrackingController extends Controller
{
    /**
     * 1. Menampilkan Halaman Magic Link
     * URL: /order/track/{token}
     */
    public function show($token)
    {
        // Cari order berdasarkan token (Guest/Member sama saja)
        $order = Order::with(['orderItems.product.images'])
                  ->where('guest_token', $token)
                  ->firstOrFail();

        // Tampilkan View Tracking
        return view('tracking.show', compact('order'));
    }

    /**
     * 2. Proses Upload Bukti Transfer (Khusus Manual BCA)
     * URL: /order/track/{token}/upload
     */
    public function uploadProof(Request $request, $token)
    {
        $order = Order::where('guest_token', $token)->firstOrFail();

        // Validasi: Cuma boleh upload kalau metode MANUAL dan status INVOICE_SENT
        if ($order->payment_preference != 'MANUAL' || $order->status != 'INVOICE_SENT') {
            return back()->with('error', 'Pesanan ini tidak membutuhkan upload bukti manual.');
        }

        $request->validate([
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('proof_image')) {
            // Hapus file lama jika ada (buat irit storage)
            if ($order->proof_of_payment) {
                Storage::disk('public')->delete($order->proof_of_payment);
            }

            // Simpan file baru
            $path = $request->file('proof_image')->store('payment_proofs', 'public');

            // Update status jadi Waiting Verification
            $order->update([
                'proof_of_payment' => $path,
                'status' => 'WAITING_VERIFICATION'
            ]);
        }

        return back()->with('success', 'Bukti berhasil diupload! Admin akan segera memverifikasi.');
    }
}