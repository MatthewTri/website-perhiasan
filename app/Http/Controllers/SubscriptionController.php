<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    /**
     * Handle newsletter subscription
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email',
        ], [
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already subscribed to our newsletter',
        ]);

        if ($validator->fails()) {
            return back()->with('subscription_error', $validator->errors()->first());
        }

        try {
            Subscriber::create([
                'email' => $request->email,
                'is_active' => true,
            ]);

            return back()->with('subscription_success', 'Thank you for subscribing! You will receive our latest updates.');
        } catch (\Exception $e) {
            return back()->with('subscription_error', 'Something went wrong. Please try again later.');
        }
    }

    /**
     * Unsubscribe from newsletter (public link)
     */
    public function unsubscribe(Request $request)
    {
        $email = $request->query('email');
        
        if (!$email) {
            return redirect()->route('home')->with('subscription_error', 'Invalid unsubscribe link.');
        }

        $subscriber = Subscriber::where('email', $email)->first();

        if (!$subscriber) {
            return redirect()->route('home')->with('subscription_error', 'Email not found in our subscription list.');
        }

        $subscriber->delete();

        return redirect()->route('home')->with('subscription_success', 'You have been successfully unsubscribed from our newsletter.');
    }
}
