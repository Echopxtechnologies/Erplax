<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductCategory;
use App\Models\Inventory\Brand;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\Lot;
use App\Models\Inventory\StockMovement;
use App\Models\Inventory\Unit;
use App\Models\Inventory\Rack;
use App\Models\Inventory\StockLevel;
use App\Models\Inventory\StockTransfer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\DataTable;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\Validator;


class InventoryController extends Controller
{
    use DataTable;

    // ==================== IMPORT VALIDATION RULES ====================
    protected $productImportable = [
        'name'           => 'required|string|max:191',
        'sku'            => 'required|string|max:50',
        'barcode'        => 'nullable|string|max:50',
        'category_id'    => 'nullable|exists:product_categories,id',
        'brand_id'       => 'nullable|exists:brands,id',
        'unit_id'        => 'nullable|exists:units,id',
        'purchase_price' => 'required|numeric|min:0',
        'sale_price'     => 'required|numeric|min:0',
        'hsn_code'       => 'nullable|string|max:20',
        'min_stock_level'=> 'nullable|numeric|min:0',
        'max_stock_level'=> 'nullable|numeric|min:0',
    ];

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

    // ==================== PRODUCTS ====================
    public function productsIndex()
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

    public function productsData(Request $request)
    {
        // =====================
        // IMPORT (POST with file)
        // =====================
        if ($request->isMethod('post') && $request->hasFile('file')) {
            return $this->handleProductImport($request);
        }

        // =====================
        // TEMPLATE DOWNLOAD
        // =====================
        if ($request->has('template')) {
            return $this->downloadProductTemplate();
        }

        // =====================
        // EXPORT
        // =====================
        if ($request->has('export')) {
            return $this->exportProducts($request);
        }

        // =====================
        // BUILD QUERY
        // =====================
        $query = Product::with(['category', 'brand', 'unit'])->select('products.*');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Sorting
        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        // =====================
        // PAGINATE & RETURN
        // =====================
        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            $currentStock = $this->getProductStock($item->id);
            return [
                'id' => $item->id,
                'sku' => $item->sku,
                'name' => $item->name,
                'category_name' => $item->category?->name ?? '-',
                'brand_name' => $item->brand?->name ?? '-',
                'unit' => $item->unit?->short_name ?? 'PCS',
                'purchase_price' => number_format($item->purchase_price, 2),
                'sale_price' => number_format($item->sale_price, 2),
                'current_stock' => $currentStock,
                'min_stock_level' => $item->min_stock_level,
                'is_low_stock' => $item->min_stock_level > 0 && $currentStock < $item->min_stock_level,
                'is_active' => $item->is_active,
                'status' => $item->is_active ? 'Active' : 'Inactive',
                '_edit_url' => route('admin.inventory.products.edit', $item->id),
                '_show_url' => route('admin.inventory.products.show', $item->id),
                '_delete_url' => route('admin.inventory.products.destroy', $item->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    /**
     * Export Products to CSV
     */
    protected function exportProducts($request)
    {
        $query = Product::with(['category', 'brand', 'unit'])->select('products.*');

        // Apply same filters as list
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        $data = $query->get();
        $filename = 'products_' . date('Y-m-d') . '.csv';

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'SKU', 'Name', 'Category', 'Brand', 'Unit', 'Purchase Price', 'Sale Price', 'HSN Code', 'Min Stock', 'Max Stock', 'Status']);
            
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->sku,
                    $row->name,
                    $row->category?->name ?? '',
                    $row->brand?->name ?? '',
                    $row->unit?->short_name ?? 'PCS',
                    $row->purchase_price,
                    $row->sale_price,
                    $row->hsn_code ?? '',
                    $row->min_stock_level ?? 0,
                    $row->max_stock_level ?? 0,
                    $row->is_active ? 'Active' : 'Inactive',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

    /**
     * Download Product Import Template
     */
    protected function downloadProductTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Products');

        $columns = $this->productImportable;
        $headers = array_keys($columns);

        // Header row
        foreach ($headers as $index => $header) {
            $colLetter = Coordinate::stringFromColumnIndex($index + 1);
            $cell = $sheet->getCell($colLetter . '1');
            $cell->setValue($header);
            $cell->getStyle()->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
            $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4F46E5');
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // Hints row
        $colIndex = 0;
        foreach ($columns as $colName => $rules) {
            $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
            $hint = $this->buildImportHint($rules);
            $cell = $sheet->getCell($colLetter . '2');
            $cell->setValue($hint);
            $cell->getStyle()->getFont()->setItalic(true)->getColor()->setRGB('9CA3AF');
            $colIndex++;
        }

        // Instructions sheet with reference data
        $infoSheet = $spreadsheet->createSheet();
        $infoSheet->setTitle('Reference Data');
        $infoSheet->setCellValue('A1', 'REFERENCE DATA FOR IMPORT');
        $infoSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        $row = 3;
        
        // Categories
        $infoSheet->setCellValue('A' . $row, 'CATEGORIES (use ID):');
        $infoSheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        foreach (ProductCategory::where('is_active', true)->get() as $cat) {
            $infoSheet->setCellValue('A' . $row, $cat->id);
            $infoSheet->setCellValue('B' . $row, $cat->name);
            $row++;
        }
        
        $row++;
        // Brands
        $infoSheet->setCellValue('A' . $row, 'BRANDS (use ID):');
        $infoSheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        foreach (Brand::where('is_active', true)->get() as $brand) {
            $infoSheet->setCellValue('A' . $row, $brand->id);
            $infoSheet->setCellValue('B' . $row, $brand->name);
            $row++;
        }
        
        $row++;
        // Units
        $infoSheet->setCellValue('A' . $row, 'UNITS (use ID):');
        $infoSheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        foreach (Unit::where('is_active', true)->get() as $unit) {
            $infoSheet->setCellValue('A' . $row, $unit->id);
            $infoSheet->setCellValue('B' . $row, $unit->name . ' (' . $unit->short_name . ')');
            $row++;
        }

        $infoSheet->getColumnDimension('A')->setAutoSize(true);
        $infoSheet->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'products_import_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Handle Product Import
     */
    protected function handleProductImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        
        try {
            // Parse file
            if ($ext === 'csv') {
                $rows = $this->parseCsv($file);
            } else {
                $spreadsheet = IOFactory::load($file->getPathname());
                $sheet = $spreadsheet->getActiveSheet();
                $rows = [];
                $headers = [];
                
                foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);
                    
                    $rowData = [];
                    foreach ($cellIterator as $cell) {
                        $rowData[] = trim($cell->getValue() ?? '');
                    }
                    
                    if ($rowIndex === 1) {
                        $headers = $rowData;
                        continue;
                    }
                    
                    $rowAssoc = [];
                    foreach ($headers as $i => $h) {
                        if (!empty($h)) {
                            $rowAssoc[$h] = $rowData[$i] ?? '';
                        }
                    }
                    $rows[] = $rowAssoc;
                }
            }

            if (empty($rows)) {
                return response()->json(['success' => false, 'message' => 'No data found'], 400);
            }

            $results = ['total' => 0, 'success' => 0, 'failed' => 0, 'errors' => []];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNum = $index + 3;
                
                // Skip empty rows
                $isEmpty = true;
                foreach ($row as $v) {
                    if (!empty(trim($v))) { $isEmpty = false; break; }
                }
                if ($isEmpty) continue;
                
                // Skip hint row
                $first = reset($row);
                if (str_contains(strtolower($first), 'required') || str_contains(strtolower($first), 'optional')) continue;

                $results['total']++;

                // Check if SKU exists (for update)
                $rules = $this->productImportable;
                $existingProduct = Product::where('sku', $row['sku'] ?? '')->first();
                if ($existingProduct) {
                    $rules['sku'] = 'required|string|max:50|unique:products,sku,' . $existingProduct->id;
                }

                $validator = Validator::make($row, $rules);
                
                if ($validator->fails()) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNum}: " . $validator->errors()->first();
                    continue;
                }

