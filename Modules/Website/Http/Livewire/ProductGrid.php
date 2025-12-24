<?php

namespace Modules\Website\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Modules\Website\Models\Ecommerce\Product;
use Modules\Website\Models\Ecommerce\ProductCategory;

class ProductGrid extends Component
{
    use WithPagination;

    public $categoryId = '';
    public $search = '';
    public $sortBy = 'newest';
    public $perPage = 12;
    public $wishlist = [];
    public $isLoggedIn = false;

    protected $queryString = [
        'categoryId' => ['except' => '', 'as' => 'category'],
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'newest', 'as' => 'sort'],
    ];

    public function mount()
    {
        $this->isLoggedIn = Auth::check();
        $this->wishlist = $this->getWishlist();
    }
    
    protected function getWishlist()
    {
        if (!Auth::check()) {
            return [];
        }
        return session('wishlist_' . Auth::id(), []);
    }

    #[On('searchProducts')]
    public function searchProducts($search)
    {
        $this->search = $search;
        $this->categoryId = '';
        $this->resetPage();
    }

    public function setCategory($id)
    {
        $this->categoryId = $id;
        $this->search = '';
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['categoryId', 'search', 'sortBy']);
        $this->resetPage();
    }

    public function loadMore()
    {
        $this->perPage += 12;
    }

    public function addToCart($productId)
    {
        // Must be logged in
        if (!Auth::check()) {
            $this->dispatch('show-notification', message: 'Please login to add to cart', type: 'info');
            return $this->redirect(route('website.login'));
        }
        
        $product = Product::find($productId);
        if (!$product) return;

        // If product has variants, redirect to product page
        if ($product->has_variants) {
            $this->dispatch('show-notification', message: 'Please select options', type: 'info');
            return $this->redirect(route('website.product', $productId));
        }

        // Check stock
        if (!$product->isInStock()) {
            $this->dispatch('show-notification', message: 'Out of stock', type: 'error');
            return;
        }

        $cartKey = 'cart_' . Auth::id();
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

        $count = 0;
        foreach ($cart as $item) $count += $item['qty'];
        
        // Dispatch browser events
        $this->dispatch('cart-count-updated', count: $count);
        $this->dispatch('show-notification', message: 'Added to cart!', type: 'success');
    }

    public function toggleWishlist($productId)
    {
        // Must be logged in
        if (!Auth::check()) {
            $this->dispatch('show-notification', message: 'Please login to add to wishlist', type: 'info');
            return $this->redirect(route('website.login'));
        }
        
        $wishlistKey = 'wishlist_' . Auth::id();
        $wishlist = session($wishlistKey, []);
        
        if (in_array($productId, $wishlist)) {
            $wishlist = array_values(array_diff($wishlist, [$productId]));
            $msg = 'Removed from wishlist';
            $type = 'info';
        } else {
            $wishlist[] = $productId;
            $msg = 'Added to wishlist!';
            $type = 'success';
        }
        
        session([$wishlistKey => $wishlist]);
        $this->wishlist = $wishlist;
        
        $this->dispatch('wishlist-count-updated', count: count($wishlist));
        $this->dispatch('show-notification', message: $msg, type: $type);
    }

    public function render()
    {
        // Refresh wishlist on each render
        $this->wishlist = $this->getWishlist();
        
        $query = Product::where('is_active', 1)->where('can_be_sold', 1);

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        switch ($this->sortBy) {
            case 'price_low':
                $query->orderBy('sale_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('sale_price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        return view('website::livewire.product-grid', [
            'products' => $query->paginate($this->perPage),
            'categories' => ProductCategory::where('is_active', 1)->orderBy('sort_order')->get(),
        ]);
    }
}
