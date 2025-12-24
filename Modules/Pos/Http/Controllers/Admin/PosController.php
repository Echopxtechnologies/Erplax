<?php

namespace Modules\Pos\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use Modules\Pos\Models\{PosSale, PosSession, PosSettings, PosHeldBill, Product};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends AdminController
{
    // Helper to get product tax rates
    private function getProductTaxRate($productId)
    {
        $product = DB::table('products')->where('id', $productId)->first();
        if (!$product) return ['rate' => 0, 'name' => ''];
        
        $tax1Rate = 0;
        $tax2Rate = 0;
        $taxName = '';
        
        if ($product->tax_1_id) {
            $tax1 = DB::table('taxes')->where('id', $product->tax_1_id)->first();
            if ($tax1) {
                $tax1Rate = $tax1->rate ?? 0;
                $taxName = $tax1->name ?? '';
            }
        }
        
        if ($product->tax_2_id) {
            $tax2 = DB::table('taxes')->where('id', $product->tax_2_id)->first();
            if ($tax2) {
                $tax2Rate = $tax2->rate ?? 0;
                if ($taxName) $taxName .= ' + ' . ($tax2->name ?? '');
                else $taxName = $tax2->name ?? '';
            }
        }
        
        return [
            'rate' => $tax1Rate + $tax2Rate,
            'tax1_rate' => $tax1Rate,
            'tax2_rate' => $tax2Rate,
            'name' => $taxName
        ];
    }
    
    public function sales()
    {
        $stats = [
            'total' => PosSale::where('status', 'completed')->count(),
            'revenue' => PosSale::where('status', 'completed')->sum('total'),
            'today' => PosSale::where('status', 'completed')->whereDate('created_at', today())->count(),
            'avg' => PosSale::where('status', 'completed')->avg('total') ?? 0,
        ];
        return view('pos::admin.sales.index', compact('stats'));
    }
    
