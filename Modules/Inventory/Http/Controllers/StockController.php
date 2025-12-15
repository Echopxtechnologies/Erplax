<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductUnit;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\Rack;
use Modules\Inventory\Models\Unit;
use Modules\Inventory\Models\Lot;
use Modules\Inventory\Models\StockLevel;
use Modules\Inventory\Models\StockMovement;
use Modules\Inventory\Models\StockTransfer;
use Illuminate\Support\Facades\DB;
use Modules\Core\Traits\DataTableTrait;

/**
 * Stock Controller - Handles all stock operations with multi-unit support
 * 
 * IMPORTANT: All stock is stored in BASE UNITS in stock_levels table
 */
class StockController extends BaseController
{
    use DataTableTrait;

    // ==================== DATATABLE CONFIGURATION ====================
    protected $model = StockMovement::class;
    
    protected $with = ['product.unit', 'warehouse', 'rack', 'lot', 'unit', 'creator'];
    
    protected $searchable = ['reference_no', 'reason', 'notes', 'product.name', 'product.sku', 'warehouse.name', 'lot.lot_no'];
    
    protected $sortable = ['id', 'reference_no', 'created_at', 'qty', 'movement_type', 'warehouse_id'];
    
    protected $filterable = ['product_id', 'warehouse_id', 'movement_type', 'lot_id', 'from_date', 'to_date'];
    
    protected $exportable = [
        'reference_no',
        'created_at',
        'movement_type', 
        'product_name',
        'product_sku',
        'qty',
        'base_qty',
        'unit',
        'base_unit',
        'warehouse_name',
        'rack_code',
        'lot_no',
        'batch_no',
        'reason',
        'notes',
        'stock_before',
        'stock_after',
        'created_by'
    ];
    
    protected $exportTitle = 'Stock Movements Export';

    // ==================== CUSTOM ROW MAPPING FOR DATATABLE ====================
    protected function mapRow($item)
    {
        $unitName = $item->unit->short_name ?? $item->product->unit->short_name ?? 'PCS';
        $baseUnitName = $item->product->unit->short_name ?? 'PCS';
        $isPositive = in_array($item->movement_type, ['IN', 'RETURN']);
        
        $warehouseName = $item->warehouse->name ?? '-';
        $rackCode = $item->rack->code ?? '';
        $locationDisplay = $warehouseName . ($rackCode ? " ({$rackCode})" : '');
        
        // Check if transfer and get details
        if ($item->movement_type === 'TRANSFER') {
            $transferNo = str_replace('-IN', '', $item->reference_no);
            $transfer = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'fromRack', 'toRack'])
                ->where('transfer_no', $transferNo)
                ->first();
            
            if ($transfer) {
                $fromWarehouse = $transfer->fromWarehouse->name ?? '-';
                $fromRackCode = $transfer->fromRack->code ?? '';
                $toWarehouse = $transfer->toWarehouse->name ?? '-';
                $toRackCode = $transfer->toRack->code ?? '';
                
                $locationDisplay = "From: {$fromWarehouse}" . ($fromRackCode ? " ({$fromRackCode})" : '') . 
                                   " → To: {$toWarehouse}" . ($toRackCode ? " ({$toRackCode})" : '');
            }
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
        
