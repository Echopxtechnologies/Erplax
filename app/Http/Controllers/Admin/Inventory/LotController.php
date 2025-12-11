<?php

namespace App\Http\Controllers\Admin\Inventory;

use Illuminate\Http\Request;
use App\Models\Inventory\Product;
use App\Models\Inventory\Lot;
use App\Models\Inventory\StockMovement;

class LotController extends BaseController
{
    // ==================== LOTS INDEX ====================
    public function index()
    {
        $stats = [
            'total' => Lot::count(),
            'available' => Lot::where('status', 'AVAILABLE')->count(),
            'expired' => Lot::where('status', 'EXPIRED')->count(),
        ];
        
        $products = Product::where('is_active', true)->where('is_batch_managed', true)->orderBy('name')->get();
        
        return view('admin.inventory.lots.index', compact('stats', 'products'));
    }

    // ==================== LOTS DATA (DataTable) ====================
    public function data(Request $request)
    {
        $query = Lot::with('product');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('lot_no', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'lot_no' => $item->lot_no,
                'product_name' => $item->product?->name ?? '-',
                'product_sku' => $item->product?->sku ?? '-',
                'initial_qty' => $item->initial_qty,
                'purchase_price' => $item->purchase_price ? number_format($item->purchase_price, 2) : '-',
                'manufacturing_date' => $item->manufacturing_date ?? '-',
                'expiry_date' => $item->expiry_date ?? '-',
                'status' => $item->status,
                '_edit_url' => route('admin.inventory.lots.edit', $item->id),
                '_delete_url' => route('admin.inventory.lots.destroy', $item->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    // ==================== LOTS CREATE ====================
    public function create()
    {
        $products = Product::where('is_active', true)->where('is_batch_managed', true)->orderBy('name')->get();
        return view('admin.inventory.lots.create', compact('products'));
    }

    // ==================== LOTS STORE ====================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'lot_no' => 'required|string|max:100',
            'initial_qty' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'manufacturing_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:manufacturing_date',
            'status' => 'required|in:AVAILABLE,RESERVED,EXPIRED,CONSUMED',
            'remarks' => 'nullable|string',
        ], [
            'lot_no.unique' => 'This lot number already exists for the selected product.',
            'expiry_date.after_or_equal' => 'Expiry date must be after manufacturing date.',
        ]);
        
        // Check unique lot_no for product
        $exists = Lot::where('product_id', $validated['product_id'])
            ->where('lot_no', $validated['lot_no'])
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'This lot number already exists for the selected product.')->withInput();
        }
        
        Lot::create($validated);
        
        return redirect()->route('admin.inventory.lots.index')
            ->with('success', 'Lot created successfully!');
    }

    // ==================== LOTS EDIT ====================
    public function edit($id)
    {
        $lot = Lot::with('product')->findOrFail($id);
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.lots.edit', compact('lot', 'products'));
    }

    // ==================== LOTS UPDATE ====================
    public function update(Request $request, $id)
    {
        $lot = Lot::findOrFail($id);
        
        $validated = $request->validate([
            'lot_no' => 'required|string|max:50',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'manufacturing_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'status' => 'required|in:AVAILABLE,RESERVED,EXPIRED,CONSUMED',
            'remarks' => 'nullable|string',
        ]);
        
        $lot->update($validated);
        
        return redirect()->route('admin.inventory.lots.index')
            ->with('success', 'Lot updated successfully!');
    }

    // ==================== LOTS DEACTIVATE ====================
    public function deactivate($id)
    {
        Lot::where('id', $id)->update(['status' => 'CONSUMED']);
        return response()->json(['success' => true, 'message' => 'Lot deactivated']);
    }

    // ==================== LOTS DESTROY ====================
    public function destroy($id)
    {
        $lot = Lot::findOrFail($id);
        
        if (StockMovement::where('lot_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete lot with movements'], 422);
        }
        
        $lot->delete();
        return response()->json(['success' => true, 'message' => 'Lot deleted successfully']);
    }

    // ==================== LOTS BY PRODUCT (AJAX) ====================
    public function byProduct($productId)
    {
        $lots = Lot::where('product_id', $productId)
            ->where('status', 'AVAILABLE')
            ->orderBy('expiry_date', 'asc')
            ->get();
        
        return response()->json($lots);
    }

    // ==================== LOTS CHECK (AJAX) ====================
    public function check(Request $request)
    {
        $lotNo = $request->get('lot_no');
        $productId = $request->get('product_id');
        
        $query = Lot::where('lot_no', $lotNo);
        
        if ($productId) {
            $query->where('product_id', $productId);
        }
        
        $existingLot = $query->first();
        
        if ($existingLot) {
            return response()->json([
                'exists' => true,
                'lot_no' => $existingLot->lot_no,
                'product_name' => $existingLot->product->name ?? null,
                'product_id' => $existingLot->product_id,
            ]);
        }
        
        return response()->json(['exists' => false]);
    }
}