<?php

namespace App\Services\Admin;

use App\Models\Module;
use App\Models\Inventory\Stock;
use App\Models\Inventory\StockMovement;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /*
    |--------------------------------------------------------------------------
    | Module Configuration
    |--------------------------------------------------------------------------
    */

    public static function config(): array
    {
        return [
            'name' => 'Inventory',
            'alias' => 'inventory',
            'description' => 'Inventory & Stock Management - products, brands, categories, units, warehouses, lots, stock',
            'version' => '1.0.0',
            'is_core' => true,
            'sort_order' => 100,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Menu Configuration
    |--------------------------------------------------------------------------
    */

    public static function menu(): array
    {
        return [
            'title' => 'Inventory',
            'icon' => 'box',
            'route' => null,
            'permission' => 'inventory.read',
            'section' => 'operations',
            'sort_order' => 100,
            'children' => [
                ['title' => 'Dashboard', 'route' => 'admin.inventory.dashboard', 'icon' => 'layout-dashboard'],
                ['title' => 'Products', 'route' => 'admin.inventory.products.index', 'icon' => 'package'],
                ['title' => 'Categories', 'route' => 'admin.inventory.categories.index', 'icon' => 'folder'],
                ['title' => 'Brands', 'route' => 'admin.inventory.brands.index', 'icon' => 'award'],
                ['title' => 'Units', 'route' => 'admin.inventory.units.index', 'icon' => 'ruler'],
                ['title' => 'Warehouses', 'route' => 'admin.inventory.warehouses.index', 'icon' => 'warehouse'],
                ['title' => 'Lots/Batches', 'route' => 'admin.inventory.lots.index', 'icon' => 'layers'],
                ['title' => 'Stock Management', 'route' => 'admin.inventory.stock.index', 'icon' => 'package-check'],
                ['title' => 'Stock Movements', 'route' => 'admin.inventory.movements.index', 'icon' => 'truck'],
                ['title' => 'Stock Report', 'route' => 'admin.inventory.report', 'icon' => 'chart-bar'],
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Stock Management Functions
    |--------------------------------------------------------------------------
    */

    public static function addStock(
        int $productId,
        int $warehouseId,
        float $qty,
        string $movementType = 'IN',
        string $referenceType = null,
        int $referenceId = null,
        int $lotId = null,
        string $reason = null,
        string $notes = null
    ): bool {
        return DB::transaction(function () use ($productId, $warehouseId, $qty, $movementType, $referenceType, $referenceId, $lotId, $reason, $notes) {
            
            // Get or create stock record
            $stock = Stock::firstOrCreate(
                [
                    'product_id' => $productId,
                    'warehouse_id' => $warehouseId,
                    'lot_id' => $lotId,
                ],
                ['quantity' => 0, 'reserved_qty' => 0]
            );

            $beforeQty = $stock->quantity;
            $stock->quantity += $qty;
            $stock->save();

            // Create movement record
            StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'lot_id' => $lotId,
                'qty' => $qty,
                'before_qty' => $beforeQty,
                'after_qty' => $stock->quantity,
                'movement_type' => $movementType,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'reason' => $reason,
                'notes' => $notes,
                'created_by' => auth()->guard('admin')->id(),
            ]);

            return true;
        });
    }

    public static function removeStock(
        int $productId,
        int $warehouseId,
        float $qty,
        string $movementType = 'OUT',
        string $referenceType = null,
        int $referenceId = null,
        int $lotId = null,
        string $reason = null,
        string $notes = null
    ): bool {
        return self::addStock(
            $productId,
            $warehouseId,
            -abs($qty),
            $movementType,
            $referenceType,
            $referenceId,
            $lotId,
            $reason,
            $notes
        );
    }

    public static function adjustStock(
        int $productId,
        int $warehouseId,
        float $newQty,
        int $lotId = null,
        string $reason = null,
        string $notes = null
    ): bool {
        $stock = Stock::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->where('lot_id', $lotId)
            ->first();

        $currentQty = $stock ? $stock->quantity : 0;
        $difference = $newQty - $currentQty;

        return self::addStock(
            $productId,
            $warehouseId,
            $difference,
            'ADJUSTMENT',
            'ADJUSTMENT',
            null,
            $lotId,
            $reason ?? 'Stock adjustment',
            $notes
        );
    }

    public static function getStockByProduct(int $productId): array
    {
        return Stock::with(['warehouse', 'lot'])
            ->where('product_id', $productId)
            ->get()
            ->toArray();
    }

    public static function getStockByWarehouse(int $warehouseId): array
    {
        return Stock::with(['product', 'lot'])
            ->where('warehouse_id', $warehouseId)
            ->get()
            ->toArray();
    }

    public static function getLowStockProducts(): array
    {
        return DB::table('products')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->select('products.*', DB::raw('COALESCE(SUM(stocks.quantity), 0) as total_stock'))
            ->groupBy('products.id')
            ->havingRaw('total_stock <= products.min_stock_level')
            ->where('products.is_active', true)
            ->get()
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Registration
    |--------------------------------------------------------------------------
    */

    public static function register(): Module
    {
        $config = self::config();

        return Module::updateOrCreate(
            ['alias' => $config['alias']],
            [
                'name' => $config['name'],
                'description' => $config['description'],
                'version' => $config['version'],
                'is_active' => true,
                'is_installed' => true,
                'is_core' => $config['is_core'],
                'sort_order' => $config['sort_order'],
                'installed_at' => now(),
            ]
        );
    }

    public static function isRegistered(): bool
    {
        return Module::where('alias', self::config()['alias'])->exists();
    }

    public static function getModule(): ?Module
    {
        return Module::where('alias', self::config()['alias'])->first();
    }
}