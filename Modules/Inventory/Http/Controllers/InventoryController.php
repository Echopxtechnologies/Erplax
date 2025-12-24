<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Main Inventory Controller - Facade Pattern
 * 
 * This controller delegates all work to specialized controllers.
 * Routes remain unchanged - all method names are preserved.
 * 
 * Structure:
 * - ProductController: Products CRUD, import/export
 * - StockController: Stock movements, transfers, adjustments
 * - WarehouseController: Warehouses & Racks
 * - LotController: Lot/Batch management
 * - SettingsController: Categories, Brands, Units
 * - ReportController: All reports
 */
class InventoryController extends BaseController
{
    protected ProductController $products;
    protected StockController $stock;
    protected WarehouseController $warehouses;
    protected LotController $lots;
    protected SettingsController $settings;
    protected ReportController $reports;

    public function __construct()
    {
        $this->products = new ProductController();
        $this->stock = new StockController();
        $this->warehouses = new WarehouseController();
        $this->lots = new LotController();
        $this->settings = new SettingsController();
        $this->reports = new ReportController();
    }

    // ==================== DASHBOARD ====================
    public function dashboard()
    {
        return $this->products->dashboard();
    }

    // ==================== PRODUCTS ====================
    public function productsIndex()
    {
        return $this->products->index();
    }

    public function productsData(Request $request)
    {
        return $this->products->data($request);
    }

    public function productsCreate()
    {
        return $this->products->create();
    }

    public function productsStore(Request $request)
    {
        return $this->products->store($request);
    }

    public function productsShow($id)
    {
        return $this->products->show($id);
    }

    public function productsEdit($id)
    {
        return $this->products->edit($id);
    }

    public function productsUpdate(Request $request, $id)
    {
        return $this->products->update($request, $id);
    }

    public function productsDeactivate($id)
    {
        return $this->products->deactivate($id);
    }

    public function productsDestroy($id)
    {
        return $this->products->destroy($id);
    }

    // ==================== PRODUCTS AJAX ====================
    public function productsGetUnits($productId)
    {
        return $this->products->getProductUnits($productId);
    }

    public function tagsSearch(Request $request)
    {
        return $this->products->searchTags($request);
    }

    public function getAttributes()
    {
        return $this->products->getAttributes();
    }

    public function getVariations($productId)
    {
        return $this->products->getVariations($productId);
    }

    public function generateVariations($productId)
    {
        return $this->products->generateVariations($productId);
    }

    public function updateVariation(Request $request, $variationId)
    {
        return $this->products->updateVariation($request, $variationId);
    }

    public function deleteVariation($variationId)
    {
        return $this->products->deleteVariation($variationId);
    }

    public function generateVariationBarcode(Request $request, $variationId)
    {
        return $this->products->generateVariationBarcode($request, $variationId);
    }

    public function generateVariationBarcodes($productId)
    {
        return $this->products->generateVariationBarcodes($productId);
    }

    // ==================== WAREHOUSES ====================
    public function warehousesIndex()
    {
        return $this->warehouses->index();
    }

    public function warehousesData(Request $request)
    {
        return $this->warehouses->data($request);
    }

    public function warehousesCreate()
    {
        return $this->warehouses->create();
    }

    public function warehousesStore(Request $request)
    {
        return $this->warehouses->store($request);
    }

    public function warehousesEdit($id)
    {
        return $this->warehouses->edit($id);
    }

    public function warehousesUpdate(Request $request, $id)
    {
        return $this->warehouses->update($request, $id);
    }

    public function warehousesSetDefault($id)
    {
        return $this->warehouses->setDefault($id);
    }

    public function warehousesDeactivate($id)
    {
        return $this->warehouses->deactivate($id);
    }

    public function warehousesDestroy($id)
    {
        return $this->warehouses->destroy($id);
    }

    // ==================== RACKS ====================
    public function racksIndex()
    {
        return $this->warehouses->racksIndex();
    }

    public function racksData(Request $request)
    {
        return $this->warehouses->racksData($request);
    }

    public function racksCreate()
    {
        return $this->warehouses->racksCreate();
    }

    public function racksStore(Request $request)
    {
        return $this->warehouses->racksStore($request);
    }

    public function racksEdit($id)
    {
        return $this->warehouses->racksEdit($id);
    }

    public function racksUpdate(Request $request, $id)
    {
        return $this->warehouses->racksUpdate($request, $id);
    }

    public function racksDeactivate($id)
    {
        return $this->warehouses->racksDeactivate($id);
    }

    public function racksDestroy($id)
    {
        return $this->warehouses->racksDestroy($id);
    }

    public function racksByWarehouse($warehouseId)
    {
        return $this->warehouses->racksByWarehouse($warehouseId);
    }

