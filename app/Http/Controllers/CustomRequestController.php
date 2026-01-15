<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomRequest;
use App\Models\CustomRequestImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewConsultationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CustomRequestController extends Controller
{
    /**
     * Menampilkan halaman pilihan konsultasi
     */
    public function index()
    {
        return view('consultation.index');
    }
    /**
     * TUGAS USER: Menampilkan form "Book a Consultation".
     */
    public function create(Request $request)
    {
        $type = $request->query('type', 'short'); // default: short
        
        // Validasi type
        if (!in_array($type, ['short', 'design'])) {
            $type = 'short';
        }

        return view('consultation.select-datetime', compact('type'));
    }

    /**
     * API untuk cek time slots yang sudah terbooked
     */
    public function checkAvailability(Request $request)
    {
        $date = $request->query('date');
        $type = $request->query('type', 'short');
        
        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        // Get all bookings for this date
        $bookings = CustomRequest::whereDate('appointment_date', $date)
            ->whereNotIn('status', ['CANCELLED', 'COMPLETED'])
            ->get(['appointment_time', 'consultation_type']);

        $bookedSlots = [];
        
        foreach ($bookings as $booking) {
            $time = Carbon::parse($booking->appointment_time);
            $duration = $booking->consultation_type === 'design' ? 60 : 30;
            
            // Add blocked time slots
            $startMinutes = $time->hour * 60 + $time->minute;
            $endMinutes = $startMinutes + $duration;
            
            // Jika consultation type yang diminta adalah SHORT
            if ($type === 'short') {
                // Block setiap 30 menit slot yang overlap
                $currentMinute = $startMinutes;
                while ($currentMinute < $endMinutes) {
                    $hour = floor($currentMinute / 60);
                    $minute = $currentMinute % 60;
                    $timeSlot = sprintf('%02d:%02d', $hour, $minute);
                    $bookedSlots[] = $timeSlot;
                    $currentMinute += 30;
                }
            } 
            // Jika consultation type yang diminta adalah DESIGN
            else {
                // Block setiap 1 jam slot yang overlap
                $currentMinute = $startMinutes;
                while ($currentMinute < $endMinutes) {
                    $hour = floor($currentMinute / 60);
                    $timeSlot = sprintf('%02d:00', $hour);
                    if (!in_array($timeSlot, $bookedSlots)) {
                        $bookedSlots[] = $timeSlot;
                    }
                    $currentMinute += 60;
                }
            }
        }

        // Tambahkan disabled slots
        $disabledSlots = \App\Models\DisabledSlot::where('date', $date)
            ->whereNotNull('time_slot')
            ->pluck('time_slot')
            ->map(fn($t) => \Carbon\Carbon::parse($t)->format('H:i'))
            ->toArray();

        $bookedSlots = array_merge($bookedSlots, $disabledSlots);

        // Check full day disabled
        $isFullDayDisabled = \App\Models\DisabledSlot::where('date', $date)
            ->whereNull('time_slot')
            ->exists();

        if ($isFullDayDisabled) {
            return response()->json([
                'booked_slots' => [],
                'is_unavailable' => true
            ]);
        }
        
        return response()->json([
            'booked_slots' => array_unique($bookedSlots)
        ]);
    }

    public function getUnavailableDates(Request $request)
    {
        $year = $request->query('year');
        $month = $request->query('month');
        
        if (!$year || !$month) {
            return response()->json(['unavailable_dates' => []]);
        }

        // Fetch full day disabled slots untuk bulan tertentu
        $dates = \App\Models\DisabledSlot::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->whereNull('time_slot') // hanya full day
            ->pluck('date')
            ->map(fn($d) => $d->format('Y-m-d'))
            ->toArray();

        return response()->json(['unavailable_dates' => $dates]);
    }

    public function showDetails(Request $request)
    {
        $type = $request->query('type', 'short');
        $date = $request->query('date');
        $time = $request->query('time');

        $dateObj = Carbon::parse($date);
        $startTime = Carbon::parse($time);
        $duration = $type === 'design' ? 60 : 30;
        $endTime = $startTime->copy()->addMinutes($duration);

        $timeDisplay = $startTime->format('g:i A') . ' – ' . $endTime->format('g:i A');


        $appointmentDisplay = $dateObj->format('l, F j, Y') . ' at ' . $timeDisplay;

        return view('consultation.details', compact('type', 'date', 'time', 'appointmentDisplay'));
    }

    /**
     * TUGAS USER: Menyimpan data dari form konsultasi.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'consultation_type' => 'required|in:short,design',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255',
            'user_phone' => 'required|string|max:20',
            'appointment_notes' => 'nullable|string|max:2000',
            'reference_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'reference_images' => 'nullable|array|max:6',
        ]);

        // Double check: Cek apakah slot sudah terbooked
        $existingBooking = CustomRequest::whereDate('appointment_date', $validated['appointment_date'])
            ->whereTime('appointment_time', $validated['appointment_time'])
            ->whereNotIn('status', ['CANCELLED', 'COMPLETED'])
            ->exists();

        if ($existingBooking) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This time slot is already booked. Please select another time.');
        }

        // Tambahkan user_id jika login
        $validated['user_id'] = Auth::id();

        // Simpan custom request ke database
        $customRequest = CustomRequest::create($validated);

        // Handle multiple image uploads
        if ($request->hasFile('reference_images')) {
            foreach ($request->file('reference_images') as $image) {
                // Store image to public disk
                $imagePath = $image->storeAs('consultation_images', 
                    Str::uuid() . '.' . $image->getClientOriginalExtension(), 
                    'public'
                );
                
                // Create permanent URL
                $imageUrl = asset('storage/' . $imagePath);
                
                // Save image reference to database
                CustomRequestImage::create([
                    'custom_request_id' => $customRequest->id,
                    'image_url' => $imageUrl,
                ]);
            }
        }

        // ✅ PERBAIKAN: Load relasi images sebelum kirim email
        $customRequestWithImages = $customRequest->load('images');

        // Kirim email ke admin
        try {
            $adminEmail = config('mail.admin_email', 'admin@yoursite.com');
            
            // ✅ PERBAIKAN: Kirim object yang sudah di-load dengan images
            // Mail::to($adminEmail)->send(new NewConsultationMail($customRequestWithImages));
            
            Log::info('Consultation email sent successfully', [
                'request_id' => $customRequest->id,
                'admin_email' => $adminEmail,
                'images_count' => $customRequestWithImages->images->count() // ✅ Pakai yang sudah di-load
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send consultation email: ' . $e->getMessage(), [
                'request_id' => $customRequest->id,
                'error' => $e->getTraceAsString()
            ]);
        }

        return redirect()->route('consultation.index')->with('success', 'Your consultation request has been submitted successfully! We will contact you within 24-48 hours.');
    }


}
