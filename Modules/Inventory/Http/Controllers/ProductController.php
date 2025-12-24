<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductCategory;
use Modules\Inventory\Models\Brand;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\Rack;
use Modules\Inventory\Models\Unit;
use App\Models\Admin\Tax;
use Modules\Inventory\Models\Tag;
use Modules\Inventory\Models\ProductImage;
use Modules\Inventory\Models\ProductUnit;
use Modules\Inventory\Models\ProductAttribute;
use Modules\Inventory\Models\AttributeValue;
use Modules\Inventory\Models\ProductVariation;
use Modules\Inventory\Models\StockLevel;
use Modules\Inventory\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Modules\Core\Traits\DataTableTrait;


class ProductController extends BaseController
{
    use DataTableTrait;

    // ==================== DATATABLE CONFIGURATION ====================
    protected $model = Product::class;
    
    protected $with = ['category', 'brand', 'unit', 'images', 'tax1', 'tax2'];
    
    protected $searchable = ['name', 'sku', 'barcode', 'category.name', 'brand.name'];
    
    protected $sortable = ['id', 'name', 'sku', 'purchase_price', 'sale_price', 'created_at'];
    
    protected $filterable = ['category_id', 'brand_id', 'is_active', 'has_variants'];
    
    protected $uniqueField = 'sku';
    
    protected $exportTitle = 'Products Export';

    // Import validation rules
    protected $importable = [
        'name'           => 'required|string|max:191',
        'sku'            => 'required|string|max:50',
        'barcode'        => 'nullable|string|max:50',
        'category_id'    => 'nullable|exists:product_categories,id',
        'brand_id'       => 'nullable|exists:brands,id',
        'unit_id'        => 'nullable|exists:units,id',
        'purchase_price' => 'required|numeric|min:0',
        'sale_price'     => 'required|numeric|min:0',
        'mrp'            => 'nullable|numeric|min:0',
        'default_profit_rate' => 'nullable|numeric|min:0|max:100',
        'tax_1_id'       => 'nullable|exists:taxes,id',
        'tax_2_id'       => 'nullable|exists:taxes,id',
        'hsn_code'       => 'nullable|string|max:20',
        'min_stock_level'=> 'nullable|numeric|min:0',
        'max_stock_level'=> 'nullable|numeric|min:0',
    ];

    // ==================== HELPER: CLEAN ATTRIBUTE IDs ====================
    /**
     * Sanitize attribute IDs to prevent SQL errors
     * Filters out invalid values and ensures only valid integer IDs are returned
     */
    private function cleanAttributeIds($attributes): array
    {
        if (empty($attributes)) {
            return [];
        }
        
        // Handle if it's not an array
        if (!is_array($attributes)) {
            return [];
        }
        
        return collect($attributes)
            ->filter(function ($value) {
                // Only keep numeric values that are positive integers
                return is_numeric($value) && (int)$value > 0;
            })
            ->map(function ($value) {
                return (int) $value;
            })
            ->unique()
            ->values()
            ->toArray();
    }

    // ==================== CUSTOM ROW MAPPING FOR LIST ====================
    protected function mapRow($item)
    {
        $currentStock = $this->getProductStock($item->id);
        $primaryImage = $item->images->where('is_primary', true)->first() ?? $item->images->first();
        
        return [
            'id' => $item->id,
            'sku' => $item->sku,
            'name' => $item->name,
            'image' => $primaryImage ? asset('storage/' . $primaryImage->image_path) : null,
            'category_name' => $item->category?->name ?? '-',
            'brand_name' => $item->brand?->name ?? '-',
            'unit' => $item->unit?->short_name ?? 'PCS',
            'purchase_price' => number_format($item->purchase_price, 2),
            'sale_price' => number_format($item->sale_price, 2),
            'mrp' => $item->mrp ? number_format($item->mrp, 2) : '-',
            'current_stock' => $currentStock,
            'min_stock_level' => $item->min_stock_level,
            'is_low_stock' => $item->min_stock_level > 0 && $currentStock < $item->min_stock_level,
            'has_variants' => $item->has_variants,
            'variant_count' => $item->has_variants ? $item->variations()->count() : 0,
            'is_active' => $item->is_active,
            'status' => $item->is_active ? 'Active' : 'Inactive',
            '_edit_url' => route('inventory.products.edit', $item->id),
            '_show_url' => route('inventory.products.show', $item->id),
            '_delete_url' => route('inventory.products.destroy', $item->id),
        ];
    }

