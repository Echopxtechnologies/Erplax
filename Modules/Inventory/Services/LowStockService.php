<?php

namespace Modules\Inventory\Services;

use Illuminate\Support\Collection;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductVariation;
use Modules\Inventory\Models\StockLevel;

class LowStockService
{
    /**
     * Check single product/variation for low stock
     */
    public static function checkAndNotifyProduct(int $productId, ?int $variationId = null): ?array
    {
        $product = Product::find($productId);
        if (!$product || !$product->track_inventory || $product->min_stock_level <= 0) {
            return null;
        }
        
        $stockQuery = StockLevel::where('product_id', $productId);
        if ($variationId) {
            $stockQuery->where('variation_id', $variationId);
        } elseif (!$product->has_variants) {
            $stockQuery->whereNull('variation_id');
        }
        $currentStock = (float) $stockQuery->sum('qty');
        
        if ($currentStock > $product->min_stock_level) {
            return null;
        }
        
        return [
            'product_id' => $productId,
            'variation_id' => $variationId,
            'name' => $product->name,
            'sku' => $product->sku,
            'current_stock' => $currentStock,
            'min_stock_level' => $product->min_stock_level,
            'shortage' => $product->min_stock_level - $currentStock,
        ];
    }

    /**
     * Get all low stock products
     */
    public static function getLowStockProducts(int $limit = 50): Collection
    {
        $products = Product::where('is_active', true)
            ->where('track_inventory', true)
            ->where('min_stock_level', '>', 0)
            ->where('has_variants', false)
            ->get(['id', 'name', 'sku', 'min_stock_level', 'unit_id']);
        
        $result = collect();
        
        foreach ($products as $product) {
            $currentStock = (float) (StockLevel::where('product_id', $product->id)
                ->whereNull('variation_id')
                ->sum('qty') ?? 0);
            
            if ($currentStock <= $product->min_stock_level) {
                $result->push((object)[
                    'id' => $product->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'variation_name' => null,
                    'min_stock_level' => $product->min_stock_level,
                    'current_stock' => $currentStock,
                    'shortage' => $product->min_stock_level - $currentStock,
                    'type' => 'product',
                    'unit_id' => $product->unit_id,
                ]);
            }
        }
        
        return $result->sortByDesc('shortage')->take($limit)->values();
    }

    /**
     * Get all low stock variations
     */
    public static function getLowStockVariations(int $limit = 50): Collection
    {
        $products = Product::where('is_active', true)
            ->where('track_inventory', true)
            ->where('min_stock_level', '>', 0)
            ->where('has_variants', true)
            ->with(['variations' => function($q) {
                $q->where('is_active', true);
            }])
            ->get();
        
        $result = collect();
        
        foreach ($products as $product) {
            foreach ($product->variations as $variation) {
                $currentStock = (float) (StockLevel::where('product_id', $product->id)
                    ->where('variation_id', $variation->id)
                    ->sum('qty') ?? 0);
                
                if ($currentStock <= $product->min_stock_level) {
                    $result->push((object)[
                        'id' => $variation->id,
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'sku' => $variation->sku,
                        'variation_name' => $variation->variation_name,
                        'min_stock_level' => $product->min_stock_level,
                        'current_stock' => $currentStock,
                        'shortage' => $product->min_stock_level - $currentStock,
                        'type' => 'variation',
                        'unit_id' => $product->unit_id,
                    ]);
                }
            }
        }
        
        return $result->sortByDesc('shortage')->take($limit)->values();
    }

    /**
     * Get all low stock items
     */
    public static function getAllLowStockItems(int $limit = 50): Collection
    {
        $products = self::getLowStockProducts(100);
        $variations = self::getLowStockVariations(100);
        
        return $products->concat($variations)
            ->sortByDesc('shortage')
            ->take($limit)
            ->values();
    }

    /**
     * Get low stock count
     */
    public static function getLowStockCount(): int
    {
        return self::getAllLowStockItems(1000)->count();
    }

    /**
     * Check and return all low stock items
     */
    public static function checkAndNotify(?int $userId = null): array
    {
        return self::getAllLowStockItems(100)->toArray();
    }

    /**
     * Get out of stock items
     */
    public static function getOutOfStockItems(int $limit = 50): Collection
    {
        return self::getAllLowStockItems(200)
            ->filter(fn($item) => $item->current_stock <= 0)
            ->take($limit)
            ->values();
    }

    /**
     * Get stock status summary
     */
    public static function getStockStatusSummary(): array
    {
        $allLowStock = self::getAllLowStockItems(1000);
        
        return [
            'total_low_stock' => $allLowStock->count(),
            'out_of_stock' => $allLowStock->filter(fn($i) => $i->current_stock <= 0)->count(),
            'critical' => $allLowStock->filter(fn($i) => $i->current_stock > 0 && $i->current_stock <= ($i->min_stock_level * 0.5))->count(),
            'warning' => $allLowStock->filter(fn($i) => $i->current_stock > ($i->min_stock_level * 0.5))->count(),
        ];
    }
}
