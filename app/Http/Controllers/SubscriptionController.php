<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $subscription = Subscription::where('user_id', $request->user()->id)
            ->latest()
            ->first();

        return response()->json($subscription);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // Check if user is on trial
        if ($user->subscription_status === 'trial' && $user->trial_ends_at && $user->trial_ends_at->isFuture()) {
            return response()->json([
                'message' => 'Your trial is still active. Please wait for trial to end.',
            ], 400);
        }

        $validated = $request->validate([
            'payment_proof' => 'nullable|string',
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_type' => 'monthly',
            'amount' => 350,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_proof' => $validated['payment_proof'] ?? null,
        ]);

        return response()->json([
            'subscription' => $subscription,
            'message' => 'Subscription request created. Waiting for admin approval.',
        ], 201);
    }

    public function invoice(Request $request, $id)
    {
        $subscription = Subscription::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $user = $subscription->user;

        // Check if DomPDF is available
        if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.subscription', [
                'subscription' => $subscription,
                'user' => $user,
            ]);

            return $pdf->download('invoice-' . $subscription->id . '.pdf');
        }

        // Fallback: Return JSON invoice data
        return response()->json([
            'invoice_number' => 'INV-' . str_pad($subscription->id, 6, '0', STR_PAD_LEFT),
            'date' => $subscription->created_at->format('d M Y'),
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'subscription' => [
                'plan_type' => $subscription->plan_type,
                'amount' => $subscription->amount,
                'status' => $subscription->status,
            ],
        ]);
    }
}