    // ==================== LOTS ====================
    public function lotsIndex()
    {
        return $this->lots->index();
    }

    public function lotsData(Request $request)
    {
        return $this->lots->data($request);
    }

    public function lotsCheck(Request $request)
    {
        return $this->lots->check($request);
    }

    public function lotsCreate()
    {
        return $this->lots->create();
    }

    public function lotsStore(Request $request)
    {
        return $this->lots->store($request);
    }

    public function lotsShow($id)
    {
        return $this->lots->show($id);
    }

    public function lotsEdit($id)
    {
        return $this->lots->edit($id);
    }

    public function lotsUpdate(Request $request, $id)
    {
        return $this->lots->update($request, $id);
    }

    public function lotsDeactivate($id)
    {
        return $this->lots->deactivate($id);
    }

    public function lotsMarkExpired($id)
    {
        return $this->lots->markExpired($id);
    }

    public function lotsMarkRecalled(Request $request, $id)
    {
        return $this->lots->markRecalled($request, $id);
    }

    public function lotsDestroy($id)
    {
        return $this->lots->destroy($id);
    }

    public function lotsByProduct($productId)
    {
        return $this->lots->byProduct($productId);
    }

    public function lotsWithStockByProduct($productId, Request $request)
    {
        return $this->lots->withStockByProduct($productId, $request);
    }

    public function lotsGenerateLotNo(Request $request)
    {
        return $this->lots->generateLotNo($request);
    }

    public function lotsGetProductInfo($productId)
    {
        return $this->lots->getProductInfo($productId);
    }

    public function lotsExpiringSoon(Request $request)
    {
        return $this->lots->expiringSoon($request);
    }

    public function lotsUpdateStatuses()
    {
        return $this->lots->updateStatuses();
    }

    // ==================== STOCK MOVEMENTS ====================
    public function stockMovements(Request $request)
    {
        return $this->stock->movements($request);
    }

    public function stockMovementsData(Request $request)
    {
        return $this->stock->movementsData($request);
    }

    public function stockReceive()
    {
        return $this->stock->receive();
    }

    public function stockReceiveStore(Request $request)
    {
        return $this->stock->receiveStore($request);
    }

    public function stockDeliver()
    {
        return $this->stock->deliver();
    }

    public function stockDeliverStore(Request $request)
    {
        return $this->stock->deliverStore($request);
    }

    public function stockReturns()
    {
        return $this->stock->returns();
    }

    public function stockReturnsStore(Request $request)
    {
        return $this->stock->returnsStore($request);
    }

    public function stockAdjustments()
    {
        return $this->stock->adjustments();
    }

    public function stockAdjustmentsStore(Request $request)
    {
        return $this->stock->adjustmentsStore($request);
    }

    public function stockTransfer()
    {
        return $this->stock->transfer();
    }

    public function stockTransferStore(Request $request)
    {
        return $this->stock->transferStore($request);
    }

    public function stockCheck(Request $request)
    {
        return $this->stock->check($request);
    }

    public function stockProductUnits(Request $request)
    {
        return $this->stock->getProductUnits($request);
    }

    public function stockProductLots(Request $request)
    {
        return $this->stock->getProductLots($request);
    }

    public function stockProductVariations(Request $request)
    {
        return $this->stock->getProductVariations($request);
    }

    // ==================== REPORTS ====================
    public function reportStockSummary(Request $request)
    {
        return $this->reports->stockSummary($request);
    }

    public function reportLotSummary(Request $request)
    {
        return $this->reports->lotSummary($request);
    }

    public function reportMovementHistory(Request $request)
    {
        return $this->reports->movementHistory($request);
    }

    public function reportMovementHistoryData(Request $request)
    {
        return $this->reports->movementHistoryData($request);
    }

    // ==================== SETTINGS ====================
    public function settingsIndex()
    {
        return $this->settings->index();
    }

    public function categoriesData(Request $request)
    {
        return $this->settings->categoriesData($request);
    }

    public function categoriesStore(Request $request)
    {
        return $this->settings->categoriesStore($request);
    }

    public function categoriesUpdate(Request $request, $id)
    {
        return $this->settings->categoriesUpdate($request, $id);
    }

    public function categoriesDeactivate($id)
    {
        return $this->settings->categoriesDeactivate($id);
    }

    public function categoriesDestroy($id)
    {
        return $this->settings->categoriesDestroy($id);
    }

    public function brandsData(Request $request)
    {
        return $this->settings->brandsData($request);
    }

    public function brandsStore(Request $request)
    {
        return $this->settings->brandsStore($request);
    }

    public function brandsUpdate(Request $request, $id)
    {
        return $this->settings->brandsUpdate($request, $id);
    }

