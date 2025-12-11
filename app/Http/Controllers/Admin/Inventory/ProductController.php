<?php

namespace App\Http\Controllers\Admin\Inventory;

use Illuminate\Http\Request;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductCategory;
use App\Models\Inventory\Brand;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\Rack;
use App\Models\Inventory\Unit;
use App\Models\Inventory\StockLevel;
use App\Models\Inventory\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ProductController extends BaseController
{
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

    // ==================== PRODUCTS INDEX ====================
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

    // ==================== PRODUCTS DATA (DataTable) ====================
    public function data(Request $request)
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

    // ==================== CREATE ====================
    public function create()
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.products.create', compact('categories', 'brands', 'units'));
    }

    // ==================== STORE ====================
    public function store(Request $request)
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

    // ==================== SHOW ====================
    public function show($id)
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

    // ==================== EDIT ====================
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.products.edit', compact('product', 'categories', 'brands', 'units'));
    }

    // ==================== UPDATE ====================
    public function update(Request $request, $id)
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

    // ==================== DEACTIVATE ====================
    public function deactivate($id)
    {
        Product::where('id', $id)->update(['is_active' => false]);
        return response()->json(['success' => true, 'message' => 'Product deactivated']);
    }

    // ==================== DESTROY ====================
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        if (StockMovement::where('product_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete product with stock movements'], 422);
        }
        
        $product->delete();
        return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
    }

    // ==================== EXPORT PRODUCTS ====================
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

    // ==================== DOWNLOAD TEMPLATE ====================
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

    // ==================== HANDLE IMPORT ====================
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
}