<?php

namespace App\Http\Controllers\Admin\Inventory;

use Illuminate\Http\Request;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductCategory;
use App\Models\Inventory\Brand;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\Rack;
use App\Models\Inventory\Unit;
use App\Models\Inventory\Tax;
use App\Models\Inventory\Tag;
use App\Models\Inventory\ProductImage;
use App\Models\Inventory\ProductUnit;
use App\Models\Inventory\ProductAttribute;
use App\Models\Inventory\AttributeValue;
use App\Models\Inventory\ProductVariation;
use App\Models\Inventory\StockLevel;
use App\Models\Inventory\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Traits\DataTable;

class ProductController extends BaseController
{
    use DataTable;

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
            '_edit_url' => route('admin.inventory.products.edit', $item->id),
            '_show_url' => route('admin.inventory.products.show', $item->id),
            '_delete_url' => route('admin.inventory.products.destroy', $item->id),
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
        $stats = [
            'totalProducts' => Product::where('is_active', true)->count(),
            'totalCategories' => ProductCategory::where('is_active', true)->count(),
            'totalBrands' => Brand::where('is_active', true)->count(),
            'totalWarehouses' => Warehouse::where('is_active', true)->count(),
            'totalRacks' => Rack::where('is_active', true)->count(),
        ];
        
        $lowStockProducts = Product::where('is_active', true)
            ->where('min_stock_level', '>', 0)
            ->get()
            ->filter(function ($product) {
                $stock = $this->getProductStock($product->id);
                $product->current_stock = $stock;
                return $stock < $product->min_stock_level;
            })->take(10);
        
        $recentMovements = StockMovement::with(['product', 'warehouse', 'rack', 'unit'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('admin.inventory.dashboard', compact('stats', 'lowStockProducts', 'recentMovements'));
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
        
        return view('admin.inventory.products.index', compact('stats', 'categories', 'brands'));
    }

    // ==================== CREATE ====================
    public function create()
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        $taxes = Tax::where('is_active', true)->orderBy('name')->get();
        $attributes = ProductAttribute::with('values')->where('is_active', true)->orderBy('sort_order')->get();
        
        return view('admin.inventory.products.create', compact('categories', 'brands', 'units', 'taxes', 'attributes'));
    }

    // ==================== STORE - FIXED ATTRIBUTE HANDLING ====================
    public function store(Request $request)
    {
        Log::info('ProductController::store - Request received', [
            'has_images' => $request->hasFile('images'),
            'files_count' => $request->hasFile('images') ? count($request->file('images')) : 0,
            'attributes' => $request->input('attributes'),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'sku' => 'required|string|max:100|unique:products,sku',
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
            if ($request->hasFile('images')) {
                $files = $request->file('images');
                $primaryIndex = (int) $request->input('primary_image', 0);
                
                Log::info('ProductController::store - Processing images', [
                    'product_id' => $product->id,
                    'files_count' => count($files),
                    'primary_index' => $primaryIndex,
                ]);
                
                $uploadedCount = 0;
                
                foreach ($files as $index => $file) {
                    if (!$file || !$file->isValid()) continue;
                    
                    $isPrimary = ($index === $primaryIndex);
                    $image = ProductImage::uploadForProduct($product, $file, $isPrimary);
                    
                    if ($image) {
                        $uploadedCount++;
                        Log::info('ProductController::store - Image uploaded', [
                            'image_id' => $image->id,
                            'is_primary' => $image->is_primary,
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
                    
                    if ($request->boolean('generate_variations')) {
                        $product->createVariationsFromCombinations();
                    }
                }
            }
            
            DB::commit();
            
            Log::info('ProductController::store - Product creation complete', ['product_id' => $product->id]);
            
            return redirect()
                ->route('admin.inventory.products.index')
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
        $product = Product::with([
            'category', 'brand', 'unit', 'images', 'tags',
            'productUnits.unit', 'attributes.values',
            'variations.attributeValues.attribute',
            'stockLevels.warehouse', 'stockLevels.rack',
        ])->findOrFail($id);
        
        $warehouses = Warehouse::where('is_active', true)->get();
        
        $stockByWarehouse = $product->stockLevels()
            ->select('warehouse_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('warehouse_id')
            ->with('warehouse')
            ->get();
        
        $recentMovements = StockMovement::where('product_id', $id)
            ->with(['warehouse', 'unit', 'variation'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
        return view('admin.inventory.products.show', compact(
            'product', 'warehouses', 'stockByWarehouse', 'recentMovements'
        ));
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
        
        return view('admin.inventory.products.edit', compact(
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
        
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'sku' => 'required|string|max:100|unique:products,sku,' . $id,
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
        
        $validated['can_be_sold'] = $request->has('can_be_sold');
        $validated['can_be_purchased'] = $request->has('can_be_purchased');
        $validated['track_inventory'] = $request->has('track_inventory');
        $validated['has_variants'] = $request->has('has_variants');
        $validated['is_batch_managed'] = $request->has('is_batch_managed');
        $validated['is_active'] = $request->has('is_active');

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
            if ($request->hasFile('images')) {
                $files = $request->file('images');
                
                foreach ($files as $index => $file) {
                    if ($file && $file->isValid()) {
                        ProductImage::uploadForProduct($product, $file, false);
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
                ->route('admin.inventory.products.index')
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
                ->route('admin.inventory.products.index')
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

    public function getVariations($productId)
    {
        $product = Product::with(['variations.attributeValues.attribute'])->findOrFail($productId);
        $variations = $product->variations->map(fn($var) => [
            'id' => $var->id,
            'sku' => $var->sku,
            'barcode' => $var->barcode,
            'variation_name' => $var->display_name,
            'purchase_price' => $var->effective_purchase_price,
            'sale_price' => $var->effective_sale_price,
            'stock_qty' => $var->current_stock,
            'is_active' => $var->is_active,
            'attributes' => $var->attributeValues->map(fn($av) => [
                'attribute' => $av->attribute->name,
                'value' => $av->value,
                'color_code' => $av->color_code,
            ]),
        ]);
        return response()->json(['success' => true, 'variations' => $variations]);
    }

    public function updateVariation(Request $request, $variationId)
    {
        $variation = ProductVariation::findOrFail($variationId);
        $validated = $request->validate([
            'sku' => 'required|string|max:100|unique:product_variations,sku,' . $variationId,
            'barcode' => 'nullable|string|max:100',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);
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

    protected function getProductStock($productId, $warehouseId = null, $rackId = null, $lotId = null)
    {
        return StockLevel::where('product_id', $productId)->sum('qty');
    }
}