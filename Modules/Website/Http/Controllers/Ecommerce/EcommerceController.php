<?php

namespace Modules\Website\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Website\WebsiteController;
use Modules\Website\Models\Ecommerce\Product;
use Modules\Website\Models\Ecommerce\ProductCategory;
use Modules\Website\Models\Website\WebsiteSetting;

class EcommerceController extends WebsiteController
{
    protected function getSettings()
    {
        $settings = WebsiteSetting::instance();
        $contentPath = module_path('Website', 'Resources/content/settings.json');
        $headerSettings = File::exists($contentPath) ? json_decode(File::get($contentPath), true) : [];
        
        // Get prefixes
        $sitePrefix = '/' . ($settings->site_prefix ?? 'site');
        $shopPrefix = '/' . ($settings->shop_prefix ?? 'shop');
        $siteMode = $settings->site_mode ?? 'both';
        
        if (!empty($headerSettings['menu'])) {
            $filteredMenu = [];
            foreach ($headerSettings['menu'] as $item) {
                // Skip Shop if mode is 'website_only'
                if ($siteMode === 'website_only' && strtolower($item['title']) === 'shop') {
                    continue;
                }
                
                // Apply correct prefix based on URL type
                if (stripos($item['url'], '/shop') === 0 || strtolower($item['title']) === 'shop') {
                    // Shop URLs use shop prefix
                    $item['url'] = $shopPrefix . (stripos($item['url'], '/shop') === 0 ? substr($item['url'], 5) : '');
                } else {
                    // Page URLs use site prefix
                    $item['url'] = $sitePrefix . $item['url'];
                }
                
                if (!empty($item['children'])) {
                    foreach ($item['children'] as &$child) {
                        if (stripos($child['url'], '/shop') === 0) {
                            $child['url'] = $shopPrefix . substr($child['url'], 5);
                        } else {
                            $child['url'] = $sitePrefix . $child['url'];
                        }
                    }
                }
                $filteredMenu[] = $item;
            }
            $headerSettings['menu'] = $filteredMenu;
        }
        
        // Add prefix to footer links
        if (!empty($headerSettings['footer']['columns'])) {
            foreach ($headerSettings['footer']['columns'] as &$col) {
                if (!empty($col['links'])) {
                    $filteredLinks = [];
                    foreach ($col['links'] as $link) {
                        if ($siteMode === 'website_only' && strtolower($link['text']) === 'shop') {
                            continue;
                        }
                        if (stripos($link['url'], '/shop') === 0) {
                            $link['url'] = $shopPrefix . substr($link['url'], 5);
                        } else {
                            $link['url'] = $sitePrefix . $link['url'];
                        }
                        $filteredLinks[] = $link;
                    }
                    $col['links'] = $filteredLinks;
                }
            }
        }
        
        return compact('settings', 'headerSettings');
    }

    public function shop(Request $request)
    {
        $data = $this->getSettings();
        
        // Redirect to home if website only mode
        if ($data['settings']->site_mode === 'website_only') {
            return redirect($data['settings']->getHomeUrl());
        }
        
        // Categories for navigation (Livewire handles product fetching)
        $data['categories'] = ProductCategory::where('is_active', 1)->orderBy('sort_order')->get();
        $data['cartCount'] = count(session('cart', []));

        return view('website::public.shop', $data);
    }

    public function product($id)
    {
        $data = $this->getSettings();
        
        // Redirect to home if website only mode
        if ($data['settings']->site_mode === 'website_only') {
            return redirect($data['settings']->getHomeUrl());
        }
        
        $data['product'] = Product::with(['category', 'images', 'activeVariations'])->findOrFail($id);
        $data['categories'] = ProductCategory::where('is_active', 1)->orderBy('sort_order')->get();
        $data['cartCount'] = count(session('cart', []));
        
        $data['relatedProducts'] = Product::where('is_active', 1)
            ->where('can_be_sold', 1)
            ->where('id', '!=', $id)
            ->where('category_id', $data['product']->category_id)
            ->limit(6)
            ->get();

        return view('website::public.product', $data);
    }

    public function cart()
    {
        $data = $this->getSettings();
        
        if ($data['settings']->site_mode === 'website_only') {
            return redirect($data['settings']->getHomeUrl());
        }
        
        $data['categories'] = ProductCategory::where('is_active', 1)->orderBy('sort_order')->get();
        $data['cartCount'] = count(session('cart', []));

        return view('website::public.cart', $data);
    }

    public function wishlist()
    {
        $data = $this->getSettings();
        
        if ($data['settings']->site_mode === 'website_only') {
            return redirect($data['settings']->getHomeUrl());
        }
        
        $data['categories'] = ProductCategory::where('is_active', 1)->orderBy('sort_order')->get();
        $data['cartCount'] = count(session('cart', []));

        return view('website::public.wishlist', $data);
    }

    public function apiAddToCart(Request $request)
    {
        // Must be logged in
        if (!Auth::check()) {
            return response()->json([
                'success' => false, 
                'message' => 'Please login to add items to cart',
                'requireLogin' => true
            ]);
        }
        
        $userId = Auth::id();
        $productId = $request->input('product_id');
        $variationId = $request->input('variation_id');
        $qty = $request->input('qty', 1);
        
        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }

        // Check if product has variants but no variation selected
        if ($product->has_variants && !$variationId) {
            return response()->json(['success' => false, 'message' => 'Please select options']);
        }

        // Get stock
        $stock = 0;
        if ($variationId) {
            $variation = \Modules\Website\Models\Ecommerce\ProductVariation::where('id', $variationId)
                ->where('product_id', $productId)
                ->where('is_active', true)
                ->first();
            
            if (!$variation) {
                return response()->json(['success' => false, 'message' => 'Invalid variation']);
            }
            $stock = $variation->getCurrentStock();
        } else {
            $stock = $product->getCurrentStock();
        }

        if ($stock <= 0) {
            return response()->json(['success' => false, 'message' => 'Out of stock']);
        }

        // Use user-specific cart key
        $cartSessionKey = 'cart_' . $userId;
        $cart = session($cartSessionKey, []);
        