                try {
                    $data = array_filter($row, fn($v) => $v !== '' && $v !== null);
                    $data['is_active'] = true;
                    
                    // Default unit
                    if (empty($data['unit_id'])) {
                        $defaultUnit = Unit::where('short_name', 'PCS')->first();
                        $data['unit_id'] = $defaultUnit?->id;
                    }
                    
                    if ($existingProduct) {
                        $existingProduct->update($data);
                    } else {
                        Product::create($data);
                    }
                    
                    $results['success']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNum}: " . $e->getMessage();
                }
            }

            if ($results['success'] === 0 && $results['failed'] > 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Import failed',
                    'results' => $results
                ], 422);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$results['success']} of {$results['total']} products imported",
                'results' => $results
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Parse CSV file
     */
    protected function parseCsv($file)
    {
        $rows = [];
        $headers = [];
        
        if (($handle = fopen($file->getPathname(), 'r')) !== false) {
            $line = 0;
            while (($data = fgetcsv($handle)) !== false) {
                $line++;
                if ($line === 1) {
                    $headers = array_map('trim', $data);
                    continue;
                }
                $row = [];
                foreach ($headers as $i => $h) {
                    $row[$h] = trim($data[$i] ?? '');
                }
                $rows[] = $row;
            }
            fclose($handle);
        }
        
        return $rows;
    }

    /**
     * Build hint from validation rules
     */
    protected function buildImportHint($rules)
    {
        $req = str_contains($rules, 'required') ? 'Required' : 'Optional';
        
        if (str_contains($rules, 'email')) return "{$req}, Email";
        if (str_contains($rules, 'integer')) return "{$req}, Integer";
        if (str_contains($rules, 'numeric')) return "{$req}, Number";
        if (str_contains($rules, 'date')) return "{$req}, Date (YYYY-MM-DD)";
        if (preg_match('/in:([^|]+)/', $rules, $m)) return "{$req}, Options: {$m[1]}";
        if (preg_match('/exists:([^,]+),(\w+)/', $rules, $m)) return "{$req}, ID from {$m[1]}";
        
        return "{$req}, Text";
    }

    public function productsCreate()
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.products.create', compact('categories', 'brands', 'units'));
    }

    public function productsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'sku' => 'required|string|max:50|unique:products,sku',
            'barcode' => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:product_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'nullable|exists:units,id',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'hsn_code' => 'nullable|string|max:20',
            'min_stock_level' => 'nullable|numeric|min:0',
            'max_stock_level' => 'nullable|numeric|min:0',
            'is_batch_managed' => 'boolean',
        ]);
        
        // Default to PCS unit if not specified
        if (empty($validated['unit_id'])) {
            $defaultUnit = Unit::where('short_name', 'PCS')->first();
            $validated['unit_id'] = $defaultUnit?->id;
        }
        
        $validated['is_active'] = true;
        $validated['is_batch_managed'] = $request->has('is_batch_managed');
        
        Product::create($validated);
        
        return redirect()->route('admin.inventory.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function productsShow($id)
    {
        $product = Product::with(['category', 'brand', 'unit'])->findOrFail($id);
        
        // Get stock by warehouse/rack from stock_levels table
        $stockByWarehouse = StockLevel::with(['warehouse', 'rack'])
            ->where('product_id', $id)
            ->where('qty', '>', 0)
            ->get();
        
        // Get recent movements
        $movements = StockMovement::with(['warehouse', 'rack'])
            ->where('product_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.inventory.products.show', compact('product', 'stockByWarehouse', 'movements'));
    }

    public function productsEdit($id)
    {
        $product = Product::findOrFail($id);
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.products.edit', compact('product', 'categories', 'brands', 'units'));
    }

    public function productsUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'sku' => 'required|string|max:50|unique:products,sku,' . $id,
            'barcode' => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:product_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'nullable|exists:units,id',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'hsn_code' => 'nullable|string|max:20',
            'min_stock_level' => 'nullable|numeric|min:0',
            'max_stock_level' => 'nullable|numeric|min:0',
            'is_batch_managed' => 'boolean',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_batch_managed'] = $request->has('is_batch_managed');
        $validated['is_active'] = $request->has('is_active');
        
        $product->update($validated);
        
        return redirect()->route('admin.inventory.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function productsDeactivate($id)
    {
        Product::where('id', $id)->update(['is_active' => false]);
        return response()->json(['success' => true, 'message' => 'Product deactivated']);
    }

    public function productsDestroy($id)
    {
        $product = Product::findOrFail($id);
        
        if (StockMovement::where('product_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete product with stock movements'], 422);
        }
        
        $product->delete();
        return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
    }

    // ==================== WAREHOUSES ====================
    public function warehousesIndex()
    {
        $stats = [
            'total' => Warehouse::count(),
            'active' => Warehouse::where('is_active', true)->count(),
        ];
        
        return view('admin.inventory.warehouses.index', compact('stats'));
    }

    public function warehousesData(Request $request)
    {
        $query = Warehouse::withCount('racks');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'city' => $item->city ?? '-',
                'state' => $item->state ?? '-',
                'type' => $item->type,
                'racks_count' => $item->racks_count,
                'contact_person' => $item->contact_person ?? '-',
                'phone' => $item->phone ?? '-',
                'is_default' => $item->is_default,
                'is_active' => $item->is_active,
                'status' => $item->is_active ? 'Active' : 'Inactive',
                '_edit_url' => route('admin.inventory.warehouses.edit', $item->id),
                '_delete_url' => route('admin.inventory.warehouses.destroy', $item->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    public function warehousesCreate()
    {
        return view('admin.inventory.warehouses.create');
    }

    public function warehousesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:warehouses,code',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'type' => 'required|in:STORAGE,SHOP,RETURN_CENTER',
            'is_default' => 'boolean',
        ]);
        
        $validated['is_active'] = true;
        $validated['is_default'] = $request->has('is_default');
        
        if ($validated['is_default']) {
            Warehouse::where('is_default', true)->update(['is_default' => false]);
        }
        
        Warehouse::create($validated);
        
        return redirect()->route('admin.inventory.warehouses.index')
            ->with('success', 'Warehouse created successfully!');
    }

    public function warehousesEdit($id)
    {
        $warehouse = Warehouse::with('racks')->findOrFail($id);
        return view('admin.inventory.warehouses.edit', compact('warehouse'));
    }

    public function warehousesUpdate(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:warehouses,code,' . $id,
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'type' => 'required|in:STORAGE,SHOP,RETURN_CENTER',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_default'] = $request->has('is_default');
        $validated['is_active'] = $request->has('is_active');
        
        if ($validated['is_default']) {
            Warehouse::where('is_default', true)->where('id', '!=', $id)->update(['is_default' => false]);
        }
        
        $warehouse->update($validated);
        
        return redirect()->route('admin.inventory.warehouses.index')
            ->with('success', 'Warehouse updated successfully!');
    }

    public function warehousesSetDefault($id)
    {
        Warehouse::where('is_default', true)->update(['is_default' => false]);
        Warehouse::where('id', $id)->update(['is_default' => true]);
        
        return response()->json(['success' => true, 'message' => 'Default warehouse updated']);
    }

    public function warehousesDeactivate($id)
    {
        Warehouse::where('id', $id)->update(['is_active' => false]);
        return response()->json(['success' => true, 'message' => 'Warehouse deactivated']);
    }

    public function warehousesDestroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        
        if (StockMovement::where('warehouse_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete warehouse with stock movements'], 422);
        }
        
        if (Rack::where('warehouse_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete warehouse with racks. Delete racks first.'], 422);
        }
        
        $warehouse->delete();
        return response()->json(['success' => true, 'message' => 'Warehouse deleted successfully']);
    }

    // ==================== RACKS ====================
    public function racksIndex()
    {
        $stats = [
            'total' => Rack::count(),
            'active' => Rack::where('is_active', true)->count(),
        ];
        
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.racks.index', compact('stats', 'warehouses'));
    }

    public function racksData(Request $request)
    {
        $query = Rack::with('warehouse');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('zone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'warehouse_name' => $item->warehouse?->name ?? '-',
                'zone' => $item->zone ?? '-',
                'aisle' => $item->aisle ?? '-',
                'level' => $item->level ?? '-',
                'full_location' => $item->full_location,
                'is_active' => $item->is_active,
                'status' => $item->is_active ? 'Active' : 'Inactive',
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    public function racksCreate()
    {
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.racks.create', compact('warehouses', 'units'));
    }

    public function racksStore(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:191',
            'zone' => 'nullable|string|max:50',
            'aisle' => 'nullable|string|max:50',
            'level' => 'nullable|string|max:50',
            'capacity' => 'nullable|numeric|min:0',
            'capacity_unit_id' => 'nullable|exists:units,id',
            'description' => 'nullable|string',
        ]);
        
        // Check unique code within warehouse
        $exists = Rack::where('warehouse_id', $validated['warehouse_id'])
            ->where('code', $validated['code'])
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'Rack code already exists in this warehouse')->withInput();
        }
        
        $validated['is_active'] = true;
        Rack::create($validated);
        
        return redirect()->route('admin.inventory.racks.index')
            ->with('success', 'Rack created successfully!');
    }

    public function racksEdit($id)
    {
        $rack = Rack::findOrFail($id);
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.racks.edit', compact('rack', 'warehouses', 'units'));
    }

    public function racksUpdate(Request $request, $id)
    {
        $rack = Rack::findOrFail($id);
        
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:191',
            'zone' => 'nullable|string|max:50',
            'aisle' => 'nullable|string|max:50',
            'level' => 'nullable|string|max:50',
            'capacity' => 'nullable|numeric|min:0',
            'capacity_unit_id' => 'nullable|exists:units,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        // Check unique code within warehouse (excluding current)
        $exists = Rack::where('warehouse_id', $validated['warehouse_id'])
            ->where('code', $validated['code'])
            ->where('id', '!=', $id)
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'Rack code already exists in this warehouse')->withInput();
        }
        
        $validated['is_active'] = $request->has('is_active');
        $rack->update($validated);
        
        return redirect()->route('admin.inventory.racks.index')
            ->with('success', 'Rack updated successfully!');
    }

    public function racksDeactivate($id)
    {
        Rack::where('id', $id)->update(['is_active' => false]);
        return response()->json(['success' => true, 'message' => 'Rack deactivated']);
    }

    public function racksDestroy($id)
    {
        $rack = Rack::findOrFail($id);
        
        if (StockLevel::where('rack_id', $id)->where('qty', '>', 0)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete rack with stock'], 422);
        }
        
        $rack->delete();
        return response()->json(['success' => true, 'message' => 'Rack deleted successfully']);
    }

    public function racksByWarehouse($warehouseId)
    {
        $racks = Rack::where('warehouse_id', $warehouseId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get(['id', 'code', 'name', 'zone']);
        
        return response()->json($racks);
    }

    // ==================== LOTS ====================
    public function lotsIndex()
    {
        $stats = [
            'total' => Lot::count(),
            'available' => Lot::where('status', 'AVAILABLE')->count(),
            'expired' => Lot::where('status', 'EXPIRED')->count(),
        ];
        
        $products = Product::where('is_active', true)->where('is_batch_managed', true)->orderBy('name')->get();
        
        return view('admin.inventory.lots.index', compact('stats', 'products'));
    }

    public function lotsData(Request $request)
    {
        $query = Lot::with('product');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('lot_no', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'lot_no' => $item->lot_no,
                'product_name' => $item->product?->name ?? '-',
                'product_sku' => $item->product?->sku ?? '-',
                'initial_qty' => $item->initial_qty,
                'purchase_price' => $item->purchase_price ? number_format($item->purchase_price, 2) : '-',
                'manufacturing_date' => $item->manufacturing_date ?? '-',
                'expiry_date' => $item->expiry_date ?? '-',
                'status' => $item->status,
                '_edit_url' => route('admin.inventory.lots.edit', $item->id),
                '_delete_url' => route('admin.inventory.lots.destroy', $item->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    public function lotsCreate()
    {
        $products = Product::where('is_active', true)->where('is_batch_managed', true)->orderBy('name')->get();
        return view('admin.inventory.lots.create', compact('products'));
    }

    public function lotsStore(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'lot_no' => 'required|string|max:100',
            'initial_qty' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'manufacturing_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:manufacturing_date',
            'status' => 'required|in:AVAILABLE,RESERVED,EXPIRED,CONSUMED',
            'remarks' => 'nullable|string',
        ], [
            'lot_no.unique' => 'This lot number already exists for the selected product.',
            'expiry_date.after_or_equal' => 'Expiry date must be after manufacturing date.',
        ]);
        
        // Check unique lot_no for product
        $exists = Lot::where('product_id', $validated['product_id'])
            ->where('lot_no', $validated['lot_no'])
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'This lot number already exists for the selected product.')->withInput();
        }
        
        Lot::create($validated);
        
        return redirect()->route('admin.inventory.lots.index')
            ->with('success', 'Lot created successfully!');
    }

    public function lotsEdit($id)
    {
        $lot = Lot::with('product')->findOrFail($id);
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.lots.edit', compact('lot', 'products'));
    }

    public function lotsUpdate(Request $request, $id)
    {
        $lot = Lot::findOrFail($id);
        
        $validated = $request->validate([
            'lot_no' => 'required|string|max:50',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'manufacturing_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'status' => 'required|in:AVAILABLE,RESERVED,EXPIRED,CONSUMED',
            'remarks' => 'nullable|string',
        ]);
        
        $lot->update($validated);
        
        return redirect()->route('admin.inventory.lots.index')
            ->with('success', 'Lot updated successfully!');
    }

    public function lotsDeactivate($id)
    {
        Lot::where('id', $id)->update(['status' => 'CONSUMED']);
        return response()->json(['success' => true, 'message' => 'Lot deactivated']);
    }

    public function lotsDestroy($id)
    {
        $lot = Lot::findOrFail($id);
        
        if (StockMovement::where('lot_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete lot with movements'], 422);
        }
        
        $lot->delete();
        return response()->json(['success' => true, 'message' => 'Lot deleted successfully']);
    }

    public function lotsByProduct($productId)
    {
        $lots = Lot::where('product_id', $productId)
            ->where('status', 'AVAILABLE')
            ->orderBy('expiry_date', 'asc')
            ->get();
        
        return response()->json($lots);
    }

    public function lotsCheck(Request $request)
    {
        $lotNo = $request->get('lot_no');
        $productId = $request->get('product_id');
        
        $query = Lot::where('lot_no', $lotNo);
        
        if ($productId) {
            $query->where('product_id', $productId);
        }
        
        $existingLot = $query->first();
        
        if ($existingLot) {
            return response()->json([
                'exists' => true,
                'lot_no' => $existingLot->lot_no,
                'product_name' => $existingLot->product->name ?? null,
                'product_id' => $existingLot->product_id,
            ]);
        }
        
        return response()->json(['exists' => false]);
    }

    // ==================== STOCK MOVEMENTS ====================
    public function stockReceive()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.stock.receive', compact('products', 'warehouses', 'units'));
    }

    public function stockReceiveStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'qty' => 'required|numeric|min:0.01',
            'rack_id' => 'nullable|exists:racks,id',
            'lot_id' => 'nullable|exists:lots,id',
            'unit_id' => 'nullable|exists:units,id',
            'purchase_price' => 'nullable|numeric|min:0',
            'reason' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);
            $unitId = $request->unit_id ?? $product->unit_id;
            $baseQty = $request->qty;
            
            $refNo = 'RCV-' . date('Ymd') . '-' . str_pad(StockMovement::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            
            StockMovement::create([
                'reference_no' => $refNo,
                'product_id' => $request->product_id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
                'lot_id' => $request->lot_id,
                'unit_id' => $unitId,
                'movement_type' => 'IN',
                'qty' => $request->qty,
                'base_qty' => $baseQty,
                'purchase_price' => $request->purchase_price ?? $product->purchase_price,
                'reason' => $request->reason ?? 'Stock received',
                'created_by' => auth()->id(),
            ]);
            
            $stockLevel = StockLevel::firstOrNew([
                'product_id' => $request->product_id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
            ]);
            
            $stockLevel->qty = ($stockLevel->qty ?? 0) + $request->qty;
            $stockLevel->unit_id = $unitId;
            $stockLevel->save();
            
            DB::commit();
            
            return redirect()->route('admin.inventory.products.show', $request->product_id)
                ->with('success', "Stock received successfully! Reference: {$refNo}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to receive stock: ' . $e->getMessage())->withInput();
        }
    }

    public function stockDeliver()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.stock.deliver', compact('products', 'warehouses', 'units'));
    }

    public function stockDeliverStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'qty' => 'required|numeric|min:0.01',
            'rack_id' => 'nullable|exists:racks,id',
            'reason' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);
            
            $stockLevel = StockLevel::where('product_id', $request->product_id)
                ->where('warehouse_id', $request->warehouse_id)
                ->when($request->rack_id, fn($q) => $q->where('rack_id', $request->rack_id))
                ->first();
                
            if (!$stockLevel || $stockLevel->qty < $request->qty) {
                return back()->with('error', 'Insufficient stock available.')->withInput();
            }
            
            $refNo = 'DLV-' . date('Ymd') . '-' . str_pad(StockMovement::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            
            StockMovement::create([
                'reference_no' => $refNo,
                'product_id' => $request->product_id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id ?? $stockLevel->rack_id,
                'unit_id' => $product->unit_id,
                'movement_type' => 'OUT',
                'qty' => $request->qty,
                'base_qty' => $request->qty,
                'reason' => $request->reason ?? 'Stock delivered',
                'created_by' => auth()->id(),
            ]);
            
            $stockLevel->qty -= $request->qty;
            $stockLevel->save();
            
            DB::commit();
            
            return redirect()->route('admin.inventory.products.show', $request->product_id)
                ->with('success', "Stock delivered successfully! Reference: {$refNo}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to deliver stock: ' . $e->getMessage())->withInput();
        }
    }

    public function stockReturns()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.stock.returns', compact('products', 'warehouses', 'units'));
    }

    public function stockReturnsStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'qty' => 'required|numeric|min:0.01',
            'rack_id' => 'nullable|exists:racks,id',
            'reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);
            
            $refNo = 'RTN-' . date('Ymd') . '-' . str_pad(StockMovement::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            
            StockMovement::create([
                'reference_no' => $refNo,
                'product_id' => $request->product_id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
                'unit_id' => $product->unit_id,
                'movement_type' => 'RETURN',
                'qty' => $request->qty,
                'base_qty' => $request->qty,
                'reason' => $request->reason,
                'created_by' => auth()->id(),
            ]);
            
            $stockLevel = StockLevel::firstOrNew([
                'product_id' => $request->product_id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
            ]);
            
            $stockLevel->qty = ($stockLevel->qty ?? 0) + $request->qty;
            $stockLevel->unit_id = $product->unit_id;
            $stockLevel->save();
            
            DB::commit();
            
            return redirect()->route('admin.inventory.products.show', $request->product_id)
                ->with('success', "Return processed successfully! Reference: {$refNo}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process return: ' . $e->getMessage())->withInput();
        }
    }

    public function stockAdjustments()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.stock.adjustments', compact('products', 'warehouses', 'units'));
    }

    public function stockAdjustmentsStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'adjustment_type' => 'required|in:add,subtract,set',
            'qty' => 'required|numeric|min:0',
            'rack_id' => 'nullable|exists:racks,id',
            'reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);
            
            $stockLevel = StockLevel::firstOrNew([
                'product_id' => $request->product_id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
            ]);
            
            $currentQty = $stockLevel->qty ?? 0;
            $newQty = $currentQty;
            $movementQty = $request->qty;
            
            switch ($request->adjustment_type) {
                case 'add':
                    $newQty = $currentQty + $request->qty;
                    break;
                case 'subtract':
                    $newQty = max(0, $currentQty - $request->qty);
                    $movementQty = $currentQty - $newQty;
                    break;
                case 'set':
                    $newQty = $request->qty;
                    $movementQty = abs($newQty - $currentQty);
                    break;
            }
            
            $refNo = 'ADJ-' . date('Ymd') . '-' . str_pad(StockMovement::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            
            StockMovement::create([
                'reference_no' => $refNo,
                'product_id' => $request->product_id,
                'warehouse_id' => $request->warehouse_id,
                'rack_id' => $request->rack_id,
                'unit_id' => $product->unit_id,
                'movement_type' => 'ADJUSTMENT',
                'qty' => $movementQty,
                'base_qty' => $movementQty,
                'reason' => "[{$request->adjustment_type}] " . $request->reason . " (Before: {$currentQty}, After: {$newQty})",
                'created_by' => auth()->id(),
            ]);
            
            $stockLevel->qty = $newQty;
            $stockLevel->unit_id = $product->unit_id;
            $stockLevel->save();
            
            DB::commit();
            
            return redirect()->route('admin.inventory.products.show', $request->product_id)
                ->with('success', "Stock adjusted successfully! Reference: {$refNo} | {$currentQty} → {$newQty}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to adjust stock: ' . $e->getMessage())->withInput();
        }
    }

    public function stockTransfer()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.stock.transfer', compact('products', 'warehouses', 'units'));
    }

    public function stockTransferStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id',
            'qty' => 'required|numeric|min:0.01',
            'from_rack_id' => 'nullable|exists:racks,id',
            'to_rack_id' => 'nullable|exists:racks,id',
            'reason' => 'nullable|string|max:500',
        ]);
        
        // Must be different location (warehouse OR rack)
        if ($request->from_warehouse_id == $request->to_warehouse_id && 
            $request->from_rack_id == $request->to_rack_id) {
            return back()->with('error', 'Source and destination must be different. Choose a different warehouse or rack.')->withInput();
        }

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);
            
            // Check stock at source
            $sourceStock = StockLevel::where('product_id', $request->product_id)
                ->where('warehouse_id', $request->from_warehouse_id)
                ->when($request->from_rack_id, fn($q) => $q->where('rack_id', $request->from_rack_id))
                ->first();
                
            if (!$sourceStock || $sourceStock->qty < $request->qty) {
                return back()->with('error', 'Insufficient stock at source location.')->withInput();
            }
            
            $refNo = 'TRF-' . date('Ymd') . '-' . str_pad(StockMovement::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            
            // Get warehouse/rack names for reason
            $fromWarehouse = Warehouse::find($request->from_warehouse_id);
            $toWarehouse = Warehouse::find($request->to_warehouse_id);
            $fromRack = $request->from_rack_id ? Rack::find($request->from_rack_id) : null;
            $toRack = $request->to_rack_id ? Rack::find($request->to_rack_id) : null;
            
            $transferReason = $request->reason ?? 'Stock Transfer';
            
            // Create StockTransfer record
            StockTransfer::create([
                'transfer_no' => $refNo,
                'product_id' => $request->product_id,
                'lot_id' => $request->lot_id ?? null,
                'unit_id' => $product->unit_id,
                'from_warehouse_id' => $request->from_warehouse_id,
                'to_warehouse_id' => $request->to_warehouse_id,
                'from_rack_id' => $request->from_rack_id,
                'to_rack_id' => $request->to_rack_id,
                'qty' => $request->qty,
                'base_qty' => $request->qty,
                'status' => 'COMPLETED',
                'reason' => $transferReason,
                'created_by' => auth()->id(),
            ]);
            
            // OUT from source
            StockMovement::create([
                'reference_no' => $refNo,
                'product_id' => $request->product_id,
                'warehouse_id' => $request->from_warehouse_id,
                'rack_id' => $request->from_rack_id,
                'lot_id' => $request->lot_id ?? null,
                'unit_id' => $product->unit_id,
                'movement_type' => 'TRANSFER',
                'qty' => $request->qty,
                'base_qty' => $request->qty,
                'reason' => "Transfer OUT → " . ($toWarehouse->name ?? '') . ($toRack ? " ({$toRack->code})" : ''),
                'created_by' => auth()->id(),
            ]);
            
            $sourceStock->qty -= $request->qty;
            $sourceStock->save();
            
            // IN to destination
            StockMovement::create([
                'reference_no' => $refNo . '-IN',
                'product_id' => $request->product_id,
                'warehouse_id' => $request->to_warehouse_id,
                'rack_id' => $request->to_rack_id,
                'lot_id' => $request->lot_id ?? null,
                'unit_id' => $product->unit_id,
                'movement_type' => 'TRANSFER',
                'qty' => $request->qty,
                'base_qty' => $request->qty,
                'reason' => "Transfer IN ← " . ($fromWarehouse->name ?? '') . ($fromRack ? " ({$fromRack->code})" : ''),
                'created_by' => auth()->id(),
            ]);
            
            $destStock = StockLevel::firstOrNew([
                'product_id' => $request->product_id,
                'warehouse_id' => $request->to_warehouse_id,
                'rack_id' => $request->to_rack_id,
            ]);
            
            $destStock->qty = ($destStock->qty ?? 0) + $request->qty;
            $destStock->unit_id = $product->unit_id;
            $destStock->save();
            
            DB::commit();
            
            // Build success message
            $fromLocation = $fromWarehouse->name . ($fromRack ? " → {$fromRack->code}" : '');
            $toLocation = $toWarehouse->name . ($toRack ? " → {$toRack->code}" : '');
            
            return redirect()->route('admin.inventory.products.show', $request->product_id)
                ->with('success', "Stock transferred successfully! {$request->qty} units from {$fromLocation} to {$toLocation}. Ref: {$refNo}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to transfer stock: ' . $e->getMessage())->withInput();
        }
    }

    public function stockMovements(Request $request)
    {
        $query = StockMovement::with(['product.unit', 'warehouse', 'rack', 'unit', 'creator'])
            ->orderBy('created_at', 'desc');
        
        // Filters
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        
        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }
        
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        $movements = $query->paginate(20);
        
        // Load transfer details for TRANSFER movements
        $transferRefNos = $movements->where('movement_type', 'TRANSFER')
            ->pluck('reference_no')
            ->map(fn($ref) => str_replace('-IN', '', $ref))
            ->unique()
            ->toArray();
        
        $transfers = collect();
        if (!empty($transferRefNos)) {
            $transfers = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'fromRack', 'toRack'])
                ->whereIn('transfer_no', $transferRefNos)
                ->get()
                ->keyBy('transfer_no');
        }
        
        // For filter dropdowns
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.stock.movements', compact('movements', 'products', 'warehouses', 'transfers'));
    }

    public function stockMovementsData(Request $request)
    {
        $query = StockMovement::with(['product.unit', 'warehouse', 'rack', 'unit', 'creator']);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    });
            });
        }

        // Filters
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        
        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }
        
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        // Get transfer details
        $transferRefNos = collect($data->items())
            ->where('movement_type', 'TRANSFER')
            ->pluck('reference_no')
            ->map(fn($ref) => str_replace('-IN', '', $ref))
            ->unique()
            ->toArray();

        $transfers = [];
        if (!empty($transferRefNos)) {
            $transfers = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'fromRack', 'toRack'])
                ->whereIn('transfer_no', $transferRefNos)
                ->get()
                ->keyBy('transfer_no')
                ->toArray();
        }

        $items = collect($data->items())->map(function ($item) use ($transfers) {
            $unitName = $item->unit->short_name ?? $item->product->unit->short_name ?? 'PCS';
            $isPositive = in_array($item->movement_type, ['IN', 'RETURN']);
            
            $isTransferOut = $item->movement_type == 'TRANSFER' && !str_contains($item->reference_no, '-IN');
            $isTransferIn = $item->movement_type == 'TRANSFER' && str_contains($item->reference_no, '-IN');
            
            $transferNo = str_replace('-IN', '', $item->reference_no);
            $transfer = $transfers[$transferNo] ?? null;
            
            $warehouseName = $item->warehouse->name ?? '-';
            $rackCode = $item->rack->code ?? '';
            $locationDisplay = $warehouseName . ($rackCode ? " ({$rackCode})" : '');
            
            $fromWarehouse = '-';
            $fromWarehouseCode = '';
            $fromRackCode = '';
            $fromRackName = '';
            $toWarehouse = '-';
            $toWarehouseCode = '';
            $toRackCode = '';
            $toRackName = '';
            $isSameWarehouse = false;
            
            if ($transfer) {
                $fromWarehouse = $transfer['from_warehouse']['name'] ?? '-';
                $fromWarehouseCode = $transfer['from_warehouse']['code'] ?? '';
                $fromRackCode = $transfer['from_rack']['code'] ?? '';
                $fromRackName = $transfer['from_rack']['name'] ?? '';
                $toWarehouse = $transfer['to_warehouse']['name'] ?? '-';
                $toWarehouseCode = $transfer['to_warehouse']['code'] ?? '';
                $toRackCode = $transfer['to_rack']['code'] ?? '';
                $toRackName = $transfer['to_rack']['name'] ?? '';
                $isSameWarehouse = ($transfer['from_warehouse_id'] ?? 0) == ($transfer['to_warehouse_id'] ?? 1);
                
                $locationDisplay = "From: {$fromWarehouse}" . ($fromRackCode ? " ({$fromRackCode})" : '') . 
                                   " → To: {$toWarehouse}" . ($toRackCode ? " ({$toRackCode})" : '');
            }
            
            return [
                'id' => $item->id,
                'reference_no' => $item->reference_no ?? '-',
                'created_at' => $item->created_at->format('d M Y'),
                'created_time' => $item->created_at->format('h:i A'),
                'movement_type' => $item->movement_type,
                'product_name' => $item->product->name ?? '-',
                'product_sku' => $item->product->sku ?? '-',
                'product_initials' => strtoupper(substr($item->product->name ?? 'P', 0, 2)),
                'qty' => number_format($item->qty, 2),
                'qty_display' => ($isPositive ? '+' : '-') . number_format($item->qty, 2),
                'unit' => $unitName,
                'is_positive' => $isPositive,
                'reason' => $item->reason ?? '-',
                'created_by' => $item->creator->name ?? 'System',
                'created_by_initial' => strtoupper(substr($item->creator->name ?? 'S', 0, 1)),
                'location_display' => $locationDisplay,
                'warehouse_name' => $warehouseName,
                'rack_code' => $rackCode,
                'rack_name' => $item->rack->name ?? '',
                'is_transfer' => $item->movement_type == 'TRANSFER',
                'is_transfer_out' => $isTransferOut,
                'is_transfer_in' => $isTransferIn,
                'from_warehouse' => $fromWarehouse,
                'from_warehouse_code' => $fromWarehouseCode,
                'from_rack_code' => $fromRackCode,
                'from_rack_name' => $fromRackName,
                'to_warehouse' => $toWarehouse,
                'to_warehouse_code' => $toWarehouseCode,
                'to_rack_code' => $toRackCode,
                'to_rack_name' => $toRackName,
                'is_same_warehouse' => $isSameWarehouse,
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    public function stockCheck(Request $request)
    {
        $productId = $request->get('product_id');
        $warehouseId = $request->get('warehouse_id');
        $rackId = $request->get('rack_id');
        $lotId = $request->get('lot_id');
        
        if (!$productId || !$warehouseId) {
            return response()->json([
                'quantity' => 0,
                'unit' => 'PCS'
            ]);
        }
        
        // Get product for unit info
        $product = Product::with('unit')->find($productId);
        $unitName = $product?->unit?->short_name ?? 'PCS';
        
        // Query stock_levels table
        $query = StockLevel::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId);
        
        if ($rackId) {
            $query->where('rack_id', $rackId);
        }
        
        if ($lotId) {
            $query->where('lot_id', $lotId);
        }
        
        $quantity = $query->sum('qty');
        
        return response()->json([
            'quantity' => round($quantity, 4),
            'unit' => $unitName,
            'product_id' => $productId,
            'warehouse_id' => $warehouseId,
            'rack_id' => $rackId,
            'lot_id' => $lotId
        ]);
    }

    // ==================== REPORTS ====================
    public function reportStockSummary(Request $request)
    {
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        
        $query = StockLevel::with(['product.category', 'product.brand', 'warehouse', 'rack', 'unit'])
            ->where('qty', '>', 0);
        
        if ($request->warehouse_id) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        if ($request->category_id) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }
        if ($request->brand_id) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('brand_id', $request->brand_id);
            });
        }
        
        $stockReport = $query->get()->map(function ($item) {
            return (object) [
                'product_id' => $item->product_id,
                'product_name' => $item->product?->name ?? '-',
                'sku' => $item->product?->sku ?? '-',
                'unit_name' => $item->unit?->short_name ?? 'PCS',
                'purchase_price' => $item->product?->purchase_price ?? 0,
                'category_name' => $item->product?->category?->name ?? '-',
                'brand_name' => $item->product?->brand?->name ?? '-',
                'warehouse_name' => $item->warehouse?->name ?? '-',
                'rack_name' => $item->rack?->name ?? '-',
                'total_stock' => $item->qty,
                'stock_value' => $item->qty * ($item->product?->purchase_price ?? 0),
            ];
        });
        
        $totalValue = $stockReport->sum('stock_value');
        
        return view('admin.inventory.reports.stock-summary', compact(
            'stockReport', 'totalValue', 'warehouses', 'categories', 'brands'
        ));
    }

    public function reportLotSummary(Request $request)
    {
        $products = Product::where('is_active', true)->where('is_batch_managed', true)->orderBy('name')->get();
        
        $query = Lot::with(['product']);
        
        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $lots = $query->orderBy('expiry_date', 'asc')->get();
        
        return view('admin.inventory.reports.lot-summary', compact('lots', 'products'));
    }

    public function reportMovementHistory(Request $request)
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.reports.movement-history', compact('products', 'warehouses'));
    }

    public function reportMovementHistoryData(Request $request)
    {
        $query = StockMovement::with(['product', 'warehouse', 'rack', 'lot', 'unit', 'creator']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'reference_no' => $item->reference_no ?? '-',
                'product_name' => $item->product?->name ?? '-',
                'warehouse_name' => $item->warehouse?->name ?? '-',
                'rack_name' => $item->rack?->name ?? '-',
                'lot_no' => $item->lot?->lot_no ?? '-',
                'qty' => $item->qty,
                'unit' => $item->unit?->short_name ?? 'PCS',
                'movement_type' => $item->movement_type,
                'stock_before' => $item->stock_before,
                'stock_after' => $item->stock_after,
                'reason' => $item->reason ?? '-',
                'created_by' => $item->creator?->name ?? '-',
                'created_at' => $item->created_at->format('Y-m-d H:i'),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    // ==================== SETTINGS ====================
    public function settingsIndex()
    {
        $categories = ProductCategory::with('children')->whereNull('parent_id')->orderBy('sort_order')->get();
        $brands = Brand::orderBy('name')->get();
        $units = Unit::with('baseUnit')->orderBy('name')->get();
        
        return view('admin.inventory.settings.index', compact('categories', 'brands', 'units'));
    }

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

    public function categoriesDeactivate($id)
    {
        ProductCategory::where('id', $id)->update(['is_active' => false]);
        return response()->json(['success' => true, 'message' => 'Category deactivated']);
    }

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
                '_delete_url' => route('admin.inventory.settings.brands.destroy', $item->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

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

    public function brandsDeactivate($id)
    {
        Brand::where('id', $id)->update(['is_active' => false]);
        return response()->json(['success' => true, 'message' => 'Brand deactivated']);
    }

    public function brandsDestroy($id)
    {
        $brand = Brand::findOrFail($id);
        
        if (Product::where('brand_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete brand with products'], 422);
        }
        
        $brand->delete();
        return response()->json(['success' => true, 'message' => 'Brand deleted successfully']);
    }

    // ==================== UNITS ====================
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

    public function unitsDestroy($id)
    {
        $unit = Unit::findOrFail($id);
        
        if (Product::where('unit_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete unit used by products'], 422);
        }
        
        $unit->delete();
        return response()->json(['success' => true, 'message' => 'Unit deleted successfully']);
    }

    // ==================== HELPER METHODS ====================
    private function getProductStock($productId, $warehouseId = null, $rackId = null, $lotId = null)
    {
        $query = StockLevel::where('product_id', $productId);
        
        if ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        }
        if ($rackId) {
            $query->where('rack_id', $rackId);
        }
        if ($lotId) {
            $query->where('lot_id', $lotId);
        }
        
        return $query->sum('qty') ?? 0;
    }
}