    public function brandsDeactivate($id)
    {
        return $this->settings->brandsDeactivate($id);
    }

    public function brandsDestroy($id)
    {
        return $this->settings->brandsDestroy($id);
    }

    public function unitsData(Request $request)
    {
        return $this->settings->unitsData($request);
    }

    public function unitsStore(Request $request)
    {
        return $this->settings->unitsStore($request);
    }

    public function unitsUpdate(Request $request, $id)
    {
        return $this->settings->unitsUpdate($request, $id);
    }

    public function unitsDestroy($id)
    {
        return $this->settings->unitsDestroy($id);
    }

    // ==================== ATTRIBUTES ====================
    public function attributesData(Request $request)
    {
        return $this->settings->attributesData($request);
    }

    public function getAttributesWithValues()
    {
        return $this->settings->getAttributesWithValues();
    }

    public function attributesStore(Request $request)
    {
        return $this->settings->attributesStore($request);
    }

    public function attributesUpdate(Request $request, $id)
    {
        return $this->settings->attributesUpdate($request, $id);
    }

    public function attributesDestroy($id)
    {
        return $this->settings->attributesDestroy($id);
    }

    public function quickAddAttribute(Request $request)
    {
        return $this->settings->quickAddAttribute($request);
    }

    // ==================== ATTRIBUTE VALUES ====================
    public function attributeValuesStore(Request $request)
    {
        return $this->settings->attributeValuesStore($request);
    }

    public function attributeValuesUpdate(Request $request, $id)
    {
        return $this->settings->attributeValuesUpdate($request, $id);
    }

    public function attributeValuesDestroy($id)
    {
        return $this->settings->attributeValuesDestroy($id);
    }

    public function quickAddAttributeValue(Request $request)
    {
        return $this->settings->quickAddAttributeValue($request);
    }

    // ==================== BARCODE ====================
    
    /**
     * Generate a new barcode
     */
    public function generateBarcode(Request $request)
    {
        $type = $request->input('type', 'EAN13');
        $prefix = $request->input('prefix');
        $sku = $request->input('sku');
        $count = min($request->input('count', 1), 100); // Max 100 at once
        
        $barcodes = [];
        for ($i = 0; $i < $count; $i++) {
            $barcodes[] = \Modules\Inventory\Helpers\BarcodeHelper::generateUnique($type, $prefix, $sku);
        }
        
        return response()->json([
            'success' => true,
            'barcode' => $barcodes[0],
            'barcodes' => $barcodes,
            'type' => $type,
        ]);
    }

    /**
     * Check if barcode exists
     */
    public function checkBarcode($code)
    {
        $exists = \Modules\Inventory\Helpers\BarcodeHelper::barcodeExists($code);
        $type = \Modules\Inventory\Helpers\BarcodeHelper::detectType($code);
        
        $valid = true;
        if ($type === 'EAN13') {
            $valid = \Modules\Inventory\Helpers\BarcodeHelper::validateEAN13($code);
        } elseif ($type === 'EAN8') {
            $valid = \Modules\Inventory\Helpers\BarcodeHelper::validateEAN8($code);
        }
        
        return response()->json([
            'exists' => $exists,
            'type' => $type,
            'valid' => $valid,
        ]);
    }

    /**
     * Find product by barcode
     */
    public function findByBarcode($code)
    {
        $result = \Modules\Inventory\Helpers\BarcodeHelper::findByBarcode($code);
        
        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }
        
        $data = [
            'success' => true,
            'type' => $result['type'],
            'product' => [
                'id' => $result['product']->id,
                'name' => $result['product']->name,
                'sku' => $result['product']->sku,
                'barcode' => $result['product']->barcode,
                'purchase_price' => $result['product']->purchase_price,
                'sale_price' => $result['product']->sale_price,
                'has_variants' => $result['product']->has_variants,
            ],
        ];
        
        if ($result['variation']) {
            $data['variation'] = [
                'id' => $result['variation']->id,
                'sku' => $result['variation']->sku,
                'barcode' => $result['variation']->barcode,
                'variation_name' => $result['variation']->variation_name,
                'purchase_price' => $result['variation']->purchase_price,
                'sale_price' => $result['variation']->sale_price,
            ];
        }
        
        if ($result['unit']) {
            $data['unit'] = [
                'id' => $result['unit']->id,
                'unit_name' => $result['unit']->unit_name,
                'barcode' => $result['unit']->barcode,
                'conversion_factor' => $result['unit']->conversion_factor,
            ];
        }
        
