<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;

class SubscriberController extends Controller
{
    /**
     * Display a listing of subscribers
     */
    public function index()
    {
        $search = request('search');
        
        $query = Subscriber::query();
        
        if ($search) {
            $query->where('email', 'like', '%' . $search . '%');
        }
        
        $subscribers = $query->latest()->paginate(10)->onEachSide(1)->appends(['search' => $search]);
        $totalSubscribers = Subscriber::count();
        
        return view('admin.subscribers.index', compact('subscribers', 'totalSubscribers'));
    }

    /**
     * Delete a subscriber
     */
    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();
        
        return back()->with('success', 'Subscriber deactivated successfully.');
    }
}