        // Qty display
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
            'lot_no' => $item->lot->lot_no ?? '',
            'batch_no' => $item->lot->batch_no ?? '',
            'stock_before' => number_format($item->stock_before, 2),
            'stock_after' => number_format($item->stock_after, 2),
        ];
    }

    // ==================== CUSTOM EXPORT ROW MAPPING ====================
    protected function mapExportRow($item)
    {
        $unitName = $item->unit->short_name ?? $item->product->unit->short_name ?? 'PCS';
        $baseUnitName = $item->product->unit->short_name ?? 'PCS';
        $isPositive = in_array($item->movement_type, ['IN', 'RETURN']);
        
        // Build location display (same logic as mapRow)
        $warehouseName = $item->warehouse->name ?? '-';
        $rackCode = $item->rack->code ?? '';
        $locationDisplay = $warehouseName . ($rackCode ? " ({$rackCode})" : '');
        
        // For transfers, get full FROM → TO details
        if ($item->movement_type === 'TRANSFER') {
            $transferNo = str_replace('-IN', '', $item->reference_no);
            $transfer = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'fromRack', 'toRack'])
                ->where('transfer_no', $transferNo)
                ->first();
            
            if ($transfer) {
                $fromWarehouse = $transfer->fromWarehouse->name ?? '-';
                $fromRackCode = $transfer->fromRack->code ?? '';
                $toWarehouse = $transfer->toWarehouse->name ?? '-';
                $toRackCode = $transfer->toRack->code ?? '';
                
                $locationDisplay = "From: {$fromWarehouse}" . ($fromRackCode ? " ({$fromRackCode})" : '') . 
                                   " → To: {$toWarehouse}" . ($toRackCode ? " ({$toRackCode})" : '');
            }
        }
        
        // Signed quantity for export
        $qtyDisplay = ($isPositive ? '+' : '-') . number_format($item->base_qty ?? $item->qty, 2) . ' ' . $baseUnitName;
        
        return [
            'Reference No' => $item->reference_no ?? '-',
            'Date' => $item->created_at->format('d M Y'),
            'Time' => $item->created_at->format('h:i A'),
            'Type' => $item->movement_type,
            'Product' => $item->product->name ?? '-',
            'SKU' => $item->product->sku ?? '-',
            'Qty' => $qtyDisplay,
            'Unit' => $unitName,
            'Base Qty' => $item->base_qty ?? $item->qty,
            'Base Unit' => $baseUnitName,
            'Location' => $locationDisplay,
            'Warehouse' => $warehouseName,
            'Rack' => $rackCode ?: '-',
            'Lot No' => $item->lot->lot_no ?? '-',
            'Batch No' => $item->lot->batch_no ?? '-',
            'Expiry Date' => $item->lot?->expiry_date?->format('d M Y') ?? '-',
            'Reason' => $item->reason ?? '-',
            'Notes' => $item->notes ?? '-',
            'Stock Before' => $item->stock_before,
            'Stock After' => $item->stock_after,
            'Created By' => $item->creator->name ?? 'System',
        ];
    }

    // ==================== CUSTOM FILTER HANDLING ====================
    protected function applyFilters($query, $filters)
    {
        // Handle from_date
        if (!empty($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }
        
        // Handle to_date
        if (!empty($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }
        
        return $query;
    }

    // ==================== STOCK MOVEMENTS LIST ====================
    public function movements(Request $request)
    {
        $stats = [
            'in' => StockMovement::where('movement_type', 'IN')->count(),
            'out' => StockMovement::where('movement_type', 'OUT')->count(),
            'transfer' => StockMovement::where('movement_type', 'TRANSFER')->count(),
            'return' => StockMovement::where('movement_type', 'RETURN')->count(),
            'adjustment' => StockMovement::where('movement_type', 'ADJUSTMENT')->count(),
        ];
        
        $products = Product::where('is_active', true)->orderBy('name')->get(['id', 'name']);
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get(['id', 'name']);
        
        return view('inventory::stock.movements', compact('stats', 'products', 'warehouses'));
    }

    // ==================== STOCK MOVEMENTS DATA (Uses DataTable Trait) ====================
    /**
     * This method uses the DataTable trait's handleData() which handles:
     * - List data (GET request)
     * - Export CSV/Excel/PDF (POST request with export=csv/xlsx/pdf)
     * - Import (POST request with file)
     * 
     * IMPORTANT: Route must be match(['get', 'post'], ...) for export to work!
     */
    public function movementsData(Request $request)
    {
        return $this->handleData($request);
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
        
        return view('inventory::stock.receive', compact('products', 'warehouses', 'units'));
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
            'lot_no' => 'nullable|string|max:100',
            'batch_no' => 'nullable|string|max:100',
            'manufacturing_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:manufacturing_date',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::with('unit')->findOrFail($request->product_id);
            
            $conversionData = $this->getConversionFactor($product, $request->unit_id);
            $conversionFactor = $conversionData['factor'];
            $baseQty = $request->qty * $conversionFactor;
            
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
                
                if (!$lot->manufacturing_date && $request->manufacturing_date) {
                    $lot->update([
                        'manufacturing_date' => $request->manufacturing_date,
                        'expiry_date' => $request->expiry_date,
                        'purchase_price' => $request->purchase_price,
                    ]);
                }
            }
            
            $stockBefore = $this->getStockInBaseUnits($product->id, $request->warehouse_id, $request->rack_id, $lotId);
            
            $stockLevel = StockLevel::firstOrNew([
                'product_id' => $product->id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
                'lot_id' => $lotId,
            ]);
            
            $stockLevel->unit_id = $product->unit_id;
            $stockLevel->qty = ($stockLevel->qty ?? 0) + $baseQty;
            $stockLevel->save();
            
            $stockAfter = $stockLevel->qty;
            $refNo = StockMovement::generateReferenceNo('RCV');
            
            StockMovement::create([
                'reference_no' => $refNo,
                'product_id' => $product->id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
                'lot_id' => $lotId,
                'unit_id' => $request->unit_id,
                'qty' => $request->qty,
                'base_qty' => $baseQty,
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
            
            return redirect()->route('inventory.products.show', $product->id)
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
        
        return view('inventory::stock.deliver', compact('products', 'warehouses', 'units'));
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
            
            $conversionData = $this->getConversionFactor($product, $request->unit_id);
            $conversionFactor = $conversionData['factor'];
            $baseQty = $request->qty * $conversionFactor;
            
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
            
            $stockLevel = StockLevel::where('product_id', $product->id)
                ->where('warehouse_id', $request->warehouse_id)
                ->when($request->rack_id, fn($q) => $q->where('rack_id', $request->rack_id))
                ->when($request->lot_id, fn($q) => $q->where('lot_id', $request->lot_id))
                ->first();
                
            if (!$stockLevel) {
                return back()->with('error', 'Stock record not found at this location.')->withInput();
            }
            
            $stockBefore = $stockLevel->qty;
            $stockLevel->qty -= $baseQty;
            if ($stockLevel->qty < 0) $stockLevel->qty = 0;
            $stockLevel->save();
            
            $stockAfter = $stockLevel->qty;
            $refNo = StockMovement::generateReferenceNo('DLV');
            
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
            
            $unitName = $conversionData['unit_name'];
            $baseUnitName = $product->unit->short_name ?? 'PCS';
            $message = "Stock delivered: {$request->qty} {$unitName}";
            if ($conversionFactor != 1) {
                $message .= " (= {$baseQty} {$baseUnitName})";
            }
            $message .= " | Ref: {$refNo}";
            
            return redirect()->route('inventory.products.show', $product->id)
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
        
        return view('inventory::stock.returns', compact('products', 'warehouses', 'units'));
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
            
            $conversionData = $this->getConversionFactor($product, $request->unit_id);
            $conversionFactor = $conversionData['factor'];
            $baseQty = $request->qty * $conversionFactor;
            
            $stockBefore = $this->getStockInBaseUnits($product->id, $request->warehouse_id, $request->rack_id, $request->lot_id);
            
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
            
            return redirect()->route('inventory.products.show', $product->id)
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
        
        return view('inventory::stock.adjustments', compact('products', 'warehouses'));
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
            $adjustQty = $request->qty;
            
            $stockLevel = StockLevel::firstOrNew([
                'product_id' => $product->id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
                'lot_id' => $request->lot_id,
            ]);
            
            $stockLevel->unit_id = $product->unit_id;
            $currentQty = $stockLevel->qty ?? 0;
            $stockBefore = $currentQty;
            
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
            
            StockMovement::create([
                'reference_no' => $refNo,
                'product_id' => $product->id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
                'lot_id' => $request->lot_id,
                'unit_id' => $product->unit_id,
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
            
            return redirect()->route('inventory.products.show', $product->id)
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
        
        return view('inventory::stock.transfer', compact('products', 'warehouses', 'units'));
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
        
        if ($request->from_warehouse_id == $request->to_warehouse_id && 
            $request->from_rack_id == $request->to_rack_id) {
            return back()->with('error', 'Source and destination must be different.')->withInput();
        }

        DB::beginTransaction();
        try {
            $product = Product::with('unit')->findOrFail($request->product_id);
            
            $conversionData = $this->getConversionFactor($product, $request->unit_id);
            $conversionFactor = $conversionData['factor'];
            $baseQty = $request->qty * $conversionFactor;
            
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
            
            $sourceStockLevel = StockLevel::where('product_id', $product->id)
                ->where('warehouse_id', $request->from_warehouse_id)
                ->when($request->from_rack_id, fn($q) => $q->where('rack_id', $request->from_rack_id))
                ->when($request->lot_id, fn($q) => $q->where('lot_id', $request->lot_id))
                ->first();
            
            $sourceStockBefore = $sourceStockLevel->qty;
            $sourceStockLevel->qty -= $baseQty;
            if ($sourceStockLevel->qty < 0) $sourceStockLevel->qty = 0;
            $sourceStockLevel->save();
            
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
            
            $transferNo = StockTransfer::generateTransferNo();
            
            $fromWarehouse = Warehouse::find($request->from_warehouse_id);
            $toWarehouse = Warehouse::find($request->to_warehouse_id);
            $fromRack = $request->from_rack_id ? Rack::find($request->from_rack_id) : null;
            $toRack = $request->to_rack_id ? Rack::find($request->to_rack_id) : null;
            
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
            
            $unitName = $conversionData['unit_name'];
            $baseUnitName = $product->unit->short_name ?? 'PCS';
            $fromLocation = $fromWarehouse->name . ($fromRack ? " ({$fromRack->code})" : '');
            $toLocation = $toWarehouse->name . ($toRack ? " ({$toRack->code})" : '');
            
            $message = "Stock transferred: {$request->qty} {$unitName}";
            if ($conversionFactor != 1) {
                $message .= " (= {$baseQty} {$baseUnitName})";
            }
            $message .= " from {$fromLocation} to {$toLocation} | Ref: {$transferNo}";
            
            return redirect()->route('inventory.products.show', $product->id)
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
        $unitId = $request->get('unit_id');
        
        if (!$productId) {
            return response()->json(['error' => 'Product ID required'], 400);
        }
        
        $product = Product::with(['unit', 'productUnits.unit'])->find($productId);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        
        $baseUnitName = $product->unit->short_name ?? 'PCS';
        $baseStock = $this->getStockInBaseUnits($productId, $warehouseId, $rackId, $lotId);
        
        $displayStock = $baseStock;
        $displayUnit = $baseUnitName;
        
        if ($unitId) {
            $conversionData = $this->getConversionFactor($product, $unitId);
            if ($conversionData['factor'] > 0) {
                $displayStock = $baseStock / $conversionData['factor'];
                $displayUnit = $conversionData['unit_name'];
            }
        }
        
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
            'base_stock' => round($baseStock, 3),
            'base_unit' => $baseUnitName,
            'base_unit_id' => $product->unit_id,
            'display_stock' => round($displayStock, 3),
            'display_unit' => $displayUnit,
            'is_batch_managed' => $product->is_batch_managed,
            'lots' => $lots,
            'units' => $units,
            'quantity' => round($baseStock, 3),
            'unit' => $baseUnitName,
        ]);
    }

    // ==================== HELPER METHODS ====================
    protected function getConversionFactor(Product $product, int $unitId): array
    {
        if ($unitId == $product->unit_id) {
            return [
                'factor' => 1,
                'unit_name' => $product->unit->short_name ?? 'PCS',
            ];
        }
        
        $productUnit = ProductUnit::where('product_id', $product->id)
            ->where('unit_id', $unitId)
            ->first();
            
        if ($productUnit) {
            return [
                'factor' => $productUnit->conversion_factor,
                'unit_name' => $productUnit->unit_name ?: ($productUnit->unit->short_name ?? 'PCS'),
            ];
        }
        
        $unit = Unit::find($unitId);
        if ($unit) {
            if ($unit->base_unit_id == $product->unit_id) {
                return [
                    'factor' => $unit->conversion_factor,
                    'unit_name' => $unit->short_name,
                ];
            }
            
            return [
                'factor' => 1,
                'unit_name' => $unit->short_name,
            ];
        }
        
        return ['factor' => 1, 'unit_name' => 'PCS'];
    }

    protected function getStockInBaseUnits(int $productId, ?int $warehouseId = null, ?int $rackId = null, ?int $lotId = null): float
    {
        $query = StockLevel::where('product_id', $productId);
        
        if ($warehouseId) $query->where('warehouse_id', $warehouseId);
        if ($rackId) $query->where('rack_id', $rackId);
        if ($lotId) $query->where('lot_id', $lotId);
        
        return (float) ($query->sum('qty') ?? 0);
    }

    // ==================== GET PRODUCT UNITS (AJAX) ====================
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
    public function productLots(Request $request)
    {
        $productId = $request->product_id;
        
        if (!$productId) {
            return response()->json(['lots' => []]);
        }
        
        $query = Lot::where('product_id', $productId)->where('status', 'ACTIVE');
        
        if ($request->filled('warehouse_id')) {
            $warehouseId = $request->warehouse_id;
            $rackId = $request->rack_id;
            
            $query->whereHas('stockLevels', function($q) use ($warehouseId, $rackId) {
                $q->where('warehouse_id', $warehouseId)->where('qty', '>', 0);
                if ($rackId) $q->where('rack_id', $rackId);
            });
            
            $query->with(['stockLevels' => function($q) use ($warehouseId, $rackId) {
                $q->where('warehouse_id', $warehouseId);
                if ($rackId) $q->where('rack_id', $rackId);
            }]);
        }
        
        $lots = $query->orderBy('expiry_date', 'asc')->get();
        
        $formattedLots = $lots->map(function($lot) {
            $stockQty = 0;
            if ($lot->relationLoaded('stockLevels') && $lot->stockLevels->count() > 0) {
                $stockQty = $lot->stockLevels->sum('qty');
            }
            
            return [
                'id' => $lot->id,
                'lot_no' => $lot->lot_no,
                'batch_no' => $lot->batch_no,
                'manufacturing_date' => $lot->manufacturing_date?->format('Y-m-d'),
                'expiry_date' => $lot->expiry_date?->format('Y-m-d'),
                'purchase_price' => $lot->purchase_price,
                'sale_price' => $lot->sale_price,
                'status' => $lot->status,
                'stock' => $stockQty,
                'stock_display' => $stockQty . ' ' . ($lot->product->unit->short_name ?? 'PCS'),
            ];
        });
        
        return response()->json(['lots' => $formattedLots]);
    }

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
        $lotsQuery = Lot::where('product_id', $productId);
        
        if ($activeOnly) {
            $lotsQuery->where('status', 'ACTIVE');
        }
        
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