        return response()->json($data);
    }

    /**
     * Barcode scanner page
     */
    public function scanBarcode()
    {
        return view('inventory::barcode.scan');
    }

    // ==================== ALERTS ====================

    /**
     * Get low stock alerts page/API
     */
    public function lowStockAlerts(Request $request)
    {
        $lowStockItems = \Modules\Inventory\Services\LowStockService::getAllLowStockItems(100);
        $statusSummary = \Modules\Inventory\Services\LowStockService::getStockStatusSummary();
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'items' => $lowStockItems,
                'summary' => $statusSummary,
            ]);
        }
        
        return view('inventory::alerts.low-stock', compact('lowStockItems', 'statusSummary'));
    }

    /**
     * Create notifications for low stock items
     */
    public function createLowStockNotifications(Request $request)
    {
        try {
            $userId = auth()->id();
            $notifications = \Modules\Inventory\Services\LowStockService::checkAndNotify($userId);
            
            $count = count($notifications);
            
            return response()->json([
                'success' => true,
                'message' => $count > 0 
                    ? "Created {$count} new low stock notification(s)" 
                    : "No new notifications needed (already notified recently)",
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ==================== SKU VALIDATION ====================

    /**
     * Check if SKU exists (AJAX)
     */
    public function checkSku(Request $request)
    {
        $sku = $request->input('sku');
        $excludeProductId = $request->input('product_id');
        $excludeVariationId = $request->input('variation_id');
        
        if (empty($sku)) {
            return response()->json([
                'valid' => false,
                'message' => 'SKU is required',
            ]);
        }
        
        $exists = \Modules\Inventory\Services\SkuService::skuExists(
            $sku, 
            $excludeProductId ? (int) $excludeProductId : null,
            $excludeVariationId ? (int) $excludeVariationId : null
        );
        
        return response()->json([
            'valid' => !$exists,
            'exists' => $exists,
            'message' => $exists ? 'SKU already exists' : 'SKU is available',
        ]);
    }

    /**
     * Generate unique SKU
     */
    public function generateSku(Request $request)
    {
        $name = $request->input('name');
        $prefix = $request->input('prefix', 'PRD');
        
        $sku = \Modules\Inventory\Services\SkuService::generateProductSku($name, $prefix);
        
        return response()->json([
            'success' => true,
            'sku' => $sku,
        ]);
    }

    // ==================== BARCODE LOOKUP ====================
    
    /**
     * Lookup product/variation by barcode or SKU
     * Used by barcode scanner in stock operations
     */
    public function barcodeLookup(Request $request)
    {
        $code = trim($request->input('code', ''));
        
        if (empty($code)) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a barcode or SKU',
            ]);
        }
        
        // First try barcode lookup
        $result = \Modules\Inventory\Helpers\BarcodeHelper::findByBarcode($code);
        
        // If not found by barcode, try SKU search
        if (!$result) {
            $result = \Modules\Inventory\Services\SkuService::findBySku($code);
        }
        
        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found for: ' . $code,
            ]);
        }
        
        $product = $result['product'];
        $variation = $result['variation'] ?? null;
        $unit = $result['unit'] ?? null;
        
        // Get product image
        $primaryImage = $product->images?->where('is_primary', true)->first() 
            ?? $product->images?->first();
        
        // Get variations if product has them
        $variations = [];
        if ($product->has_variants) {
            $variations = $product->variations()
                ->where('is_active', true)
                ->get()
                ->map(fn($v) => [
                    'id' => $v->id,
                    'name' => $v->variation_name ?? $v->sku,
                    'sku' => $v->sku,
                    'barcode' => $v->barcode,
                    'price' => $v->sale_price ?? $product->sale_price,
                ])
                ->toArray();
        }
        
        return response()->json([
            'success' => true,
            'type' => $result['type'],
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'barcode' => $product->barcode,
                'purchase_price' => $product->purchase_price,
                'sale_price' => $product->sale_price,
                'unit_id' => $product->unit_id,
                'unit_name' => $product->unit?->short_name ?? 'PCS',
                'has_variants' => $product->has_variants,
                'is_batch_managed' => $product->is_batch_managed,
                'track_inventory' => $product->track_inventory,
                'image' => $primaryImage ? asset('storage/' . $primaryImage->image_path) : null,
            ],
            'variation' => $variation ? [
                'id' => $variation->id,
                'name' => $variation->variation_name ?? $variation->sku,
                'sku' => $variation->sku,
                'barcode' => $variation->barcode,
                'price' => $variation->sale_price ?? $product->sale_price,
            ] : null,
            'unit' => $unit ? [
                'id' => $unit->id,
                'unit_id' => $unit->unit_id,
                'unit_name' => $unit->unit?->short_name,
                'barcode' => $unit->barcode,
            ] : null,
            'variations' => $variations,
        ]);
    }
}