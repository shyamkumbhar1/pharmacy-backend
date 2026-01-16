<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subscription;
use App\Models\Medicine;
use App\Models\BarcodeEntry;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!$request->user()->isAdmin()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return $next($request);
        });
    }

    public function users(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        // Filter by subscription status
        if ($request->has('subscription_status')) {
            $query->where('subscription_status', $request->subscription_status);
        }

        $perPage = $request->get('per_page', 15);
        $users = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($users);
    }

    public function showUser(Request $request, $id)
    {
        $user = User::with(['medicines', 'subscriptions'])
            ->findOrFail($id);

        return response()->json($user);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'is_active' => 'sometimes|boolean',
            'subscription_status' => 'sometimes|in:trial,active,expired,cancelled',
        ]);

        $user->update($validated);

        return response()->json([
            'user' => $user,
            'message' => 'User updated successfully',
        ]);
    }

    public function dashboard(Request $request)
    {
        $totalUsers = User::where('role', 'pharmacist')->count();
        $activeUsers = User::where('role', 'pharmacist')
            ->where('is_active', true)
            ->count();
        $trialUsers = User::where('subscription_status', 'trial')->count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $pendingPayments = Subscription::where('payment_status', 'pending')->count();
        $totalMedicines = Medicine::count();
        $totalBarcodeEntries = BarcodeEntry::count();

        return response()->json([
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'trial_users' => $trialUsers,
            'active_subscriptions' => $activeSubscriptions,
            'pending_payments' => $pendingPayments,
            'total_medicines' => $totalMedicines,
            'total_barcode_entries' => $totalBarcodeEntries,
        ]);
    }

    public function pendingSubscriptions(Request $request)
    {
        $subscriptions = Subscription::where('payment_status', 'pending')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($subscriptions);
    }

    public function approveSubscription(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);

        $subscription->update([
            'payment_status' => 'paid',
            'status' => 'active',
            'next_billing_date' => Carbon::now()->addMonth(),
        ]);

        $user = $subscription->user;
        $user->update([
            'subscription_status' => 'active',
            'subscription_started_at' => Carbon::now(),
            'subscription_ends_at' => Carbon::now()->addMonth(),
        ]);

        // Create notification
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'type' => 'subscription',
            'title' => 'Subscription Activated',
            'message' => 'Your subscription has been activated successfully.',
            'is_read' => false,
        ]);

        return response()->json([
            'subscription' => $subscription,
            'message' => 'Subscription approved successfully',
        ]);
    }
}

