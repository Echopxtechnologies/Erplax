<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductCategory;
use Modules\Inventory\Models\Brand;
use Modules\Inventory\Models\Unit;

class SettingsController extends BaseController
{
    // ==================== SETTINGS INDEX ====================
    public function index()
    {
        $categories = ProductCategory::with('children')->whereNull('parent_id')->orderBy('sort_order')->get();
        $brands = Brand::orderBy('name')->get();
        $units = Unit::with('baseUnit')->orderBy('name')->get();
        
        return view('inventory::settings.index', compact('categories', 'brands', 'units'));
    }

    // ==================== CATEGORIES DATA (DataTable) ====================
    public function categoriesData(Request $request)
    {
        $query = ProductCategory::with('parent');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'sort_order');
        $sortDir = $request->get('dir', 'asc');
        $query->orderBy($sortField, $sortDir);

        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'parent_id' => $item->parent_id,
                'parent_name' => $item->parent?->name ?? '-',
                'description' => $item->description,
                'sort_order' => $item->sort_order,
                'is_active' => $item->is_active,
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    // ==================== CATEGORIES STORE ====================
    public function categoriesStore(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:product_categories,code',
            'name' => 'required|string|max:100',
            'parent_id' => 'nullable|exists:product_categories,id',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        
        $validated['is_active'] = true;
        ProductCategory::create($validated);
        
        return response()->json(['success' => true, 'message' => 'Category created successfully']);
    }

    // ==================== CATEGORIES UPDATE ====================
    public function categoriesUpdate(Request $request, $id)
    {
        $category = ProductCategory::findOrFail($id);
        
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:product_categories,code,' . $id,
            'name' => 'required|string|max:100',
            'parent_id' => 'nullable|exists:product_categories,id',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        $category->update($validated);
        return response()->json(['success' => true, 'message' => 'Category updated successfully']);
    }

    // ==================== CATEGORIES DEACTIVATE ====================
    public function categoriesDeactivate($id)
    {
        ProductCategory::where('id', $id)->update(['is_active' => false]);
        return response()->json(['success' => true, 'message' => 'Category deactivated']);
    }

    // ==================== CATEGORIES DESTROY ====================
    public function categoriesDestroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        
        if (Product::where('category_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete category with products'], 422);
        }
        if (ProductCategory::where('parent_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete category with subcategories'], 422);
        }
        
        $category->delete();
        return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
    }

    // ==================== BRANDS DATA (DataTable) ====================
    public function brandsData(Request $request)
    {
        $query = Brand::query();

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $sortField = $request->get('sort', 'name');
        $sortDir = $request->get('dir', 'asc');
        $query->orderBy($sortField, $sortDir);

        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'logo' => $item->logo,
                'description' => $item->description ?? '-',
                'is_active' => $item->is_active,
                'status' => $item->is_active ? 'Active' : 'Inactive',
                '_delete_url' => route('inventory.settings.brands.destroy', $item->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    // ==================== BRANDS STORE ====================
    public function brandsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);
        
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }
        
        $validated['is_active'] = true;
        Brand::create($validated);
        
        return response()->json(['success' => true, 'message' => 'Brand created successfully']);
    }

    // ==================== BRANDS UPDATE ====================
    public function brandsUpdate(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }
        
        $brand->update($validated);
        return response()->json(['success' => true, 'message' => 'Brand updated successfully']);
    }

    // ==================== BRANDS DEACTIVATE ====================
    public function brandsDeactivate($id)
    {
        Brand::where('id', $id)->update(['is_active' => false]);
        return response()->json(['success' => true, 'message' => 'Brand deactivated']);
    }

    // ==================== BRANDS DESTROY ====================
    public function brandsDestroy($id)
    {
        $brand = Brand::findOrFail($id);
        
        if (Product::where('brand_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete brand with products'], 422);
        }
        
        $brand->delete();
        return response()->json(['success' => true, 'message' => 'Brand deleted successfully']);
    }

    // ==================== UNITS DATA (DataTable) ====================
    public function unitsData(Request $request)
    {
        $query = Unit::with('baseUnit');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('short_name', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'name');
        $sortDir = $request->get('dir', 'asc');
        $query->orderBy($sortField, $sortDir);

        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'short_name' => $item->short_name,
                'base_unit_name' => $item->baseUnit?->short_name ?? '-',
                'conversion_factor' => $item->conversion_factor,
                'is_active' => $item->is_active,
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    // ==================== UNITS STORE ====================
    public function unitsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'short_name' => 'required|string|max:20|unique:units,short_name',
            'base_unit_id' => 'nullable|exists:units,id',
            'conversion_factor' => 'required|numeric|min:0.0001',
        ]);
        
        $validated['is_active'] = true;
        Unit::create($validated);
        
        return response()->json(['success' => true, 'message' => 'Unit created successfully']);
    }

    // ==================== UNITS UPDATE ====================
    public function unitsUpdate(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'short_name' => 'required|string|max:20|unique:units,short_name,' . $id,
            'base_unit_id' => 'nullable|exists:units,id',
            'conversion_factor' => 'required|numeric|min:0.0001',
            'is_active' => 'boolean',
        ]);
        
        $unit->update($validated);
        return response()->json(['success' => true, 'message' => 'Unit updated successfully']);
    }

    // ==================== UNITS DESTROY ====================
    public function unitsDestroy($id)
    {
        $unit = Unit::findOrFail($id);
        
        if (Product::where('unit_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete unit used by products'], 422);
        }
        
        $unit->delete();
        return response()->json(['success' => true, 'message' => 'Unit deleted successfully']);
    }
}