    public function salesData(Request $request)
    {
        $query = PosSale::with(['admin', 'items']);
        
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('invoice_no', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%");
            });
        }
        
        if ($status = $request->input('status')) $query->where('status', $status);
        if ($payment = $request->input('payment_method')) $query->where('payment_method', $payment);
        
        $sortCol = $request->input('sort', 'id');
        $sortDir = $request->input('dir', 'desc');
        $query->orderBy(in_array($sortCol, ['id','invoice_no','total','status','created_at']) ? $sortCol : 'id', $sortDir);
        
        $data = $query->paginate($request->input('per_page', 10));
        
        return response()->json([
            'data' => collect($data->items())->map(fn($s) => [
                'id' => $s->id,
                'invoice_no' => $s->invoice_no,
                'invoice_id' => $s->invoice_id,
                'customer_name' => $s->customer_name ?: 'Walk-in',
                'items_count' => $s->items->count() . ' items',
                'payment_method' => $s->payment_method,
                'total' => $s->total,
                'status' => $s->status,
                'created_at' => $s->created_at->toISOString(),
                '_show_url' => route('admin.pos.sales.show', $s->id),
            ]),
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }
    
    public function showSale($id)
    {
        $sale = PosSale::with(['admin', 'items', 'session'])->findOrFail($id);
        return view('pos::admin.sales.show', compact('sale'));
    }
    
    public function voidSale($id)
    {
        PosSale::where('id', $id)->update(['status' => 'voided']);
        return request()->ajax() ? response()->json(['success' => true]) : back()->with('success', 'Voided');
    }
    
    public function sessions()
    {
        $admin = $this->admin();
        $activeSession = PosSession::where('admin_id', $admin->id)->where('status', 'open')->first();
        
        $today = now()->startOfDay();
        
        // Session specific stats
        $sessionSales = 0;
        $sessionCount = 0;
        $sessionCash = 0;
        
        if ($activeSession) {
            $sessionSales = PosSale::where('session_id', $activeSession->id)->where('status', 'completed')->sum('total');
            $sessionCount = PosSale::where('session_id', $activeSession->id)->where('status', 'completed')->count();
            $sessionCash = PosSale::where('session_id', $activeSession->id)->where('status', 'completed')->where('payment_method', 'cash')->sum('total');
            
            // Update session total_sales in case it's out of sync
            if ($activeSession->total_sales != $sessionSales) {
                $activeSession->update(['total_sales' => $sessionSales]);
                $activeSession->refresh();
            }
        }
        
        $stats = [
            'todaySales' => PosSale::where('created_at', '>=', $today)->where('status', 'completed')->sum('total'),
            'todayCount' => PosSale::where('created_at', '>=', $today)->where('status', 'completed')->count(),
            'cashInHand' => $activeSession ? $activeSession->opening_cash + $sessionCash : 0,
            'sessionSales' => $sessionSales,
            'sessionCount' => $sessionCount,
            'sessionCash' => $sessionCash,
        ];
        
        return view('pos::admin.sessions.index', compact('stats', 'activeSession'));
    }
    
    public function sessionsData(Request $request)
    {
        $query = PosSession::where('admin_id', $this->admin()->id);
        
        if ($search = $request->input('search')) $query->where('session_code', 'LIKE', "%{$search}%");
        if ($status = $request->input('status')) $query->where('status', $status);
        
        $query->orderBy($request->input('sort', 'id'), $request->input('dir', 'desc'));
        $data = $query->paginate($request->input('per_page', 10));
        
        return response()->json([
            'data' => collect($data->items())->map(function($s) {
                $sales = PosSale::where('session_id', $s->id)->where('status', 'completed');
                $cashSales = (clone $sales)->where('payment_method', 'cash')->sum('total');
                $totalSales = $sales->sum('total');
                $salesCount = PosSale::where('session_id', $s->id)->where('status', 'completed')->count();
                $expectedCash = $s->opening_cash + $cashSales;
                
                return [
                    'id' => $s->id,
                    'session_code' => $s->session_code,
                    'opened_at' => $s->opened_at?->toISOString(),
                    'opening_cash' => $s->opening_cash,
                    'total_sales' => $totalSales,
                    'sales_count' => $salesCount,
                    'closing_cash' => $s->closing_cash,
                    'difference' => $s->closing_cash !== null ? $s->closing_cash - $expectedCash : null,
                    'status' => $s->status,
                ];
            }),
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }
    
    public function openSession(Request $request)
    {
        $admin = $this->admin();
        if (PosSession::where('admin_id', $admin->id)->where('status', 'open')->exists()) {
            return back()->with('error', 'Session already open');
        }
        
        PosSession::create([
            'admin_id' => $admin->id,
            'session_code' => 'SES-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
            'opening_cash' => $request->input('opening_cash') ?? 0,
            'opened_at' => now(),
            'status' => 'open',
        ]);
        
        return back()->with('success', 'Session opened');
    }
    
    public function closeSession(Request $request)
    {
        $session = PosSession::where('admin_id', $this->admin()->id)->where('status', 'open')->first();
        if (!$session) return back()->with('error', 'No active session');
        
        $session->update([
            'closing_cash' => $request->input('closing_cash') ?? 0,
            'total_sales' => PosSale::where('session_id', $session->id)->where('status', 'completed')->sum('total'),
            'closed_at' => now(),
            'status' => 'closed',
        ]);
        
        return back()->with('success', 'Session closed');
    }
    
    public function settings()
    {
        $settings = PosSettings::first() ?: PosSettings::create(['store_name' => 'My Store', 'invoice_prefix' => 'INV-', 'default_tax_rate' => 18]);
        $warehouses = DB::table('warehouses')->where('is_active', true)->get();
        $staff = DB::table('admins')->where('is_active', true)->get();
        return view('pos::admin.settings', compact('settings', 'warehouses', 'staff'));
    }
    
    public function saveSettings(Request $request)
    {
        $settings = PosSettings::first() ?: new PosSettings();
        $settings->fill($request->only(['store_name', 'store_phone', 'store_address', 'store_gstin', 'invoice_prefix', 'default_tax_rate', 'tax_inclusive', 'receipt_footer', 'default_warehouse_id']));
        $settings->save();
        return back()->with('success', 'Settings saved');
    }
    
    public function assignWarehouse(Request $request)
    {
        DB::table('admins')->where('id', $request->staff_id)->update(['warehouse_id' => $request->warehouse_id ?: null]);
        return response()->json(['success' => true]);
    }
    
    public function billing()
    {
        $admin = $this->admin();
        $session = PosSession::where('admin_id', $admin->id)->where('status', 'open')->first();
        if (!$session) return redirect()->route('admin.pos.sessions')->with('error', 'Start a session first');
        
        $settings = PosSettings::first();
        $warehouseId = $admin->warehouse_id 
            ?? $settings?->default_warehouse_id 
            ?? DB::table('warehouses')->where('is_active', 1)->value('id');
        $warehouseName = $warehouseId ? DB::table('warehouses')->where('id', $warehouseId)->value('name') : 'No Warehouse';
        $heldBills = PosHeldBill::where('admin_id', $admin->id)->get();
        
        // Get categories with product counts
        $categories = collect();
        try {
            // Try product_categories first, then categories
            $cats = collect();
            try {
                $cats = DB::table('product_categories')->get();
            } catch (\Exception $e1) {
                try {
                    $cats = DB::table('categories')->get();
                } catch (\Exception $e2) {
                    $cats = collect();
                }
            }
            
            if ($cats->count() > 0) {
                $categories = $cats->map(function($cat) {
                    // Handle different column names for active status
                    $isActive = $cat->is_active ?? $cat->status ?? $cat->active ?? 1;
                    if (!$isActive) return null;
                    
                    return (object)[
                        'id' => $cat->id,
                        'name' => $cat->name,
                        'image' => $cat->image ?? null,
                        'product_count' => DB::table('products')->where('category_id', $cat->id)->where('is_active', 1)->count()
                    ];
                })->filter()->values();
            }
        } catch (\Exception $e) {
            \Log::error('POS Categories Error: ' . $e->getMessage());
        }
        
        return view('pos::admin.billing', compact('session', 'settings', 'warehouseId', 'warehouseName', 'heldBills', 'categories'));
    }
    
    public function searchProducts(Request $request)
    {
        $admin = $this->admin();
        $settings = PosSettings::first();
        $warehouseId = $admin->warehouse_id 
            ?? $settings?->default_warehouse_id 
            ?? DB::table('warehouses')->where('is_active', 1)->value('id');
        $search = $request->input('q');
        
        $results = [];
        
        $products = Product::where('is_active', true)
            ->where(fn($q) => $q->where('name', 'LIKE', "%{$search}%")->orWhere('sku', 'LIKE', "%{$search}%")->orWhere('barcode', 'LIKE', "%{$search}%"))
            ->limit(20)->get();
        
        foreach ($products as $p) {
            // Get product tax rates (same for all variants)
            $taxInfo = $this->getProductTaxRate($p->id);
            
            // If product has variants, show only variants
            if ($p->has_variants) {
                $variations = DB::table('product_variations')
                    ->where('product_id', $p->id)
                    ->where('is_active', 1)
                    ->get();
                
                foreach ($variations as $var) {
                    $stock = $warehouseId ? (DB::table('stock_levels')
                        ->where('product_id', $p->id)
                        ->where('variation_id', $var->id)
                        ->where('warehouse_id', $warehouseId)
                        ->value('qty') ?? 0) : 0;
                    
                    $results[] = [
                        'id' => $p->id,
                        'name' => $p->name,
                        'variant_id' => $var->id,
                        'variant_name' => $var->variation_name ?? $var->sku,
                        'sku' => $var->sku ?? $p->sku,
                        'barcode' => $var->barcode ?? $p->barcode,
                        'price' => $var->sale_price ?? $p->sale_price,
                        'image' => $var->image_path ? asset('storage/' . $var->image_path) : $p->image,
                        'stock' => $stock,
                        'has_variant' => true,
                        'tax_rate' => $taxInfo['rate'],
                        'tax_name' => $taxInfo['name'],
                    ];
                }
            } else {
                // Simple product without variants
                $stock = $warehouseId ? (DB::table('stock_levels')
                    ->where('product_id', $p->id)
                    ->where('warehouse_id', $warehouseId)
                    ->whereNull('variation_id')
                    ->value('qty') ?? 0) : 0;
                
                $results[] = [
                    'id' => $p->id,
                    'name' => $p->name,
                    'variant_id' => null,
                    'variant_name' => null,
                    'sku' => $p->sku,
                    'barcode' => $p->barcode,
                    'price' => $p->sale_price,
                    'image' => $p->image,
                    'stock' => $stock,
                    'has_variant' => false,
                    'tax_rate' => $taxInfo['rate'],
                    'tax_name' => $taxInfo['name'],
                ];
            }
        }
        
        // Also search in variations table directly
        $varSearch = DB::table('product_variations as v')
            ->join('products as p', 'p.id', '=', 'v.product_id')
            ->where('p.is_active', 1)
            ->where('v.is_active', 1)
            ->where(fn($q) => $q->where('v.sku', 'LIKE', "%{$search}%")
                ->orWhere('v.barcode', 'LIKE', "%{$search}%")
                ->orWhere('v.variation_name', 'LIKE', "%{$search}%"))
            ->select('p.*', 'v.id as var_id', 'v.variation_name', 'v.sku as var_sku', 'v.barcode as var_barcode', 'v.sale_price as var_price', 'v.image_path as var_image')
            ->limit(10)
            ->get();
        
        foreach ($varSearch as $item) {
            // Check if already added
            $exists = collect($results)->first(fn($r) => $r['id'] == $item->id && $r['variant_id'] == $item->var_id);
            if (!$exists) {
                $taxInfo = $this->getProductTaxRate($item->id);
                $stock = $warehouseId ? (DB::table('stock_levels')
                    ->where('product_id', $item->id)
                    ->where('variation_id', $item->var_id)
                    ->where('warehouse_id', $warehouseId)
                    ->value('qty') ?? 0) : 0;
                
                $results[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'variant_id' => $item->var_id,
                    'variant_name' => $item->variation_name ?? $item->var_sku,
                    'sku' => $item->var_sku ?? $item->sku,
                    'barcode' => $item->var_barcode ?? $item->barcode,
                    'price' => $item->var_price ?? $item->sale_price,
                    'image' => $item->var_image ? asset('storage/' . $item->var_image) : $item->image,
                    'stock' => $stock,
                    'has_variant' => true,
                    'tax_rate' => $taxInfo['rate'],
                    'tax_name' => $taxInfo['name'],
                ];
            }
        }
        
        return response()->json($results);
    }
    
    public function getProductsByCategory(Request $request)
    {
        $admin = $this->admin();
        $settings = PosSettings::first();
        $warehouseId = $admin->warehouse_id 
            ?? $settings?->default_warehouse_id 
            ?? DB::table('warehouses')->where('is_active', 1)->value('id');
        $categoryId = $request->input('category_id');
        
        $query = Product::where('is_active', true);
        
        if ($categoryId && $categoryId != 'all') {
            $query->where('category_id', $categoryId);
        }
        
        $results = [];
        $products = $query->limit(50)->get();
        
        foreach ($products as $p) {
            // Get product tax rates
            $taxInfo = $this->getProductTaxRate($p->id);
            
            // If product has variants, show only variants
            if ($p->has_variants) {
                $variations = DB::table('product_variations')
                    ->where('product_id', $p->id)
                    ->where('is_active', 1)
                    ->get();
                
                foreach ($variations as $var) {
                    $stock = $warehouseId ? (DB::table('stock_levels')
                        ->where('product_id', $p->id)
                        ->where('variation_id', $var->id)
                        ->where('warehouse_id', $warehouseId)
                        ->value('qty') ?? 0) : 0;
                    
                    $results[] = [
                        'id' => $p->id,
                        'name' => $p->name,
                        'variant_id' => $var->id,
                        'variant_name' => $var->variation_name ?? $var->sku,
                        'sku' => $var->sku ?? $p->sku,
                        'barcode' => $var->barcode ?? $p->barcode,
                        'price' => $var->sale_price ?? $p->sale_price,
                        'image' => $var->image_path ? asset('storage/' . $var->image_path) : $p->image,
                        'stock' => $stock,
                        'has_variant' => true,
                        'tax_rate' => $taxInfo['rate'],
                        'tax_name' => $taxInfo['name'],
                    ];
                }
            } else {
                // Simple product without variants
                $stock = $warehouseId ? (DB::table('stock_levels')
                    ->where('product_id', $p->id)
                    ->where('warehouse_id', $warehouseId)
                    ->whereNull('variation_id')
                    ->value('qty') ?? 0) : 0;
                
                $results[] = [
                    'id' => $p->id,
                    'name' => $p->name,
                    'variant_id' => null,
                    'variant_name' => null,
                    'sku' => $p->sku,
                    'barcode' => $p->barcode,
                    'price' => $p->sale_price,
                    'image' => $p->image,
                    'stock' => $stock,
                    'has_variant' => false,
                    'tax_rate' => $taxInfo['rate'],
                    'tax_name' => $taxInfo['name'],
                ];
            }
        }
        
        return response()->json($results);
    }
    
    public function scanBarcode(Request $request)
    {
        $admin = $this->admin();
        $settings = PosSettings::first();
        $warehouseId = $admin->warehouse_id 
            ?? $settings?->default_warehouse_id 
            ?? DB::table('warehouses')->where('is_active', 1)->value('id');
        $barcode = $request->input('barcode');
        
        // Try to find by barcode first
        $product = Product::where('barcode', $barcode)->first();
        
        // If not found, try by SKU
        if (!$product) {
            $product = Product::where('sku', $barcode)->first();
        }
        
        if (!$product) {
            // Check variations
            $variant = DB::table('product_variations')->where('barcode', $barcode)->orWhere('sku', $barcode)->first();
            if ($variant) {
                $product = Product::find($variant->product_id);
                $taxInfo = $this->getProductTaxRate($product->id);
                return response()->json(['success' => true, 'product' => [
                    'id' => $product->id, 
                    'name' => $product->name, 
                    'variant_id' => $variant->id, 
                    'variant_name' => $variant->variation_name ?? $variant->sku,
                    'price' => $variant->sale_price ?? $product->sale_price, 
                    'image' => $variant->image_path ? asset('storage/' . $variant->image_path) : $product->image,
                    'barcode' => $variant->barcode ?? $barcode,
                    'sku' => $variant->sku,
                    'stock' => $warehouseId ? (DB::table('stock_levels')->where('product_id', $product->id)->where('variation_id', $variant->id)->where('warehouse_id', $warehouseId)->value('qty') ?? 0) : 999,
                    'has_variant' => true,
                    'tax_rate' => $taxInfo['rate'],
                    'tax_name' => $taxInfo['name'],
                ]]);
            }
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }
        
        $taxInfo = $this->getProductTaxRate($product->id);
        return response()->json(['success' => true, 'product' => [
            'id' => $product->id, 
            'name' => $product->name, 
            'variant_id' => null,
            'variant_name' => null,
            'price' => $product->sale_price, 
            'image' => $product->image,
            'barcode' => $product->barcode ?? $barcode,
            'sku' => $product->sku,
            'stock' => $warehouseId ? (DB::table('stock_levels')->where('product_id', $product->id)->where('warehouse_id', $warehouseId)->whereNull('variation_id')->value('qty') ?? 0) : 999,
            'has_variant' => false,
            'tax_rate' => $taxInfo['rate'],
            'tax_name' => $taxInfo['name'],
        ]]);
    }
    
    public function completeSale(Request $request)
    {
        $admin = $this->admin();
        $session = PosSession::where('admin_id', $admin->id)->where('status', 'open')->first();
        if (!$session) return response()->json(['success' => false, 'message' => 'No session'], 400);
        
        $settings = PosSettings::first();
        
        // Get warehouse - admin's assigned, or POS default, or first active warehouse
        $warehouseId = $admin->warehouse_id 
            ?? $settings?->default_warehouse_id 
            ?? DB::table('warehouses')->where('is_active', 1)->value('id');
        
        if (!$warehouseId) {
            return response()->json(['success' => false, 'message' => 'No warehouse configured. Please set a default warehouse in POS Settings.'], 400);
        }
        
        $cart = $request->input('cart', []);
        if (empty($cart)) return response()->json(['success' => false, 'message' => 'Empty'], 400);
        
        try {
            return DB::transaction(function() use ($admin, $session, $settings, $warehouseId, $cart, $request) {
                $lastSale = PosSale::whereDate('created_at', today())->orderBy('id', 'desc')->first();
                $seq = $lastSale ? ((int)substr($lastSale->invoice_no, -4) + 1) : 1;
                $invoiceNo = ($settings->invoice_prefix ?? 'INV-') . date('Ymd') . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
                
                $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
                $discountAmount = $request->input('discount_amount', 0);
                
                // Calculate tax per item based on product tax rates
                $totalTax = 0;
                foreach ($cart as $item) {
                    $taxInfo = $this->getProductTaxRate($item['id']);
                    $itemTotal = $item['price'] * $item['qty'];
                    $itemTax = $itemTotal * $taxInfo['rate'] / 100;
                    $totalTax += $itemTax;
                }
                
                // Apply discount proportionally to tax
                $taxAmount = $subtotal > 0 ? $totalTax * (1 - $discountAmount / $subtotal) : 0;
                $total = $subtotal - $discountAmount + $taxAmount;
                
                $sale = PosSale::create([
                    'invoice_no' => $invoiceNo, 'session_id' => $session->id, 'admin_id' => $admin->id, 'warehouse_id' => $warehouseId,
                    'customer_id' => $request->input('customer_id'), 'customer_name' => $request->input('customer_name'), 
                    'subtotal' => $subtotal, 'discount_amount' => $discountAmount,
                    'tax_amount' => $taxAmount, 'total' => $total, 'payment_method' => $request->input('payment_method', 'cash'),
                    'cash_received' => $request->input('cash_received'), 'change_amount' => max(0, ($request->input('cash_received') ?? 0) - $total),
                    'status' => 'completed',
                ]);
                
                foreach ($cart as $item) {
                    // Get item's tax rate
                    $taxInfo = $this->getProductTaxRate($item['id']);
                    $itemTotal = $item['price'] * $item['qty'];
                    $itemTax = $itemTotal * $taxInfo['rate'] / 100;
                    
                    $sale->items()->create([
                        'product_id' => $item['id'], 
                        'variant_id' => $item['variant_id'] ?? null, 
                        'product_name' => $item['name'], 
                        'variant_name' => $item['variant_name'] ?? null, 
                        'qty' => $item['qty'], 
                        'price' => $item['price'], 
                        'line_total' => $itemTotal,
                        'tax_rate' => $taxInfo['rate'],
                        'tax_amount' => $itemTax,
                    ]);
                    
                    // Update stock levels and create stock movement
                    if ($warehouseId) {
                        $productId = $item['id'];
                        $variationId = $item['variant_id'] ?? null;
                        $qty = $item['qty'];
                        
                        // Get product unit_id
                        $product = DB::table('products')->where('id', $productId)->first();
                        $unitId = $product->unit_id ?? 1; // Default to PCS (1)
                        
                        // Find stock level record
                        $stockLevel = DB::table('stock_levels')
                            ->where('product_id', $productId)
                            ->where('warehouse_id', $warehouseId)
                            ->when($variationId, fn($q) => $q->where('variation_id', $variationId))
                            ->when(!$variationId, fn($q) => $q->whereNull('variation_id'))
                            ->first();
                        
                        $stockBefore = $stockLevel ? $stockLevel->qty : 0;
                        $stockAfter = $stockBefore - $qty;
                        
                        // Update or create stock level
                        if ($stockLevel) {
                            DB::table('stock_levels')
                                ->where('id', $stockLevel->id)
                                ->update([
                                    'qty' => DB::raw("qty - {$qty}"),
                                    'updated_at' => now()
                                ]);
                        } else {
                            DB::table('stock_levels')->insert([
                                'product_id' => $productId,
                                'variation_id' => $variationId,
                                'warehouse_id' => $warehouseId,
                                'unit_id' => $unitId,
                                'qty' => -$qty,
                                'reserved_qty' => 0,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                        
                        // Create stock movement record
                        DB::table('stock_movements')->insert([
                            'reference_no' => $invoiceNo,
                            'product_id' => $productId,
                            'variation_id' => $variationId,
                            'warehouse_id' => $warehouseId,
                            'unit_id' => $unitId,
                            'qty' => $qty,
                            'base_qty' => $qty,
                            'stock_before' => $stockBefore,
                            'stock_after' => $stockAfter,
                            'movement_type' => 'OUT',
                            'reference_type' => 'SALE',
                            'reference_id' => $sale->id,
                            'reason' => 'POS Sale',
                            'notes' => 'POS Invoice: ' . $invoiceNo,
                            'created_by' => $admin->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
                
                // Create Invoice in invoices table
                $customerId = $request->input('customer_id');
                $customer = $customerId ? DB::table('customers')->where('id', $customerId)->first() : null;
                
                // Get next invoice number - use max ID + 1 for uniqueness
                $maxId = DB::table('invoices')->max('id') ?? 0;
                $nextNum = $maxId + 1;
                $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);
                
                // Ensure uniqueness - if somehow exists, add timestamp
                while (DB::table('invoices')->where('invoice_number', $invoiceNumber)->exists()) {
                    $nextNum++;
                    $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);
                }
                
                $invoiceId = DB::table('invoices')->insertGetId([
                    'invoice_number' => $invoiceNumber,
                    'customer_id' => $customerId,
                    'subject' => 'POS Sale: ' . $invoiceNo,
                    'date' => now()->toDateString(),
                    'due_date' => now()->toDateString(),
                    'status' => 'paid',
                    'payment_status' => 'paid',
                    'email' => $customer->email ?? null,
                    'phone' => $customer->phone ?? null,
                    'address' => $customer->address ?? null,
                    'city' => $customer->city ?? null,
                    'state' => $customer->state ?? null,
                    'zip_code' => $customer->zip_code ?? null,
                    'country' => $customer->country ?? 'India',
                    'subtotal' => $subtotal,
                    'discount' => $discountAmount,
                    'discount_type' => $discountAmount > 0 ? 'fixed' : 'no_discount',
                    'discount_percent' => 0,
                    'discount_amount' => $discountAmount,
                    'tax' => 0,
                    'tax_amount' => $taxAmount,
                    'adjustment' => 0,
                    'total' => $total,
                    'amount_paid' => $total,
                    'amount_due' => 0,
                    'allow_comments' => 0,
                    'admin_note' => 'POS Invoice: ' . $invoiceNo . ' | Payment: ' . $request->input('payment_method', 'cash'),
                    'currency' => 'INR',
                    'created_by' => $admin->name ?? 'POS',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Create invoice items
                $sortOrder = 0;
                foreach ($cart as $item) {
                    $itemTaxInfo = $this->getProductTaxRate($item['id']);
                    $itemTaxRate = $itemTaxInfo['rate'];
                    $itemTaxAmount = ($item['price'] * $item['qty']) * $itemTaxRate / 100;
                    
                    DB::table('invoice_items')->insert([
                        'invoice_id' => $invoiceId,
                        'item_type' => 'product',
                        'product_id' => $item['id'],
                        'description' => $item['name'] . ($item['variant_name'] ? ' - ' . $item['variant_name'] : ''),
                        'quantity' => $item['qty'],
                        'unit' => 'PCS',
                        'rate' => $item['price'],
                        'tax_rate' => $itemTaxRate,
                        'tax_amount' => $itemTaxAmount,
                        'amount' => $item['price'] * $item['qty'],
                        'sort_order' => $sortOrder++,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                
                // Update sale with invoice_id
                $sale->update(['invoice_id' => $invoiceId]);
                
                // Update session totals
                $session->update([
                    'total_sales' => PosSale::where('session_id', $session->id)->where('status', 'completed')->sum('total'),
                ]);
                
                return response()->json(['success' => true, 'sale' => $sale, 'invoice_id' => $invoiceId, 'invoice_number' => $invoiceNumber]);
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    
    public function holdBill(Request $request)
    {
        PosHeldBill::create([
            'admin_id' => $this->admin()->id, 
            'hold_ref' => 'HOLD-' . time(), 
            'customer_id' => $request->input('customer_id'),
            'customer_name' => $request->input('customer_name'), 
            'cart_items' => $request->input('cart'), 
            'subtotal' => $request->input('subtotal', 0)
        ]);
        return response()->json(['success' => true]);
    }
    
    public function getHeldBills() { return response()->json(PosHeldBill::where('admin_id', $this->admin()->id)->get()); }
    public function recallBill($id) { return response()->json(PosHeldBill::findOrFail($id)); }
    public function deleteHeldBill($id) { PosHeldBill::destroy($id); return response()->json(['success' => true]); }
    
    public function receipt($id)
    {
        $sale = PosSale::with(['items', 'admin'])->findOrFail($id);
        $settings = PosSettings::first();
        
        // Create default settings if not exist
        if (!$settings) {
            $settings = PosSettings::create([
                'store_name' => 'EchoPx Store',
                'store_phone' => '',
                'store_address' => '',
                'store_gstin' => '',
                'invoice_prefix' => 'INV-',
                'default_tax_rate' => 0,
                'receipt_footer' => 'Thank you for shopping!',
            ]);
        }
        
        return view('pos::admin.receipt', compact('sale', 'settings'));
    }
    
    public function invoicePdf($id)
    {
        $sale = PosSale::with(['items', 'admin'])->findOrFail($id);
        $settings = PosSettings::first();
        
        // Create default settings if not exist
        if (!$settings) {
            $settings = PosSettings::create([
                'store_name' => 'EchoPx Store',
                'store_phone' => '',
                'store_address' => '',
                'store_gstin' => '',
                'invoice_prefix' => 'INV-',
                'default_tax_rate' => 0,
                'receipt_footer' => 'Thank you for shopping!',
            ]);
        }
        
        // Get customer details
        $customer = null;
        if ($sale->customer_id) {
            $customer = DB::table('customers')->where('id', $sale->customer_id)->first();
        }
        
        // Get warehouse
        $warehouse = null;
        if ($sale->warehouse_id) {
            $warehouse = DB::table('warehouses')->where('id', $sale->warehouse_id)->first();
        }
        
        // Generate PDF using dompdf
        $html = view('pos::admin.invoice-pdf', compact('sale', 'settings', 'customer', 'warehouse'))->render();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('Invoice-' . $sale->invoice_no . '.pdf');
    }
    
    // Mobile Scanner - standalone page for mobile devices
    public function mobileScanner($code)
    {
        $session = PosSession::where('session_code', $code)->where('status', 'open')->first();
        if (!$session) {
            return view('pos::admin.scanner-error', ['message' => 'Invalid or expired session code']);
        }
        return view('pos::admin.scanner', compact('session', 'code'));
    }
    
    // Receive barcode from mobile scanner
    public function remoteScan(Request $request)
    {
        $code = $request->input('session_code');
        $barcode = $request->input('barcode');
        
        $session = PosSession::where('session_code', $code)->where('status', 'open')->first();
        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Invalid session'], 400);
        }
        
        // Store barcode in cache for polling (expires in 30 seconds)
        $key = 'pos_scan_' . $session->id;
        $scans = cache()->get($key, []);
        $scans[] = ['barcode' => $barcode, 'time' => now()->timestamp];
        cache()->put($key, $scans, 30);
        
        return response()->json(['success' => true, 'message' => 'Sent to POS']);
    }
    
    // Main POS polls for remote scans
    public function pollScans(Request $request)
    {
        $admin = $this->admin();
        $session = PosSession::where('admin_id', $admin->id)->where('status', 'open')->first();
        if (!$session) {
            return response()->json(['scans' => []]);
        }
        
        $key = 'pos_scan_' . $session->id;
        $scans = cache()->get($key, []);
        
        // Clear after reading
        cache()->forget($key);
        
        // Only return barcodes (not full scan objects)
        $barcodes = array_map(fn($s) => $s['barcode'], $scans);
        
        return response()->json(['scans' => $barcodes]);
    }
    
    public function searchCustomers(Request $request)
    {
        $q = $request->input('q');
        
        $customers = DB::table('customers')
            ->where('active', 1)
            ->where(function($query) use ($q) {
                $query->where('name', 'LIKE', "%{$q}%")
                    ->orWhere('email', 'LIKE', "%{$q}%")
                    ->orWhere('phone', 'LIKE', "%{$q}%");
            })
            ->select('id', 'name', 'email', 'phone', 'company')
            ->limit(10)
            ->get();
        
        return response()->json($customers);
    }
    
    public function createCustomer(Request $request)
    {
        $email = $request->input('email');
        $phone = $request->input('phone');
        $errors = [];
        
        // Check unique email
        if ($email) {
            $exists = DB::table('customers')->where('email', $email)->exists();
            if ($exists) {
                $errors['email'] = 'Email already exists';
            }
        }
        
        // Check unique phone
        if ($phone) {
            $exists = DB::table('customers')->where('phone', $phone)->exists();
            if ($exists) {
                $errors['phone'] = 'Phone already exists';
            }
        }
        
        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ], 422);
        }
        
        $id = DB::table('customers')->insertGetId([
            'name' => $request->input('name'),
            'email' => $email,
            'phone' => $phone,
            'customer_type' => 'individual',
            'active' => 1,
            'added_by' => $this->admin()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'customer' => [
                'id' => $id,
                'name' => $request->input('name'),
                'email' => $email,
                'phone' => $phone,
            ]
        ]);
    }
}
