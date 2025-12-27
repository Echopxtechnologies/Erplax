<?php

namespace Modules\Ecommerce\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Ecommerce\Models\Product;
use Modules\Ecommerce\Models\ProductVariation;
use Modules\Ecommerce\Models\WebsiteSetting;

class Cart extends Component
{
    public $cartItems = [];
    public $cartTotal = 0;
    public $cartCount = 0;
    
    // Shipping & totals
    public $shippingFee = 0;
    public $freeShippingMin = 0;
    public $amountForFreeShipping = 0;
    public $grandTotal = 0;
    public $deliveryDays = '';
    
    // Login state
    public $isLoggedIn = false;

    public function mount()
    {
        $this->isLoggedIn = Auth::check();
        $this->loadCart();
    }
    
    protected function getCartSessionKey()
    {
        if (!Auth::check()) {
            return null;
        }
        return 'cart_' . Auth::id();
    }

    public function loadCart()
    {
        $cartKey = $this->getCartSessionKey();
        
        // If not logged in, show empty cart
        if (!$cartKey) {
            $this->cartItems = [];
            $this->cartTotal = 0;
            $this->cartCount = 0;
            $this->grandTotal = 0;
            return;
        }
        
        $cart = session($cartKey, []);
        $this->cartItems = [];
        $this->cartTotal = 0;
        $this->cartCount = 0;

        foreach ($cart as $key => $item) {
            $product = Product::find($item['product_id']);
            if (!$product) continue;

            $variation = null;
            $price = $product->sale_price;
            $mrp = $product->mrp;
            $image = $product->getPrimaryImageUrl();
            $name = $product->name;
            $variationName = null;
            $stock = $product->getCurrentStock();

            // Check if this is a variation
            if (!empty($item['variation_id'])) {
                $variation = ProductVariation::find($item['variation_id']);
                if ($variation) {
                    $price = $variation->getEffectiveSalePrice();
                    $mrp = $variation->getEffectiveMrp();
                    $image = $variation->getImageUrl() ?: $image;
                    $variationName = $variation->getDisplayName();
                    $stock = $variation->getCurrentStock();
                }
            }

            $qty = min($item['qty'], max(1, floor($stock))); // Limit to available stock

            $this->cartItems[] = [
                'key' => $key,
                'product_id' => $product->id,
                'variation_id' => $item['variation_id'] ?? null,
                'name' => $name,
                'variation_name' => $variationName,
                'image' => $image,
                'price' => $price,
                'mrp' => $mrp,
                'qty' => $qty,
                'stock' => $stock,
                'subtotal' => $price * $qty,
                'unit' => $product->getUnitName(),
                'allows_decimal' => $product->allowsDecimal(),
            ];
            $this->cartTotal += $price * $qty;
            $this->cartCount += $qty;
        }
        
        // Calculate shipping from settings
        $this->calculateShipping();
    }
    
    protected function calculateShipping()
    {
        $settings = WebsiteSetting::instance();
        
        $this->freeShippingMin = (float) ($settings->free_shipping_min ?? 0);
        $this->deliveryDays = $settings->delivery_days ?? '3-5 business days';
        
        // Check if qualifies for free shipping
        if ($this->freeShippingMin > 0 && $this->cartTotal >= $this->freeShippingMin) {
            $this->shippingFee = 0;
            $this->amountForFreeShipping = 0;
        } else {
            $this->shippingFee = (float) ($settings->shipping_fee ?? 0);
            $this->amountForFreeShipping = $this->freeShippingMin > 0 
                ? max(0, $this->freeShippingMin - $this->cartTotal) 
                : 0;
        }
        
        $this->grandTotal = $this->cartTotal + $this->shippingFee;
    }

    public function incrementQty($key)
    {
        $cartKey = $this->getCartSessionKey();
        if (!$cartKey) return;
        
        $cart = session($cartKey, []);
        if (isset($cart[$key])) {
            $cart[$key]['qty']++;
            session([$cartKey => $cart]);
        }
        $this->loadCart();
        $this->dispatch('cart-count-updated', count: $this->cartCount);
    }

    public function decrementQty($key)
    {
        $cartKey = $this->getCartSessionKey();
        if (!$cartKey) return;
        
        $cart = session($cartKey, []);
        if (isset($cart[$key])) {
            if ($cart[$key]['qty'] > 1) {
                $cart[$key]['qty']--;
                session([$cartKey => $cart]);
                $this->loadCart();
                $this->dispatch('cart-count-updated', count: $this->cartCount);
            } else {
                $this->removeItem($key);
            }
        }
    }

    public function removeItem($key)
    {
        $cartKey = $this->getCartSessionKey();
        if (!$cartKey) return;
        
        $cart = session($cartKey, []);
        unset($cart[$key]);
        session([$cartKey => $cart]);
        
        $this->loadCart();
        $this->dispatch('cart-count-updated', count: $this->cartCount);
        $this->dispatch('show-notification', message: 'Item removed', type: 'info');
    }

    public function render()
    {
        return view('ecommerce::livewire.cart');
    }
}
