<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\BarcodeEntryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'environment' => app()->environment(),
    ]);
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Medicines
    Route::get('/medicines', [MedicineController::class, 'index']);
    Route::post('/medicines', [MedicineController::class, 'store']);
    Route::get('/medicines/{id}', [MedicineController::class, 'show']);
    Route::put('/medicines/{id}', [MedicineController::class, 'update']);
    Route::delete('/medicines/{id}', [MedicineController::class, 'destroy']);
    Route::get('/medicines/barcode/{barcode}', [MedicineController::class, 'getByBarcode']);

    // Barcode Entries
    Route::get('/barcode-entries', [BarcodeEntryController::class, 'index']);
    Route::post('/barcode-entries', [BarcodeEntryController::class, 'store']);
    Route::get('/barcode-entries/{id}', [BarcodeEntryController::class, 'show']);

    // Stock Monitoring
    Route::get('/stock/alerts', [StockController::class, 'alerts']);
    Route::post('/stock/check', [StockController::class, 'check']);
    Route::get('/stock/dashboard', [StockController::class, 'dashboard']);

    // Subscriptions
    Route::get('/subscriptions', [SubscriptionController::class, 'index']);
    Route::post('/subscriptions', [SubscriptionController::class, 'store']);
    Route::get('/subscriptions/invoice/{id}', [SubscriptionController::class, 'invoice']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);

    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('/users', [AdminController::class, 'users']);
        Route::get('/users/{id}', [AdminController::class, 'showUser']);
        Route::put('/users/{id}', [AdminController::class, 'updateUser']);
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/subscriptions/pending', [AdminController::class, 'pendingSubscriptions']);
        Route::put('/subscriptions/{id}/approve', [AdminController::class, 'approveSubscription']);
    });
});

