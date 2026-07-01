<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomRequest;
use App\Models\CustomRequestImage;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\Consultation\NewConsultationMail;
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
        $type = $request->query('type', 'short');

        if (!in_array($type, ['short', 'design'], true)) {
            $type = 'short';
        }

        $weeklyHours = Setting::getConsultationWeeklyHours();

        return view('consultation.select-datetime', compact('type', 'weeklyHours'));
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

        $dateObj = Carbon::parse($date);
        $schedule = $this->getWeeklyScheduleForDate($dateObj);

        if (!$this->isScheduleOpen($schedule)) {
            return response()->json([
                'booked_slots' => [],
                'is_unavailable' => true,
            ]);
        }

        $isFullDayDisabled = \App\Models\DisabledSlot::where('date', $date)
            ->whereNull('time_slot')
            ->exists();

        if ($isFullDayDisabled) {
            return response()->json([
                'booked_slots' => [],
                'is_unavailable' => true,
            ]);
        }

        $bookings = CustomRequest::whereDate('appointment_date', $date)
            ->whereNotIn('status', ['CANCELLED', 'COMPLETED'])
            ->get(['appointment_time', 'consultation_type']);

        $disabledSlots = \App\Models\DisabledSlot::where('date', $date)
            ->whereNotNull('time_slot')
            ->pluck('time_slot')
            ->map(fn ($t) => Carbon::parse($t)->format('H:i'))
            ->toArray();

        $bookedSlots = [];

        foreach ($bookings as $booking) {
            $time = Carbon::parse($booking->appointment_time);
            $duration = $booking->consultation_type === 'design' ? 60 : 30;

            $startMinutes = $time->hour * 60 + $time->minute;
            $endMinutes = $startMinutes + $duration;

            if ($type === 'short') {
                $currentMinute = $startMinutes;
                while ($currentMinute < $endMinutes) {
                    $hour = floor($currentMinute / 60);
                    $minute = $currentMinute % 60;
                    $bookedSlots[] = sprintf('%02d:%02d', $hour, $minute);
                    $currentMinute += 30;
                }
            } else {
                $currentMinute = $startMinutes;
                while ($currentMinute < $endMinutes) {
                    $hour = floor($currentMinute / 60);
                    $slot = sprintf('%02d:00', $hour);
                    if (!in_array($slot, $bookedSlots, true)) {
                        $bookedSlots[] = $slot;
                    }
                    $currentMinute += 60;
                }
            }
        }

        if ($type === 'design') {
            foreach ($disabledSlots as $disabledTime) {
                [$disabledHour, $disabledMinute] = explode(':', $disabledTime);
                $disabledMinutes = (int) $disabledHour * 60 + (int) $disabledMinute;
                $rangeStart = $disabledMinutes - 59;

                for ($min = 0; $min <= $disabledMinutes; $min += 60) {
                    if ($min >= $rangeStart && $min <= $disabledMinutes) {
                        $hour = floor($min / 60);
                        $slot = sprintf('%02d:00', $hour);
                        if (!in_array($slot, $bookedSlots, true)) {
                            $bookedSlots[] = $slot;
                        }
                    }
                }

                $blockHour = floor($disabledMinutes / 60);
                $blockSlot = sprintf('%02d:00', $blockHour);
                if (!in_array($blockSlot, $bookedSlots, true)) {
                    $bookedSlots[] = $blockSlot;
                }
            }
        } else {
            $bookedSlots = array_merge($bookedSlots, $disabledSlots);
        }

        return response()->json([
            'booked_slots' => array_unique($bookedSlots),
        ]);
    }

    public function getUnavailableDates(Request $request)
    {
        $year = $request->query('year');
        $month = $request->query('month');

        if (!$year || !$month) {
            return response()->json(['unavailable_dates' => []]);
        }

        $weeklyHours = Setting::getConsultationWeeklyHours();
        $dates = [];
        $daysInMonth = Carbon::create((int) $year, (int) $month, 1)->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create((int) $year, (int) $month, $day);
            $dateKey = $date->format('Y-m-d');
            $schedule = $this->getWeeklyScheduleForDate($date, $weeklyHours);

            if (!$this->isScheduleOpen($schedule)) {
                $dates[] = $dateKey;
                continue;
            }

            $isFullDayDisabled = \App\Models\DisabledSlot::where('date', $dateKey)
                ->whereNull('time_slot')
                ->exists();

            if ($isFullDayDisabled) {
                $dates[] = $dateKey;
            }
        }

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
        $validated = $request->validate([
            'consultation_type' => 'required|in:short,design',
            'meeting_type' => 'required|in:offline,online',
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

        $appointmentDate = Carbon::parse($validated['appointment_date']);
        $schedule = $this->getWeeklyScheduleForDate($appointmentDate);

        if (!$this->isScheduleOpen($schedule)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Selected date is not available for consultation.');
        }

        $duration = $validated['consultation_type'] === 'design' ? 60 : 30;
        $allowedSlots = $this->generateTimeSlotsForSchedule($schedule['start'], $schedule['end'], $duration);

        if (!in_array($validated['appointment_time'], $allowedSlots, true)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Selected time is outside the available schedule.');
        }

        $existingBooking = CustomRequest::whereDate('appointment_date', $validated['appointment_date'])
            ->whereTime('appointment_time', $validated['appointment_time'])
            ->whereNotIn('status', ['CANCELLED', 'COMPLETED'])
            ->exists();

        if ($existingBooking) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This time slot is already booked. Please select another time.');
        }

        $validated['user_id'] = Auth::id();

        $customRequest = CustomRequest::create($validated);

        if ($request->hasFile('reference_images')) {
            foreach ($request->file('reference_images') as $image) {
                $imagePath = $image->storeAs(
                    'consultation_images',
                    Str::uuid() . '.' . $image->getClientOriginalExtension(),
                    'public'
                );

                $imageUrl = asset('storage/' . $imagePath);

                CustomRequestImage::create([
                    'custom_request_id' => $customRequest->id,
                    'image_url' => $imageUrl,
                ]);
            }
        }

        $customRequestWithImages = $customRequest->load('images');

        try {
            $adminEmail = config('mail.admin_email', 'admin@yoursite.com');
            Mail::to($adminEmail)->queue(new NewConsultationMail($customRequestWithImages));

            Log::info('Consultation email sent successfully', [
                'request_id' => $customRequest->id,
                'admin_email' => $adminEmail,
                'images_count' => $customRequestWithImages->images->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send consultation email: ' . $e->getMessage(), [
                'request_id' => $customRequest->id,
                'error' => $e->getTraceAsString(),
            ]);
        }

        try {
            Mail::to($customRequest->user_email)->queue(new \App\Mail\Consultation\ConsultationConfirmationMail($customRequestWithImages));

            Log::info('Client confirmation email sent', [
                'request_id' => $customRequest->id,
                'client_email' => $customRequest->user_email,
                'images_count' => $customRequestWithImages->images->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send client confirmation email: ' . $e->getMessage(), [
                'request_id' => $customRequest->id,
                'error' => $e->getTraceAsString(),
            ]);
        }

        return redirect()->route('consultation.index')->with('success', 'Your consultation request has been submitted successfully! Our team will contact you in due time.');
    }

    protected function getWeeklyScheduleForDate(Carbon $date, ?array $weeklyHours = null): array
    {
        $weeklyHours = $weeklyHours ?? Setting::getConsultationWeeklyHours();
        $dayKey = strtolower($date->format('l'));

        return $weeklyHours[$dayKey] ?? ['start' => null, 'end' => null];
    }

    protected function isScheduleOpen(array $schedule): bool
    {
        if (empty($schedule['start']) || empty($schedule['end'])) {
            return false;
        }

        return Carbon::createFromFormat('H:i', $schedule['end'])->greaterThan(Carbon::createFromFormat('H:i', $schedule['start']));
    }

    protected function generateTimeSlotsForSchedule(?string $startTime, ?string $endTime, int $duration): array
    {
        if (!$startTime || !$endTime) {
            return [];
        }

        $start = Carbon::createFromFormat('H:i', $startTime);
        $end = Carbon::createFromFormat('H:i', $endTime);
        $slots = [];
        $current = $start->copy();

        while ($current->copy()->addMinutes($duration)->lessThanOrEqualTo($end)) {
            $slots[] = $current->format('H:i');
            $current->addMinutes($duration);
        }

        return $slots;
    }
}
