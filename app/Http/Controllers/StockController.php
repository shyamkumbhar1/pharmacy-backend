<?php

namespace App\Http\Controllers;

use App\Services\StockAlertService;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    protected $stockAlertService;

    public function __construct(StockAlertService $stockAlertService)
    {
        $this->stockAlertService = $stockAlertService;
    }

    public function alerts(Request $request)
    {
        $alerts = $this->stockAlertService->getActiveAlerts($request->user()->id);

        return response()->json($alerts);
    }

    public function check(Request $request)
    {
        $this->stockAlertService->checkStockLevels($request->user()->id);

        return response()->json([
            'message' => 'Stock levels checked successfully',
        ]);
    }

    public function dashboard(Request $request)
    {
        $userId = $request->user()->id;

        $totalMedicines = Medicine::where('user_id', $userId)->count();
        $lowStockMedicines = Medicine::where('user_id', $userId)
            ->whereRaw('(current_stock / NULLIF(initial_stock, 0) * 100) <= 10')
            ->count();
        $outOfStockMedicines = Medicine::where('user_id', $userId)
            ->where('current_stock', 0)
            ->count();
        $totalStockValue = Medicine::where('user_id', $userId)
            ->sum(DB::raw('current_stock * price'));

        $recentLowStock = Medicine::where('user_id', $userId)
            ->whereRaw('(current_stock / NULLIF(initial_stock, 0) * 100) <= 10')
            ->orderBy('current_stock', 'asc')
            ->limit(10)
            ->get();

        return response()->json([
            'total_medicines' => $totalMedicines,
            'low_stock_medicines' => $lowStockMedicines,
            'out_of_stock_medicines' => $outOfStockMedicines,
            'total_stock_value' => $totalStockValue,
            'recent_low_stock' => $recentLowStock,
        ]);
    }
}

