<?php

namespace Modules\Website\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Modules\Website\Models\Ecommerce\Product;

class WishlistButton extends Component
{
    public $productId;
    public $isInWishlist = false;

    public function mount($productId)
    {
        $this->productId = $productId;
        if (Auth::check()) {
            $wishlist = session('wishlist_' . Auth::id(), []);
            $this->isInWishlist = in_array($this->productId, $wishlist);
        }
    }

    public function toggle()
    {
        // Must be logged in
        if (!Auth::check()) {
            $this->dispatch('show-notification', message: 'Please login to add to wishlist', type: 'info');
            return $this->redirect(route('website.login'));
        }
        
        $wishlistKey = 'wishlist_' . Auth::id();
        $wishlist = session($wishlistKey, []);
        $product = Product::find($this->productId);
        
        if (!$product) return;

        if (in_array($this->productId, $wishlist)) {
            $wishlist = array_values(array_diff($wishlist, [$this->productId]));
            $this->isInWishlist = false;
            $message = 'Removed from wishlist';
            $type = 'info';
        } else {
            $wishlist[] = $this->productId;
            $this->isInWishlist = true;
            $message = 'Added to wishlist!';
            $type = 'success';
        }

        session([$wishlistKey => $wishlist]);
        
        $this->dispatch('wishlist-count-updated', count: count($wishlist));
        $this->dispatch('show-notification', message: $message, type: $type);
    }

    public function render()
    {
        return view('website::livewire.wishlist-button');
    }
}
