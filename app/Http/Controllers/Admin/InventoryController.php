<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductCategory;
use App\Models\Inventory\Brand;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\Lot;
use App\Models\Inventory\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    // ==================== DASHBOARD ====================
    public function dashboard()
    {
        $stats = [
            'totalProducts' => Product::where('is_active', true)->count(),
            'totalCategories' => ProductCategory::where('is_active', true)->count(),
            'totalBrands' => Brand::where('is_active', true)->count(),
            'totalWarehouses' => Warehouse::where('is_active', true)->count(),
        ];
        
        $lowStockProducts = Product::where('is_active', true)
            ->where('min_stock_level', '>', 0)
            ->get()
            ->filter(function ($product) {
                $stock = $this->getProductStock($product->id);
                $product->current_stock = $stock;
                return $stock < $product->min_stock_level;
            })->take(10);
        
        $recentMovements = StockMovement::with(['product', 'warehouse'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('admin.inventory.dashboard', compact('stats', 'lowStockProducts', 'recentMovements'));
    }

    // ==================== PRODUCTS ====================
    public function productsIndex()
    {
        $stats = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'inactive' => Product::where('is_active', false)->count(),
        ];
        
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.products.index', compact('stats', 'categories', 'brands'));
    }

    public function productsData(Request $request)
    {
        $query = Product::with(['category', 'brand'])->select('products.*');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            $currentStock = $this->getProductStock($item->id);
            return [
                'id' => $item->id,
                'sku' => $item->sku,
                'name' => $item->name,
                'category_name' => $item->category?->name ?? '-',
                'brand_name' => $item->brand?->name ?? '-',
                'unit' => $item->unit ?? 'PCS',
                'purchase_price' => number_format($item->purchase_price, 2),
                'sale_price' => number_format($item->sale_price, 2),
                'current_stock' => $currentStock,
                'min_stock_level' => $item->min_stock_level,
                'is_low_stock' => $item->min_stock_level > 0 && $currentStock < $item->min_stock_level,
                'is_active' => $item->is_active,
                'status' => $item->is_active ? 'Active' : 'Inactive',
                '_edit_url' => route('admin.inventory.products.edit', $item->id),
                '_show_url' => route('admin.inventory.products.show', $item->id),
                '_delete_url' => route('admin.inventory.products.destroy', $item->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    public function productsCreate()
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.products.create', compact('categories', 'brands'));
    }

    public function productsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'sku' => 'required|string|max:50|unique:products,sku',
            'barcode' => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:product_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit' => 'nullable|string|max:20',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'hsn_code' => 'nullable|string|max:20',
            'min_stock_level' => 'nullable|numeric|min:0',
            'max_stock_level' => 'nullable|numeric|min:0',
            'is_batch_managed' => 'boolean',
        ]);
        
        $validated['unit'] = $validated['unit'] ?? 'PCS';
        $validated['is_active'] = true;
        $validated['is_batch_managed'] = $request->has('is_batch_managed');
        
        Product::create($validated);
        
        return redirect()->route('admin.inventory.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function productsShow($id)
    {
        $product = Product::with(['category', 'brand'])->findOrFail($id);
        
        $stockByWarehouse = StockMovement::where('product_id', $id)
            ->select('warehouse_id')
            ->selectRaw("SUM(CASE 
                WHEN movement_type IN ('IN', 'RETURN') THEN qty 
                WHEN movement_type = 'OUT' THEN -qty 
                WHEN movement_type = 'ADJUSTMENT' THEN qty 
                ELSE 0 END) as total_qty")
            ->groupBy('warehouse_id')
            ->with('warehouse')
            ->get();
        
        $movements = StockMovement::where('product_id', $id)
            ->with(['warehouse', 'lot'])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
        
        return view('admin.inventory.products.show', compact('product', 'stockByWarehouse', 'movements'));
    }

    public function productsEdit($id)
    {
        $product = Product::findOrFail($id);
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.products.edit', compact('product', 'categories', 'brands'));
    }

    public function productsUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'sku' => 'required|string|max:50|unique:products,sku,' . $id,
            'barcode' => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:product_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit' => 'nullable|string|max:20',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'hsn_code' => 'nullable|string|max:20',
            'min_stock_level' => 'nullable|numeric|min:0',
            'max_stock_level' => 'nullable|numeric|min:0',
            'is_batch_managed' => 'boolean',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_batch_managed'] = $request->has('is_batch_managed');
        $validated['is_active'] = $request->has('is_active');
        
        $product->update($validated);
        
        return redirect()->route('admin.inventory.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function productsDeactivate($id)
    {
        Product::where('id', $id)->update(['is_active' => false]);
        return response()->json(['success' => true, 'message' => 'Product deactivated']);
    }

    public function productsDestroy($id)
    {
        $product = Product::findOrFail($id);
        
        if (StockMovement::where('product_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete product with stock movements'], 422);
        }
        
        $product->delete();
        return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
    }

    // ==================== WAREHOUSES ====================
    public function warehousesIndex()
    {
        $stats = [
            'total' => Warehouse::count(),
            'active' => Warehouse::where('is_active', true)->count(),
        ];
        
        return view('admin.inventory.warehouses.index', compact('stats'));
    }

    public function warehousesData(Request $request)
    {
        $query = Warehouse::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'city' => $item->city ?? '-',
                'state' => $item->state ?? '-',
                'type' => $item->type,
                'contact_person' => $item->contact_person ?? '-',
                'phone' => $item->phone ?? '-',
                'is_default' => $item->is_default,
                'is_active' => $item->is_active,
                'status' => $item->is_active ? 'Active' : 'Inactive',
                '_edit_url' => route('admin.inventory.warehouses.edit', $item->id),
                '_delete_url' => route('admin.inventory.warehouses.destroy', $item->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    public function warehousesCreate()
    {
        return view('admin.inventory.warehouses.create');
    }

    public function warehousesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:warehouses,code',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'type' => 'required|in:STORAGE,SHOP,RETURN_CENTER',
            'is_default' => 'boolean',
        ]);
        
        $validated['is_active'] = true;
        $validated['is_default'] = $request->has('is_default');
        
        if ($validated['is_default']) {
            Warehouse::where('is_default', true)->update(['is_default' => false]);
        }
        
        Warehouse::create($validated);
        
        return redirect()->route('admin.inventory.warehouses.index')
            ->with('success', 'Warehouse created successfully!');
    }

    public function warehousesEdit($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return view('admin.inventory.warehouses.edit', compact('warehouse'));
    }

    public function warehousesUpdate(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:warehouses,code,' . $id,
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'type' => 'required|in:STORAGE,SHOP,RETURN_CENTER',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_default'] = $request->has('is_default');
        $validated['is_active'] = $request->has('is_active');
        
        if ($validated['is_default']) {
            Warehouse::where('is_default', true)->where('id', '!=', $id)->update(['is_default' => false]);
        }
        
        $warehouse->update($validated);
        
        return redirect()->route('admin.inventory.warehouses.index')
            ->with('success', 'Warehouse updated successfully!');
    }

    public function warehousesSetDefault($id)
    {
        Warehouse::where('is_default', true)->update(['is_default' => false]);
        Warehouse::where('id', $id)->update(['is_default' => true]);
        
        return response()->json(['success' => true, 'message' => 'Default warehouse updated']);
    }

    public function warehousesDeactivate($id)
    {
        Warehouse::where('id', $id)->update(['is_active' => false]);
        return response()->json(['success' => true, 'message' => 'Warehouse deactivated']);
    }

    public function warehousesDestroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        
        if (StockMovement::where('warehouse_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete warehouse with stock movements'], 422);
        }
        
        $warehouse->delete();
        return response()->json(['success' => true, 'message' => 'Warehouse deleted successfully']);
    }

    // ==================== LOTS ====================
    public function lotsIndex()
    {
        $stats = [
            'total' => Lot::count(),
            'available' => Lot::where('status', 'AVAILABLE')->count(),
            'expired' => Lot::where('status', 'EXPIRED')->count(),
        ];
        
        $products = Product::where('is_active', true)->where('is_batch_managed', true)->orderBy('name')->get();
        
        return view('admin.inventory.lots.index', compact('stats', 'products'));
    }

    public function lotsData(Request $request)
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

    public function lotsCreate()
    {
        $products = Product::where('is_active', true)->where('is_batch_managed', true)->orderBy('name')->get();
        return view('admin.inventory.lots.create', compact('products'));
    }

    public function lotsStore(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'lot_no' => 'required|string|max:50',
            'initial_qty' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'manufacturing_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:manufacturing_date',
            'status' => 'required|in:AVAILABLE,RESERVED,EXPIRED,CONSUMED',
            'remarks' => 'nullable|string',
        ]);
        
        Lot::create($validated);
        
        return redirect()->route('admin.inventory.lots.index')
            ->with('success', 'Lot created successfully!');
    }

    public function lotsEdit($id)
    {
        $lot = Lot::with('product')->findOrFail($id);
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.lots.edit', compact('lot', 'products'));
    }

    public function lotsUpdate(Request $request, $id)
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

    public function lotsDeactivate($id)
    {
        Lot::where('id', $id)->update(['status' => 'CONSUMED']);
        return response()->json(['success' => true, 'message' => 'Lot deactivated']);
    }

    public function lotsDestroy($id)
    {
        $lot = Lot::findOrFail($id);
        
        if (StockMovement::where('lot_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete lot with movements'], 422);
        }
        
        $lot->delete();
        return response()->json(['success' => true, 'message' => 'Lot deleted successfully']);
    }

    public function lotsByProduct($productId)
    {
        $lots = Lot::where('product_id', $productId)
            ->where('status', 'AVAILABLE')
            ->orderBy('expiry_date', 'asc')
            ->get();
        
        return response()->json($lots);
    }

    // ==================== STOCK MOVEMENTS ====================
    public function stockReceive()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.stock.receive', compact('products', 'warehouses'));
    }

    public function stockReceiveStore(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'lot_id' => 'nullable|exists:lots,id',
            'qty' => 'required|numeric|min:0.001',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
        $product = Product::find($validated['product_id']);
        
        StockMovement::create([
            'product_id' => $validated['product_id'],
            'warehouse_id' => $validated['warehouse_id'],
            'lot_id' => $validated['lot_id'] ?? null,
            'qty' => $validated['qty'],
            'uom' => $product->unit ?? 'PCS',
            'movement_type' => 'IN',
            'reason' => $validated['reason'] ?? 'Stock received',
            'notes' => $validated['notes'] ?? null,
            'created_by' => Auth::guard('admin')->id(),
        ]);
        
        return redirect()->route('admin.inventory.stock.receive')
            ->with('success', 'Stock received successfully!');
    }

    public function stockDeliver()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.stock.deliver', compact('products', 'warehouses'));
    }

    public function stockDeliverStore(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'lot_id' => 'nullable|exists:lots,id',
            'qty' => 'required|numeric|min:0.001',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
        $available = $this->getStock($validated['product_id'], $validated['warehouse_id'], $validated['lot_id'] ?? null);
        
        if ($validated['qty'] > $available) {
            return back()->with('error', "Insufficient stock. Available: {$available}")->withInput();
        }
        
        $product = Product::find($validated['product_id']);
        
        StockMovement::create([
            'product_id' => $validated['product_id'],
            'warehouse_id' => $validated['warehouse_id'],
            'lot_id' => $validated['lot_id'] ?? null,
            'qty' => $validated['qty'],
            'uom' => $product->unit ?? 'PCS',
            'movement_type' => 'OUT',
            'reason' => $validated['reason'] ?? 'Stock delivered',
            'notes' => $validated['notes'] ?? null,
            'created_by' => Auth::guard('admin')->id(),
        ]);
        
        return redirect()->route('admin.inventory.stock.deliver')
            ->with('success', 'Stock delivered successfully!');
    }

    public function stockReturns()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.stock.returns', compact('products', 'warehouses'));
    }

    public function stockReturnsStore(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'lot_id' => 'nullable|exists:lots,id',
            'qty' => 'required|numeric|min:0.001',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
        $product = Product::find($validated['product_id']);
        
        StockMovement::create([
            'product_id' => $validated['product_id'],
            'warehouse_id' => $validated['warehouse_id'],
            'lot_id' => $validated['lot_id'] ?? null,
            'qty' => $validated['qty'],
            'uom' => $product->unit ?? 'PCS',
            'movement_type' => 'RETURN',
            'reason' => $validated['reason'],
            'notes' => $validated['notes'] ?? null,
            'created_by' => Auth::guard('admin')->id(),
        ]);
        
        return redirect()->route('admin.inventory.stock.returns')
            ->with('success', 'Return recorded successfully!');
    }

    public function stockAdjustments()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.stock.adjustments', compact('products', 'warehouses'));
    }

    public function stockAdjustmentsStore(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'lot_id' => 'nullable|exists:lots,id',
            'new_qty' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
        $currentStock = $this->getStock($validated['product_id'], $validated['warehouse_id'], $validated['lot_id'] ?? null);
        $difference = $validated['new_qty'] - $currentStock;
        
        if ($difference == 0) {
            return back()->with('error', 'New quantity is same as current stock')->withInput();
        }
        
        $product = Product::find($validated['product_id']);
        
        StockMovement::create([
            'product_id' => $validated['product_id'],
            'warehouse_id' => $validated['warehouse_id'],
            'lot_id' => $validated['lot_id'] ?? null,
            'qty' => $difference,
            'uom' => $product->unit ?? 'PCS',
            'movement_type' => 'ADJUSTMENT',
            'reason' => $validated['reason'],
            'notes' => "Adjusted from {$currentStock} to {$validated['new_qty']}. " . ($validated['notes'] ?? ''),
            'created_by' => Auth::guard('admin')->id(),
        ]);
        
        return redirect()->route('admin.inventory.stock.adjustments')
            ->with('success', 'Stock adjusted successfully!');
    }

    public function stockCheck(Request $request)
    {
        $stock = $this->getStock(
            $request->product_id,
            $request->warehouse_id,
            $request->lot_id
        );
        
        return response()->json([
            'quantity' => $stock,
            'available' => $stock
        ]);
    }

    // ==================== REPORTS ====================
    public function reportStockSummary(Request $request)
    {
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        
        $query = DB::table('stock_movements as sm')
            ->join('products as p', 'sm.product_id', '=', 'p.id')
            ->join('warehouses as w', 'sm.warehouse_id', '=', 'w.id')
            ->leftJoin('product_categories as c', 'p.category_id', '=', 'c.id')
            ->leftJoin('brands as b', 'p.brand_id', '=', 'b.id')
            ->select(
                'p.id as product_id', 'p.name as product_name', 'p.sku',
                'p.unit as unit_name', 'p.purchase_price',
                'c.name as category_name', 'b.name as brand_name',
                'w.name as warehouse_name', 'sm.warehouse_id'
            )
            ->selectRaw("SUM(CASE 
                WHEN sm.movement_type IN ('IN', 'RETURN') THEN sm.qty 
                WHEN sm.movement_type = 'OUT' THEN -sm.qty 
                WHEN sm.movement_type = 'ADJUSTMENT' THEN sm.qty 
                ELSE 0 END) as total_stock")
            ->groupBy('sm.product_id', 'sm.warehouse_id', 'p.id', 'p.name', 'p.sku', 'p.unit', 'p.purchase_price', 'c.name', 'b.name', 'w.name');
        
        if ($request->warehouse_id) {
            $query->where('sm.warehouse_id', $request->warehouse_id);
        }
        if ($request->category_id) {
            $query->where('p.category_id', $request->category_id);
        }
        if ($request->brand_id) {
            $query->where('p.brand_id', $request->brand_id);
        }
        
        $stockReport = $query->having('total_stock', '>', 0)->get();
        
        $stockReport->each(function ($item) {
            $item->stock_value = $item->total_stock * $item->purchase_price;
        });
        
        $totalValue = $stockReport->sum('stock_value');
        
        return view('admin.inventory.reports.stock-summary', compact(
            'stockReport', 'totalValue', 'warehouses', 'categories', 'brands'
        ));
    }

    public function reportLotSummary(Request $request)
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

    public function reportMovementHistory(Request $request)
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.reports.movement-history', compact('products', 'warehouses'));
    }

    public function reportMovementHistoryData(Request $request)
    {
        $query = StockMovement::with(['product', 'warehouse', 'lot', 'creator']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reason', 'like', "%{$search}%")
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
                'date' => $item->created_at->format('d M Y, H:i'),
                'product_name' => $item->product?->name ?? '-',
                'warehouse_name' => $item->warehouse?->name ?? '-',
                'lot_no' => $item->lot?->lot_no ?? '-',
                'movement_type' => $item->movement_type,
                'qty' => $item->qty,
                'uom' => $item->uom ?? 'PCS',
                'reason' => $item->reason ?? '-',
                'created_by' => $item->creator?->name ?? '-',
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    // ==================== SETTINGS ====================
    public function settingsIndex()
    {
        $categories = ProductCategory::with('parent')->orderBy('sort_order')->get();
        $brands = Brand::orderBy('name')->get();
        return view('admin.inventory.settings.index', compact('categories', 'brands'));
    }

    public function categoriesData(Request $request)
    {
        $query = ProductCategory::with('parent');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'sort_order');
        $sortDir = $request->get('dir', 'asc');
        $query->orderBy($sortField, $sortDir);

        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'parent_name' => $item->parent?->name ?? '-',
                'sort_order' => $item->sort_order,
                'is_active' => $item->is_active,
                'status' => $item->is_active ? 'Active' : 'Inactive',
                '_delete_url' => route('admin.inventory.settings.categories.destroy', $item->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    public function categoriesStore(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:product_categories,code',
            'name' => 'required|string|max:100',
            'parent_id' => 'nullable|exists:product_categories,id',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);
        
        $validated['is_active'] = true;
        ProductCategory::create($validated);
        
        return response()->json(['success' => true, 'message' => 'Category created successfully']);
    }

    public function categoriesUpdate(Request $request, $id)
    {
        $category = ProductCategory::findOrFail($id);
        
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:product_categories,code,' . $id,
            'name' => 'required|string|max:100',
            'parent_id' => 'nullable|exists:product_categories,id',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);
        
        $category->update($validated);
        return response()->json(['success' => true, 'message' => 'Category updated successfully']);
    }

    public function categoriesDeactivate($id)
    {
        ProductCategory::where('id', $id)->update(['is_active' => false]);
        return response()->json(['success' => true, 'message' => 'Category deactivated']);
    }

    public function categoriesDestroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        
        if (Product::where('category_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete category with products'], 422);
        }
        if (ProductCategory::where('parent_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete category with subcategories'], 422);
        }
        
        $category->delete();
        return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
    }

    public function brandsData(Request $request)
    {
        $query = Brand::query();

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $sortField = $request->get('sort', 'name');
        $sortDir = $request->get('dir', 'asc');
        $query->orderBy($sortField, $sortDir);

        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'logo' => $item->logo,
                'description' => $item->description ?? '-',
                'is_active' => $item->is_active,
                'status' => $item->is_active ? 'Active' : 'Inactive',
                '_delete_url' => route('admin.inventory.settings.brands.destroy', $item->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    public function brandsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);
        
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }
        
        $validated['is_active'] = true;
        Brand::create($validated);
        
        return response()->json(['success' => true, 'message' => 'Brand created successfully']);
    }

    public function brandsUpdate(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }
        
        $brand->update($validated);
        return response()->json(['success' => true, 'message' => 'Brand updated successfully']);
    }

    public function brandsDeactivate($id)
    {
        Brand::where('id', $id)->update(['is_active' => false]);
        return response()->json(['success' => true, 'message' => 'Brand deactivated']);
    }

    public function brandsDestroy($id)
    {
        $brand = Brand::findOrFail($id);
        
        if (Product::where('brand_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete brand with products'], 422);
        }
        
        $brand->delete();
        return response()->json(['success' => true, 'message' => 'Brand deleted successfully']);
    }

    // ==================== HELPER METHODS ====================
    private function getProductStock($productId, $warehouseId = null, $lotId = null)
    {
        return $this->getStock($productId, $warehouseId, $lotId);
    }

    private function getStock($productId, $warehouseId = null, $lotId = null)
    {
        $query = StockMovement::where('product_id', $productId);
        
        if ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        }
        if ($lotId) {
            $query->where('lot_id', $lotId);
        }
        
        return $query->selectRaw("SUM(CASE 
            WHEN movement_type IN ('IN', 'RETURN') THEN qty 
            WHEN movement_type = 'OUT' THEN -qty 
            WHEN movement_type = 'ADJUSTMENT' THEN qty 
            ELSE 0 END) as total")
            ->value('total') ?? 0;
    }

    /**
 * Stock Transfer - Show form
 */
