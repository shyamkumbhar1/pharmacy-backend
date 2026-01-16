<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Services\BarcodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    protected $barcodeService;

    public function __construct(BarcodeService $barcodeService)
    {
        $this->barcodeService = $barcodeService;
    }

    public function index(Request $request)
    {
        $query = Medicine::where('user_id', $request->user()->id);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by low stock
        if ($request->has('low_stock') && $request->low_stock) {
            $query->whereRaw('(current_stock / NULLIF(initial_stock, 0) * 100) <= 10');
        }

        $perPage = $request->get('per_page', 15);
        $medicines = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($medicines);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'barcode' => 'nullable|string|unique:medicines,barcode',
            'price' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'initial_stock' => 'required|integer|min:0',
            'current_stock' => 'nullable|integer|min:0',
            'expiry_date' => 'nullable|date',
            'manufacturer' => 'nullable|string|max:255',
        ]);

        // Generate barcode if not provided
        if (empty($validated['barcode'])) {
            do {
                $barcode = $this->barcodeService->generateBarcode();
            } while (Medicine::where('barcode', $barcode)->exists());
            
            $validated['barcode'] = $barcode;
        }

        $validated['user_id'] = $request->user()->id;
        $validated['current_stock'] = $validated['current_stock'] ?? $validated['initial_stock'];

        $medicine = Medicine::create($validated);

        return response()->json([
            'medicine' => $medicine,
            'message' => 'Medicine created successfully',
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $medicine = Medicine::where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json($medicine);
    }

    public function update(Request $request, $id)
    {
        $medicine = Medicine::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'barcode' => 'sometimes|string|unique:medicines,barcode,' . $id,
            'price' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'initial_stock' => 'sometimes|required|integer|min:0',
            'current_stock' => 'nullable|integer|min:0',
            'expiry_date' => 'nullable|date',
            'manufacturer' => 'nullable|string|max:255',
        ]);

        $medicine->update($validated);

        return response()->json([
            'medicine' => $medicine,
            'message' => 'Medicine updated successfully',
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $medicine = Medicine::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $medicine->delete();

        return response()->json([
            'message' => 'Medicine deleted successfully',
        ]);
    }

    public function getByBarcode(Request $request, $barcode)
    {
        $medicine = Medicine::where('user_id', $request->user()->id)
            ->where('barcode', $barcode)
            ->firstOrFail();

        return response()->json($medicine);
    }
}

