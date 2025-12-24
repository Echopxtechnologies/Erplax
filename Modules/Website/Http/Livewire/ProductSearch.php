<?php

namespace Modules\Website\Http\Livewire;

use Livewire\Component;
use Modules\Website\Models\Ecommerce\Product;
use Modules\Website\Models\Ecommerce\ProductCategory;

class ProductSearch extends Component
{
    public $query = '';
    public $suggestions = [];
    public $searchTerms = [];
    public $showSuggestions = false;

    public function updatedQuery()
    {
        $this->search();
    }

    public function search()
    {
        if (strlen($this->query) < 2) {
            $this->suggestions = [];
            $this->searchTerms = [];
            $this->showSuggestions = false;
            return;
        }

        // Get matching products (with images)
        $products = Product::where('is_active', 1)
            ->where('can_be_sold', 1)
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->query . '%')
                  ->orWhere('sku', 'like', '%' . $this->query . '%');
            })
            ->with('category')
            ->limit(4)
            ->get();

        $this->suggestions = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->getPrimaryImageUrl(),
                'category' => $product->category->name ?? '',
            ];
        })->toArray();

        // Get search term suggestions from product names
        $terms = Product::where('is_active', 1)
            ->where('can_be_sold', 1)
            ->where('name', 'like', '%' . $this->query . '%')
            ->select('name')
            ->distinct()
            ->limit(6)
            ->pluck('name')
            ->map(function ($name) {
                return strtolower($name);
            })
            ->unique()
            ->take(6)
            ->toArray();

        $this->searchTerms = $terms;
        $this->showSuggestions = true;
    }

    public function selectSuggestion($productId)
    {
        $this->showSuggestions = false;
        return redirect()->route('website.product', $productId);
    }

    public function searchTerm($term)
    {
        $this->query = $term;
        return $this->submitSearch();
    }

    public function submitSearch()
    {
        if (empty($this->query)) {
            return;
        }
        
        $this->showSuggestions = false;
        $this->dispatch('searchProducts', search: $this->query);
    }

    public function closeSuggestions()
    {
        $this->showSuggestions = false;
    }

    public function highlightMatch($text, $query)
    {
        if (empty($query)) return $text;
        return preg_replace('/(' . preg_quote($query, '/') . ')/i', '<strong>$1</strong>', $text);
    }

    public function render()
    {
        return view('website::livewire.product-search');
    }
}
