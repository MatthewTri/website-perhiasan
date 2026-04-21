<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    /**
     * Display a listing of subscribers
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $allowedPerPage = [5, 10, 20];
        $perPage = (int) $request->input('per_page', session('admin.subscribers.per_page', 10));
        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }
        session(['admin.subscribers.per_page' => $perPage]);

        $query = Subscriber::query()->select(['id', 'email', 'created_at']);

        if ($search !== '') {
            // Contains search (supports matching in the middle, e.g. 'gaw' -> 'ngawur@gmail.com')
            // Note: leading wildcard may prevent index usage; email has a UNIQUE index for equality lookups.
            $escaped = addcslashes($search, "\\\\%_");
            $query->where('email', 'like', '%' . $escaped . '%');
        }

        $subscribers = $query->latest()
            ->paginate($perPage)
            ->onEachSide(1)
            ->withQueryString();

        $totalSubscribers = Subscriber::count();

        if ($request->ajax()) {
            $html = view('admin.subscribers.partials.table', compact('subscribers'))->render();
            $paginationHtml = view('admin.subscribers.partials.pagination', compact('subscribers'))->render();

            return response()->json([
                'html' => $html,
                'pagination' => $paginationHtml,
            ]);
        }

        return view('admin.subscribers.index', compact('subscribers', 'totalSubscribers', 'perPage'));
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