        // Cart key: product_id or product_id_variation_id
        $cartKey = $variationId ? "{$productId}_{$variationId}" : (string) $productId;
        
        // Check existing qty in cart
        $existingQty = isset($cart[$cartKey]) ? $cart[$cartKey]['qty'] : 0;
        $newTotalQty = $existingQty + $qty;
        
        // Limit to available stock
        if ($newTotalQty > $stock) {
            $allowedQty = $stock - $existingQty;
            if ($allowedQty <= 0) {
                return response()->json([
                    'success' => false, 
                    'message' => "Already have maximum ({$existingQty}) in cart"
                ]);
            }
            $qty = $allowedQty;
            $newTotalQty = $stock;
        }
        
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] = $newTotalQty;
        } else {
            $cart[$cartKey] = [
                'product_id' => $productId,
                'variation_id' => $variationId,
                'qty' => $qty
            ];
        }

        session([$cartSessionKey => $cart]);

        $cartCount = 0;
        foreach ($cart as $item) {
            $cartCount += $item['qty'];
        }

        return response()->json([
            'success' => true,
            'cartCount' => $cartCount,
            'message' => $qty < $request->input('qty', 1) 
                ? "Added {$qty} (max stock reached)" 
                : 'Added to cart',
            'addedQty' => $qty,
            'totalInCart' => $newTotalQty
        ]);
    }

    public function apiToggleWishlist(Request $request)
    {
        // Must be logged in
        if (!Auth::check()) {
            return response()->json([
                'success' => false, 
                'message' => 'Please login to add items to wishlist',
                'requireLogin' => true
            ]);
        }
        
        $userId = Auth::id();
        $productId = $request->input('product_id');
        
        // Use user-specific wishlist key
        $wishlistSessionKey = 'wishlist_' . $userId;
        $wishlist = session($wishlistSessionKey, []);
        
        if (in_array($productId, $wishlist)) {
            $wishlist = array_values(array_diff($wishlist, [$productId]));
            $inWishlist = false;
        } else {
            $wishlist[] = $productId;
            $inWishlist = true;
        }

        session([$wishlistSessionKey => $wishlist]);

        return response()->json([
            'success' => true,
            'inWishlist' => $inWishlist,
            'wishlistCount' => count($wishlist)
        ]);
    }

    /**
     * Checkout page
     */
    public function checkout(Request $request)
    {
        // Must be logged in
        if (!Auth::check()) {
            return redirect()->route('website.login')->with('error', 'Please login to checkout');
        }
        
        $user = Auth::user();
        $userId = Auth::id();
        
        // Get customer by email
        $customer = \Illuminate\Support\Facades\DB::table('customers')->where('email', $user->email)->first();
        
        // Get layout settings (returns array with 'settings' and 'headerSettings')
        $layoutData = $this->getSettings();
        $settings = $layoutData['settings']; // WebsiteSetting model for layout
        $headerSettings = $layoutData['headerSettings'];
        
        $wsSettings = WebsiteSetting::instance(); // For ecommerce settings
        
        $cart = session('cart_' . $userId, []);
        
        if (empty($cart)) {
            return redirect()->route('website.cart')->with('error', 'Your cart is empty');
        }
        
        // Build cart items with full details
        $cartItems = [];
        $subtotal = 0;
        
        foreach ($cart as $key => $item) {
            $product = Product::find($item['product_id']);
            if (!$product) continue;
            
            $variation = null;
            $price = $product->sale_price;
            $name = $product->name;
            $variationName = null;
            
            if (!empty($item['variation_id'])) {
                $variation = \Modules\Website\Models\Ecommerce\ProductVariation::find($item['variation_id']);
                if ($variation) {
                    $price = $variation->getEffectiveSalePrice();
                    $variationName = $variation->getDisplayName();
                }
            }
            
            $qty = $item['qty'];
            $itemTotal = $price * $qty;
            
            $cartItems[] = [
                'key' => $key,
                'product' => $product,
                'variation' => $variation,
                'variation_name' => $variationName,
                'price' => $price,
                'qty' => $qty,
                'total' => $itemTotal,
            ];
            
            $subtotal += $itemTotal;
        }
        
        // Calculate shipping
        $freeShippingMin = (float) ($wsSettings->free_shipping_min ?? 0);
        $shippingFee = 0;
        
        if ($freeShippingMin <= 0 || $subtotal < $freeShippingMin) {
            $shippingFee = (float) ($wsSettings->shipping_fee ?? 0);
        }
        
        // COD settings
        $codFee = (float) ($wsSettings->cod_fee ?? 0);
        $codEnabled = $wsSettings->cod_enabled ?? true;
        $codMaxAmount = (float) ($wsSettings->cod_max_amount ?? 0);
        
        // Calculate grand total
        $grandTotal = $subtotal + $shippingFee;
        
        // Check if COD is available for this order (max amount check)
        if ($codMaxAmount > 0 && $grandTotal > $codMaxAmount) {
            $codEnabled = false;
        }
        
        // Minimum order amount
        $minOrderAmount = (float) ($wsSettings->min_order_amount ?? 0);
        
        // Delivery days
        $deliveryDays = $wsSettings->delivery_days ?? '3-5 business days';
        
        // Pre-fill shipping address from customer if available
        $shippingAddress = [
            'name' => $customer->name ?? $user->name ?? '',
            'phone' => $customer->phone ?? '',
            'address' => $customer->shipping_address ?? $customer->address ?? '',
            'city' => $customer->shipping_city ?? $customer->city ?? '',
            'state' => $customer->shipping_state ?? $customer->state ?? '',
            'pincode' => $customer->shipping_zip_code ?? $customer->zip_code ?? '',
        ];
        
        return view('website::public.checkout', compact(
            'settings', 'headerSettings', 'cart', 'cartItems', 'user', 'customer',
            'subtotal', 'shippingFee', 'codFee', 'codEnabled', 'codMaxAmount',
            'freeShippingMin', 'grandTotal', 'wsSettings',
            'minOrderAmount', 'deliveryDays', 'shippingAddress'
        ));
    }

    /**
     * Place order
     */
    public function placeOrder(Request $request)
    {
        // Must be logged in
        if (!Auth::check()) {
            return redirect()->route('website.login')->with('error', 'Please login to checkout');
        }
        
        $user = Auth::user();
        $userId = Auth::id();
        
        // Get customer ID
        $customer = \Illuminate\Support\Facades\DB::table('customers')->where('email', $user->email)->first();
        $customerId = $customer ? $customer->id : null;
        
        $wsSettings = WebsiteSetting::instance();
        $cart = session('cart_' . $userId, []);
        
        if (empty($cart)) {
            return redirect()->route('website.cart')->with('error', 'Your cart is empty');
        }
        
        // Check minimum order amount
        $minOrderAmount = (float) ($wsSettings->min_order_amount ?? 0);
        
        // Validate request
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:100',
            'shipping_pincode' => 'required|string|max:20',
            'payment_method' => 'required|string',
            'customer_notes' => 'nullable|string|max:500',
        ]);
        
        // Calculate totals and validate stock
        $subtotal = 0;
        $taxAmount = 0;
        $cartItems = [];
        
        foreach ($cart as $key => $item) {
            $product = Product::find($item['product_id']);
            if (!$product) continue;
            
            $variation = null;
            $price = $product->sale_price ?? 0;
            $stock = $product->getCurrentStock();
            
            if (!empty($item['variation_id'])) {
                $variation = \Modules\Website\Models\Ecommerce\ProductVariation::find($item['variation_id']);
                if ($variation) {
                    $price = $variation->getEffectiveSalePrice();
                    $stock = $variation->getCurrentStock();
                }
            }
            
            // Check stock availability
            if ($stock < $item['qty']) {
                return redirect()->route('website.cart')->with('error', "Insufficient stock for {$product->name}. Available: {$stock}");
            }
            
            $itemTotal = $price * $item['qty'];
            $subtotal += $itemTotal;
            
            $cartItems[] = [
                'cart_item' => $item,
                'product' => $product,
                'variation' => $variation,
                'price' => $price,
                'qty' => $item['qty'],
            ];
        }
        
        // Check minimum order amount
        if ($minOrderAmount > 0 && $subtotal < $minOrderAmount) {
            return redirect()->route('website.cart')->with('error', "Minimum order amount is ₹{$minOrderAmount}");
        }
        
        // Shipping calculation
        $freeShippingMin = (float) ($wsSettings->free_shipping_min ?? 0);
        $shippingFee = ($freeShippingMin > 0 && $subtotal >= $freeShippingMin) ? 0 : (float) ($wsSettings->shipping_fee ?? 0);
        
        // Get payment method details
        // Validate payment method - now only 'cod' or 'online'
        $paymentMethod = $validated['payment_method'];
        $validMethods = [];
        if ($wsSettings->cod_enabled ?? true) {
            $validMethods[] = 'cod';
        }
        if ($wsSettings->online_payment_enabled ?? false) {
            $validMethods[] = 'online';
        }
        
        if (!in_array($paymentMethod, $validMethods)) {
            return redirect()->back()->with('error', 'Invalid or unavailable payment method');
        }
        
        // COD fee (only for cod payment)
        $codFee = 0;
        if ($paymentMethod === 'cod') {
            $codFee = (float) ($wsSettings->cod_fee ?? 0);
            $codMaxAmount = (float) ($wsSettings->cod_max_amount ?? 0);
            if ($codMaxAmount > 0 && ($subtotal + $shippingFee) > $codMaxAmount) {
                return redirect()->back()->with('error', "COD not available for orders above ₹{$codMaxAmount}");
            }
        }
        
        $total = $subtotal + $shippingFee + $codFee;
        
        // Start transaction
        \Illuminate\Support\Facades\DB::beginTransaction();
        
        try {
            // Create order
            $order = \Modules\Website\Models\Ecommerce\WebsiteOrder::create([
                'order_no' => $wsSettings->generateOrderNumber(),
                'customer_id' => $customerId,
                'user_id' => $userId,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'] ?? $user->email,
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_state' => $validated['shipping_state'],
                'shipping_pincode' => $validated['shipping_pincode'],
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_fee' => $shippingFee,
                'cod_fee' => $codFee,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => $paymentMethod === 'online' ? 'awaiting' : 'pending',
                'payment_method' => $paymentMethod,
                'customer_notes' => $validated['customer_notes'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            // Create order items and decrement stock
            foreach ($cartItems as $cartData) {
                // Create order item
                \Modules\Website\Models\Ecommerce\WebsiteOrderItem::createFromCart($cartData['cart_item'], $order);
                
                // Decrement stock
                $this->decrementStock(
                    $cartData['product']->id,
                    $cartData['variation'] ? $cartData['variation']->id : null,
                    $cartData['qty'],
                    $order->order_no
                );
            }
            
            // Add status history
            \Modules\Website\Models\Ecommerce\WebsiteOrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'comment' => 'Order placed by customer',
            ]);
            
            // Generate Invoice
            $this->generateInvoice($order, $customerId, $wsSettings);
            
            // Update customer shipping address if empty
            if ($customerId && $customer) {
                $updateData = [];
                if (empty($customer->shipping_address)) {
                    $updateData['shipping_address'] = $validated['shipping_address'];
                    $updateData['shipping_city'] = $validated['shipping_city'];
                    $updateData['shipping_state'] = $validated['shipping_state'];
                    $updateData['shipping_zip_code'] = $validated['shipping_pincode'];
                }
                if (!empty($updateData)) {
                    \Illuminate\Support\Facades\DB::table('customers')
                        ->where('id', $customerId)
                        ->update($updateData);
                }
            }
            
            \Illuminate\Support\Facades\DB::commit();
            
            // Clear cart
            session()->forget('cart_' . $userId);
            
            // Send order confirmation emails
            $this->sendOrderEmails($order, $wsSettings);
            
            return redirect()->route('website.order.success', $order->id);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Log::error('Order placement failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to place order. Please try again.');
        }
    }
    
    /**
     * Decrement stock for product/variation
     */
    protected function decrementStock($productId, $variationId, $qty, $reference)
    {
        // Check if stock_movements table exists
        $hasStockMovements = \Illuminate\Support\Facades\Schema::hasTable('stock_movements');
        
        // Get product for unit_id
        $product = \Illuminate\Support\Facades\DB::table('products')->find($productId);
        $unitId = $product->unit_id ?? 1;
        
        // Determine stock column name in stock_levels table
        $stockColumn = 'qty'; // Default column name
        if (\Illuminate\Support\Facades\Schema::hasTable('stock_levels')) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('stock_levels', 'quantity')) {
                $stockColumn = 'quantity';
            } elseif (\Illuminate\Support\Facades\Schema::hasColumn('stock_levels', 'qty')) {
                $stockColumn = 'qty';
            } elseif (\Illuminate\Support\Facades\Schema::hasColumn('stock_levels', 'stock_qty')) {
                $stockColumn = 'stock_qty';
            }
        }
        
        if ($variationId) {
            // For variations - use stock_levels table if exists
            if (\Illuminate\Support\Facades\Schema::hasTable('stock_levels')) {
                $stockLevel = \Illuminate\Support\Facades\DB::table('stock_levels')
                    ->where('product_id', $productId)
                    ->where('variation_id', $variationId)
                    ->first();
                    
                if ($stockLevel) {
                    \Illuminate\Support\Facades\DB::table('stock_levels')
                        ->where('id', $stockLevel->id)
                        ->decrement($stockColumn, $qty);
                }
            }
            
            // Also update product_variations.stock_qty if column exists
            if (\Illuminate\Support\Facades\Schema::hasColumn('product_variations', 'stock_qty')) {
                \Illuminate\Support\Facades\DB::table('product_variations')
                    ->where('id', $variationId)
                    ->decrement('stock_qty', $qty);
            }
            
            // Add stock movement record
            if ($hasStockMovements) {
                \Illuminate\Support\Facades\DB::table('stock_movements')->insert([
                    'reference_no' => 'WEB-' . $reference,
                    'product_id' => $productId,
                    'variation_id' => $variationId,
                    'warehouse_id' => 1,
                    'unit_id' => $unitId,
                    'qty' => $qty,
                    'base_qty' => $qty,
                    'movement_type' => 'OUT',
                    'reference_type' => 'SALE',
                    'reason' => 'Website Order',
                    'notes' => 'Website order: ' . $reference,
                    'created_by' => \Illuminate\Support\Facades\Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            // For simple products
            if (\Illuminate\Support\Facades\Schema::hasTable('stock_levels')) {
                $stockLevel = \Illuminate\Support\Facades\DB::table('stock_levels')
                    ->where('product_id', $productId)
                    ->whereNull('variation_id')
                    ->first();
                    
                if ($stockLevel) {
                    \Illuminate\Support\Facades\DB::table('stock_levels')
                        ->where('id', $stockLevel->id)
                        ->decrement($stockColumn, $qty);
                }
            }
            
            // Also update products.stock_qty if column exists
            if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'stock_qty')) {
                \Illuminate\Support\Facades\DB::table('products')
                    ->where('id', $productId)
                    ->decrement('stock_qty', $qty);
            }
            
            // Add stock movement record
            if ($hasStockMovements) {
                \Illuminate\Support\Facades\DB::table('stock_movements')->insert([
                    'reference_no' => 'WEB-' . $reference,
                    'product_id' => $productId,
                    'variation_id' => null,
                    'warehouse_id' => 1,
                    'unit_id' => $unitId,
                    'qty' => $qty,
                    'base_qty' => $qty,
                    'movement_type' => 'OUT',
                    'reference_type' => 'SALE',
                    'reason' => 'Website Order',
                    'notes' => 'Website order: ' . $reference,
                    'created_by' => \Illuminate\Support\Facades\Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
    
    /**
     * Generate invoice for order
     */
    protected function generateInvoice($order, $customerId, $wsSettings)
    {
        // Generate invoice number
        $invoicePrefix = $wsSettings->invoice_prefix ?? 'INV-';
        $year = date('Y');
        $lastInvoice = \Illuminate\Support\Facades\DB::table('invoices')
            ->where('invoice_number', 'like', $invoicePrefix . $year . '%')
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNum = 1;
        if ($lastInvoice) {
            preg_match('/(\d+)$/', $lastInvoice->invoice_number, $matches);
            $nextNum = isset($matches[1]) ? ((int)$matches[1] + 1) : 1;
        }
        $invoiceNumber = $invoicePrefix . $year . '-' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);
        
        // Create invoice
        $invoiceId = \Illuminate\Support\Facades\DB::table('invoices')->insertGetId([
            'invoice_number' => $invoiceNumber,
            'customer_id' => $customerId,
            'subject' => 'Website Order: ' . $order->order_no,
            'date' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
            'status' => 'draft',
            'payment_status' => 'unpaid',
            'email' => $order->customer_email,
            'phone' => $order->customer_phone,
            'address' => $order->shipping_address,
            'city' => $order->shipping_city,
            'state' => $order->shipping_state,
            'zip_code' => $order->shipping_pincode,
            'country' => 'India',
            'subtotal' => $order->subtotal,
            'discount' => 0,
            'discount_type' => 'no_discount',
            'discount_percent' => 0,
            'discount_amount' => 0,
            'tax' => $order->tax_amount,
            'tax_amount' => $order->tax_amount,
            'adjustment' => $order->shipping_fee + $order->cod_fee,
            'total' => $order->total,
            'amount_paid' => 0,
            'amount_due' => $order->total,
            'content' => null,
            'tags' => null,
            'allow_comments' => 1,
            'admin_note' => 'Auto-generated from website order ' . $order->order_no,
            'terms_conditions' => $wsSettings->invoice_footer,
            'currency' => 'INR',
            'created_by' => 'Website',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Create invoice items
        $orderItems = $order->items;
        $sortOrder = 0;
        
        foreach ($orderItems as $item) {
            \Illuminate\Support\Facades\DB::table('invoice_items')->insert([
                'invoice_id' => $invoiceId,
                'item_type' => 'product',
                'product_id' => $item->product_id,
                'description' => $item->product_name . ($item->variation_name ? ' - ' . $item->variation_name : ''),
                'long_description' => null,
                'quantity' => $item->qty,
                'unit' => $item->unit_name,
                'rate' => $item->unit_price,
                'tax_ids' => null,
                'tax_rate' => $item->tax_rate,
                'tax_amount' => $item->tax_amount,
                'amount' => $item->total,
                'sort_order' => $sortOrder++,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Add shipping as line item if applicable
        if ($order->shipping_fee > 0) {
            \Illuminate\Support\Facades\DB::table('invoice_items')->insert([
                'invoice_id' => $invoiceId,
                'item_type' => 'custom',
                'product_id' => null,
                'description' => 'Shipping Fee',
                'long_description' => null,
                'quantity' => 1,
                'unit' => null,
                'rate' => $order->shipping_fee,
                'tax_ids' => null,
                'tax_rate' => 0,
                'tax_amount' => 0,
                'amount' => $order->shipping_fee,
                'sort_order' => $sortOrder++,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Add COD fee as line item if applicable
        if ($order->cod_fee > 0) {
            \Illuminate\Support\Facades\DB::table('invoice_items')->insert([
                'invoice_id' => $invoiceId,
                'item_type' => 'custom',
                'product_id' => null,
                'description' => 'COD Fee',
                'long_description' => null,
                'quantity' => 1,
                'unit' => null,
                'rate' => $order->cod_fee,
                'tax_ids' => null,
                'tax_rate' => 0,
                'tax_amount' => 0,
                'amount' => $order->cod_fee,
                'sort_order' => $sortOrder++,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Update order with invoice reference
        $order->update(['admin_notes' => 'Invoice: ' . $invoiceNumber]);
        
        return $invoiceId;
    }

    /**
     * Order success page
     */
    public function orderSuccess($orderId)
    {
        $layoutData = $this->getSettings();
        $settings = $layoutData['settings'];
        $headerSettings = $layoutData['headerSettings'];
        
        $order = \Modules\Website\Models\Ecommerce\WebsiteOrder::with('items')->findOrFail($orderId);
        
        return view('website::public.order-success', compact('settings', 'headerSettings', 'order'));
    }

    /**
     * Submit product review (Amazon-style - login + purchase required)
     */
    public function submitReview(Request $request, $productId)
    {
        // Must be logged in
        if (!auth()->check()) {
            return redirect()->route('website.login')
                ->with('error', 'Please login to write a review.');
        }

        $product = Product::findOrFail($productId);
        $user = auth()->user();
        $userEmail = strtolower(trim($user->email));
        
        // Find customer record
        $customer = \Modules\Website\Models\Ecommerce\Customer::where('email', $userEmail)->first();
        
        if (!$customer) {
            return redirect()->back()->with('error', 'Customer account not found. Please make a purchase first.');
        }

        // Check if already reviewed (by customer_id OR email)
        $existingReview = \Modules\Website\Models\Ecommerce\ProductReview::where('product_id', $productId)
            ->where(function($q) use ($customer, $userEmail) {
                $q->where('customer_id', $customer->id)
                  ->orWhere('reviewer_email', $userEmail);
            })
            ->first();

        if ($existingReview) {
            return redirect()->back()
                ->with('error', 'You have already reviewed this product.');
        }

        // Check if customer has purchased this product
        $purchasedOrder = \Modules\Website\Models\Ecommerce\WebsiteOrder::where('customer_id', $customer->id)
            ->whereIn('status', ['delivered', 'shipped', 'processing', 'confirmed'])
            ->whereHas('items', function($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->first();

        if (!$purchasedOrder) {
            return redirect()->back()
                ->with('error', 'Only customers who have purchased this product can write a review.');
        }

        // Validate input
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'review' => 'required|string|min:10|max:2000',
        ]);

        // Create the review (auto-approved for verified purchases)
        \Modules\Website\Models\Ecommerce\ProductReview::create([
            'product_id' => $productId,
            'customer_id' => $customer->id,
            'order_id' => $purchasedOrder->id,
            'reviewer_name' => $user->name,
            'reviewer_email' => $userEmail,
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'review' => $validated['review'],
            'status' => 'approved', // Auto-approve verified purchase reviews
            'is_verified_purchase' => true,
        ]);

        return redirect()->back()->with('success', 'Thank you for your review!');
    }

    /**
     * Send order confirmation emails
     */
    protected function sendOrderEmails($order, $wsSettings)
    {
        try {
            // Load order items
            $order->load('items');
            
            // Get mail settings from Options (database) - same as Purchase module
            if (!class_exists('\App\Models\Option')) {
                \Log::warning('Option model not found - cannot send emails');
                return;
            }
            
            $mailConfig = [
                'driver' => \App\Models\Option::get('mail_mailer', 'smtp'),
                'host' => \App\Models\Option::get('mail_host', ''),
                'port' => \App\Models\Option::get('mail_port', 587),
                'username' => \App\Models\Option::get('mail_username', ''),
                'password' => \App\Models\Option::get('mail_password', ''),
                'encryption' => \App\Models\Option::get('mail_encryption', 'tls'),
                'from_address' => \App\Models\Option::get('mail_from_address', ''),
                'from_name' => \App\Models\Option::get('mail_from_name', \App\Models\Option::get('company_name', 'Store')),
            ];
            
            if (empty($mailConfig['host']) || empty($mailConfig['username'])) {
                \Log::warning('Mail settings not configured in options table');
                return;
            }
            
            // Set runtime mail config - same as Purchase module
            config([
                'mail.default' => $mailConfig['driver'],
                'mail.mailers.smtp.host' => $mailConfig['host'],
                'mail.mailers.smtp.port' => $mailConfig['port'],
                'mail.mailers.smtp.username' => $mailConfig['username'],
                'mail.mailers.smtp.password' => $mailConfig['password'],
                'mail.mailers.smtp.encryption' => $mailConfig['encryption'],
                'mail.from.address' => $mailConfig['from_address'],
                'mail.from.name' => $mailConfig['from_name'],
            ]);
            
            \Log::info("Mail config set - Host: {$mailConfig['host']}, From: {$mailConfig['from_address']}");
            
            // Generate PDF Invoice
            $pdfContent = $this->generateOrderInvoicePdf($order, $wsSettings);
            
            // Check settings - default to TRUE if column doesn't exist
            $sendToCustomer = $wsSettings->send_customer_order_email ?? true;
            $sendToAdmin = $wsSettings->send_admin_order_alert ?? true;
            
            // Send to customer
            if ($sendToCustomer && !empty($order->customer_email)) {
                $this->sendCustomerOrderEmail($order, $wsSettings, $mailConfig, $pdfContent);
            }
            
            // Send to admin
            $adminEmail = $wsSettings->order_notification_email;
            if (empty($adminEmail)) {
                $adminEmail = $mailConfig['from_address'];
            }
            
            if ($sendToAdmin && !empty($adminEmail)) {
                $this->sendAdminOrderAlert($order, $wsSettings, $mailConfig, $adminEmail, $pdfContent);
            }
            
        } catch (\Exception $e) {
            \Log::error('Failed to send order emails: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }

    /**
     * Generate PDF Invoice for order
     */
    protected function generateOrderInvoicePdf($order, $wsSettings)
    {
        try {
            // Get company info
            $company = [
                'name' => $wsSettings->site_name ?? \App\Models\Option::get('company_name', 'Store'),
                'address' => $wsSettings->store_address ?? \App\Models\Option::get('company_address', ''),
                'city' => $wsSettings->store_city ?? \App\Models\Option::get('company_city', ''),
                'state' => $wsSettings->store_state ?? \App\Models\Option::get('company_state', ''),
                'pincode' => $wsSettings->store_pincode ?? \App\Models\Option::get('company_zip', ''),
                'phone' => $wsSettings->contact_phone ?? \App\Models\Option::get('company_phone', ''),
                'email' => $wsSettings->contact_email ?? \App\Models\Option::get('company_email', ''),
                'gstin' => $wsSettings->store_gstin ?? \App\Models\Option::get('company_gst', ''),
            ];
            
            // Build invoice HTML
            $html = $this->buildInvoiceHtml($order, $company, $wsSettings);
            
            // Generate PDF using dompdf or similar
            if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
                $pdf->setPaper('A4', 'portrait');
                return $pdf->output();
            } elseif (class_exists('\PDF')) {
                $pdf = \PDF::loadHTML($html);
                return $pdf->output();
            } elseif (class_exists('\Dompdf\Dompdf')) {
                $dompdf = new \Dompdf\Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                return $dompdf->output();
            }
            
            \Log::warning('No PDF library found - sending email without invoice attachment');
            return null;
            
        } catch (\Exception $e) {
            \Log::error('Failed to generate invoice PDF: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Build Invoice HTML
     */
    protected function buildInvoiceHtml($order, $company, $wsSettings)
    {
        $companyName = $company['name'];
        $companyAddress = trim(implode(', ', array_filter([
            $company['address'],
            $company['city'],
            $company['state'],
            $company['pincode'],
        ])));
        
        // Build items table
        $itemsHtml = '';
        foreach ($order->items as $i => $item) {
            $itemsHtml .= "
            <tr>
                <td style='padding:12px;border-bottom:1px solid #eee;'>" . ($i + 1) . "</td>
                <td style='padding:12px;border-bottom:1px solid #eee;'>
                    <strong>" . ($item->product_name ?? 'Product') . "</strong>
                    " . ($item->variation_name ? "<br><small style='color:#666;'>" . $item->variation_name . "</small>" : "") . "
                </td>
                <td style='padding:12px;border-bottom:1px solid #eee;text-align:center;'>{$item->quantity}</td>
                <td style='padding:12px;border-bottom:1px solid #eee;text-align:right;'>₹" . number_format($item->unit_price, 2) . "</td>
                <td style='padding:12px;border-bottom:1px solid #eee;text-align:right;'>₹" . number_format($item->total, 2) . "</td>
            </tr>";
        }
        
        $paymentMethod = $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Online Payment';
        $paymentStatus = $order->payment_status === 'paid' ? 'PAID' : 'PENDING';
        $paymentStatusColor = $order->payment_status === 'paid' ? '#059669' : '#d97706';
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <title>Invoice - {$order->order_no}</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 14px; color: #333; margin: 0; padding: 20px; }
                .invoice-box { max-width: 800px; margin: auto; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #1e40af, #3b82f6); color: #fff; padding: 30px; }
                .header h1 { margin: 0 0 5px; font-size: 24px; }
                .header p { margin: 0; opacity: 0.9; font-size: 13px; }
                .invoice-title { float: right; text-align: right; }
                .invoice-title h2 { margin: 0; font-size: 28px; }
                .body { padding: 30px; }
                .info-row { display: table; width: 100%; margin-bottom: 30px; }
                .info-col { display: table-cell; width: 50%; vertical-align: top; }
                .info-col h3 { font-size: 12px; text-transform: uppercase; color: #666; margin: 0 0 10px; }
                .info-col p { margin: 3px 0; }
                .items-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                .items-table th { background: #f8f9fa; padding: 12px; text-align: left; font-size: 12px; text-transform: uppercase; color: #666; border-bottom: 2px solid #e5e7eb; }
                .items-table td { padding: 12px; border-bottom: 1px solid #f0f0f0; }
                .totals { width: 300px; margin-left: auto; }
                .totals-row { display: table; width: 100%; padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
                .totals-label { display: table-cell; color: #666; }
                .totals-value { display: table-cell; text-align: right; font-weight: 500; }
                .totals-row.grand { border-top: 2px solid #333; margin-top: 10px; font-size: 18px; font-weight: bold; }
                .totals-row.grand .totals-value { color: #1e40af; }
                .footer { background: #f8f9fa; padding: 20px 30px; text-align: center; color: #666; font-size: 12px; border-top: 1px solid #eee; }
                .status-badge { display: inline-block; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class='invoice-box'>
                <div class='header'>
                    <div style='display: inline-block;'>
                        <h1>{$companyName}</h1>
                        <p>{$companyAddress}</p>
                        " . ($company['phone'] ? "<p>Phone: {$company['phone']}</p>" : "") . "
                        " . ($company['gstin'] ? "<p>GSTIN: {$company['gstin']}</p>" : "") . "
                    </div>
                    <div class='invoice-title'>
                        <h2>INVOICE</h2>
                        <p style='margin:5px 0;font-size:16px;'>{$order->order_no}</p>
                        <p style='margin:0;opacity:0.9;'>" . $order->created_at->format('d M Y') . "</p>
                    </div>
                </div>
                
                <div class='body'>
                    <div class='info-row'>
                        <div class='info-col'>
                            <h3>Bill To</h3>
                            <p><strong>{$order->customer_name}</strong></p>
                            <p>{$order->shipping_address}</p>
                            <p>{$order->shipping_city}, {$order->shipping_state} - {$order->shipping_pincode}</p>
                            <p>Phone: {$order->customer_phone}</p>
                            " . ($order->customer_email ? "<p>Email: {$order->customer_email}</p>" : "") . "
                        </div>
                        <div class='info-col' style='text-align:right;'>
                            <h3>Order Details</h3>
                            <p><strong>Order Date:</strong> " . $order->created_at->format('d M Y, h:i A') . "</p>
                            <p><strong>Payment:</strong> {$paymentMethod}</p>
                            <p><strong>Status:</strong> <span class='status-badge' style='background:" . ($order->payment_status === 'paid' ? '#d1fae5;color:#065f46' : '#fef3c7;color:#92400e') . ";'>{$paymentStatus}</span></p>
                        </div>
                    </div>
                    
                    <table class='items-table'>
                        <thead>
                            <tr>
                                <th style='width:40px;'>#</th>
                                <th>Product</th>
                                <th style='width:80px;text-align:center;'>Qty</th>
                                <th style='width:100px;text-align:right;'>Price</th>
                                <th style='width:100px;text-align:right;'>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$itemsHtml}
                        </tbody>
                    </table>
                    
                    <div class='totals'>
                        <div class='totals-row'>
                            <div class='totals-label'>Subtotal</div>
                            <div class='totals-value'>₹" . number_format($order->subtotal, 2) . "</div>
                        </div>
                        <div class='totals-row'>
                            <div class='totals-label'>Shipping</div>
                            <div class='totals-value'>₹" . number_format($order->shipping_fee, 2) . "</div>
                        </div>
                        " . ($order->cod_fee > 0 ? "
                        <div class='totals-row'>
                            <div class='totals-label'>COD Fee</div>
                            <div class='totals-value'>₹" . number_format($order->cod_fee, 2) . "</div>
                        </div>" : "") . "
                        " . ($order->tax_amount > 0 ? "
                        <div class='totals-row'>
                            <div class='totals-label'>Tax</div>
                            <div class='totals-value'>₹" . number_format($order->tax_amount, 2) . "</div>
                        </div>" : "") . "
                        <div class='totals-row grand'>
                            <div class='totals-label'>Grand Total</div>
                            <div class='totals-value'>₹" . number_format($order->total, 2) . "</div>
                        </div>
                    </div>
                </div>
                
                <div class='footer'>
                    <p style='margin:0 0 10px;'>Thank you for your order!</p>
                    <p style='margin:0;color:#999;'>This is a computer generated invoice. No signature required.</p>
                    " . ($wsSettings->invoice_footer ?? '') . "
                </div>
            </div>
        </body>
        </html>";
    }

    /**
     * Send order confirmation email to customer
     */
    protected function sendCustomerOrderEmail($order, $wsSettings, $mailConfig, $pdfContent = null)
    {
        try {
            $siteName = $wsSettings->site_name ?? $mailConfig['from_name'] ?? 'Store';
            $subject = "Order Confirmation - {$order->order_no} | {$siteName}";
            $companyEmail = $mailConfig['from_address'];
            
            $html = $this->buildOrderEmailHtml($order, $wsSettings, 'customer');
            
            \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($order, $subject, $html, $siteName, $companyEmail, $pdfContent) {
                $message->to($order->customer_email, $order->customer_name)
                        ->subject($subject)
                        ->html($html);
                
                if ($companyEmail) {
                    $message->from($companyEmail, $siteName);
                    $message->replyTo($companyEmail, $siteName);
                }
                
                // Attach PDF Invoice
                if ($pdfContent) {
                    $message->attachData($pdfContent, "Invoice_{$order->order_no}.pdf", ['mime' => 'application/pdf']);
                }
            });
            
            \Log::info("✅ Order confirmation email SENT to customer: {$order->customer_email} for order {$order->order_no}");
            
        } catch (\Exception $e) {
            \Log::error("❌ Failed to send customer email: " . $e->getMessage());
        }
    }

    /**
     * Send new order alert to admin
     */
    protected function sendAdminOrderAlert($order, $wsSettings, $mailConfig, $adminEmail, $pdfContent = null)
    {
        try {
            $siteName = $wsSettings->site_name ?? $mailConfig['from_name'] ?? 'Store';
            $subject = "🛒 New Order #{$order->order_no} - ₹" . number_format($order->total, 0);
            $companyEmail = $mailConfig['from_address'];
            
            $html = $this->buildOrderEmailHtml($order, $wsSettings, 'admin');
            
            \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($adminEmail, $subject, $html, $siteName, $companyEmail, $order, $pdfContent) {
                $message->to($adminEmail)
                        ->subject($subject)
                        ->html($html);
                
                if ($companyEmail) {
                    $message->from($companyEmail, $siteName);
                    $message->replyTo($companyEmail, $siteName);
                }
                
                // Attach PDF Invoice
                if ($pdfContent) {
                    $message->attachData($pdfContent, "Invoice_{$order->order_no}.pdf", ['mime' => 'application/pdf']);
                }
            });
            
            \Log::info("✅ Order alert email SENT to admin: {$adminEmail} for order {$order->order_no}");
            
        } catch (\Exception $e) {
            \Log::error("❌ Failed to send admin email: " . $e->getMessage());
        }
    }

    /**
     * Build order email HTML
     */
    protected function buildOrderEmailHtml($order, $wsSettings, $type = 'customer')
    {
        $siteName = $wsSettings->site_name ?? 'Store';
        $isAdmin = $type === 'admin';
        
        $itemsHtml = '';
        foreach ($order->items as $item) {
            $itemsHtml .= "
                <tr>
                    <td style='padding: 12px; border-bottom: 1px solid #eee;'>{$item->product_name}" . ($item->variation_name ? " <small style='color:#666'>({$item->variation_name})</small>" : "") . "</td>
                    <td style='padding: 12px; border-bottom: 1px solid #eee; text-align: center;'>{$item->quantity}</td>
                    <td style='padding: 12px; border-bottom: 1px solid #eee; text-align: right;'>₹" . number_format($item->unit_price, 2) . "</td>
                    <td style='padding: 12px; border-bottom: 1px solid #eee; text-align: right;'>₹" . number_format($item->total, 2) . "</td>
                </tr>";
        }

        $greeting = $isAdmin 
            ? "You have received a new order!" 
            : "Thank you for your order, {$order->customer_name}!";
        
        $intro = $isAdmin
            ? "A new order has been placed on your store."
            : "Your order has been placed successfully. Here are the details:";

        $adminUrl = url('/admin/website/orders/' . $order->id);
        $adminLink = $isAdmin 
            ? "<p style='margin: 20px 0;'><a href='{$adminUrl}' style='display: inline-block; padding: 12px 24px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 6px;'>View Order in Admin</a></p>" 
            : "";

        $paymentBadge = $order->payment_method === 'cod' 
            ? "<span style='background:#fef3c7;color:#92400e;padding:4px 10px;border-radius:4px;font-size:12px;'>Cash on Delivery</span>"
            : "<span style='background:#dbeafe;color:#1e40af;padding:4px 10px;border-radius:4px;font-size:12px;'>Online Payment</span>";

        $html = "
        <!DOCTYPE html>
        <html>
        <head><meta charset='UTF-8'></head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: linear-gradient(135deg, #4f46e5, #7c3aed); padding: 30px; text-align: center; border-radius: 12px 12px 0 0;'>
                <h1 style='color: #fff; margin: 0; font-size: 24px;'>{$siteName}</h1>
            </div>
            
            <div style='background: #fff; padding: 30px; border: 1px solid #e5e7eb; border-top: none;'>
                <h2 style='color: #111; margin-top: 0;'>{$greeting}</h2>
                <p style='color: #666;'>{$intro}</p>
                
                <div style='background: #f8fafc; border-radius: 8px; padding: 20px; margin: 20px 0;'>
                    <table style='width: 100%; border-collapse: collapse;'>
                        <tr>
                            <td style='padding: 8px 0;'><strong>Order Number:</strong></td>
                            <td style='padding: 8px 0; text-align: right;'><strong style='color: #4f46e5;'>{$order->order_no}</strong></td>
                        </tr>
                        <tr>
                            <td style='padding: 8px 0;'><strong>Order Date:</strong></td>
                            <td style='padding: 8px 0; text-align: right;'>" . $order->created_at->format('d M Y, h:i A') . "</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px 0;'><strong>Payment:</strong></td>
                            <td style='padding: 8px 0; text-align: right;'>{$paymentBadge}</td>
                        </tr>
                    </table>
                </div>
                
                <h3 style='border-bottom: 2px solid #4f46e5; padding-bottom: 10px; color: #111;'>Order Items</h3>
                <table style='width: 100%; border-collapse: collapse;'>
                    <thead>
                        <tr style='background: #f8fafc;'>
                            <th style='padding: 12px; text-align: left;'>Product</th>
                            <th style='padding: 12px; text-align: center;'>Qty</th>
                            <th style='padding: 12px; text-align: right;'>Price</th>
                            <th style='padding: 12px; text-align: right;'>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        {$itemsHtml}
                    </tbody>
                </table>
                
                <table style='width: 100%; margin-top: 20px;'>
                    <tr><td style='padding: 8px 0;'>Subtotal:</td><td style='text-align: right;'>₹" . number_format($order->subtotal, 2) . "</td></tr>
                    <tr><td style='padding: 8px 0;'>Shipping:</td><td style='text-align: right;'>₹" . number_format($order->shipping_fee, 2) . "</td></tr>
                    " . ($order->cod_fee > 0 ? "<tr><td style='padding: 8px 0;'>COD Fee:</td><td style='text-align: right;'>₹" . number_format($order->cod_fee, 2) . "</td></tr>" : "") . "
                    <tr style='font-size: 18px; font-weight: bold; border-top: 2px solid #4f46e5;'>
                        <td style='padding: 12px 0;'>Total:</td>
                        <td style='text-align: right; color: #4f46e5;'>₹" . number_format($order->total, 2) . "</td>
                    </tr>
                </table>
                
                <h3 style='border-bottom: 2px solid #4f46e5; padding-bottom: 10px; margin-top: 30px; color: #111;'>Shipping Address</h3>
                <div style='background: #f8fafc; border-radius: 8px; padding: 15px;'>
                    <p style='margin: 0;'>
                        <strong>{$order->customer_name}</strong><br>
                        {$order->shipping_address}<br>
                        {$order->shipping_city}, {$order->shipping_state} - {$order->shipping_pincode}<br>
                        Phone: {$order->customer_phone}
                    </p>
                </div>

                {$adminLink}
            </div>
            
            <div style='background: #f8fafc; padding: 20px; text-align: center; border-radius: 0 0 12px 12px; border: 1px solid #e5e7eb; border-top: none;'>
                <p style='margin: 0; color: #666; font-size: 14px;'>
                    " . ($isAdmin ? "This is an automated notification from your store." : "If you have any questions, please contact us.") . "
                </p>
                <p style='margin: 10px 0 0; color: #999; font-size: 12px;'>© " . date('Y') . " {$siteName}. All rights reserved.</p>
            </div>
        </body>
        </html>";

        return $html;
    }
}
