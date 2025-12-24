<?php

namespace Modules\Website\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Website\Models\Ecommerce\Product;

class Wishlist extends Component
{
    public $wishlistItems = [];
    public $wishlistCount = 0;
    public $isLoggedIn = false;

    public function mount()
    {
        $this->isLoggedIn = Auth::check();
        $this->loadWishlist();
    }
    
    protected function getWishlistSessionKey()
    {
        if (!Auth::check()) {
            return null;
        }
        return 'wishlist_' . Auth::id();
    }
    
    protected function getCartSessionKey()
    {
        if (!Auth::check()) {
            return null;
        }
        return 'cart_' . Auth::id();
    }

    public function loadWishlist()
    {
        $wishlistKey = $this->getWishlistSessionKey();
        
        if (!$wishlistKey) {
            $this->wishlistItems = [];
            $this->wishlistCount = 0;
            return;
        }
        
        $wishlist = session($wishlistKey, []);
        $this->wishlistItems = [];
        $this->wishlistCount = count($wishlist);

        foreach ($wishlist as $id) {
            $product = Product::find($id);
            if ($product) {
                $this->wishlistItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->getPrimaryImageUrl(),
                    'price' => $product->sale_price,
                    'mrp' => $product->mrp,
                    'in_stock' => $product->getCurrentStock() > 0,
                    'discount' => $product->getDiscountPercent(),
                    'has_variants' => $product->has_variants,
                ];
            }
        }
    }

    public function removeFromWishlist($productId)
    {
        $wishlistKey = $this->getWishlistSessionKey();
        if (!$wishlistKey) return;
        
        $wishlist = session($wishlistKey, []);
        $wishlist = array_values(array_diff($wishlist, [$productId]));
        session([$wishlistKey => $wishlist]);
        
        $this->loadWishlist();
        $this->dispatch('wishlist-count-updated', count: count($wishlist));
        $this->dispatch('show-notification', message: 'Removed from wishlist', type: 'info');
    }

    public function moveToCart($productId)
    {
        $cartKey = $this->getCartSessionKey();
        $wishlistKey = $this->getWishlistSessionKey();
        if (!$cartKey || !$wishlistKey) return;
        
        $product = Product::find($productId);
        if (!$product) return;
        
        // If product has variants, redirect to product page
        if ($product->has_variants) {
            $this->dispatch('show-notification', message: 'Please select options on product page', type: 'info');
            return $this->redirect(route('website.product', $productId));
        }

        // Add to cart
        $cart = session($cartKey, []);
        $cartItemKey = (string) $productId;
        
        if (isset($cart[$cartItemKey])) {
            $cart[$cartItemKey]['qty']++;
        } else {
            $cart[$cartItemKey] = [
                'product_id' => $productId,
                'variation_id' => null,
                'qty' => 1
            ];
        }
        session([$cartKey => $cart]);

        // Remove from wishlist
        $wishlist = session($wishlistKey, []);
        $wishlist = array_values(array_diff($wishlist, [$productId]));
        session([$wishlistKey => $wishlist]);

        $this->loadWishlist();
        
        $cartCount = 0;
        foreach ($cart as $item) {
            $cartCount += $item['qty'];
        }
        
        $this->dispatch('cart-count-updated', count: $cartCount);
        $this->dispatch('wishlist-count-updated', count: count($wishlist));
        $this->dispatch('show-notification', message: 'Moved to cart!', type: 'success');
    }

    public function render()
    {
        return view('website::livewire.wishlist');
    }
}
