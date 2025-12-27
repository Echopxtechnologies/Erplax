<?php

namespace Modules\Ecommerce\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Modules\Ecommerce\Models\Product;

class AddToCartButton extends Component
{
    public $productId;
    public $qty = 1;
    public $style = 'default';
    public $loading = false;

    public function mount($productId, $style = 'default')
    {
        $this->productId = $productId;
        $this->style = $style;
    }

    public function addToCart()
    {
        // Must be logged in
        if (!Auth::check()) {
            $this->dispatch('show-notification', message: 'Please login to add to cart', type: 'info');
            return $this->redirect(route('ecommerce.login'));
        }
        
        $product = Product::find($this->productId);
        if (!$product) return;

        $cartKey = 'cart_' . Auth::id();
        $cart = session($cartKey, []);
        $cartItemKey = (string) $this->productId;
        
        if (isset($cart[$cartItemKey])) {
            $cart[$cartItemKey]['qty'] += $this->qty;
        } else {
            $cart[$cartItemKey] = [
                'product_id' => $this->productId,
                'variation_id' => null,
                'qty' => $this->qty
            ];
        }

        session([$cartKey => $cart]);
        
        $cartCount = 0;
        foreach ($cart as $item) {
            $cartCount += $item['qty'];
        }
        
        $this->dispatch('cart-count-updated', count: $cartCount);
        $this->dispatch('show-notification', 
            message: $product->name . ' added to cart!',
            type: 'success'
        );
        
        $this->qty = 1;
    }

    public function increment()
    {
        $this->qty++;
    }

    public function decrement()
    {
        if ($this->qty > 1) {
            $this->qty--;
        }
    }

    public function render()
    {
        return view('ecommerce::livewire.add-to-cart-button');
    }
}
