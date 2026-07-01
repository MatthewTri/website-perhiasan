<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DisabledSlot;
use App\Models\CustomRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarSettingController extends Controller
{
    public function index()
    {
        $weeklyHours = Setting::getConsultationWeeklyHours();

        return view('admin.calendar-settings', compact('weeklyHours'));
    }

    public function updateWeeklyHours(Request $request)
    {
        $validated = $request->validate([
            'weekly_hours' => 'required|array',
            'weekly_hours.monday.start' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
            'weekly_hours.monday.end' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
            'weekly_hours.tuesday.start' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
            'weekly_hours.tuesday.end' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
            'weekly_hours.wednesday.start' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
            'weekly_hours.wednesday.end' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
            'weekly_hours.thursday.start' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
            'weekly_hours.thursday.end' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
            'weekly_hours.friday.start' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
            'weekly_hours.friday.end' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
            'weekly_hours.saturday.start' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
            'weekly_hours.saturday.end' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
            'weekly_hours.sunday.start' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
            'weekly_hours.sunday.end' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
        ]);

        $weeklyHours = Setting::normalizeConsultationWeeklyHours($validated['weekly_hours']);

        foreach ($weeklyHours as $day => $schedule) {
            if (($schedule['start'] === null) xor ($schedule['end'] === null)) {
                return back()->withInput()->with('error', ucfirst($day) . ' must have both start and end time, or leave both empty to close the day.');
            }

            if ($schedule['start'] !== null && Carbon::createFromFormat('H:i', $schedule['end'])->lessThanOrEqualTo(Carbon::createFromFormat('H:i', $schedule['start']))) {
                return back()->withInput()->with('error', ucfirst($day) . ' end time must be later than the start time.');
            }
        }

        Setting::setValue(Setting::CONSULTATION_WEEKLY_HOURS_KEY, json_encode($weeklyHours));

        return back()->with('success', 'Weekly consultation hours updated successfully.');
    }

    // Fetch booked slots (sama seperti consultation availability)
    public function getBookedSlots(Request $request)
    {
        $date = $request->query('date');
        
        if (!$date) {
            return response()->json(['booked_slots' => []]);
        }

        $bookings = CustomRequest::whereDate('appointment_date', $date)
            ->whereNotIn('status', ['CANCELLED', 'COMPLETED'])
            ->get(['appointment_time', 'consultation_type']);

        $bookedSlots = [];
        
        foreach ($bookings as $booking) {
            $startTime = Carbon::parse($booking->appointment_time);
            $duration = $booking->consultation_type === 'design' ? 60 : 30;
            
            $currentMinutes = $startTime->hour * 60 + $startTime->minute;
            $endMinutes = $currentMinutes + $duration;
            
            while ($currentMinutes < $endMinutes) {
                $hour = floor($currentMinutes / 60);
                $minute = $currentMinutes % 60;
                $timeSlot = sprintf('%02d:%02d', $hour, $minute);
                $bookedSlots[] = $timeSlot;
                $currentMinutes += 30;
            }
        }

        return response()->json(['booked_slots' => array_unique($bookedSlots)]);
    }

    // Fetch disabled slots
    public function getDisabledSlots(Request $request)
    {
        $date = $request->query('date');
        
        if (!$date) {
            return response()->json(['disabled_slots' => []]);
        }

        $disabled = DisabledSlot::where('date', $date)
            ->get()
            ->map(function($slot) {
                return $slot->time_slot ? Carbon::parse($slot->time_slot)->format('H:i') : 'full_day';
            })
            ->toArray();

        return response()->json(['disabled_slots' => $disabled]);
    }

    // Toggle disable slot
    public function toggleSlot(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'time_slot' => 'nullable|date_format:H:i'
        ]);

        $existing = DisabledSlot::where('date', $validated['date'])
            ->where('time_slot', $validated['time_slot'])
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'enabled', 'message' => 'Slot enabled']);
        } else {
            DisabledSlot::create($validated);
            return response()->json(['status' => 'disabled', 'message' => 'Slot disabled']);
        }
    }

    public function getUnavailableDates(Request $request)
    {
        $year = $request->query('year');
        $month = $request->query('month');
        
        if (!$year || !$month) {
            return response()->json(['unavailable_dates' => []]);
        }

        // Fetch full day disabled slots untuk bulan tertentu
        $dates = DisabledSlot::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->whereNull('time_slot') // hanya full day
            ->pluck('date')
            ->map(fn($d) => $d->format('Y-m-d'))
            ->toArray();

        return response()->json(['unavailable_dates' => $dates]);
    }
}