    // ==================== CUSTOM EXPORT ROW MAPPING ====================
    protected function mapExportRow($item)
    {
        $currentStock = $this->getProductStock($item->id);
        
        return [
            'ID' => $item->id,
            'SKU' => $item->sku,
            'Barcode' => $item->barcode ?? '',
            'Name' => $item->name,
            'Category' => $item->category?->name ?? '',
            'Brand' => $item->brand?->name ?? '',
            'Unit' => $item->unit?->short_name ?? 'PCS',
            'Purchase Price' => $item->purchase_price,
            'Sale Price' => $item->sale_price,
            'MRP' => $item->mrp ?? '',
            'Tax 1' => $item->tax1?->name ?? '',
            'Tax 1 %' => $item->tax1?->rate ?? '',
            'Tax 2' => $item->tax2?->name ?? '',
            'Tax 2 %' => $item->tax2?->rate ?? '',
            'HSN Code' => $item->hsn_code ?? '',
            'Current Stock' => $currentStock,
            'Min Stock' => $item->min_stock_level ?? 0,
            'Max Stock' => $item->max_stock_level ?? 0,
            'Can Sell' => $item->can_be_sold ? 'Yes' : 'No',
            'Can Purchase' => $item->can_be_purchased ? 'Yes' : 'No',
            'Track Stock' => $item->track_inventory ? 'Yes' : 'No',
            'Has Variants' => $item->has_variants ? 'Yes' : 'No',
            'Status' => $item->is_active ? 'Active' : 'Inactive',
        ];
    }

    // ==================== CUSTOM IMPORT ROW HANDLER ====================
    protected function importRow($data, $row)
    {
        $data['is_active'] = true;
        $data['can_be_sold'] = true;
        $data['can_be_purchased'] = true;
        $data['track_inventory'] = true;
        
        if (empty($data['unit_id'])) {
            $defaultUnit = Unit::where('short_name', 'PCS')->first();
            $data['unit_id'] = $defaultUnit?->id;
        }
        
        $existing = Product::where('sku', $data['sku'])->first();
        
        if ($existing) {
            $existing->update($data);
            return $existing;
        }
        
        return Product::create($data);
    }

    // ==================== DATA ENDPOINT ====================
    public function data(Request $request)
    {
        return $this->handleData($request);
    }

