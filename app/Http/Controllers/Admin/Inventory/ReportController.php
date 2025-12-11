<?php

namespace App\Http\Controllers\Admin\Inventory;

use Illuminate\Http\Request;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductCategory;
use App\Models\Inventory\Brand;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\Lot;
use App\Models\Inventory\StockLevel;
use App\Models\Inventory\StockMovement;

class ReportController extends BaseController
{
    // ==================== STOCK SUMMARY REPORT ====================
    public function stockSummary(Request $request)
    {
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        
        $query = StockLevel::with(['product.category', 'product.brand', 'warehouse', 'rack', 'unit'])
            ->where('qty', '>', 0);
        
        if ($request->warehouse_id) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        if ($request->category_id) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }
        if ($request->brand_id) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('brand_id', $request->brand_id);
            });
        }
        
        $stockReport = $query->get()->map(function ($item) {
            return (object) [
                'product_id' => $item->product_id,
                'product_name' => $item->product?->name ?? '-',
                'sku' => $item->product?->sku ?? '-',
                'unit_name' => $item->unit?->short_name ?? 'PCS',
                'purchase_price' => $item->product?->purchase_price ?? 0,
                'category_name' => $item->product?->category?->name ?? '-',
                'brand_name' => $item->product?->brand?->name ?? '-',
                'warehouse_name' => $item->warehouse?->name ?? '-',
                'rack_name' => $item->rack?->name ?? '-',
                'total_stock' => $item->qty,
                'stock_value' => $item->qty * ($item->product?->purchase_price ?? 0),
            ];
        });
        
        $totalValue = $stockReport->sum('stock_value');
        
        return view('admin.inventory.reports.stock-summary', compact(
            'stockReport', 'totalValue', 'warehouses', 'categories', 'brands'
        ));
    }

    // ==================== LOT SUMMARY REPORT ====================
    public function lotSummary(Request $request)
    {
        $products = Product::where('is_active', true)->where('is_batch_managed', true)->orderBy('name')->get();
        
        $query = Lot::with(['product']);
        
        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $lots = $query->orderBy('expiry_date', 'asc')->get();
        
        return view('admin.inventory.reports.lot-summary', compact('lots', 'products'));
    }

    // ==================== MOVEMENT HISTORY REPORT ====================
    public function movementHistory(Request $request)
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.reports.movement-history', compact('products', 'warehouses'));
    }

    // ==================== MOVEMENT HISTORY DATA (DataTable) ====================
    public function movementHistoryData(Request $request)
    {
        $query = StockMovement::with(['product', 'warehouse', 'rack', 'lot', 'unit', 'creator']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'reference_no' => $item->reference_no ?? '-',
                'product_name' => $item->product?->name ?? '-',
                'warehouse_name' => $item->warehouse?->name ?? '-',
                'rack_name' => $item->rack?->name ?? '-',
                'lot_no' => $item->lot?->lot_no ?? '-',
                'qty' => $item->qty,
                'unit' => $item->unit?->short_name ?? 'PCS',
                'movement_type' => $item->movement_type,
                'stock_before' => $item->stock_before,
                'stock_after' => $item->stock_after,
                'reason' => $item->reason ?? '-',
                'created_by' => $item->creator?->name ?? '-',
                'created_at' => $item->created_at->format('Y-m-d H:i'),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }
}