<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use App\Mail\Newsletter\BroadcastMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class BroadcastController extends Controller
{
    /**
     * Show the broadcast creation form
     */
    public function create()
    {
        $totalSubscribers = Subscriber::count();
        
        return view('admin.broadcasts.create', compact('totalSubscribers'));
    }

    /**
     * Send broadcast email to all active subscribers
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $subscribers = Subscriber::all();

            if ($subscribers->isEmpty()) {
                return back()->with('error', 'No subscribers found.');
            }

            // Queue emails for better performance
            foreach ($subscribers as $subscriber) {
                Mail::to($subscriber->email)->queue(
                    new BroadcastMail($request->subject, $request->body, $subscriber->email)
                );
            }

            return redirect()->route('admin.subscribers.index')
                ->with('success', "Broadcast email queued successfully! Sending to {$subscribers->count()} subscribers.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send broadcast: ' . $e->getMessage())->withInput();
        }
    }
}
