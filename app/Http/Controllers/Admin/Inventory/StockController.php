<?php

namespace App\Http\Controllers\Admin\Inventory;

use Illuminate\Http\Request;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductUnit;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\Rack;
use App\Models\Inventory\Unit;
use App\Models\Inventory\Lot;
use App\Models\Inventory\StockLevel;
use App\Models\Inventory\StockMovement;
use App\Models\Inventory\StockTransfer;
use Illuminate\Support\Facades\DB;

/**
 * Stock Controller - Handles all stock operations with multi-unit support
 * 
 * IMPORTANT: All stock is stored in BASE UNITS in stock_levels table
 * 
 * Example: Product "Rice" with base unit "KG"
 * - User receives 10 × "5 KG Bag" (conversion_factor = 5)
 * - qty = 10, base_qty = 50 (10 × 5)
 * - stock_levels.qty increases by 50 KG
 * 
 * Example: Product "T-Shirt" with base unit "PCS"
 * - User receives 5 × "Box of 6" (conversion_factor = 6)
 * - qty = 5, base_qty = 30 (5 × 6)
 * - stock_levels.qty increases by 30 PCS
 */
class StockController extends BaseController
{
    // ==================== STOCK MOVEMENTS LIST ====================
    public function movements(Request $request)
    {
        $query = StockMovement::with(['product.unit', 'warehouse', 'rack', 'lot', 'unit', 'creator'])
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
        if ($request->filled('lot_id')) {
            $query->where('lot_id', $request->lot_id);
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
        
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.stock.movements', compact('movements', 'products', 'warehouses', 'transfers'));
    }

    // ==================== STOCK MOVEMENTS DATA (DataTable) ====================
    public function movementsData(Request $request)
    {
        $query = StockMovement::with(['product.unit', 'warehouse', 'rack', 'lot', 'unit', 'creator']);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%")
                    ->orWhereHas('product', fn($q2) => $q2->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%"))
                    ->orWhereHas('lot', fn($q2) => $q2->where('lot_no', 'like', "%{$search}%"));
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
        if ($request->filled('lot_id')) {
            $query->where('lot_id', $request->lot_id);
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
            $baseUnitName = $item->product->unit->short_name ?? 'PCS';
            $isPositive = in_array($item->movement_type, ['IN', 'RETURN']);
            
            $transferNo = str_replace('-IN', '', $item->reference_no);
            $transfer = $transfers[$transferNo] ?? null;
            
            $warehouseName = $item->warehouse->name ?? '-';
            $rackCode = $item->rack->code ?? '';
            $locationDisplay = $warehouseName . ($rackCode ? " ({$rackCode})" : '');
            
            // Transfer details
            if ($transfer) {
                $fromWarehouse = $transfer['from_warehouse']['name'] ?? '-';
                $fromRackCode = $transfer['from_rack']['code'] ?? '';
                $toWarehouse = $transfer['to_warehouse']['name'] ?? '-';
                $toRackCode = $transfer['to_rack']['code'] ?? '';
                
                $locationDisplay = "From: {$fromWarehouse}" . ($fromRackCode ? " ({$fromRackCode})" : '') . 
                                   " → To: {$toWarehouse}" . ($toRackCode ? " ({$toRackCode})" : '');
            }
            
            // Lot info
            $lotInfo = null;
            if ($item->lot) {
                $lotInfo = [
                    'lot_no' => $item->lot->lot_no,
                    'batch_no' => $item->lot->batch_no,
                    'expiry_date' => $item->lot->expiry_date?->format('d M Y'),
                ];
            }
            
            // Show both qty (in transaction unit) and base_qty (in base unit) if different
            $qtyDisplay = number_format($item->qty, 2) . ' ' . $unitName;
            if ($item->qty != $item->base_qty && $item->base_qty) {
                $qtyDisplay .= ' (' . number_format($item->base_qty, 2) . ' ' . $baseUnitName . ')';
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
                'base_qty' => number_format($item->base_qty ?? $item->qty, 2),
                'qty_display' => $qtyDisplay,
                'qty_signed' => ($isPositive ? '+' : '-') . number_format($item->base_qty ?? $item->qty, 2),
                'unit' => $unitName,
                'base_unit' => $baseUnitName,
                'is_positive' => $isPositive,
                'reason' => $item->reason ?? '-',
                'notes' => $item->notes,
                'created_by' => $item->creator->name ?? 'System',
                'location_display' => $locationDisplay,
                'warehouse_name' => $warehouseName,
                'rack_code' => $rackCode,
                'lot_info' => $lotInfo,
                'stock_before' => number_format($item->stock_before, 2),
                'stock_after' => number_format($item->stock_after, 2),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'per_page' => $data->perPage(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    // ==================== RECEIVE STOCK (Purchase/Opening) ====================
    public function receive()
    {
        $products = Product::with(['unit', 'productUnits.unit'])
            ->where('is_active', true)
            ->where('track_inventory', true)
            ->orderBy('name')
            ->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.stock.receive', compact('products', 'warehouses', 'units'));
    }

    public function receiveStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'qty' => 'required|numeric|min:0.001',
            'unit_id' => 'required|exists:units,id',
            'rack_id' => 'nullable|exists:racks,id',
            'purchase_price' => 'nullable|numeric|min:0',
            'reference_type' => 'required|in:PURCHASE,OPENING',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            // Lot fields
            'lot_no' => 'nullable|string|max:100',
            'batch_no' => 'nullable|string|max:100',
            'manufacturing_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:manufacturing_date',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::with('unit')->findOrFail($request->product_id);
            
            // Calculate conversion factor and base quantity
            $conversionData = $this->getConversionFactor($product, $request->unit_id);
            $conversionFactor = $conversionData['factor'];
            $baseQty = $request->qty * $conversionFactor;
            
            // Handle Lot/Batch
            $lotId = null;
            if ($product->is_batch_managed && $request->filled('lot_no')) {
                $lot = Lot::firstOrCreate([
                    'product_id' => $product->id,
                    'lot_no' => $request->lot_no,
                ], [
                    'batch_no' => $request->batch_no,
                    'manufacturing_date' => $request->manufacturing_date,
                    'expiry_date' => $request->expiry_date,
                    'purchase_price' => $request->purchase_price,
                    'status' => 'ACTIVE',
                ]);
                $lotId = $lot->id;
                
                // Update lot details if they were empty
                if (!$lot->manufacturing_date && $request->manufacturing_date) {
                    $lot->update([
                        'manufacturing_date' => $request->manufacturing_date,
                        'expiry_date' => $request->expiry_date,
                        'purchase_price' => $request->purchase_price,
                    ]);
                }
            }
            
            // Get current stock BEFORE update (in base units)
            $stockBefore = $this->getStockInBaseUnits($product->id, $request->warehouse_id, $request->rack_id, $lotId);
            
            // Update stock level (always in base units)
            $stockLevel = StockLevel::firstOrNew([
                'product_id' => $product->id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
                'lot_id' => $lotId,
            ]);
            
            $stockLevel->unit_id = $product->unit_id; // Always base unit
            $stockLevel->qty = ($stockLevel->qty ?? 0) + $baseQty;
            $stockLevel->save();
            
            $stockAfter = $stockLevel->qty;
            
            // Generate reference number
            $refNo = StockMovement::generateReferenceNo('RCV');
            
            // Create stock movement
            StockMovement::create([
                'reference_no' => $refNo,
                'product_id' => $product->id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
                'lot_id' => $lotId,
                'unit_id' => $request->unit_id,  // Transaction unit (could be "5 KG Bag")
                'qty' => $request->qty,           // Quantity in transaction unit (10 bags)
                'base_qty' => $baseQty,           // Quantity in base unit (50 KG)
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'purchase_price' => $request->purchase_price,
                'movement_type' => 'IN',
                'reference_type' => $request->reference_type,
                'reason' => $request->reason ?? ($request->reference_type == 'OPENING' ? 'Opening Stock' : 'Purchase Receipt'),
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();
            
            // Build success message with unit info
            $unitName = $conversionData['unit_name'];
            $baseUnitName = $product->unit->short_name ?? 'PCS';
            $message = "Stock received: {$request->qty} {$unitName}";
            if ($conversionFactor != 1) {
                $message .= " (= {$baseQty} {$baseUnitName})";
            }
            $message .= " | Ref: {$refNo}";
            if ($lotId) {
                $message .= " | Lot: {$request->lot_no}";
            }
            
            return redirect()->route('admin.inventory.products.show', $product->id)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to receive stock: ' . $e->getMessage())->withInput();
        }
    }

    // ==================== DELIVER STOCK (Sale/Issue) ====================
    public function deliver()
    {
        $products = Product::with(['unit', 'productUnits.unit'])
            ->where('is_active', true)
            ->where('track_inventory', true)
            ->orderBy('name')
            ->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.stock.deliver', compact('products', 'warehouses', 'units'));
    }

    public function deliverStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'qty' => 'required|numeric|min:0.001',
            'unit_id' => 'required|exists:units,id',
            'rack_id' => 'nullable|exists:racks,id',
            'lot_id' => 'nullable|exists:lots,id',
            'reference_type' => 'required|in:SALE,ADJUSTMENT',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::with('unit')->findOrFail($request->product_id);
            
            // Calculate conversion factor and base quantity
            $conversionData = $this->getConversionFactor($product, $request->unit_id);
            $conversionFactor = $conversionData['factor'];
            $baseQty = $request->qty * $conversionFactor;
            
            // Check stock availability (in base units)
            $availableStock = $this->getStockInBaseUnits(
                $product->id, 
                $request->warehouse_id, 
                $request->rack_id, 
                $request->lot_id
            );
            
            if ($availableStock < $baseQty) {
                $baseUnitName = $product->unit->short_name ?? 'PCS';
                return back()->with('error', "Insufficient stock! Available: {$availableStock} {$baseUnitName}, Required: {$baseQty} {$baseUnitName}")->withInput();
            }
            
            // Get stock level to update
            $stockLevel = StockLevel::where('product_id', $product->id)
                ->where('warehouse_id', $request->warehouse_id)
                ->when($request->rack_id, fn($q) => $q->where('rack_id', $request->rack_id))
                ->when($request->lot_id, fn($q) => $q->where('lot_id', $request->lot_id))
                ->first();
                
            if (!$stockLevel) {
                return back()->with('error', 'Stock record not found at this location.')->withInput();
            }
            
            $stockBefore = $stockLevel->qty;
            
            // Deduct stock (in base units)
            $stockLevel->qty -= $baseQty;
            if ($stockLevel->qty < 0) $stockLevel->qty = 0;
            $stockLevel->save();
            
            $stockAfter = $stockLevel->qty;
            
            // Generate reference number
            $refNo = StockMovement::generateReferenceNo('DLV');
            
            // Create stock movement
            StockMovement::create([
                'reference_no' => $refNo,
                'product_id' => $product->id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
                'lot_id' => $request->lot_id,
                'unit_id' => $request->unit_id,
                'qty' => $request->qty,
                'base_qty' => $baseQty,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'movement_type' => 'OUT',
                'reference_type' => $request->reference_type,
                'reason' => $request->reason ?? 'Stock Delivered',
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();
            
            // Build success message
            $unitName = $conversionData['unit_name'];
            $baseUnitName = $product->unit->short_name ?? 'PCS';
            $message = "Stock delivered: {$request->qty} {$unitName}";
            if ($conversionFactor != 1) {
                $message .= " (= {$baseQty} {$baseUnitName})";
            }
            $message .= " | Ref: {$refNo}";
            
            return redirect()->route('admin.inventory.products.show', $product->id)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to deliver stock: ' . $e->getMessage())->withInput();
        }
    }

    // ==================== RETURNS ====================
    public function returns()
    {
        $products = Product::with(['unit', 'productUnits.unit'])
            ->where('is_active', true)
            ->where('track_inventory', true)
            ->orderBy('name')
            ->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.stock.returns', compact('products', 'warehouses', 'units'));
    }

    public function returnsStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'qty' => 'required|numeric|min:0.001',
            'unit_id' => 'required|exists:units,id',
            'rack_id' => 'nullable|exists:racks,id',
            'lot_id' => 'nullable|exists:lots,id',
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::with('unit')->findOrFail($request->product_id);
            
            // Calculate conversion
            $conversionData = $this->getConversionFactor($product, $request->unit_id);
            $conversionFactor = $conversionData['factor'];
            $baseQty = $request->qty * $conversionFactor;
            
            // Get current stock
            $stockBefore = $this->getStockInBaseUnits($product->id, $request->warehouse_id, $request->rack_id, $request->lot_id);
            
            // Update stock level
            $stockLevel = StockLevel::firstOrNew([
                'product_id' => $product->id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
                'lot_id' => $request->lot_id,
            ]);
            
            $stockLevel->unit_id = $product->unit_id;
            $stockLevel->qty = ($stockLevel->qty ?? 0) + $baseQty;
            $stockLevel->save();
            
            $stockAfter = $stockLevel->qty;
            
            $refNo = StockMovement::generateReferenceNo('RET');
            
            StockMovement::create([
                'reference_no' => $refNo,
                'product_id' => $product->id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
                'lot_id' => $request->lot_id,
                'unit_id' => $request->unit_id,
                'qty' => $request->qty,
                'base_qty' => $baseQty,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'movement_type' => 'RETURN',
                'reference_type' => 'RETURN',
                'reason' => $request->reason,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();
            
            $unitName = $conversionData['unit_name'];
            $baseUnitName = $product->unit->short_name ?? 'PCS';
            $message = "Stock returned: {$request->qty} {$unitName}";
            if ($conversionFactor != 1) {
                $message .= " (= {$baseQty} {$baseUnitName})";
            }
            $message .= " | Ref: {$refNo}";
            
            return redirect()->route('admin.inventory.products.show', $product->id)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process return: ' . $e->getMessage())->withInput();
        }
    }

    // ==================== ADJUSTMENTS ====================
    public function adjustments()
    {
        $products = Product::with(['unit', 'productUnits.unit'])
            ->where('is_active', true)
            ->where('track_inventory', true)
            ->orderBy('name')
            ->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.stock.adjustments', compact('products', 'warehouses'));
    }

    public function adjustmentsStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'adjustment_type' => 'required|in:set,add,subtract',
            'qty' => 'required|numeric|min:0',
            'rack_id' => 'nullable|exists:racks,id',
            'lot_id' => 'nullable|exists:lots,id',
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::with('unit')->findOrFail($request->product_id);
            
            // For adjustments, always work in BASE UNIT
            $adjustQty = $request->qty;
            
            // Get or create stock level
            $stockLevel = StockLevel::firstOrNew([
                'product_id' => $product->id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
                'lot_id' => $request->lot_id,
            ]);
            
            $stockLevel->unit_id = $product->unit_id;
            $currentQty = $stockLevel->qty ?? 0;
            $stockBefore = $currentQty;
            
            // Calculate new quantity based on adjustment type
            switch ($request->adjustment_type) {
                case 'set':
                    $newQty = $adjustQty;
                    $movementQty = abs($newQty - $currentQty);
                    $isPositive = $newQty >= $currentQty;
                    break;
                case 'add':
                    $newQty = $currentQty + $adjustQty;
                    $movementQty = $adjustQty;
                    $isPositive = true;
                    break;
                case 'subtract':
                    $newQty = max(0, $currentQty - $adjustQty);
                    $movementQty = $adjustQty;
                    $isPositive = false;
                    break;
                default:
                    throw new \Exception('Invalid adjustment type');
            }
            
            $stockLevel->qty = $newQty;
            $stockLevel->save();
            
            $refNo = StockMovement::generateReferenceNo('ADJ');
            
            // Create movement record
            StockMovement::create([
                'reference_no' => $refNo,
                'product_id' => $product->id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
                'lot_id' => $request->lot_id,
                'unit_id' => $product->unit_id,  // Base unit for adjustments
                'qty' => $movementQty,
                'base_qty' => $movementQty,
                'stock_before' => $stockBefore,
                'stock_after' => $newQty,
                'movement_type' => 'ADJUSTMENT',
                'reference_type' => 'ADJUSTMENT',
                'reason' => $request->reason,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();
            
            $baseUnitName = $product->unit->short_name ?? 'PCS';
            $message = "Stock adjusted: {$stockBefore} → {$newQty} {$baseUnitName} | Ref: {$refNo}";
            
            return redirect()->route('admin.inventory.products.show', $product->id)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to adjust stock: ' . $e->getMessage())->withInput();
        }
    }

    // ==================== TRANSFER ====================
    public function transfer()
    {
        $products = Product::with(['unit', 'productUnits.unit'])
            ->where('is_active', true)
            ->where('track_inventory', true)
            ->orderBy('name')
            ->get();
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
            'qty' => 'required|numeric|min:0.001',
            'unit_id' => 'required|exists:units,id',
            'from_rack_id' => 'nullable|exists:racks,id',
            'to_rack_id' => 'nullable|exists:racks,id',
            'lot_id' => 'nullable|exists:lots,id',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        // Must be different location
        if ($request->from_warehouse_id == $request->to_warehouse_id && 
            $request->from_rack_id == $request->to_rack_id) {
            return back()->with('error', 'Source and destination must be different.')->withInput();
        }

        DB::beginTransaction();
        try {
            $product = Product::with('unit')->findOrFail($request->product_id);
            
            // Calculate conversion
            $conversionData = $this->getConversionFactor($product, $request->unit_id);
            $conversionFactor = $conversionData['factor'];
            $baseQty = $request->qty * $conversionFactor;
            
            // Check source stock
            $sourceStock = $this->getStockInBaseUnits(
                $product->id,
                $request->from_warehouse_id,
                $request->from_rack_id,
                $request->lot_id
            );
            
            if ($sourceStock < $baseQty) {
                $baseUnitName = $product->unit->short_name ?? 'PCS';
                return back()->with('error', "Insufficient stock at source! Available: {$sourceStock} {$baseUnitName}, Required: {$baseQty} {$baseUnitName}")->withInput();
            }
            
            // Get source stock level
            $sourceStockLevel = StockLevel::where('product_id', $product->id)
                ->where('warehouse_id', $request->from_warehouse_id)
                ->when($request->from_rack_id, fn($q) => $q->where('rack_id', $request->from_rack_id))
                ->when($request->lot_id, fn($q) => $q->where('lot_id', $request->lot_id))
                ->first();
            
            $sourceStockBefore = $sourceStockLevel->qty;
            
            // Deduct from source
            $sourceStockLevel->qty -= $baseQty;
            if ($sourceStockLevel->qty < 0) $sourceStockLevel->qty = 0;
            $sourceStockLevel->save();
            
            // Add to destination
            $destStockLevel = StockLevel::firstOrNew([
                'product_id' => $product->id,
                'warehouse_id' => $request->to_warehouse_id,
                'rack_id' => $request->to_rack_id,
                'lot_id' => $request->lot_id,
            ]);
            
            $destStockBefore = $destStockLevel->qty ?? 0;
            $destStockLevel->unit_id = $product->unit_id;
            $destStockLevel->qty = ($destStockLevel->qty ?? 0) + $baseQty;
            $destStockLevel->save();
            
            // Generate transfer number
            $transferNo = StockTransfer::generateTransferNo();
            
            // Get warehouse/rack names
            $fromWarehouse = Warehouse::find($request->from_warehouse_id);
            $toWarehouse = Warehouse::find($request->to_warehouse_id);
            $fromRack = $request->from_rack_id ? Rack::find($request->from_rack_id) : null;
            $toRack = $request->to_rack_id ? Rack::find($request->to_rack_id) : null;
            
            // Create StockTransfer record
            StockTransfer::create([
                'transfer_no' => $transferNo,
                'product_id' => $product->id,
                'lot_id' => $request->lot_id,
                'unit_id' => $request->unit_id,
                'from_warehouse_id' => $request->from_warehouse_id,
                'to_warehouse_id' => $request->to_warehouse_id,
                'from_rack_id' => $request->from_rack_id,
                'to_rack_id' => $request->to_rack_id,
                'qty' => $request->qty,
                'base_qty' => $baseQty,
                'status' => 'COMPLETED',
                'reason' => $request->reason ?? 'Stock Transfer',
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);
            
            // Create OUT movement (from source)
            StockMovement::create([
                'reference_no' => $transferNo,
                'product_id' => $product->id,
                'warehouse_id' => $request->from_warehouse_id,
                'rack_id' => $request->from_rack_id,
                'lot_id' => $request->lot_id,
                'unit_id' => $request->unit_id,
                'qty' => $request->qty,
                'base_qty' => $baseQty,
                'stock_before' => $sourceStockBefore,
                'stock_after' => $sourceStockLevel->qty,
                'movement_type' => 'TRANSFER',
                'reference_type' => 'TRANSFER',
                'reason' => "Transfer OUT → " . $toWarehouse->name . ($toRack ? " ({$toRack->code})" : ''),
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);
            
            // Create IN movement (to destination)
            StockMovement::create([
                'reference_no' => $transferNo . '-IN',
                'product_id' => $product->id,
                'warehouse_id' => $request->to_warehouse_id,
                'rack_id' => $request->to_rack_id,
                'lot_id' => $request->lot_id,
                'unit_id' => $request->unit_id,
                'qty' => $request->qty,
                'base_qty' => $baseQty,
                'stock_before' => $destStockBefore,
                'stock_after' => $destStockLevel->qty,
                'movement_type' => 'TRANSFER',
                'reference_type' => 'TRANSFER',
                'reason' => "Transfer IN ← " . $fromWarehouse->name . ($fromRack ? " ({$fromRack->code})" : ''),
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();
            
            // Build success message
            $unitName = $conversionData['unit_name'];
            $baseUnitName = $product->unit->short_name ?? 'PCS';
            $fromLocation = $fromWarehouse->name . ($fromRack ? " ({$fromRack->code})" : '');
            $toLocation = $toWarehouse->name . ($toRack ? " ({$toRack->code})" : '');
            
            $message = "Stock transferred: {$request->qty} {$unitName}";
            if ($conversionFactor != 1) {
                $message .= " (= {$baseQty} {$baseUnitName})";
            }
            $message .= " from {$fromLocation} to {$toLocation} | Ref: {$transferNo}";
            
            return redirect()->route('admin.inventory.products.show', $product->id)
                ->with('success', $message);
                
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
        $unitId = $request->get('unit_id');  // Optional: return stock in this unit
        
        if (!$productId) {
            return response()->json(['error' => 'Product ID required'], 400);
        }
        
        $product = Product::with(['unit', 'productUnits.unit'])->find($productId);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        
        $baseUnitName = $product->unit->short_name ?? 'PCS';
        
        // Get stock in base units
        $baseStock = $this->getStockInBaseUnits($productId, $warehouseId, $rackId, $lotId);
        
        // If unit_id provided, convert stock to that unit
        $displayStock = $baseStock;
        $displayUnit = $baseUnitName;
        
        if ($unitId) {
            $conversionData = $this->getConversionFactor($product, $unitId);
            if ($conversionData['factor'] > 0) {
                $displayStock = $baseStock / $conversionData['factor'];
                $displayUnit = $conversionData['unit_name'];
            }
        }
        
        // Get available lots for this product
        $lots = [];
        if ($product->is_batch_managed) {
            $lotsQuery = Lot::where('product_id', $productId)
                ->where('status', 'ACTIVE')
                ->orderBy('expiry_date');
            
            if ($warehouseId) {
                $lotIds = StockLevel::where('product_id', $productId)
                    ->where('warehouse_id', $warehouseId)
                    ->when($rackId, fn($q) => $q->where('rack_id', $rackId))
                    ->where('qty', '>', 0)
                    ->pluck('lot_id')
                    ->filter()
                    ->unique();
                    
                $lotsQuery->whereIn('id', $lotIds);
            }
            
            $lots = $lotsQuery->get()->map(function ($lot) use ($productId, $warehouseId, $rackId, $baseUnitName) {
                $lotStock = $this->getStockInBaseUnits($productId, $warehouseId, $rackId, $lot->id);
                return [
                    'id' => $lot->id,
                    'lot_no' => $lot->lot_no,
                    'batch_no' => $lot->batch_no,
                    'manufacturing_date' => $lot->manufacturing_date?->format('Y-m-d'),
                    'expiry_date' => $lot->expiry_date?->format('Y-m-d'),
                    'expiry_display' => $lot->expiry_date?->format('d M Y'),
                    'is_expired' => $lot->expiry_date && $lot->expiry_date->isPast(),
                    'days_to_expiry' => $lot->expiry_date ? now()->diffInDays($lot->expiry_date, false) : null,
                    'stock' => $lotStock,
                    'stock_display' => number_format($lotStock, 2) . ' ' . $baseUnitName,
                ];
            });
        }
        
        // Get available units for this product
        $units = collect([
            [
                'id' => $product->unit_id,
                'name' => $product->unit->name ?? 'Base Unit',
                'short_name' => $baseUnitName,
                'conversion_factor' => 1,
                'is_base' => true,
            ]
        ]);
        
        foreach ($product->productUnits as $pu) {
            $units->push([
                'id' => $pu->unit_id,
                'name' => $pu->unit_name ?: $pu->unit->name,
                'short_name' => $pu->unit->short_name ?? '',
                'conversion_factor' => $pu->conversion_factor,
                'is_base' => false,
                'is_purchase_unit' => $pu->is_purchase_unit,
                'is_sale_unit' => $pu->is_sale_unit,
                'purchase_price' => $pu->purchase_price,
                'sale_price' => $pu->sale_price,
            ]);
        }
        
        return response()->json([
            'product_id' => $productId,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'warehouse_id' => $warehouseId,
            'rack_id' => $rackId,
            'lot_id' => $lotId,
            
            // Stock in base unit
            'base_stock' => round($baseStock, 3),
            'base_unit' => $baseUnitName,
            'base_unit_id' => $product->unit_id,
            
            // Stock in requested/display unit
            'display_stock' => round($displayStock, 3),
            'display_unit' => $displayUnit,
            
            // Additional data
            'is_batch_managed' => $product->is_batch_managed,
            'lots' => $lots,
            'units' => $units,
            
            // For compatibility
            'quantity' => round($baseStock, 3),
            'unit' => $baseUnitName,
        ]);
    }

    // ==================== HELPER: Get Conversion Factor ====================
    /**
     * Get conversion factor for a unit relative to product's base unit
     * 
     * @param Product $product
     * @param int $unitId
     * @return array ['factor' => float, 'unit_name' => string]
     */
    protected function getConversionFactor(Product $product, int $unitId): array
    {
        // If it's the base unit, conversion is 1
        if ($unitId == $product->unit_id) {
            return [
                'factor' => 1,
                'unit_name' => $product->unit->short_name ?? 'PCS',
            ];
        }
        
        // Check product_units table first (product-specific conversions)
        $productUnit = ProductUnit::where('product_id', $product->id)
            ->where('unit_id', $unitId)
            ->first();
            
        if ($productUnit) {
            return [
                'factor' => $productUnit->conversion_factor,
                'unit_name' => $productUnit->unit_name ?: ($productUnit->unit->short_name ?? 'PCS'),
            ];
        }
        
        // Check units table (global conversions)
        $unit = Unit::find($unitId);
        if ($unit) {
            // If unit has same base as product's base unit, use conversion_factor
            if ($unit->base_unit_id == $product->unit_id) {
                return [
                    'factor' => $unit->conversion_factor,
                    'unit_name' => $unit->short_name,
                ];
            }
            
            // Otherwise, treat as 1:1 (fallback)
            return [
                'factor' => 1,
                'unit_name' => $unit->short_name,
            ];
        }
        
        // Fallback
        return [
            'factor' => 1,
            'unit_name' => 'PCS',
        ];
    }

    // ==================== HELPER: Get Stock in Base Units ====================
    /**
     * Get total stock for a product in BASE UNITS
     * 
     * @param int $productId
     * @param int|null $warehouseId
     * @param int|null $rackId
     * @param int|null $lotId
     * @return float
     */
    protected function getStockInBaseUnits(int $productId, ?int $warehouseId = null, ?int $rackId = null, ?int $lotId = null): float
    {
        $query = StockLevel::where('product_id', $productId);
        
        if ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        }
        if ($rackId) {
            $query->where('rack_id', $rackId);
        }
        if ($lotId) {
            $query->where('lot_id', $lotId);
        }
        
        return (float) ($query->sum('qty') ?? 0);
    }

    // ==================== GET PRODUCT UNITS (AJAX) ====================
    /**
     * Get available units for a product (for dropdown population)
     */
    public function getProductUnits(Request $request)
    {
        $productId = $request->get('product_id');
        
        if (!$productId) {
            return response()->json(['error' => 'Product ID required'], 400);
        }
        
        $product = Product::with(['unit', 'productUnits.unit'])->find($productId);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        
        $units = [];
        
        // Add base unit first
        $units[] = [
            'id' => $product->unit_id,
            'name' => $product->unit->name ?? 'Base Unit',
            'short_name' => $product->unit->short_name ?? 'PCS',
            'display_name' => ($product->unit->name ?? 'Base Unit') . ' (' . ($product->unit->short_name ?? 'PCS') . ')',
            'conversion_factor' => 1,
            'is_base' => true,
            'purchase_price' => $product->purchase_price,
            'sale_price' => $product->sale_price,
        ];
        
        // Add product-specific units
        foreach ($product->productUnits as $pu) {
            $unitName = $pu->unit_name ?: $pu->unit->name;
            $shortName = $pu->unit->short_name ?? '';
            
            $units[] = [
                'id' => $pu->unit_id,
                'name' => $unitName,
                'short_name' => $shortName,
                'display_name' => "{$unitName} ({$shortName}) - {$pu->conversion_factor}x",
                'conversion_factor' => $pu->conversion_factor,
                'is_base' => false,
                'is_purchase_unit' => $pu->is_purchase_unit,
                'is_sale_unit' => $pu->is_sale_unit,
                'purchase_price' => $pu->purchase_price ?? $product->purchase_price * $pu->conversion_factor,
                'sale_price' => $pu->sale_price ?? $product->sale_price * $pu->conversion_factor,
                'barcode' => $pu->barcode,
            ];
        }
        
        return response()->json([
            'product_id' => $productId,
            'base_unit_id' => $product->unit_id,
            'base_unit_name' => $product->unit->short_name ?? 'PCS',
            'is_batch_managed' => $product->is_batch_managed,
            'units' => $units,
        ]);
    }

    // ==================== GET PRODUCT LOTS (AJAX) ====================

    /**
 * Get lots for a product
 * For RECEIVE: Returns ALL lots (no warehouse filter)
 * For DELIVER/TRANSFER: Returns lots with stock at warehouse (with warehouse filter)
 */
public function productLots(Request $request)
{
    $productId = $request->product_id;
    
    if (!$productId) {
        return response()->json(['lots' => []]);
    }
    
    $query = Lot::where('product_id', $productId)
        ->where('status', 'ACTIVE');
    
    // If warehouse_id is provided, filter to lots that have stock at this location
    if ($request->filled('warehouse_id')) {
        $warehouseId = $request->warehouse_id;
        $rackId = $request->rack_id;
        
        $query->whereHas('stockLevels', function($q) use ($warehouseId, $rackId) {
            $q->where('warehouse_id', $warehouseId)
              ->where('qty', '>', 0);
            
            if ($rackId) {
                $q->where('rack_id', $rackId);
            }
        });
        
        // Also load stock quantity for display
        $query->with(['stockLevels' => function($q) use ($warehouseId, $rackId) {
            $q->where('warehouse_id', $warehouseId);
            if ($rackId) {
                $q->where('rack_id', $rackId);
            }
        }]);
    }
    
    // Order by expiry date (FEFO - First Expiry First Out)
    $lots = $query->orderBy('expiry_date', 'asc')->get();
    
    // Format the response with all needed fields including prices
    $formattedLots = $lots->map(function($lot) {
        $stockQty = 0;
        if ($lot->relationLoaded('stockLevels') && $lot->stockLevels->count() > 0) {
            $stockQty = $lot->stockLevels->sum('qty');
        }
        
        return [
            'id' => $lot->id,
            'lot_no' => $lot->lot_no,
            'batch_no' => $lot->batch_no,
            'manufacturing_date' => $lot->manufacturing_date ? $lot->manufacturing_date->format('Y-m-d') : null,
            'expiry_date' => $lot->expiry_date ? $lot->expiry_date->format('Y-m-d') : null,
            'purchase_price' => $lot->purchase_price,  // IMPORTANT: Include lot prices
            'sale_price' => $lot->sale_price,          // IMPORTANT: Include lot prices
            'status' => $lot->status,
            'stock' => $stockQty,
            'stock_display' => $stockQty . ' ' . ($lot->product->unit->short_name ?? 'PCS'),
        ];
    });
    
    return response()->json(['lots' => $formattedLots]);
}
    /**
     * Get available lots for a product (for dropdown population)
     */
    public function getProductLots(Request $request)
    {
        $productId = $request->get('product_id');
        $warehouseId = $request->get('warehouse_id');
        $rackId = $request->get('rack_id');
        $activeOnly = $request->get('active_only', true);
        
        if (!$productId) {
            return response()->json(['error' => 'Product ID required'], 400);
        }
        
        $product = Product::with('unit')->find($productId);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        
        if (!$product->is_batch_managed) {
            return response()->json([
                'product_id' => $productId,
                'is_batch_managed' => false,
                'lots' => [],
            ]);
        }
        
        $baseUnitName = $product->unit->short_name ?? 'PCS';
        
        // Get lots with stock
        $lotsQuery = Lot::where('product_id', $productId);
        
        if ($activeOnly) {
            $lotsQuery->where('status', 'ACTIVE');
        }
        
        // Filter by warehouse stock if specified
        if ($warehouseId) {
            $lotIdsWithStock = StockLevel::where('product_id', $productId)
                ->where('warehouse_id', $warehouseId)
                ->when($rackId, fn($q) => $q->where('rack_id', $rackId))
                ->where('qty', '>', 0)
                ->pluck('lot_id')
                ->filter()
                ->unique();
                
            $lotsQuery->whereIn('id', $lotIdsWithStock);
        }
        
        $lots = $lotsQuery->orderBy('expiry_date')
            ->get()
            ->map(function ($lot) use ($productId, $warehouseId, $rackId, $baseUnitName) {
                $stock = $this->getStockInBaseUnits($productId, $warehouseId, $rackId, $lot->id);
                
                $daysToExpiry = null;
                $expiryStatus = 'ok';
                
                if ($lot->expiry_date) {
                    $daysToExpiry = now()->diffInDays($lot->expiry_date, false);
                    if ($daysToExpiry < 0) {
                        $expiryStatus = 'expired';
                    } elseif ($daysToExpiry <= 30) {
                        $expiryStatus = 'expiring_soon';
                    }
                }
                
                return [
                    'id' => $lot->id,
                    'lot_no' => $lot->lot_no,
                    'batch_no' => $lot->batch_no,
                    'display_name' => $lot->lot_no . ($lot->batch_no ? " / {$lot->batch_no}" : ''),
                    'manufacturing_date' => $lot->manufacturing_date?->format('Y-m-d'),
                    'manufacturing_display' => $lot->manufacturing_date?->format('d M Y'),
                    'expiry_date' => $lot->expiry_date?->format('Y-m-d'),
                    'expiry_display' => $lot->expiry_date?->format('d M Y'),
                    'days_to_expiry' => $daysToExpiry,
                    'expiry_status' => $expiryStatus,
                    'status' => $lot->status,
                    'stock' => round($stock, 3),
                    'stock_display' => number_format($stock, 2) . ' ' . $baseUnitName,
                    'purchase_price' => $lot->purchase_price,
                    'sale_price' => $lot->sale_price,
                ];
            });
        
        return response()->json([
            'product_id' => $productId,
            'is_batch_managed' => true,
            'base_unit' => $baseUnitName,
            'lots' => $lots,
        ]);
    }
}