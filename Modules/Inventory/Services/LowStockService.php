<?php

namespace Modules\Inventory\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductVariation;
use Modules\Inventory\Models\StockLevel;

class LowStockService
{
    /**
     * Check single product/variation and create notification IMMEDIATELY if low stock
     * Call this after EVERY stock movement (deliver, adjust, transfer OUT)
     */
    public static function checkAndNotifyProduct(int $productId, ?int $variationId = null): ?array
    {
        $product = Product::find($productId);
        if (!$product || !$product->track_inventory || $product->min_stock_level <= 0) {
            return null;
        }
        
        // Calculate current stock
        $stockQuery = StockLevel::where('product_id', $productId);
        if ($variationId) {
            $stockQuery->where('variation_id', $variationId);
        } elseif (!$product->has_variants) {
            $stockQuery->whereNull('variation_id');
        }
        $currentStock = (float) $stockQuery->sum('qty');
        
        // Check if below minimum
        if ($currentStock > $product->min_stock_level) {
            return null; // Stock is OK
        }
        
        // Get item details
        $item = (object)[
            'id' => $variationId ?? $productId,
            'product_id' => $productId,
            'name' => $product->name,
            'sku' => $product->sku,
            'variation_name' => null,
            'min_stock_level' => $product->min_stock_level,
            'current_stock' => $currentStock,
            'shortage' => $product->min_stock_level - $currentStock,
            'type' => 'product',
        ];
        
        if ($variationId) {
            $variation = ProductVariation::find($variationId);
            if ($variation) {
                $item->sku = $variation->sku;
                $item->variation_name = $variation->variation_name;
                $item->type = 'variation';
            }
        }
        
        // Create notification immediately
        return self::createImmediateNotification($item);
    }

    /**
     * Create notification immediately (1 hour spam prevention)
     */
    private static function createImmediateNotification($item): ?array
    {
        // Get admin users
        $adminUsers = [];
        if (DB::getSchemaBuilder()->hasTable('admins')) {
            $adminUsers = DB::table('admins')->pluck('id')->toArray();
        }
        
        // Also notify current user if logged in
        if (auth()->check()) {
            $currentUserId = auth()->id();
            if (!in_array($currentUserId, $adminUsers)) {
                $adminUsers[] = $currentUserId;
            }
        }
        
        if (empty($adminUsers)) {
            return null;
        }
        
        $notifications = [];
        $url = $item->type === 'variation' 
            ? '/admin/inventory/products/' . $item->product_id 
            : '/admin/inventory/products/' . $item->id;
        
        foreach ($adminUsers as $adminId) {
            // Check if same notification exists in last 1 hour (prevent spam)
            $exists = DB::table('notifications')
                ->where('user_id', $adminId)
                ->where('type', 'low_stock')
                ->where('created_at', '>=', now()->subHour())
                ->where('message', 'LIKE', '%' . $item->sku . '%')
                ->exists();
            
            if (!$exists) {
                $title = $item->current_stock <= 0 ? '🚨 OUT OF STOCK!' : '⚠️ Low Stock Alert';
                
                $notificationId = DB::table('notifications')->insertGetId([
                    'user_id' => $adminId,
                    'from_user_id' => null,
                    'title' => $title,
                    'message' => self::buildNotificationMessage($item),
                    'type' => 'low_stock',
                    'url' => $url,
                    'created_at' => now(),
                ]);
                
                $notifications[] = $notificationId;
            }
        }
        
        return empty($notifications) ? null : [
            'item' => $item,
            'notification_ids' => $notifications,
        ];
    }

    /**
     * Get all low stock products (stock <= min_stock_level)
     * Returns Support Collection of stdClass objects
     */
    public static function getLowStockProducts(int $limit = 50): Collection
    {
        $products = Product::where('is_active', true)
            ->where('track_inventory', true)
            ->where('min_stock_level', '>', 0)
            ->where('has_variants', false)
            ->get(['id', 'name', 'sku', 'min_stock_level', 'unit_id']);
        
        // Convert to stdClass objects in a Support Collection
        $result = collect();
        
        foreach ($products as $product) {
            $currentStock = StockLevel::where('product_id', $product->id)
                ->whereNull('variation_id')
                ->sum('qty') ?? 0;
            
            if ((float) $currentStock <= $product->min_stock_level) {
                $result->push((object)[
                    'id' => $product->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'variation_name' => null,
                    'min_stock_level' => $product->min_stock_level,
                    'current_stock' => (float) $currentStock,
                    'shortage' => $product->min_stock_level - (float) $currentStock,
                    'type' => 'product',
                    'unit_id' => $product->unit_id,
                ]);
            }
        }
        
        return $result->sortByDesc('shortage')->take($limit)->values();
    }