    // ==================== BULK DELETE ====================
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }

        $hasStock = StockLevel::whereIn('product_id', $ids)->where('qty', '>', 0)->exists();
        if ($hasStock) {
            return response()->json([
                'success' => false, 
                'message' => 'Cannot delete products with existing stock.'
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            foreach ($ids as $id) {
                $product = Product::find($id);
                if (!$product) continue;
                
                foreach ($product->images as $image) {
                    $image->deleteWithFile();
                }
                
                $product->productUnits()->delete();
                $product->tags()->detach();
                $product->attributes()->detach();
                $product->variations()->delete();
                $product->delete();
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true, 
                'message' => count($ids) . ' products deleted'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ==================== DASHBOARD ====================
public function dashboard()
{
    // Basic stats
    $stats = [
        'totalProducts' => Product::count(),
        'totalWarehouses' => Warehouse::where('is_active', true)->count(),
        'totalRacks' => Rack::where('is_active', true)->count(),
        'totalCategories' => ProductCategory::where('is_active', true)->count(),
        'totalBrands' => Brand::where('is_active', true)->count(),
    ];
    
    // Stock value calculations - single optimized query
    $stockData = StockLevel::join('products', 'stock_levels.product_id', '=', 'products.id')
        ->selectRaw('SUM(stock_levels.qty) as total_qty, SUM(stock_levels.qty * products.purchase_price) as total_value')
        ->first();
    
    $totalStockValue = $stockData->total_value ?? 0;
    $totalStockQty = $stockData->total_qty ?? 0;
    
    // Use LowStockService for low stock items
    $lowStockProducts = \Modules\Inventory\Services\LowStockService::getAllLowStockItems(10);
    $lowStockCount = \Modules\Inventory\Services\LowStockService::getLowStockCount();
    $stockStatusSummary = \Modules\Inventory\Services\LowStockService::getStockStatusSummary();
    
    // Today's movements summary - single query with grouping
    $todayMovements = StockMovement::whereDate('created_at', today())
        ->selectRaw("movement_type, COUNT(*) as count")
        ->groupBy('movement_type')
        ->pluck('count', 'movement_type')
        ->toArray();
    
    $todayIn = $todayMovements['IN'] ?? 0;
    $todayOut = $todayMovements['OUT'] ?? 0;
    $todayTransfer = $todayMovements['TRANSFER'] ?? 0;
    $todayAdjust = $todayMovements['ADJUSTMENT'] ?? 0;
    
    // Warehouses with stock count
    $warehousesWithStock = Warehouse::where('is_active', true)
        ->withCount('racks')
        ->withSum('stockLevels', 'qty')
        ->orderBy('name')
        ->get();
    
    // Recent movements with relationships
    $recentMovements = StockMovement::with(['product', 'warehouse', 'rack'])
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
    
    // Get transfer details for transfer movements
    $transferRefNos = $recentMovements->where('movement_type', 'TRANSFER')
        ->pluck('reference_no')
        ->map(fn($ref) => str_replace(['-IN', '-OUT'], '', $ref))
        ->unique()
        ->values()
        ->toArray();
    
    $transfers = collect();
    if (!empty($transferRefNos)) {
        $transfers = \Modules\Inventory\Models\StockTransfer::with(['fromWarehouse', 'toWarehouse'])
            ->whereIn('transfer_no', $transferRefNos)
            ->get()
            ->keyBy('transfer_no');
    }
    
    // Greeting based on time of day
    $hour = now()->hour;
    $greeting = match(true) {
        $hour < 12 => 'Good Morning',
        $hour < 17 => 'Good Afternoon',
        default => 'Good Evening'
    };
    
    return view('inventory::dashboard', compact(
        'stats',
        'totalStockValue',
        'totalStockQty',
        'lowStockProducts',
        'lowStockCount',
        'stockStatusSummary',
        'todayIn',
        'todayOut',
        'todayTransfer',
        'todayAdjust',
        'warehousesWithStock',
        'recentMovements',
        'transfers',
        'greeting'
    ));
}

    // ==================== INDEX ====================
    public function index()
    {
        $stats = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'inactive' => Product::where('is_active', false)->count(),
        ];
        
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        
        return view('inventory::products.index', compact('stats', 'categories', 'brands'));
    }

    // ==================== CREATE ====================
    public function create()
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        $taxes = Tax::where('is_active', true)->orderBy('name')->get();
        $attributes = ProductAttribute::with('values')->where('is_active', true)->orderBy('sort_order')->get();
        
        return view('inventory::products.create', compact('categories', 'brands', 'units', 'taxes', 'attributes'));
    }

    // ==================== STORE - FIXED ATTRIBUTE HANDLING ====================
    public function store(Request $request)
    {
        Log::info('ProductController::store - Request received', [
            'has_images' => $request->hasFile('images'),
            'files_count' => $request->hasFile('images') ? count($request->file('images')) : 0,
            'attributes' => $request->input('attributes'),
        ]);

        // Custom SKU validation - check both products and variations tables
        $sku = $request->input('sku');
        if ($sku && \Modules\Inventory\Services\SkuService::skuExists($sku)) {
            return back()->withInput()->withErrors(['sku' => 'This SKU already exists in products or variations.']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'sku' => 'required|string|max:100',
            'barcode' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:product_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'nullable|exists:units,id',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'default_profit_rate' => 'nullable|numeric',
            'tax_1_id' => 'nullable|exists:taxes,id',
            'tax_2_id' => 'nullable|exists:taxes,id',
            'hsn_code' => 'nullable|string|max:50',
            'min_stock_level' => 'nullable|numeric|min:0',
            'max_stock_level' => 'nullable|numeric|min:0',
            'tags' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'primary_image' => 'nullable|integer',
            'product_units' => 'nullable|array',
            'attributes' => 'nullable|array',
            'attributes.*' => 'nullable',
        ]);
        
        if (empty($validated['unit_id'])) {
            $defaultUnit = Unit::where('short_name', 'PCS')->first();
            $validated['unit_id'] = $defaultUnit?->id;
        }

        $productData = [
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'barcode' => $validated['barcode'] ?? null,
            'description' => $validated['description'] ?? null,
            'short_description' => $validated['short_description'] ?? null,
            'category_id' => $validated['category_id'] ?: null,
            'brand_id' => $validated['brand_id'] ?: null,
            'unit_id' => $validated['unit_id'],
            'purchase_price' => $validated['purchase_price'],
            'sale_price' => $validated['sale_price'],
            'mrp' => $validated['mrp'] ?? null,
            'default_profit_rate' => $validated['default_profit_rate'] ?? 0,
            'tax_1_id' => $validated['tax_1_id'] ?? null,
            'tax_2_id' => $validated['tax_2_id'] ?? null,
            'hsn_code' => $validated['hsn_code'] ?? null,
            'min_stock_level' => $validated['min_stock_level'] ?? 0,
            'max_stock_level' => $validated['max_stock_level'] ?? 0,
            'can_be_sold' => $request->has('can_be_sold') ? 1 : 0,
            'can_be_purchased' => $request->has('can_be_purchased') ? 1 : 0,
            'track_inventory' => $request->has('track_inventory') ? 1 : 0,
            'has_variants' => $request->has('has_variants') ? 1 : 0,
            'is_batch_managed' => $request->has('is_batch_managed') ? 1 : 0,
            'is_active' => 1,
        ];

        DB::beginTransaction();
        
        try {
            $product = Product::create($productData);
            
            Log::info('ProductController::store - Product created', ['product_id' => $product->id]);
            
            // Handle tags
            if (!empty($validated['tags'])) {
                Tag::syncProductTags($product, $validated['tags']);
            }
            
            // Handle images
            $uploadedImages = [];
            if ($request->hasFile('images')) {
                $files = $request->file('images');
                $primaryIndex = (int) $request->input('primary_image', 0);
                $imageColors = json_decode($request->input('image_colors', '[]'), true) ?: [];
                
                Log::info('ProductController::store - Processing images', [
                    'product_id' => $product->id,
                    'files_count' => count($files),
                    'primary_index' => $primaryIndex,
                    'image_colors' => $imageColors,
                ]);
                
                $uploadedCount = 0;
                
                foreach ($files as $index => $file) {
                    if (!$file || !$file->isValid()) continue;
                    
                    $isPrimary = ($index === $primaryIndex);
                    $image = ProductImage::uploadForProduct($product, $file, $isPrimary);
                    
                    if ($image) {
                        $uploadedCount++;
                        $colorValueId = isset($imageColors[$index]) && $imageColors[$index] ? (int)$imageColors[$index] : null;
                        $uploadedImages[] = [
                            'image' => $image,
                            'color_value_id' => $colorValueId,
                        ];
                        Log::info('ProductController::store - Image uploaded', [
                            'image_id' => $image->id,
                            'is_primary' => $image->is_primary,
                            'color_value_id' => $colorValueId,
                        ]);
                    }
                }
                
                $product->ensurePrimaryImage();
            }
            
            // Handle product units
            if (!empty($request->product_units)) {
                foreach ($request->product_units as $unitData) {
                    if (empty($unitData['unit_id'])) continue;
                    
                    $product->productUnits()->create([
                        'unit_id' => $unitData['unit_id'],
                        'unit_name' => $unitData['unit_name'] ?? null,
                        'conversion_factor' => $unitData['conversion_factor'] ?? 1,
                        'purchase_price' => $unitData['purchase_price'] ?? null,
                        'sale_price' => $unitData['sale_price'] ?? null,
                        'barcode' => $unitData['barcode'] ?? null,
                        'is_purchase_unit' => !empty($unitData['is_purchase_unit']),
                        'is_sale_unit' => !empty($unitData['is_sale_unit']),
                    ]);
                }
            }
            
            // ========================================
            // FIXED: Handle variations with clean attribute IDs
            // ========================================
            if ($productData['has_variants']) {
                $attributeIds = $this->cleanAttributeIds($request->input('attributes'));
                
                Log::info('ProductController::store - Processing attributes', [
                    'raw_attributes' => $request->input('attributes'),
                    'cleaned_attributes' => $attributeIds,
                ]);
                
                if (!empty($attributeIds)) {
                    $product->attributes()->sync($attributeIds);
                    
                    // Check if we have pre-defined variation data (from new UI)
                    $variationsData = $request->input('variations_data');
                    
                    if ($variationsData && $request->boolean('generate_variations')) {
                        // Parse JSON variation data
                        $variations = json_decode($variationsData, true);
                        
                        if (!empty($variations)) {
                            foreach ($variations as $varData) {
                                // Create variation with custom SKU, barcode, prices
                                $variation = ProductVariation::create([
                                    'product_id' => $product->id,
                                    'sku' => $varData['sku'] ?? $product->sku . '-V' . ($varData['index'] + 1),
                                    'barcode' => $varData['barcode'] ?? null,
                                    'purchase_price' => $varData['purchase_price'] ?? $product->purchase_price,
                                    'sale_price' => $varData['sale_price'] ?? $product->sale_price,
                                    'mrp' => $product->mrp,
                                    'is_active' => true,
                                ]);
                                
                                // Attach attribute values
                                if (!empty($varData['attributes'])) {
                                    $valueIds = array_column($varData['attributes'], 'value_id');
                                    $variation->attributeValues()->sync($valueIds);
                                }
                            }
                            
                            Log::info('ProductController::store - Variations created from data', [
                                'count' => count($variations),
                            ]);
                            
                            // Link images to variations based on color attribute values
                            if (!empty($uploadedImages)) {
                                foreach ($uploadedImages as $imgData) {
                                    if (!$imgData['color_value_id']) continue;
                                    
                                    // Find variation that has this color value
                                    $variation = $product->variations()
                                        ->whereHas('attributeValues', function($q) use ($imgData) {
                                            $q->where('attribute_values.id', $imgData['color_value_id']);
                                        })
                                        ->first();
                                    
                                    if ($variation) {
                                        $imgData['image']->update(['variation_id' => $variation->id]);
                                        Log::info('ProductController::store - Image linked to variation', [
                                            'image_id' => $imgData['image']->id,
                                            'variation_id' => $variation->id,
                                            'color_value_id' => $imgData['color_value_id'],
                                        ]);
                                    }
                                }
                            }
                        }
                    } elseif ($request->boolean('generate_variations')) {
                        // Fallback to old method
                        $product->createVariationsFromCombinations();
                    }
                }
            }
            
            DB::commit();
            
            Log::info('ProductController::store - Product creation complete', ['product_id' => $product->id]);
            
            return redirect()
                ->route('inventory.products.index')
                ->with('success', 'Product created successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('ProductController::store - Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ==================== SHOW ====================
    public function show($id)
{
    // Load product with essential relationships (NOT variations - loaded via AJAX)
    $product = Product::with([
        'category',
        'brand',
        'unit',
        'images',
        'tags',
        'tax1',
        'tax2',
        'productUnits.unit',
        'attributes.values',
        // Stock levels with full location details
        'stockLevels' => function($query) {
            $query->where('qty', '>', 0)
                  ->with(['warehouse', 'rack', 'lot', 'unit']);
        },
    ])->findOrFail($id);
    
    // Get recent movements (limited for performance)
    $recentMovements = StockMovement::where('product_id', $id)
        ->with(['warehouse', 'rack', 'lot', 'unit', 'variation'])
        ->orderBy('created_at', 'desc')
        ->limit(20)
        ->get();
    
    // Get list of warehouses for dropdowns (if needed)
    $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
    
    return view('inventory::products.show', compact(
        'product',
        'recentMovements', 
        'warehouses'
    ));
}

// ==================== GET VARIATIONS (AJAX - Lazy Load) ====================
/**
 * Get variations via AJAX to avoid loading them on page load
 * This prevents server load when products have many variations
 */
public function getVariations($productId)
{
    $product = Product::with(['unit'])->findOrFail($productId);
    
    if (!$product->has_variants) {
        return response()->json([
            'success' => false, 
            'message' => 'Product does not have variants'
        ]);
    }
    
    $variations = ProductVariation::where('product_id', $productId)
        ->with(['attributeValues.attribute'])
        ->orderBy('sku')
        ->get()
        ->map(function($var) use ($product) {
            // Get stock for this variation
            $stock = StockLevel::where('variation_id', $var->id)->sum('qty');
            
            return [
                'id' => $var->id,
                'sku' => $var->sku,
                'barcode' => $var->barcode,
                'variation_name' => $var->display_name,
                'purchase_price' => $var->purchase_price ?? $product->purchase_price,
                'sale_price' => $var->sale_price ?? $product->sale_price,
                'mrp' => $var->mrp ?? $product->mrp,
                'stock_qty' => $stock,
                'is_active' => $var->is_active,
                'attributes' => $var->attributeValues->map(function($av) {
                    return [
                        'attribute' => $av->attribute->name ?? '-',
                        'value' => $av->value,
                        'color_code' => $av->color_code,
                    ];
                }),
            ];
        });
    
    return response()->json([
        'success' => true,
        'variations' => $variations,
        'total' => $variations->count(),
    ]);
}


    // ==================== EDIT ====================
    public function edit($id)
    {
        $product = Product::with([
            'images', 'tags', 'productUnits.unit', 'attributes',
            'variations.attributeValues', 'tax1', 'tax2',
        ])->findOrFail($id);
        
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        $taxes = Tax::where('is_active', true)->orderBy('name')->get();
        $attributes = ProductAttribute::with('values')->where('is_active', true)->orderBy('sort_order')->get();
        
        return view('inventory::products.edit', compact(
            'product', 'categories', 'brands', 'units', 'taxes', 'attributes'
        ));
    }

    // ==================== UPDATE - FIXED ATTRIBUTE HANDLING ====================
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        Log::info('ProductController::update - Request received', [
            'product_id' => $id,
            'has_images' => $request->hasFile('images'),
            'attributes' => $request->input('attributes'),
        ]);
        
        // Custom SKU validation - check both products and variations tables (excluding current product)
        $sku = $request->input('sku');
        if ($sku && \Modules\Inventory\Services\SkuService::skuExists($sku, $id)) {
            return back()->withInput()->withErrors(['sku' => 'This SKU already exists in products or variations.']);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'sku' => 'required|string|max:100',
            'barcode' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:product_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'nullable|exists:units,id',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'default_profit_rate' => 'nullable|numeric',
            'tax_1_id' => 'nullable|exists:taxes,id',
            'tax_2_id' => 'nullable|exists:taxes,id', 
            'hsn_code' => 'nullable|string|max:50',
            'min_stock_level' => 'nullable|numeric|min:0',
            'max_stock_level' => 'nullable|numeric|min:0',
            'tags' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'delete_images' => 'nullable|array',
            'primary_image_id' => 'nullable|integer',
            'product_units' => 'nullable|array',
            'delete_units' => 'nullable|array',
            'attributes' => 'nullable|array',
            'attributes.*' => 'nullable',
        ]);
        
        // Fix: Use input value check instead of has() since hidden inputs always exist
        $validated['can_be_sold'] = $request->input('can_be_sold') == '1';
        $validated['can_be_purchased'] = $request->input('can_be_purchased') == '1';
        $validated['track_inventory'] = $request->input('track_inventory') == '1';
        $validated['has_variants'] = $request->input('has_variants') == '1';
        $validated['is_batch_managed'] = $request->input('is_batch_managed') == '1';
        $validated['is_active'] = $request->input('is_active') == '1';

        DB::beginTransaction();
        
        try {
            $product->update($validated);
            
            // Handle tags
            if ($request->has('tags')) {
                Tag::syncProductTags($product, $request->tags ?? '');
            }
            
            // Delete selected images
            if (!empty($request->delete_images)) {
                foreach ($request->delete_images as $imageId) {
                    $image = ProductImage::find($imageId);
                    if ($image && $image->product_id == $product->id) {
                        $image->deleteWithFile();
                        Log::info('ProductController::update - Image deleted', ['image_id' => $imageId]);
                    }
                }
            }
            
            // Upload new images
            $uploadedImages = [];
            if ($request->hasFile('images')) {
                $files = $request->file('images');
                $imageColors = json_decode($request->input('image_colors', '[]'), true) ?: [];
                
                foreach ($files as $index => $file) {
                    if ($file && $file->isValid()) {
                        $image = ProductImage::uploadForProduct($product, $file, false);
                        if ($image) {
                            $colorValueId = isset($imageColors[$index]) && $imageColors[$index] ? (int)$imageColors[$index] : null;
                            $uploadedImages[] = [
                                'image' => $image,
                                'color_value_id' => $colorValueId,
                            ];
                        }
                    }
                }
                
                // Link new images to variations based on color
                foreach ($uploadedImages as $imgData) {
                    if (!$imgData['color_value_id']) continue;
                    
                    $variation = $product->variations()
                        ->whereHas('attributeValues', function($q) use ($imgData) {
                            $q->where('attribute_values.id', $imgData['color_value_id']);
                        })
                        ->first();
                    
                    if ($variation) {
                        $imgData['image']->update(['variation_id' => $variation->id]);
                        Log::info('ProductController::update - New image linked to variation', [
                            'image_id' => $imgData['image']->id,
                            'variation_id' => $variation->id,
                            'color_value_id' => $imgData['color_value_id'],
                        ]);
                    }
                }
            }
            
            // Set primary image
            if ($request->filled('primary_image_id')) {
                $primaryImage = ProductImage::find($request->primary_image_id);
                if ($primaryImage && $primaryImage->product_id == $product->id) {
                    $primaryImage->setAsPrimary();
                    Log::info('ProductController::update - Primary image set', ['image_id' => $request->primary_image_id]);
                }
            }
            
            $product->ensurePrimaryImage();
            
            // Handle image color assignments (update product_variations.image_path)
            if (!empty($request->image_color_assignments)) {
                foreach ($request->image_color_assignments as $imageId => $colorValueId) {
                    if (empty($colorValueId)) continue;
                    
                    // Get the image path
                    $image = ProductImage::find($imageId);
                    if (!$image || $image->product_id != $product->id) continue;
                    
                    // Find all variations that have this color attribute value and update their image_path
                    $variations = ProductVariation::where('product_id', $product->id)
                        ->whereHas('attributeValues', function($q) use ($colorValueId) {
                            $q->where('attribute_value_id', $colorValueId);
                        })
                        ->get();
                    
                    foreach ($variations as $variation) {
                        $variation->image_path = $image->image_path;
                        $variation->save();
                    }
                    
                    Log::info('ProductController::update - Image assigned to color', [
                        'image_id' => $imageId,
                        'color_value_id' => $colorValueId,
                        'variations_updated' => $variations->count()
                    ]);
                }
            }
            
            // Delete selected units
            if (!empty($request->delete_units)) {
                ProductUnit::whereIn('id', $request->delete_units)
                    ->where('product_id', $product->id)
                    ->delete();
            }
            
            // Update/create product units
            if (!empty($request->product_units)) {
                foreach ($request->product_units as $unitData) {
                    if (empty($unitData['unit_id'])) continue;
                    
                    $data = [
                        'unit_id' => $unitData['unit_id'],
                        'unit_name' => $unitData['unit_name'] ?? null,
                        'conversion_factor' => $unitData['conversion_factor'] ?? 1,
                        'purchase_price' => $unitData['purchase_price'] ?? null,
                        'sale_price' => $unitData['sale_price'] ?? null,
                        'barcode' => $unitData['barcode'] ?? null,
                        'is_purchase_unit' => !empty($unitData['is_purchase_unit']),
                        'is_sale_unit' => !empty($unitData['is_sale_unit']),
                    ];
                    
                    if (!empty($unitData['id'])) {
                        ProductUnit::where('id', $unitData['id'])
                            ->where('product_id', $product->id)
                            ->update($data);
                    } else {
                        $product->productUnits()->create($data);
                    }
                }
            }
            
            // ========================================
            // FIXED: Handle variations with clean attribute IDs
            // ========================================
            if ($validated['has_variants']) {
                $attributeIds = $this->cleanAttributeIds($request->input('attributes'));
                
                Log::info('ProductController::update - Processing attributes', [
                    'raw_attributes' => $request->input('attributes'),
                    'cleaned_attributes' => $attributeIds,
                ]);
                
                $product->attributes()->sync($attributeIds);
                
                if ($request->boolean('generate_variations')) {
                    $product->createVariationsFromCombinations();
                }
            } else {
                $product->attributes()->detach();
                $product->variations()->update(['is_active' => false]);
            }
            
            DB::commit();
            
            Log::info('ProductController::update - Update complete', ['product_id' => $product->id]);
            
            return redirect()
                ->route('inventory.products.index')
                ->with('success', 'Product updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('ProductController::update - Exception', [
                'product_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ==================== DESTROY (Returns JSON for AJAX) ====================
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        $hasStock = StockLevel::where('product_id', $id)->where('qty', '>', 0)->exists();
        if ($hasStock) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Cannot delete product with existing stock.'
                ], 422);
            }
            return back()->with('error', 'Cannot delete product with existing stock.');
        }
        
        DB::beginTransaction();
        
        try {
            foreach ($product->images as $image) {
                $image->deleteWithFile();
            }
            
            $product->productUnits()->delete();
            $product->tags()->detach();
            $product->attributes()->detach();
            $product->variations()->delete();
            $product->delete();
            
            DB::commit();
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Product deleted!']);
            }
            
            return redirect()
                ->route('inventory.products.index')
                ->with('success', 'Product deleted successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('ProductController::destroy - Exception', [
                'product_id' => $id,
                'message' => $e->getMessage(),
            ]);
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ==================== AJAX METHODS ====================
    public function getProductUnits($productId)
    {
        $product = Product::with(['unit', 'productUnits.unit'])->findOrFail($productId);
        return response()->json([
            'success' => true,
            'units' => $product->getAvailableUnits(),
            'base_unit' => [
                'id' => $product->unit_id,
                'name' => $product->unit?->name,
                'short_name' => $product->unit?->short_name,
            ],
        ]);
    }

    public function searchTags(Request $request)
    {
        $tags = Tag::where('name', 'like', "%{$request->get('q', '')}%")
            ->orderBy('name')->limit(20)->get(['id', 'name', 'color']);
        return response()->json($tags);
    }

    public function getAttributes()
    {
        $attributes = ProductAttribute::with('values')
            ->where('is_active', true)->orderBy('sort_order')->get();
        return response()->json(['success' => true, 'attributes' => $attributes]);
    }


    public function updateVariation(Request $request, $variationId)
    {
        $variation = ProductVariation::findOrFail($variationId);
        
        // Custom SKU validation if SKU is being updated
        if ($request->has('sku')) {
            $sku = $request->input('sku');
            if (\Modules\Inventory\Services\SkuService::skuExists($sku, null, $variationId)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'This SKU already exists in products or variations.'
                ], 422);
            }
        }
        
        // Only validate fields that are present
        $rules = [];
        if ($request->has('sku')) {
            $rules['sku'] = 'required|string|max:100';
        }
        if ($request->has('barcode')) {
            $rules['barcode'] = 'nullable|string|max:100';
        }
        if ($request->has('purchase_price')) {
            $rules['purchase_price'] = 'nullable|numeric|min:0';
        }
        if ($request->has('sale_price')) {
            $rules['sale_price'] = 'nullable|numeric|min:0';
        }
        if ($request->has('mrp')) {
            $rules['mrp'] = 'nullable|numeric|min:0';
        }
        if ($request->has('is_active')) {
            $rules['is_active'] = 'boolean';
        }
        
        $validated = $request->validate($rules);
        $variation->update($validated);
        return response()->json(['success' => true, 'message' => 'Variation updated', 'variation' => $variation]);
    }

    public function deleteVariation($variationId)
    {
        $variation = ProductVariation::findOrFail($variationId);
        if (StockLevel::where('variation_id', $variationId)->where('qty', '>', 0)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete variation with stock.'], 422);
        }
        $variation->delete();
        return response()->json(['success' => true, 'message' => 'Variation deleted']);
    }

    public function generateVariations($productId)
    {
        $product = Product::findOrFail($productId);
        if (!$product->has_variants) {
            return response()->json(['success' => false, 'message' => 'Product does not have variants enabled'], 422);
        }
        $created = $product->createVariationsFromCombinations();
        return response()->json(['success' => true, 'message' => count($created) . ' variations created', 'count' => count($created)]);
    }

    /**
     * Generate barcode for a single variation
     */
    public function generateVariationBarcode(Request $request, $variationId)
    {
        $variation = ProductVariation::with('product')->findOrFail($variationId);
        
        $type = $request->input('type', 'EAN13');
        $barcode = \Modules\Inventory\Helpers\BarcodeHelper::generateUnique($type, null, $variation->sku);
        
        $variation->barcode = $barcode;
        $variation->save();
        
        return response()->json([
            'success' => true,
            'barcode' => $barcode,
            'variation_id' => $variation->id,
        ]);
    }

    /**
     * Generate barcodes for all variations of a product
     */
    public function generateVariationBarcodes($productId)
    {
        $product = Product::findOrFail($productId);
        
        if (!$product->has_variants) {
            return response()->json(['success' => false, 'message' => 'Product does not have variants'], 422);
        }
        
        $variations = ProductVariation::where('product_id', $productId)
            ->whereNull('barcode')
            ->orWhere('barcode', '')
            ->get();
        
        $generated = [];
        $productBarcode = $product->barcode;
        
        foreach ($variations as $index => $variation) {
            // Generate unique barcode
            if ($productBarcode && strlen($productBarcode) === 13) {
                // If product has EAN-13, derive variation barcode from it
                $barcode = \Modules\Inventory\Helpers\BarcodeHelper::generateVariationBarcode($productBarcode, $index + 1, 'EAN13');
                // Check if exists and regenerate if needed
                if (\Modules\Inventory\Helpers\BarcodeHelper::barcodeExists($barcode)) {
                    $barcode = \Modules\Inventory\Helpers\BarcodeHelper::generateUnique('EAN13', null, $variation->sku);
                }
            } else {
                $barcode = \Modules\Inventory\Helpers\BarcodeHelper::generateUnique('EAN13', null, $variation->sku);
            }
            
            $variation->barcode = $barcode;
            $variation->save();
            
            $generated[] = [
                'id' => $variation->id,
                'sku' => $variation->sku,
                'barcode' => $barcode,
            ];
        }
        
        return response()->json([
            'success' => true,
            'generated' => $generated,
            'count' => count($generated),
            'message' => count($generated) . ' barcodes generated',
        ]);
    }

    protected function getProductStock($productId, $warehouseId = null, $rackId = null, $lotId = null)
    {
        return StockLevel::where('product_id', $productId)->sum('qty');
    }
}