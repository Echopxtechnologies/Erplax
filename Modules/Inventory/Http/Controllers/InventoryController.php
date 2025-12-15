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
}