<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Purchase\Models\GoodsReceiptNote;
use Modules\Purchase\Models\GoodsReceiptNoteItem;
use Modules\Purchase\Models\PurchaseOrder;
use Modules\Purchase\Models\PurchaseSetting;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\Rack;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\StockLevel;
use Modules\Inventory\Models\StockMovement;
use Modules\Inventory\Models\Lot;

class GoodsReceiptNoteController extends AdminController
{
    /**
     * Display GRN listing
     */
    public function index()
    {
        $stats = [
            'total' => GoodsReceiptNote::count(),
            'draft' => GoodsReceiptNote::where('status', 'DRAFT')->count(),
            'inspecting' => GoodsReceiptNote::where('status', 'INSPECTING')->count(),
            'approved' => GoodsReceiptNote::where('status', 'APPROVED')->count(),
        ];

        return $this->moduleView('purchase::grn.index', compact('stats'));
    }

    /**
     * DataTable data
     */
    public function dataTable(Request $request)
    {
        $query = GoodsReceiptNote::with(['vendor', 'purchaseOrder', 'warehouse', 'creator']);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('grn_number', 'like', "%{$search}%")
                  ->orWhere('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('vendor', fn($v) => $v->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('purchaseOrder', fn($p) => $p->where('po_number', 'like', "%{$search}%"));
            });
        }

        // Status filter
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Date range
        if ($from = $request->get('from_date')) {
            $query->whereDate('grn_date', '>=', $from);
        }
        if ($to = $request->get('to_date')) {
            $query->whereDate('grn_date', '<=', $to);
        }

        // Sorting
        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        // Pagination
        $perPage = $request->get('per_page', 25);
        $paginated = $query->paginate($perPage);

        $items = collect($paginated->items())->map(function($grn) {
            return [
                'id' => $grn->id,
                'grn_number' => $grn->grn_number,
                'grn_date' => $grn->grn_date->format('d M Y'),
                'po_number' => $grn->purchaseOrder->po_number ?? '-',
                'po_id' => $grn->purchase_order_id,
                'vendor_name' => $grn->vendor->name ?? '-',
                'vendor_id' => $grn->vendor_id,
                'warehouse_name' => $grn->warehouse->name ?? '-',
                'invoice_number' => $grn->invoice_number ?? '-',
                'total_qty' => number_format($grn->total_qty, 2),
                'accepted_qty' => number_format($grn->accepted_qty, 2),
                'rejected_qty' => number_format($grn->rejected_qty, 2),
                'status' => $grn->status,
                'status_badge' => $grn->status_badge,
                'stock_updated' => $grn->stock_updated,
                'created_by' => $grn->creator->name ?? '-',
                'created_at' => $grn->created_at->format('d M Y'),
                '_show_url' => route('admin.purchase.grn.show', $grn->id),
                '_edit_url' => route('admin.purchase.grn.edit', $grn->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $paginated->total(),
            'current_page' => $paginated->currentPage(),
            'last_page' => $paginated->lastPage(),
            'per_page' => $paginated->perPage(),
        ]);
    }

    /**
     * Show create form - Select PO first
     */
    public function create(Request $request)
    {
        // Get confirmed POs that are not fully received
        $purchaseOrders = PurchaseOrder::with('vendor')
            ->whereIn('status', ['CONFIRMED', 'PARTIALLY_RECEIVED'])
            ->orderBy('po_date', 'desc')
            ->get();

        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        
        $selectedPO = null;
        if ($request->has('po_id')) {
            $selectedPO = PurchaseOrder::with(['vendor', 'items.product', 'items.unit'])
                ->find($request->po_id);
        }

        return $this->moduleView('purchase::grn.create', compact('purchaseOrders', 'warehouses', 'selectedPO'));
    }

    /**
     * Store new GRN
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'grn_date' => 'required|date',
            'warehouse_id' => 'required|exists:warehouses,id',
            'rack_id' => 'nullable|exists:racks,id',
            'invoice_number' => 'nullable|string|max:100',
            'invoice_date' => 'nullable|date',
            'lr_number' => 'nullable|string|max:100',
            'vehicle_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.po_item_id' => 'required|exists:purchase_order_items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.received_qty' => 'required|numeric|min:0',
            'items.*.accepted_qty' => 'required|numeric|min:0',
            'items.*.rejected_qty' => 'nullable|numeric|min:0',
            'items.*.lot_no' => 'nullable|string|max:100',
            'items.*.batch_no' => 'nullable|string|max:100',
            'items.*.manufacturing_date' => 'nullable|date',
            'items.*.expiry_date' => 'nullable|date',
        ]);

        $po = PurchaseOrder::with('vendor')->findOrFail($validated['purchase_order_id']);

        DB::beginTransaction();
        try {
            // Create GRN
            $grn = GoodsReceiptNote::create([
                'grn_number' => GoodsReceiptNote::generateGrnNumber(),
                'purchase_order_id' => $po->id,
                'vendor_id' => $po->vendor_id,
                'grn_date' => $validated['grn_date'],
                'warehouse_id' => $validated['warehouse_id'],
                'rack_id' => $validated['rack_id'] ?? null,
                'invoice_number' => $validated['invoice_number'] ?? null,
                'invoice_date' => $validated['invoice_date'] ?? null,
                'lr_number' => $validated['lr_number'] ?? null,
                'vehicle_number' => $validated['vehicle_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'DRAFT',
                'received_by' => auth()->id(),
                'created_by' => auth()->id(),
            ]);

            // Create GRN items
            foreach ($validated['items'] as $itemData) {
                if (($itemData['received_qty'] ?? 0) <= 0) continue;

                $poItem = $po->items()->find($itemData['po_item_id']);
                if (!$poItem) continue;

                $grn->items()->create([
                    'purchase_order_item_id' => $poItem->id,
                    'product_id' => $itemData['product_id'],
                    'variation_id' => $itemData['variation_id'] ?? $poItem->variation_id,
                    'unit_id' => $itemData['unit_id'] ?? $poItem->unit_id,
                    'ordered_qty' => $poItem->qty,
                    'received_qty' => $itemData['received_qty'],
                    'accepted_qty' => $itemData['accepted_qty'],
                    'rejected_qty' => $itemData['rejected_qty'] ?? 0,
                    'rate' => $itemData['rate'] ?? $poItem->rate,
                    'lot_no' => $itemData['lot_no'] ?? null,
                    'batch_no' => $itemData['batch_no'] ?? null,
                    'manufacturing_date' => $itemData['manufacturing_date'] ?? null,
                    'expiry_date' => $itemData['expiry_date'] ?? null,
                ]);
            }

            $grn->calculateTotals();

            DB::commit();

            return redirect()
                ->route('admin.purchase.grn.show', $grn->id)
                ->with('success', "GRN {$grn->grn_number} created successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating GRN: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show GRN details
     */
    public function show(Request $request, $id)
    {
        $grn = GoodsReceiptNote::with([
            'vendor',
            'purchaseOrder',
            'warehouse',
            'rack',
            'items.product',
            'items.unit',
            'items.lot',
            'creator',
            'receiver',
            'approver',
        ])->findOrFail($id);

        // Return JSON for AJAX requests
        if ($request->wantsJson() || $request->input('format') === 'json') {
            return response()->json([
                'id' => $grn->id,
                'grn_number' => $grn->grn_number,
                'vendor_id' => $grn->vendor_id,
                'vendor' => $grn->vendor,
                'warehouse_id' => $grn->warehouse_id,
                'items' => $grn->items->map(function($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product' => $item->product,
                        'unit_id' => $item->unit_id,
                        'unit' => $item->unit,
                        'variation_id' => $item->variation_id,
                        'ordered_qty' => $item->ordered_qty,
                        'received_qty' => $item->received_qty,
                        'accepted_qty' => $item->accepted_qty,
                        'rejected_qty' => $item->rejected_qty,
                        'rate' => $item->rate,
                    ];
                }),
            ]);
        }

        return $this->moduleView('purchase::grn.show', compact('grn'));
    }

    /**
     * Edit GRN (only Draft/Inspecting)
     */
    public function edit($id)
    {
        $grn = GoodsReceiptNote::with([
            'items.product',
            'items.unit',
            'purchaseOrder.items',
        ])->findOrFail($id);

        if (!$grn->can_edit) {
            return redirect()
                ->route('admin.purchase.grn.show', $grn->id)
                ->with('error', 'Cannot edit GRN in current status.');
        }

        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $racks = $grn->warehouse_id 
            ? Rack::where('warehouse_id', $grn->warehouse_id)->where('is_active', true)->get() 
            : collect();

        return $this->moduleView('purchase::grn.edit', compact('grn', 'warehouses', 'racks'));
    }

    /**
     * Update GRN
     */
    public function update(Request $request, $id)
    {
        $grn = GoodsReceiptNote::findOrFail($id);

        if (!$grn->can_edit) {
            return back()->with('error', 'Cannot update GRN in current status.');
        }

        $validated = $request->validate([
            'grn_date' => 'required|date',
            'warehouse_id' => 'required|exists:warehouses,id',
            'rack_id' => 'nullable|exists:racks,id',
            'invoice_number' => 'nullable|string|max:100',
            'invoice_date' => 'nullable|date',
            'lr_number' => 'nullable|string|max:100',
            'vehicle_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:goods_receipt_note_items,id',
            'items.*.received_qty' => 'nullable|numeric|min:0',
            'items.*.accepted_qty' => 'nullable|numeric|min:0',
            'items.*.rejected_qty' => 'nullable|numeric|min:0',
            'items.*.lot_no' => 'nullable|string|max:100',
            'items.*.batch_no' => 'nullable|string|max:100',
            'items.*.manufacturing_date' => 'nullable|date',
            'items.*.expiry_date' => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $grn->update([
                'grn_date' => $validated['grn_date'],
                'warehouse_id' => $validated['warehouse_id'],
                'rack_id' => $validated['rack_id'] ?? null,
                'invoice_number' => $validated['invoice_number'] ?? null,
                'invoice_date' => $validated['invoice_date'] ?? null,
                'lr_number' => $validated['lr_number'] ?? null,
                'vehicle_number' => $validated['vehicle_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Update items
            foreach ($validated['items'] as $itemData) {
                $grnItem = $grn->items()->find($itemData['id']);
                if ($grnItem) {
                    $grnItem->update([
                        'received_qty' => $itemData['received_qty'] ?? $grnItem->received_qty,
                        'accepted_qty' => $itemData['accepted_qty'] ?? $grnItem->accepted_qty,
                        'rejected_qty' => $itemData['rejected_qty'] ?? 0,
                        'lot_no' => $itemData['lot_no'] ?? null,
                        'batch_no' => $itemData['batch_no'] ?? null,
                        'manufacturing_date' => $itemData['manufacturing_date'] ?? null,
                        'expiry_date' => $itemData['expiry_date'] ?? null,
                    ]);
                }
            }

            $grn->calculateTotals();

            DB::commit();

            return redirect()
                ->route('admin.purchase.grn.show', $grn->id)
                ->with('success', 'GRN updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating GRN: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Submit for inspection (alias: inspect)
     */
    public function submit($id)
    {
        $grn = GoodsReceiptNote::findOrFail($id);

        if ($grn->status !== 'DRAFT') {
            return back()->with('error', 'Only draft GRNs can be submitted.');
        }

        $grn->update(['status' => 'INSPECTING']);

        return back()->with('success', 'GRN submitted for inspection.');
    }

    /**
     * Start inspection (change status from DRAFT to INSPECTING)
     */
    public function inspect($id)
    {
        return $this->submit($id);
    }

    /**
     * Approve GRN and update stock
     */
    public function approve($id)
    {
        $grn = GoodsReceiptNote::with(['items.product'])->findOrFail($id);

        if ($grn->status !== 'INSPECTING') {
            return back()->with('error', 'Only inspecting GRNs can be approved.');
        }

        if ($grn->stock_updated) {
            return back()->with('error', 'Stock already updated for this GRN.');
        }

        DB::beginTransaction();
        try {
            // Update stock for each item
            foreach ($grn->items as $item) {
                if ($item->accepted_qty <= 0) continue;

                $product = $item->product;
                if (!$product || !$product->track_inventory) continue;

                $lotId = null;

                // Handle lot/batch if product is batch managed
                if ($product->is_batch_managed && $item->lot_no) {
                    $lot = Lot::firstOrCreate([
                        'product_id' => $product->id,
                        'lot_no' => $item->lot_no,
                    ], [
                        'variation_id' => $item->variation_id,
                        'batch_no' => $item->batch_no,
                        'manufacturing_date' => $item->manufacturing_date,
                        'expiry_date' => $item->expiry_date,
                        'purchase_price' => $item->rate,
                        'initial_qty' => $item->accepted_qty,
                        'status' => 'ACTIVE',
                    ]);

                    $lotId = $lot->id;
                    $item->lot_id = $lotId;
                    $item->save();
                }

                // Get current stock before update
                $stockBefore = StockLevel::where('product_id', $product->id)
                    ->where('warehouse_id', $grn->warehouse_id)
                    ->when($grn->rack_id, fn($q) => $q->where('rack_id', $grn->rack_id))
                    ->when($lotId, fn($q) => $q->where('lot_id', $lotId))
                    ->sum('qty') ?? 0;

                // Update/Create stock level
                $stockLevel = StockLevel::firstOrNew([
                    'product_id' => $product->id,
                    'variation_id' => $item->variation_id,
                    'warehouse_id' => $grn->warehouse_id,
                    'rack_id' => $grn->rack_id,
                    'lot_id' => $lotId,
                ]);

                $stockLevel->unit_id = $item->unit_id ?? $product->unit_id;
                $stockLevel->qty = ($stockLevel->qty ?? 0) + $item->accepted_qty;
                $stockLevel->save();

                $stockAfter = $stockLevel->qty;

                // Create stock movement record
                $refNo = 'GRN-' . $grn->grn_number . '-' . str_pad($item->id, 3, '0', STR_PAD_LEFT);
                
                $movement = StockMovement::create([
                    'reference_no' => $refNo,
                    'product_id' => $product->id,
                    'variation_id' => $item->variation_id,
                    'warehouse_id' => $grn->warehouse_id,
                    'rack_id' => $grn->rack_id,
                    'lot_id' => $lotId,
                    'unit_id' => $item->unit_id ?? $product->unit_id,
                    'qty' => $item->accepted_qty,
                    'base_qty' => $item->accepted_qty, // Assuming same unit, adjust if needed
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'purchase_price' => $item->rate,
                    'movement_type' => 'IN',
                    'reference_type' => 'PURCHASE',
                    'reference_id' => $grn->id,
                    'reason' => 'GRN Receipt: ' . $grn->grn_number,
                    'notes' => 'PO: ' . ($grn->purchaseOrder->po_number ?? '-'),
                    'created_by' => auth()->id(),
                ]);

                // Link movement to GRN item
                $item->stock_movement_id = $movement->id;
                $item->save();
            }

            // Update GRN status
            $grn->update([
                'status' => 'APPROVED',
                'stock_updated' => true,
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

            // Update Purchase Order status
            $grn->updatePurchaseOrder();

            DB::commit();

            return back()->with('success', 'GRN approved and stock updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error approving GRN: ' . $e->getMessage());
        }
    }

    /**
     * Reject GRN
     */
    public function reject(Request $request, $id)
    {
        $grn = GoodsReceiptNote::findOrFail($id);

        if (!in_array($grn->status, ['DRAFT', 'INSPECTING'])) {
            return back()->with('error', 'Cannot reject GRN in current status.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $grn->update([
            'status' => 'REJECTED',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'GRN rejected.');
    }

    /**
     * Cancel GRN
     */
    public function cancel($id)
    {
        $grn = GoodsReceiptNote::findOrFail($id);

        if ($grn->stock_updated) {
            return back()->with('error', 'Cannot cancel GRN after stock update.');
        }

        if ($grn->status === 'APPROVED') {
            return back()->with('error', 'Cannot cancel approved GRN.');
        }

        $grn->update(['status' => 'CANCELLED']);

        return back()->with('success', 'GRN cancelled.');
    }

    /**
     * Delete GRN
     */
    public function destroy($id)
    {
        $grn = GoodsReceiptNote::findOrFail($id);

        if ($grn->stock_updated) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete GRN after stock update.'], 422);
            }
            return back()->with('error', 'Cannot delete GRN after stock update.');
        }

        $grn->items()->delete();
        $grn->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'GRN deleted successfully.']);
        }

        return redirect()
            ->route('admin.purchase.grn.index')
            ->with('success', 'GRN deleted successfully.');
    }

    /**
     * Bulk delete GRNs
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.'], 400);
        }

        // Check for stock updated GRNs
        $hasStockUpdated = GoodsReceiptNote::whereIn('id', $ids)->where('stock_updated', true)->exists();
        if ($hasStockUpdated) {
            return response()->json([
                'success' => false, 
                'message' => 'Cannot delete GRNs with updated stock.'
            ], 422);
        }

        GoodsReceiptNoteItem::whereIn('goods_receipt_note_id', $ids)->delete();
        GoodsReceiptNote::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($ids) . ' GRN(s) deleted.'
        ]);
    }

    /**
     * Get PO items for GRN creation (AJAX)
     */
    public function getPOItems($poId)
    {
        $po = PurchaseOrder::with(['items.product.unit', 'items.unit', 'vendor'])
            ->findOrFail($poId);

        $items = $po->items->map(function($item) {
            $pendingQty = max(0, $item->qty - $item->received_qty);
            $product = $item->product;
            
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $product->name ?? '-',
                'product_sku' => $product->sku ?? '-',
                'is_batch_managed' => $product->is_batch_managed ?? false,
                'unit_id' => $item->unit_id,
                'unit_name' => $item->unit->short_name ?? $product->unit->short_name ?? 'PCS',
                'ordered_qty' => $item->qty,
                'received_qty' => $item->received_qty,
                'pending_qty' => $pendingQty,
                'rate' => $item->rate,
            ];
        })->filter(function($item) {
            return $item['pending_qty'] > 0;
        })->values();

        return response()->json([
            'success' => true,
            'po' => [
                'id' => $po->id,
                'po_number' => $po->po_number,
                'vendor_name' => $po->vendor->name ?? '-',
                'po_date' => $po->po_date->format('d M Y'),
            ],
            'items' => $items,
        ]);
    }

    /**
     * Get racks by warehouse (AJAX)
     */
    public function getRacks($warehouseId)
    {
        $racks = [];
        
        if (class_exists('\Modules\Inventory\Models\Rack')) {
            $racks = \Modules\Inventory\Models\Rack::where('warehouse_id', $warehouseId)
                ->where('is_active', true)
                ->orderBy('code')
                ->get(['id', 'code', 'name']);
        }

        return response()->json(['racks' => $racks]);
    }
}
