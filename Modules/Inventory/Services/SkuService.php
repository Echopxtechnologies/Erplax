<?php

namespace Modules\Inventory\Services;

use Illuminate\Support\Facades\DB;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductVariation;

class SkuService
{
    /**
     * Check if SKU exists in products or variations
     * Returns false if SKU is available, true if already exists
     */
    public static function skuExists(string $sku, ?int $excludeProductId = null, ?int $excludeVariationId = null): bool
    {
        // Check in products table
        $productQuery = Product::where('sku', $sku);
        if ($excludeProductId) {
            $productQuery->where('id', '!=', $excludeProductId);
        }
        if ($productQuery->exists()) {
            return true;
        }
        
        // Check in variations table
        $variationQuery = ProductVariation::where('sku', $sku);
        if ($excludeVariationId) {
            $variationQuery->where('id', '!=', $excludeVariationId);
        }
        if ($variationQuery->exists()) {
            return true;
        }
        
        return false;
    }

    /**
     * Validate SKU for a product (create or update)
     */
    public static function validateProductSku(string $sku, ?int $productId = null): array
    {
        if (empty($sku)) {
            return ['valid' => false, 'message' => 'SKU is required'];
        }
        
        if (strlen($sku) > 100) {
            return ['valid' => false, 'message' => 'SKU must be 100 characters or less'];
        }
        
        if (self::skuExists($sku, $productId, null)) {
            return ['valid' => false, 'message' => 'SKU already exists'];
        }
        
        return ['valid' => true, 'message' => 'SKU is available'];
    }

    /**
     * Validate SKU for a variation
     */
    public static function validateVariationSku(string $sku, ?int $variationId = null): array
    {
        if (empty($sku)) {
            return ['valid' => false, 'message' => 'SKU is required'];
        }
        
        if (strlen($sku) > 100) {
            return ['valid' => false, 'message' => 'SKU must be 100 characters or less'];
        }
        
        if (self::skuExists($sku, null, $variationId)) {
            return ['valid' => false, 'message' => 'SKU already exists'];
        }
        
        return ['valid' => true, 'message' => 'SKU is available'];
    }

    /**
     * Generate unique SKU for product
     */
    public static function generateProductSku(?string $baseName = null, ?string $prefix = null): string
    {
        $prefix = $prefix ?? 'PRD';
        $base = $baseName 
            ? strtoupper(preg_replace('/[^A-Za-z0-9]/', '', substr($baseName, 0, 6)))
            : '';
        
        $counter = 1;
        do {
            $sku = $prefix . '-' . $base . str_pad($counter, 4, '0', STR_PAD_LEFT);
            $counter++;
        } while (self::skuExists($sku) && $counter < 10000);
        
        return $sku;
    }

    /**
     * Generate unique SKU for variation
     */
    public static function generateVariationSku(Product $product, array $attributeValues = [], ?int $index = null): string
    {
        $baseSku = $product->sku;
        
        // Build suffix from attribute values
        $suffix = '';
        if (!empty($attributeValues)) {
            $suffix = '-' . implode('-', array_map(function($val) {
                return strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $val), 0, 3));
            }, $attributeValues));
        } elseif ($index !== null) {
            $suffix = '-V' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);
        }
        
        $sku = $baseSku . $suffix;
        
        // Ensure uniqueness
        $counter = 1;
        $originalSku = $sku;
        while (self::skuExists($sku) && $counter < 100) {
            $sku = $originalSku . '-' . $counter;
            $counter++;
        }
        
        return $sku;
    }

    /**
     * Validate multiple variation SKUs at once
     */
    public static function validateVariationSkus(array $skus, array $excludeVariationIds = []): array
    {
        $results = [];
        $seenSkus = [];
        
        foreach ($skus as $index => $sku) {
            $excludeId = $excludeVariationIds[$index] ?? null;
            
            // Check for duplicates within the batch
            if (in_array($sku, $seenSkus)) {
                $results[$index] = ['valid' => false, 'message' => 'Duplicate SKU in batch'];
                continue;
            }
            $seenSkus[] = $sku;
            
            $results[$index] = self::validateVariationSku($sku, $excludeId);
        }
        
        return $results;
    }

    /**
     * Get all SKUs (for autocomplete/search)
     */
    public static function searchSkus(string $query, int $limit = 20): array
    {
        $productSkus = Product::where('sku', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->pluck('sku')
            ->toArray();
        
        $variationSkus = ProductVariation::where('sku', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->pluck('sku')
            ->toArray();
        
        return array_slice(array_merge($productSkus, $variationSkus), 0, $limit);
    }

    /**
     * Find product or variation by SKU
     * Returns format compatible with BarcodeHelper::findByBarcode
     */
    public static function findBySku(string $sku): ?array
    {
        // Check products first
        $product = Product::with('images', 'unit')->where('sku', $sku)->first();
        if ($product) {
            return [
                'type' => 'product',
                'product' => $product,
                'variation' => null,
                'unit' => null,
            ];
        }
        
        // Check variations
        $variation = ProductVariation::with(['product.images', 'product.unit'])
            ->where('sku', $sku)
            ->first();
        if ($variation) {
            return [
                'type' => 'variation',
                'product' => $variation->product,
                'variation' => $variation,
                'unit' => null,
            ];
        }
        
        return null;
    }
}