    /**
     * Get all low stock variations
     * Returns Support Collection of stdClass objects
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
                $currentStock = StockLevel::where('product_id', $product->id)
                    ->where('variation_id', $variation->id)
                    ->sum('qty') ?? 0;
                
                if ((float) $currentStock <= $product->min_stock_level) {
                    $result->push((object)[
                        'id' => $variation->id,
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'sku' => $variation->sku,
                        'variation_name' => $variation->variation_name,
                        'min_stock_level' => $product->min_stock_level,
                        'current_stock' => (float) $currentStock,
                        'shortage' => $product->min_stock_level - (float) $currentStock,
                        'type' => 'variation',
                        'unit_id' => $product->unit_id,
                    ]);
                }
            }
        }
        
        return $result->sortByDesc('shortage')->take($limit)->values();
    }

    /**
     * Get all low stock items (products + variations)
     * Returns Support Collection of stdClass objects
     */
    public static function getAllLowStockItems(int $limit = 50): Collection
    {
        $products = self::getLowStockProducts(100);
        $variations = self::getLowStockVariations(100);
        
        // Both are now Support Collections, merge works correctly
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
     * Check and create notifications for ALL low stock items (bulk - for manual trigger)
     */
    public static function checkAndNotify(?int $userId = null): array
    {
        $lowStockItems = self::getAllLowStockItems(100);
        $notifications = [];
        
        if ($lowStockItems->isEmpty()) {
            return $notifications;
        }
        
        $adminUsers = [];
        if ($userId) {
            $adminUsers = [$userId];
        } else {
            if (DB::getSchemaBuilder()->hasTable('admins')) {
                $adminUsers = DB::table('admins')->pluck('id')->toArray();
            }
        }
        
        if (empty($adminUsers)) {
            return $notifications;
        }
        
        foreach ($lowStockItems as $item) {
            foreach ($adminUsers as $adminId) {
                $exists = DB::table('notifications')
                    ->where('user_id', $adminId)
                    ->where('type', 'low_stock')
                    ->where('created_at', '>=', now()->subDay())
                    ->where('message', 'LIKE', '%' . $item->sku . '%')
                    ->exists();
                
                if (!$exists) {
                    $url = $item->type === 'variation' 
                        ? '/admin/inventory/products/' . $item->product_id 
                        : '/admin/inventory/products/' . $item->id;
                    
                    $notificationId = DB::table('notifications')->insertGetId([
                        'user_id' => $adminId,
                        'from_user_id' => null,
                        'title' => self::buildNotificationTitle($item),
                        'message' => self::buildNotificationMessage($item),
                        'type' => 'low_stock',
                        'url' => $url,
                        'created_at' => now(),
                    ]);
                    
                    $notifications[] = [
                        'id' => $notificationId,
                        'item' => $item,
                    ];
                }
            }
        }
        
        return $notifications;
    }

    /**
     * Build notification title
     */
    private static function buildNotificationTitle($item): string
    {
        if ($item->current_stock <= 0) {
            return '🚨 Out of Stock Alert';
        }
        return '⚠️ Low Stock Alert';
    }

    /**
     * Build notification message
     */
    private static function buildNotificationMessage($item): string
    {
        $name = $item->type === 'variation' 
            ? $item->name . ' (' . ($item->variation_name ?? 'Variation') . ')'
            : $item->name;
        
        $stock = number_format($item->current_stock, 0);
        $min = number_format($item->min_stock_level, 0);
        
        if ($item->current_stock <= 0) {
            return "OUT OF STOCK: {$name} [SKU: {$item->sku}] - Current: {$stock}, Min: {$min}";
        }
        
        return "Low Stock: {$name} [SKU: {$item->sku}] - Current: {$stock}, Min: {$min}";
    }

    /**
     * Get critical stock items (stock = 0)
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
