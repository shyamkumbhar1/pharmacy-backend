<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\StockAlert;
use App\Models\Notification;
use Carbon\Carbon;

class StockAlertService
{
    private const THRESHOLD_PERCENTAGE = 10.0;

    public function checkStockLevels(int $userId): void
    {
        $medicines = Medicine::where('user_id', $userId)
            ->where('current_stock', '>', 0)
            ->get();

        foreach ($medicines as $medicine) {
            $stockPercentage = $medicine->stock_percentage;
            
            if ($stockPercentage <= self::THRESHOLD_PERCENTAGE) {
                $this->createOrUpdateAlert($medicine, $stockPercentage);
            }
        }
    }

    private function createOrUpdateAlert(Medicine $medicine, float $stockPercentage): void
    {
        $alert = StockAlert::where('medicine_id', $medicine->id)
            ->where('user_id', $medicine->user_id)
            ->where('is_resolved', false)
            ->first();

        if ($alert) {
            $alert->update([
                'current_stock_percentage' => $stockPercentage,
                'alert_sent_at' => Carbon::now(),
            ]);
        } else {
            $alert = StockAlert::create([
                'medicine_id' => $medicine->id,
                'user_id' => $medicine->user_id,
                'threshold_percentage' => self::THRESHOLD_PERCENTAGE,
                'current_stock_percentage' => $stockPercentage,
                'alert_sent_at' => Carbon::now(),
            ]);
        }

        // Create notification
        Notification::create([
            'user_id' => $medicine->user_id,
            'type' => 'stock_alert',
            'title' => 'Low Stock Alert',
            'message' => "Medicine '{$medicine->name}' is running low. Only {$stockPercentage}% stock remaining.",
            'is_read' => false,
        ]);
    }

    public function getActiveAlerts(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return StockAlert::where('user_id', $userId)
            ->where('is_resolved', false)
            ->with('medicine')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}

