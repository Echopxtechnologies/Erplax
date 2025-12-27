<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Ecommerce\Models\Product;

// Cart API
Route::post('/cart/add', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['requireLogin' => true]);
    }
    
    $productId = $request->product_id;
    $variationId = $request->variation_id;
    $qty = (int) ($request->qty ?? 1);
    
    $product = Product::find($productId);
    if (!$product) {
        return response()->json(['success' => false, 'message' => 'Product not found']);
    }
    
    $cartKey = 'cart_' . Auth::id();
    $cart = session($cartKey, []);
    
    // Create unique key for product + variation
    $itemKey = $variationId ? "{$productId}_{$variationId}" : (string) $productId;
    
    if (isset($cart[$itemKey])) {
        $cart[$itemKey]['qty'] += $qty;
    } else {
        $cart[$itemKey] = [
            'product_id' => $productId,
            'variation_id' => $variationId,
            'qty' => $qty
        ];
    }
    
    session([$cartKey => $cart]);
    
    // Calculate total cart count
    $cartCount = 0;
    foreach ($cart as $item) {
        $cartCount += $item['qty'];
    }
    
    return response()->json([
        'success' => true,
        'message' => $product->name . ' added to cart!',
        'cartCount' => $cartCount,
        'totalInCart' => $cart[$itemKey]['qty'],
        'addedQty' => $qty
    ]);
});

// Wishlist API
Route::post('/wishlist/toggle', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['requireLogin' => true]);
    }
    
    $productId = $request->product_id;
    
    $wishKey = 'wishlist_' . Auth::id();
    $wishlist = session($wishKey, []);
    
    $inWishlist = false;
    if (in_array($productId, $wishlist)) {
        // Remove
        $wishlist = array_diff($wishlist, [$productId]);
    } else {
        // Add
        $wishlist[] = $productId;
        $inWishlist = true;
    }
    
    session([$wishKey => array_values($wishlist)]);
    
    return response()->json([
        'success' => true,
        'inWishlist' => $inWishlist,
        'wishlistCount' => count($wishlist)
    ]);
});
