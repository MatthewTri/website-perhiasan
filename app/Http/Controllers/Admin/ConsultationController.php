<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\CustomRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\Consultation\ConsultationRescheduledMail;
use App\Mail\Consultation\ConsultationCancelledMail;


class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $allowedPerPage = [5, 10, 20];
        $perPage = (int) $request->input('per_page', session('admin.consultations.per_page', 10));
        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }
        session(['admin.consultations.per_page' => $perPage]);

        $query = CustomRequest::with(['user', 'images']);
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('type') && $request->type != '') {
            $query->where('consultation_type', $request->type);
        }
        
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('user_email', 'like', '%' . $search . '%');
            });
        }
        
        $consultations = $query->orderBy('appointment_date', 'asc')
                             ->orderBy('appointment_time', 'asc')
                             ->paginate($perPage)->onEachSide(1)->withQueryString();

        // Stats only needed for full page render (AJAX only updates the table)
        $stats = null;
        if (!$request->ajax()) {
            $today = Carbon::today()->toDateString();
            $weekStart = Carbon::now()->startOfWeek()->toDateString();
            $weekEnd = Carbon::now()->endOfWeek()->toDateString();

            $statsRow = CustomRequest::query()
                ->selectRaw('COUNT(*) as total')
                ->selectRaw("SUM(CASE WHEN status = 'SCHEDULED' THEN 1 ELSE 0 END) as scheduled")
                ->selectRaw("SUM(CASE WHEN status = 'COMPLETED' THEN 1 ELSE 0 END) as completed")
                ->selectRaw("SUM(CASE WHEN status = 'CANCELLED' THEN 1 ELSE 0 END) as cancelled")
                ->selectRaw("SUM(CASE WHEN status = 'RESCHEDULED' THEN 1 ELSE 0 END) as rescheduled")
                ->selectRaw('SUM(CASE WHEN DATE(appointment_date) = ? THEN 1 ELSE 0 END) as upcoming_today', [$today])
                ->selectRaw('SUM(CASE WHEN DATE(appointment_date) BETWEEN ? AND ? THEN 1 ELSE 0 END) as upcoming_week', [$weekStart, $weekEnd])
                ->first();

            $stats = [
                'total' => (int) ($statsRow->total ?? 0),
                'scheduled' => (int) ($statsRow->scheduled ?? 0),
                'completed' => (int) ($statsRow->completed ?? 0),
                'cancelled' => (int) ($statsRow->cancelled ?? 0),
                'rescheduled' => (int) ($statsRow->rescheduled ?? 0),
                'upcoming_today' => (int) ($statsRow->upcoming_today ?? 0),
                'upcoming_week' => (int) ($statsRow->upcoming_week ?? 0),
            ];
        }
        
        // Handle AJAX requests
        if ($request->ajax()) {
            $html = view('admin.consultation.partials.table', compact('consultations'))->render();
            $paginationHtml = view('admin.consultation.partials.pagination', compact('consultations'))->render();
            
            return response()->json([
                'html' => $html,
                'pagination' => $paginationHtml
            ]);
        }
        
        return view('admin.consultation.index', compact('consultations', 'stats', 'perPage'));
    }

    public function show(CustomRequest $consultation)
    {
        $consultation->load(['user', 'images']);
        return view('admin.consultation.show', compact('consultation'));
    }

    public function updateStatus(Request $request, CustomRequest $consultation)
    {
        // Cek jika sudah completed atau cancelled, tidak bisa update lagi
        if (in_array($consultation->status, ['COMPLETED', 'CANCELLED'])) {
            return redirect()->back()->with('error', 'This consultation has already been ' . strtolower($consultation->status) . ' and cannot be updated.');
        }

        $request->validate([
            'status' => 'required|in:COMPLETED,CANCELLED',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $consultation->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes
        ]);

        // Send email notification to customer
        if ($request->status === 'CANCELLED') {
            Mail::to($consultation->user_email)->queue(new ConsultationCancelledMail($consultation));
        }

        $message = $request->status === 'COMPLETED' 
            ? 'Consultation marked as completed successfully.' 
            : 'Consultation cancelled successfully. The time slot is now available for rebooking.';

        return redirect()->route('admin.consultation.show', $consultation)->with('success', $message);  
    }

    public function reschedule(Request $request, CustomRequest $consultation)
    {
        // Cek jika sudah completed atau cancelled, tidak bisa reschedule
        if (in_array($consultation->status, ['COMPLETED', 'CANCELLED'])) {
            return redirect()->back()->with('error', 'Cannot reschedule a ' . strtolower($consultation->status) . ' consultation.');
        }

        $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required'
        ]);

        $existingBooking = CustomRequest::whereDate('appointment_date', $request->appointment_date)
            ->whereTime('appointment_time', $request->appointment_time)
            ->where('id', '!=', $consultation->id)
            ->whereNotIn('status', ['CANCELLED', 'COMPLETED'])
            ->exists();

        if ($existingBooking) {
            return redirect()->back()->with('error', 'This time slot is already booked. Please select another time.');
        }

        $consultation->update([
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'status' => 'RESCHEDULED'
        ]);

        // TODO: Send email notification to customer about reschedule
        Mail::to($consultation->user_email)->queue(new ConsultationRescheduledMail($consultation));

        return redirect()->route('admin.consultation.show', $consultation)->with('success', 'Consultation rescheduled successfully.');

    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:complete,cancel,delete',
            'consultation_ids' => 'required|array',
            'consultation_ids.*' => 'exists:custom_requests,id'
        ]);

        $consultations = CustomRequest::whereIn('id', $request->consultation_ids);

        switch ($request->action) {
            case 'complete':
                $consultations->update(['status' => 'COMPLETED']);
                return redirect()->back()->with('success', 'Selected consultations marked as completed.');
                
            case 'cancel':
                $consultations->update(['status' => 'CANCELLED']);
                return redirect()->back()->with('success', 'Selected consultations cancelled.');
        }
    }
}