<?php

namespace App\Http\Controllers;

use App\Models\BarcodeEntry;
use App\Models\Medicine;
use Illuminate\Http\Request;

class BarcodeEntryController extends Controller
{
    public function index(Request $request)
    {
        $query = BarcodeEntry::where('user_id', $request->user()->id)
            ->with(['medicine']);

        // Filter by entry type
        if ($request->has('entry_type')) {
            $query->where('entry_type', $request->entry_type);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $perPage = $request->get('per_page', 15);
        $entries = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($entries);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string',
            'entry_type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Find medicine by barcode
        $medicine = Medicine::where('user_id', $request->user()->id)
            ->where('barcode', $validated['barcode'])
            ->firstOrFail();

        // Create barcode entry
        $entry = BarcodeEntry::create([
            'medicine_id' => $medicine->id,
            'user_id' => $request->user()->id,
            'barcode' => $validated['barcode'],
            'entry_type' => $validated['entry_type'],
            'quantity' => $validated['quantity'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Update medicine stock
        if ($validated['entry_type'] === 'in') {
            $medicine->increment('current_stock', $validated['quantity']);
        } else {
            $medicine->decrement('current_stock', $validated['quantity']);
            if ($medicine->current_stock < 0) {
                $medicine->current_stock = 0;
                $medicine->save();
            }
        }

        // Check stock levels after update
        app(\App\Services\StockAlertService::class)->checkStockLevels($request->user()->id);

        return response()->json([
            'entry' => $entry->load('medicine'),
            'message' => 'Barcode entry created successfully',
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $entry = BarcodeEntry::where('user_id', $request->user()->id)
            ->with(['medicine'])
            ->findOrFail($id);

        return response()->json($entry);
    }
}