public function stockTransfer()
{
    $products = Product::where('is_active', true)->orderBy('name')->get();
    $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
    
    return view('admin.inventory.stock.transfer', compact('products', 'warehouses'));
}

/**
 * Stock Transfer - Process transfer
 */
public function stockTransferStore(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'from_warehouse_id' => 'required|exists:warehouses,id',
        'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
        'qty' => 'required|numeric|min:0.001',
        'lot_id' => 'nullable|exists:lots,id',
        'reason' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
    ]);
    
    // Check available stock
    $availableStock = $this->getStock(
        $request->product_id, 
        $request->from_warehouse_id, 
        $request->lot_id
    );
    
    if ($request->qty > $availableStock) {
        return back()->with('error', 'Insufficient stock! Available: ' . $availableStock)->withInput();
    }
    
    DB::beginTransaction();
    
    try {
        $reason = $request->reason ?? 'Stock Transfer';
        $transferRef = 'TRF-' . date('YmdHis');
        
        // Create OUT movement from source warehouse
        StockMovement::create([
            'product_id' => $request->product_id,
            'warehouse_id' => $request->from_warehouse_id,
            'lot_id' => $request->lot_id,
            'movement_type' => 'OUT',
            'qty' => $request->qty,
            'reason' => $reason . ' to ' . Warehouse::find($request->to_warehouse_id)->name,
            'reference' => $transferRef,
            'notes' => $request->notes,
            'created_by' => auth('admin')->id(),
        ]);
        
        // Create IN movement to destination warehouse
        StockMovement::create([
            'product_id' => $request->product_id,
            'warehouse_id' => $request->to_warehouse_id,
            'lot_id' => $request->lot_id,
            'movement_type' => 'IN',
            'qty' => $request->qty,
            'reason' => $reason . ' from ' . Warehouse::find($request->from_warehouse_id)->name,
            'reference' => $transferRef,
            'notes' => $request->notes,
            'created_by' => auth('admin')->id(),
        ]);
        
        DB::commit();
        
        return redirect()->route('admin.inventory.stock.transfer')
            ->with('success', 'Stock transferred successfully! Reference: ' . $transferRef);
            
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Transfer failed: ' . $e->getMessage())->withInput();
    }
}
}