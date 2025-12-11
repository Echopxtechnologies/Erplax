<?php

namespace App\Http\Controllers\Admin\Inventory;

use Illuminate\Http\Request;
use App\Models\Inventory\Product;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\Rack;
use App\Models\Inventory\Unit;
use App\Models\Inventory\StockLevel;
use App\Models\Inventory\StockMovement;
use App\Models\Inventory\StockTransfer;
use Illuminate\Support\Facades\DB;

class StockController extends BaseController
{
    // ==================== STOCK MOVEMENTS LIST ====================
    public function movements(Request $request)
    {
        $query = StockMovement::with(['product.unit', 'warehouse', 'rack', 'unit', 'creator'])
            ->orderBy('created_at', 'desc');
        
        // Filters
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        
        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }
        
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        $movements = $query->paginate(20);
        
        // Load transfer details for TRANSFER movements
        $transferRefNos = $movements->where('movement_type', 'TRANSFER')
            ->pluck('reference_no')
            ->map(fn($ref) => str_replace('-IN', '', $ref))
            ->unique()
            ->toArray();
        
        $transfers = collect();
        if (!empty($transferRefNos)) {
            $transfers = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'fromRack', 'toRack'])
                ->whereIn('transfer_no', $transferRefNos)
                ->get()
                ->keyBy('transfer_no');
        }
        
        // For filter dropdowns
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.stock.movements', compact('movements', 'products', 'warehouses', 'transfers'));
    }

    // ==================== STOCK MOVEMENTS DATA (DataTable) ====================
    public function movementsData(Request $request)
    {
        $query = StockMovement::with(['product.unit', 'warehouse', 'rack', 'unit', 'creator']);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    });
            });
        }

        // Filters
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        
        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }
        
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        // Get transfer details
        $transferRefNos = collect($data->items())
            ->where('movement_type', 'TRANSFER')
            ->pluck('reference_no')
            ->map(fn($ref) => str_replace('-IN', '', $ref))
            ->unique()
            ->toArray();

        $transfers = [];
        if (!empty($transferRefNos)) {
            $transfers = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'fromRack', 'toRack'])
                ->whereIn('transfer_no', $transferRefNos)
                ->get()
                ->keyBy('transfer_no')
                ->toArray();
        }

        $items = collect($data->items())->map(function ($item) use ($transfers) {
            $unitName = $item->unit->short_name ?? $item->product->unit->short_name ?? 'PCS';
            $isPositive = in_array($item->movement_type, ['IN', 'RETURN']);
            
            $isTransferOut = $item->movement_type == 'TRANSFER' && !str_contains($item->reference_no, '-IN');
            $isTransferIn = $item->movement_type == 'TRANSFER' && str_contains($item->reference_no, '-IN');
            
            $transferNo = str_replace('-IN', '', $item->reference_no);
            $transfer = $transfers[$transferNo] ?? null;
            
            $warehouseName = $item->warehouse->name ?? '-';
            $rackCode = $item->rack->code ?? '';
            $locationDisplay = $warehouseName . ($rackCode ? " ({$rackCode})" : '');
            
            $fromWarehouse = '-';
            $fromWarehouseCode = '';
            $fromRackCode = '';
            $fromRackName = '';
            $toWarehouse = '-';
            $toWarehouseCode = '';
            $toRackCode = '';
            $toRackName = '';
            $isSameWarehouse = false;
            
            if ($transfer) {
                $fromWarehouse = $transfer['from_warehouse']['name'] ?? '-';
                $fromWarehouseCode = $transfer['from_warehouse']['code'] ?? '';
                $fromRackCode = $transfer['from_rack']['code'] ?? '';
                $fromRackName = $transfer['from_rack']['name'] ?? '';
                $toWarehouse = $transfer['to_warehouse']['name'] ?? '-';
                $toWarehouseCode = $transfer['to_warehouse']['code'] ?? '';
                $toRackCode = $transfer['to_rack']['code'] ?? '';
                $toRackName = $transfer['to_rack']['name'] ?? '';
                $isSameWarehouse = ($transfer['from_warehouse_id'] ?? 0) == ($transfer['to_warehouse_id'] ?? 1);
                
                $locationDisplay = "From: {$fromWarehouse}" . ($fromRackCode ? " ({$fromRackCode})" : '') . 
                                   " → To: {$toWarehouse}" . ($toRackCode ? " ({$toRackCode})" : '');
            }
            
            return [
                'id' => $item->id,
                'reference_no' => $item->reference_no ?? '-',
                'created_at' => $item->created_at->format('d M Y'),
                'created_time' => $item->created_at->format('h:i A'),
                'movement_type' => $item->movement_type,
                'product_name' => $item->product->name ?? '-',
                'product_sku' => $item->product->sku ?? '-',
                'product_initials' => strtoupper(substr($item->product->name ?? 'P', 0, 2)),
                'qty' => number_format($item->qty, 2),
                'qty_display' => ($isPositive ? '+' : '-') . number_format($item->qty, 2),
                'unit' => $unitName,
                'is_positive' => $isPositive,
                'reason' => $item->reason ?? '-',
                'created_by' => $item->creator->name ?? 'System',
                'created_by_initial' => strtoupper(substr($item->creator->name ?? 'S', 0, 1)),
                'location_display' => $locationDisplay,
                'warehouse_name' => $warehouseName,
                'rack_code' => $rackCode,
                'rack_name' => $item->rack->name ?? '',
                'is_transfer' => $item->movement_type == 'TRANSFER',
                'is_transfer_out' => $isTransferOut,
                'is_transfer_in' => $isTransferIn,
                'from_warehouse' => $fromWarehouse,
                'from_warehouse_code' => $fromWarehouseCode,
                'from_rack_code' => $fromRackCode,
                'from_rack_name' => $fromRackName,
                'to_warehouse' => $toWarehouse,
                'to_warehouse_code' => $toWarehouseCode,
                'to_rack_code' => $toRackCode,
                'to_rack_name' => $toRackName,
                'is_same_warehouse' => $isSameWarehouse,
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    // ==================== RECEIVE STOCK (IN) ====================
    public function receive()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.stock.receive', compact('products', 'warehouses', 'units'));
    }

    public function receiveStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'qty' => 'required|numeric|min:0.01',
            'rack_id' => 'nullable|exists:racks,id',
            'lot_id' => 'nullable|exists:lots,id',
            'unit_id' => 'nullable|exists:units,id',
            'purchase_price' => 'nullable|numeric|min:0',
            'reason' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);
            $unitId = $request->unit_id ?? $product->unit_id;
            $baseQty = $request->qty;
            
            $refNo = 'RCV-' . date('Ymd') . '-' . str_pad(StockMovement::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            
            StockMovement::create([
                'reference_no' => $refNo,
                'product_id' => $request->product_id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
                'lot_id' => $request->lot_id,
                'unit_id' => $unitId,
                'movement_type' => 'IN',
                'qty' => $request->qty,
                'base_qty' => $baseQty,
                'purchase_price' => $request->purchase_price ?? $product->purchase_price,
                'reason' => $request->reason ?? 'Stock received',
                'created_by' => auth()->id(),
            ]);
            
            $stockLevel = StockLevel::firstOrNew([
                'product_id' => $request->product_id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
            ]);
            
            $stockLevel->qty = ($stockLevel->qty ?? 0) + $request->qty;
            $stockLevel->unit_id = $unitId;
            $stockLevel->save();
            
            DB::commit();
            
            return redirect()->route('admin.inventory.products.show', $request->product_id)
                ->with('success', "Stock received successfully! Reference: {$refNo}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to receive stock: ' . $e->getMessage())->withInput();
        }
    }

    // ==================== DELIVER STOCK (OUT) ====================
    public function deliver()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.stock.deliver', compact('products', 'warehouses', 'units'));
    }

    public function deliverStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'qty' => 'required|numeric|min:0.01',
            'rack_id' => 'nullable|exists:racks,id',
            'reason' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);
            
            $stockLevel = StockLevel::where('product_id', $request->product_id)
                ->where('warehouse_id', $request->warehouse_id)
                ->when($request->rack_id, fn($q) => $q->where('rack_id', $request->rack_id))
                ->first();
                
            if (!$stockLevel || $stockLevel->qty < $request->qty) {
                return back()->with('error', 'Insufficient stock available.')->withInput();
            }
            
            $refNo = 'DLV-' . date('Ymd') . '-' . str_pad(StockMovement::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            
            StockMovement::create([
                'reference_no' => $refNo,
                'product_id' => $request->product_id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id ?? $stockLevel->rack_id,
                'unit_id' => $product->unit_id,
                'movement_type' => 'OUT',
                'qty' => $request->qty,
                'base_qty' => $request->qty,
                'reason' => $request->reason ?? 'Stock delivered',
                'created_by' => auth()->id(),
            ]);
            
            $stockLevel->qty -= $request->qty;
            $stockLevel->save();
            
            DB::commit();
            
            return redirect()->route('admin.inventory.products.show', $request->product_id)
                ->with('success', "Stock delivered successfully! Reference: {$refNo}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to deliver stock: ' . $e->getMessage())->withInput();
        }
    }

    // ==================== RETURNS (IN) ====================
    public function returns()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.stock.returns', compact('products', 'warehouses', 'units'));
    }

    public function returnsStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'qty' => 'required|numeric|min:0.01',
            'rack_id' => 'nullable|exists:racks,id',
            'reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);
            
            $refNo = 'RTN-' . date('Ymd') . '-' . str_pad(StockMovement::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            
            StockMovement::create([
                'reference_no' => $refNo,
                'product_id' => $request->product_id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
                'unit_id' => $product->unit_id,
                'movement_type' => 'RETURN',
                'qty' => $request->qty,
                'base_qty' => $request->qty,
                'reason' => $request->reason,
                'created_by' => auth()->id(),
            ]);
            
            $stockLevel = StockLevel::firstOrNew([
                'product_id' => $request->product_id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
            ]);
            
            $stockLevel->qty = ($stockLevel->qty ?? 0) + $request->qty;
            $stockLevel->unit_id = $product->unit_id;
            $stockLevel->save();
            
            DB::commit();
            
            return redirect()->route('admin.inventory.products.show', $request->product_id)
                ->with('success', "Return processed successfully! Reference: {$refNo}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process return: ' . $e->getMessage())->withInput();
        }
    }

    // ==================== ADJUSTMENTS ====================
    public function adjustments()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.stock.adjustments', compact('products', 'warehouses', 'units'));
    }

    public function adjustmentsStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'adjustment_type' => 'required|in:add,subtract,set',
            'qty' => 'required|numeric|min:0',
            'rack_id' => 'nullable|exists:racks,id',
            'reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);
            
            $stockLevel = StockLevel::firstOrNew([
                'product_id' => $request->product_id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
            ]);
            
            $currentQty = $stockLevel->qty ?? 0;
            $newQty = $currentQty;
            $movementQty = $request->qty;
            
            switch ($request->adjustment_type) {
                case 'add':
                    $newQty = $currentQty + $request->qty;
                    break;
                case 'subtract':
                    $newQty = max(0, $currentQty - $request->qty);
                    $movementQty = $currentQty - $newQty;
                    break;
                case 'set':
                    $newQty = $request->qty;
                    $movementQty = abs($newQty - $currentQty);
                    break;
            }
            
            $refNo = 'ADJ-' . date('Ymd') . '-' . str_pad(StockMovement::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            
            StockMovement::create([
                'reference_no' => $refNo,
                'product_id' => $request->product_id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
                'unit_id' => $product->unit_id,
                'movement_type' => 'ADJUSTMENT',
                'qty' => $movementQty,
                'base_qty' => $movementQty,
                'reason' => "[{$request->adjustment_type}] " . $request->reason . " (Before: {$currentQty}, After: {$newQty})",
                'created_by' => auth()->id(),
            ]);
            
            $stockLevel->qty = $newQty;
            $stockLevel->unit_id = $product->unit_id;
            $stockLevel->save();
            
            DB::commit();
            
            return redirect()->route('admin.inventory.products.show', $request->product_id)
                ->with('success', "Stock adjusted successfully! Reference: {$refNo} | {$currentQty} → {$newQty}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to adjust stock: ' . $e->getMessage())->withInput();
        }
    }

    // ==================== TRANSFER ====================
    public function transfer()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.stock.transfer', compact('products', 'warehouses', 'units'));
    }

    public function transferStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id',
            'qty' => 'required|numeric|min:0.01',
            'from_rack_id' => 'nullable|exists:racks,id',
            'to_rack_id' => 'nullable|exists:racks,id',
            'reason' => 'nullable|string|max:500',
        ]);
        
        // Must be different location (warehouse OR rack)
        if ($request->from_warehouse_id == $request->to_warehouse_id && 
            $request->from_rack_id == $request->to_rack_id) {
            return back()->with('error', 'Source and destination must be different. Choose a different warehouse or rack.')->withInput();
        }

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);
            
            // Check stock at source
            $sourceStock = StockLevel::where('product_id', $request->product_id)
                ->where('warehouse_id', $request->from_warehouse_id)
                ->when($request->from_rack_id, fn($q) => $q->where('rack_id', $request->from_rack_id))
                ->first();
                
            if (!$sourceStock || $sourceStock->qty < $request->qty) {
                return back()->with('error', 'Insufficient stock at source location.')->withInput();
            }
            
            $refNo = 'TRF-' . date('Ymd') . '-' . str_pad(StockMovement::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            
            // Get warehouse/rack names for reason
            $fromWarehouse = Warehouse::find($request->from_warehouse_id);
            $toWarehouse = Warehouse::find($request->to_warehouse_id);
            $fromRack = $request->from_rack_id ? Rack::find($request->from_rack_id) : null;
            $toRack = $request->to_rack_id ? Rack::find($request->to_rack_id) : null;
            
            $transferReason = $request->reason ?? 'Stock Transfer';
            
            // Create StockTransfer record
            StockTransfer::create([
                'transfer_no' => $refNo,
                'product_id' => $request->product_id,
                'lot_id' => $request->lot_id ?? null,
                'unit_id' => $product->unit_id,
                'from_warehouse_id' => $request->from_warehouse_id,
                'to_warehouse_id' => $request->to_warehouse_id,
                'from_rack_id' => $request->from_rack_id,
                'to_rack_id' => $request->to_rack_id,
                'qty' => $request->qty,
                'base_qty' => $request->qty,
                'status' => 'COMPLETED',
                'reason' => $transferReason,
                'created_by' => auth()->id(),
            ]);
            
            // OUT from source
            StockMovement::create([
                'reference_no' => $refNo,
                'product_id' => $request->product_id,
                'warehouse_id' => $request->from_warehouse_id,
                'rack_id' => $request->from_rack_id,
                'lot_id' => $request->lot_id ?? null,
                'unit_id' => $product->unit_id,
                'movement_type' => 'TRANSFER',
                'qty' => $request->qty,
                'base_qty' => $request->qty,
                'reason' => "Transfer OUT → " . ($toWarehouse->name ?? '') . ($toRack ? " ({$toRack->code})" : ''),
                'created_by' => auth()->id(),
            ]);
            
            $sourceStock->qty -= $request->qty;
            $sourceStock->save();
            
            // IN to destination
            StockMovement::create([
                'reference_no' => $refNo . '-IN',
                'product_id' => $request->product_id,
                'warehouse_id' => $request->to_warehouse_id,
                'rack_id' => $request->to_rack_id,
                'lot_id' => $request->lot_id ?? null,
                'unit_id' => $product->unit_id,
                'movement_type' => 'TRANSFER',
                'qty' => $request->qty,
                'base_qty' => $request->qty,
                'reason' => "Transfer IN ← " . ($fromWarehouse->name ?? '') . ($fromRack ? " ({$fromRack->code})" : ''),
                'created_by' => auth()->id(),
            ]);
            
            $destStock = StockLevel::firstOrNew([
                'product_id' => $request->product_id,
                'warehouse_id' => $request->to_warehouse_id,
                'rack_id' => $request->to_rack_id,
            ]);
            
            $destStock->qty = ($destStock->qty ?? 0) + $request->qty;
            $destStock->unit_id = $product->unit_id;
            $destStock->save();
            
            DB::commit();
            
            // Build success message
            $fromLocation = $fromWarehouse->name . ($fromRack ? " → {$fromRack->code}" : '');
            $toLocation = $toWarehouse->name . ($toRack ? " → {$toRack->code}" : '');
            
            return redirect()->route('admin.inventory.products.show', $request->product_id)
                ->with('success', "Stock transferred successfully! {$request->qty} units from {$fromLocation} to {$toLocation}. Ref: {$refNo}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to transfer stock: ' . $e->getMessage())->withInput();
        }
    }

    // ==================== STOCK CHECK (AJAX) ====================
    public function check(Request $request)
    {
        $productId = $request->get('product_id');
        $warehouseId = $request->get('warehouse_id');
        $rackId = $request->get('rack_id');
        $lotId = $request->get('lot_id');
        
        if (!$productId || !$warehouseId) {
            return response()->json([
                'quantity' => 0,
                'unit' => 'PCS'
            ]);
        }
        
        // Get product for unit info
        $product = Product::with('unit')->find($productId);
        $unitName = $product?->unit?->short_name ?? 'PCS';
        
        // Query stock_levels table
        $query = StockLevel::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId);
        
        if ($rackId) {
            $query->where('rack_id', $rackId);
        }
        
        if ($lotId) {
            $query->where('lot_id', $lotId);
        }
        
        $quantity = $query->sum('qty');
        
        return response()->json([
            'quantity' => round($quantity, 4),
            'unit' => $unitName,
            'product_id' => $productId,
            'warehouse_id' => $warehouseId,
            'rack_id' => $rackId,
            'lot_id' => $lotId
        ]);
    }
}