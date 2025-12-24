<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Lot;
use Modules\Inventory\Models\StockMovement;
use Modules\Core\Traits\DataTableTrait;

class LotController extends BaseController
{
    use DataTableTrait;

    // ==================== DATATABLE CONFIGURATION ====================
    
    protected $model = Lot::class;
    
    protected $with = ['product.images', 'product.unit', 'variation', 'stockLevels'];
    
    protected $searchable = [
        'lot_no',
        'batch_no',
        'product.name',
        'product.sku',
        'variation.sku',
        'variation.variation_name',
    ];
    
    protected $sortable = [
        'id',
        'lot_no',
        'batch_no',
        'initial_qty',
        'purchase_price',
        'sale_price',
        'manufacturing_date',
        'expiry_date',
        'status',
        'created_at',
    ];
    
    protected $filterable = [
        'product_id',
        'variation_id',
        'status',
    ];
    
    protected $routePrefix = 'inventory.lots';
    
    protected $exportable = [
        'id',
        'lot_no',
        'batch_no',
        'product.name',
        'product.sku',
        'initial_qty',
        'purchase_price',
        'sale_price',
        'manufacturing_date',
        'expiry_date',
        'status',
    ];
    
    protected $exportTitle = 'Lots Report';
    
    protected $importable = true;

    // ==================== DATA METHOD ====================
    
    public function data(Request $request)
    {
        if ($request->has('export') || $request->get('action') === 'export') {
            return $this->handleExport($request);
        }
        
        if ($request->has('import') || $request->hasFile('import_file')) {
            return $this->handleImport($request);
        }
        
        return $this->getDataTableResponse($request);
    }

    // ==================== DATATABLE RESPONSE ====================
    
    protected function getDataTableResponse(Request $request)
    {
        $query = Lot::with($this->with);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('lot_no', 'like', "%{$search}%")
                    ->orWhere('batch_no', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('expiring_soon')) {
            $query->where('status', 'ACTIVE')
                ->whereNotNull('expiry_date')
                ->where('expiry_date', '<=', now()->addDays(30))
                ->where('expiry_date', '>', now());
        }

        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        
        if (in_array($sortField, $this->sortable)) {
            $query->orderBy($sortField, $sortDir);
        } else {
            $query->orderBy('id', $sortDir);
        }

        $perPage = $request->get('per_page', 25);
        $paginated = $query->paginate($perPage);

        $items = collect($paginated->items())->map(function ($item) {
            return $this->mapRow($item);
        });

        return response()->json([
            'data' => $items,
            'total' => $paginated->total(),
            'current_page' => $paginated->currentPage(),
            'last_page' => $paginated->lastPage(),
            'per_page' => $paginated->perPage(),
        ]);
    }

    // ==================== EXPORT HANDLER ====================
    
    protected function handleExport(Request $request)
    {
        $format = $request->get('format', $request->get('export', 'csv'));
        
        $query = Lot::with($this->with);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('lot_no', 'like', "%{$search}%")
                    ->orWhere('batch_no', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderBy('id', 'desc')->get();

        $exportData = $items->map(function ($item) {
            return $this->mapExportRow($item);
        });

        $filename = 'lots_export_' . date('Y-m-d_His');

        if ($format === 'csv') {
            return $this->exportCsv($exportData, $filename);
        } elseif ($format === 'excel' || $format === 'xlsx') {
            return $this->exportExcel($exportData, $filename);
        }

        return response()->json(['error' => 'Invalid export format'], 400);
    }

    // ==================== CSV EXPORT ====================
    
    protected function exportCsv($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            if ($data->count() > 0) {
                fputcsv($file, array_keys($data->first()));
            }
            
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ==================== EXCEL EXPORT (FIXED FOR NEW PHPSPREADSHEET) ====================
    
    protected function exportExcel($data, $filename)
    {
        if (!class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
            return $this->exportCsv($data, $filename);
        }
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Lots');
        
        if ($data->count() > 0) {
            $headers = array_keys($data->first());
            $columnCount = count($headers);
            
            // Use fromArray instead of deprecated setCellValueByColumnAndRow
            $sheet->fromArray($headers, null, 'A1');
            
            $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnCount);
            $headerRange = 'A1:' . $lastColumn . '1';
            
            $sheet->getStyle($headerRange)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ]);
            
            $rowNum = 2;
            foreach ($data as $row) {
                $sheet->fromArray(array_values($row), null, 'A' . $rowNum);
                $rowNum++;
            }
            
            foreach (range('A', $lastColumn) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            $sheet->freezePane('A2');
        }
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, "{$filename}.xlsx", [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}.xlsx\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }

    // ==================== IMPORT HANDLER ====================
    
    protected function handleImport(Request $request)
    {
        if (!$request->hasFile('import_file')) {
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }

        $file = $request->file('import_file');
        $extension = strtolower($file->getClientOriginalExtension());

        try {
            if ($extension === 'csv') {
                $data = $this->parseCsv($file);
            } else {
                return response()->json(['success' => false, 'message' => 'Only CSV files supported'], 400);
            }

            $imported = 0;
            $updated = 0;
            $errors = [];

            foreach ($data as $index => $row) {
                try {
                    $result = $this->importRow($row);
                    if ($result === 'updated') {
                        $updated++;
                    } else {
                        $imported++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'imported' => $imported,
                'updated' => $updated,
                'errors' => $errors,
                'message' => "Imported {$imported} new, updated {$updated}" . (count($errors) ? " with " . count($errors) . " errors" : ""),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    protected function parseCsv($file)
    {
        $data = [];
        $handle = fopen($file->getPathname(), 'r');
        
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }
        
        $headers = fgetcsv($handle);
        $headers = array_map(fn($h) => trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $h)), $headers);
        
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === count($headers)) {
                $data[] = array_combine($headers, $row);
            }
        }
        
        fclose($handle);
        return $data;
    }

    protected function importRow($row)
    {
        $data = [
            'product_id' => $row['Product ID'] ?? $row['product_id'] ?? null,
            'lot_no' => $row['Lot No'] ?? $row['lot_no'] ?? null,
            'batch_no' => $row['Batch No'] ?? $row['batch_no'] ?? null,
            'initial_qty' => $row['Initial Qty'] ?? $row['initial_qty'] ?? null,
            'purchase_price' => $row['Purchase Price'] ?? $row['purchase_price'] ?? null,
            'sale_price' => $row['Sale Price'] ?? $row['sale_price'] ?? null,
            'manufacturing_date' => $row['Manufacturing Date'] ?? $row['manufacturing_date'] ?? null,
            'expiry_date' => $row['Expiry Date'] ?? $row['expiry_date'] ?? null,
            'status' => $row['Status'] ?? $row['status'] ?? 'ACTIVE',
            'notes' => $row['Notes'] ?? $row['notes'] ?? null,
        ];

        if (empty($data['product_id'])) {
            throw new \Exception('Product ID is required');
        }
        
        if (empty($data['lot_no'])) {
            throw new \Exception('Lot No is required');
        }
        
        if (!Product::find($data['product_id'])) {
            throw new \Exception("Product ID {$data['product_id']} not found");
        }

        $data = array_filter($data, fn($v) => $v !== '' && $v !== null && $v !== '-');
        $data['status'] = strtoupper($data['status'] ?? 'ACTIVE');

        $existing = Lot::where('product_id', $data['product_id'])
            ->where('lot_no', $data['lot_no'])
            ->first();

        if ($existing) {
            $existing->update($data);
            return 'updated';
        }
        
        Lot::create($data);
        return 'created';
    }

    // ==================== MAP ROW FOR DATATABLE ====================
    
    protected function mapRow($item)
    {
        $primaryImage = null;
        if ($item->product && $item->product->images && $item->product->images->count() > 0) {
            $primaryImage = $item->product->images->where('is_primary', true)->first() 
                ?? $item->product->images->first();
        }
        
        $currentStock = $item->stockLevels ? $item->stockLevels->sum('qty') : 0;
        $unitName = $item->product?->unit?->short_name ?? 'PCS';
        
        $daysToExpiry = null;
        $isExpired = false;
        if ($item->expiry_date) {
            $daysToExpiry = (int) now()->startOfDay()->diffInDays($item->expiry_date->startOfDay(), false);
            $isExpired = $item->expiry_date->isPast();
        }
        
        $expiryStatus = 'no_expiry';
        $expiryBadgeColor = 'secondary';
        if ($item->expiry_date) {
            if ($isExpired || $daysToExpiry < 0) {
                $expiryStatus = 'expired';
                $expiryBadgeColor = 'danger';
            } elseif ($daysToExpiry <= 30) {
                $expiryStatus = 'expiring_soon';
                $expiryBadgeColor = 'warning';
            } elseif ($daysToExpiry <= 90) {
                $expiryStatus = 'expiring_medium';
                $expiryBadgeColor = 'info';
            } else {
                $expiryStatus = 'ok';
                $expiryBadgeColor = 'success';
            }
        }
        
        $statusBadgeColor = match($item->status) {
            'ACTIVE' => 'success',
            'EXPIRED' => 'danger',
            'RECALLED' => 'warning',
            'CONSUMED' => 'secondary',
            default => 'secondary',
        };
        
        return [
            'id' => $item->id,
            'lot_no' => $item->lot_no,
            'batch_no' => $item->batch_no ?? '-',
            'product_id' => $item->product_id,
            'product_name' => $item->product?->name ?? '-',
            'product_sku' => $item->product?->sku ?? '-',
            'product_image' => $primaryImage ? asset('storage/' . $primaryImage->image_path) : null,
            'variation_id' => $item->variation_id,
            'variation_name' => $item->variation?->variation_name ?? null,
            'variation_sku' => $item->variation?->sku ?? null,
            'initial_qty' => $item->initial_qty ? number_format($item->initial_qty, 2) : '-',
            'current_stock' => number_format($currentStock, 2),
            'current_stock_raw' => $currentStock,
            'unit_name' => $unitName,
            'purchase_price' => $item->purchase_price,
            'sale_price' => $item->sale_price,
            'purchase_price_display' => $item->purchase_price ? number_format($item->purchase_price, 2) : '-',
            'sale_price_display' => $item->sale_price ? number_format($item->sale_price, 2) : '-',
            'manufacturing_date' => $item->manufacturing_date?->format('Y-m-d') ?? '-',
            'expiry_date' => $item->expiry_date?->format('Y-m-d') ?? '-',
            'expiry_date_display' => $item->expiry_date?->format('d M Y') ?? '-',
            'days_to_expiry' => $daysToExpiry,
            'is_expired' => $isExpired,
            'expiry_status' => $expiryStatus,
            'expiry_badge_color' => $expiryBadgeColor,
            'status' => $item->status,
            'status_badge_color' => $statusBadgeColor,
            'created_at' => $item->created_at?->format('Y-m-d H:i'),
            '_show_url' => route('inventory.lots.show', $item->id),
            '_edit_url' => route('inventory.lots.edit', $item->id),
            '_delete_url' => route('inventory.lots.destroy', $item->id),
        ];
    }

    // ==================== MAP ROW FOR EXPORT ====================
    
    protected function mapExportRow($item)
    {
        $currentStock = $item->stockLevels ? $item->stockLevels->sum('qty') : 0;
        $daysToExpiry = $item->expiry_date 
            ? (int) now()->startOfDay()->diffInDays($item->expiry_date->startOfDay(), false) 
            : null;
        
        return [
            'ID' => $item->id,
            'Lot No' => $item->lot_no,
            'Batch No' => $item->batch_no ?? '',
            'Product ID' => $item->product_id,
            'Product Name' => $item->product?->name ?? '',
            'Product SKU' => $item->product?->sku ?? '',
            'Initial Qty' => $item->initial_qty ?? 0,
            'Current Stock' => $currentStock,
            'Unit' => $item->product?->unit?->short_name ?? 'PCS',
            'Purchase Price' => $item->purchase_price ?? '',
            'Sale Price' => $item->sale_price ?? '',
            'Manufacturing Date' => $item->manufacturing_date?->format('Y-m-d') ?? '',
            'Expiry Date' => $item->expiry_date?->format('Y-m-d') ?? '',
            'Days to Expiry' => $daysToExpiry ?? '',
            'Status' => $item->status,
            'Notes' => $item->notes ?? '',
            'Created At' => $item->created_at?->format('Y-m-d H:i'),
        ];
    }

    // ==================== INDEX ====================
    
    public function index()
    {
        $stats = [
            'total' => Lot::count(),
            'active' => Lot::where('status', 'ACTIVE')->count(),
            'expired' => Lot::where('status', 'EXPIRED')->count(),
            'expiring_soon' => Lot::where('status', 'ACTIVE')
                ->whereNotNull('expiry_date')
                ->where('expiry_date', '<=', now()->addDays(30))
                ->where('expiry_date', '>', now())
                ->count(),
        ];
        
        $products = Product::where('is_active', true)
            ->where('is_batch_managed', true)
            ->orderBy('name')
            ->get(['id', 'name', 'sku']);
        
        return view('inventory::lots.index', compact('stats', 'products'));
    }

    // ==================== CREATE ====================
    
    public function create()
    {
        $products = Product::with(['images', 'unit', 'variations' => function($q) {
                $q->where('is_active', true);
            }])
            ->where('is_active', true)
            ->where('is_batch_managed', true)
            ->orderBy('name')
            ->get();
            
        return view('inventory::lots.create', compact('products'));
    }
  
    // ==================== STORE ====================
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'nullable|exists:product_variations,id',
            'lot_no' => 'required|string|max:100',
            'batch_no' => 'nullable|string|max:100',
            'initial_qty' => 'nullable|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'manufacturing_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:manufacturing_date',
            'status' => 'required|in:ACTIVE,EXPIRED,RECALLED,CONSUMED',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        // Check lot uniqueness per product + variation combo
        $exists = Lot::where('product_id', $validated['product_id'])
            ->where('lot_no', $validated['lot_no'])
            ->when($validated['variation_id'] ?? null, 
                fn($q, $vid) => $q->where('variation_id', $vid),
                fn($q) => $q->whereNull('variation_id')
            )
            ->exists();
            
        if ($exists) {
            return back()
                ->with('error', 'This lot number already exists for the selected product/variation.')
                ->withInput();
        }
        
        if (!empty($validated['expiry_date']) && $validated['status'] === 'ACTIVE') {
            if (now()->startOfDay()->gt($validated['expiry_date'])) {
                $validated['status'] = 'EXPIRED';
            }
        }
        
        $lot = Lot::create($validated);
        
        return redirect()
            ->route('inventory.lots.index')
            ->with('success', "Lot created successfully! Lot No: {$lot->lot_no}");
    }

    // ==================== SHOW ====================
    
    public function show($id)
    {
        $lot = Lot::with([
            'product.images', 
            'product.unit',
            'stockLevels.warehouse',
            'stockLevels.rack',
        ])->findOrFail($id);
        
        $stockMovements = StockMovement::where('lot_id', $id)
            ->with(['warehouse', 'rack', 'unit', 'creator'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
        
        return view('inventory::lots.show', compact('lot', 'stockMovements'));
    }

    // ==================== EDIT ====================
    
    public function edit($id)
    {
        $lot = Lot::with(['product.images', 'product.unit', 'product.variations', 'variation'])->findOrFail($id);
        
        $products = Product::with(['unit', 'variations' => function($q) {
                $q->where('is_active', true);
            }])
            ->where('is_active', true)
            ->where('is_batch_managed', true)
            ->orderBy('name')
            ->get();
            
        return view('inventory::lots.edit', compact('lot', 'products'));
    }

    // ==================== UPDATE ====================
    
    public function update(Request $request, $id)
    {
        $lot = Lot::findOrFail($id);
        
        $validated = $request->validate([
            'variation_id' => 'nullable|exists:product_variations,id',
            'lot_no' => 'required|string|max:100',
            'batch_no' => 'nullable|string|max:100',
            'initial_qty' => 'nullable|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'manufacturing_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'status' => 'required|in:ACTIVE,EXPIRED,RECALLED,CONSUMED',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        // Check lot uniqueness per product + variation combo
        $exists = Lot::where('product_id', $lot->product_id)
            ->where('lot_no', $validated['lot_no'])
            ->where('id', '!=', $id)
            ->when($validated['variation_id'] ?? null, 
                fn($q, $vid) => $q->where('variation_id', $vid),
                fn($q) => $q->whereNull('variation_id')
            )
            ->exists();
            
        if ($exists) {
            return back()
                ->with('error', 'This lot number already exists for this product/variation.')
                ->withInput();
        }
        
        $lot->update($validated);
        
        return redirect()
            ->route('inventory.lots.index')
            ->with('success', 'Lot updated successfully!');
    }

    // ==================== DESTROY ====================
    
    public function destroy($id)
    {
        $lot = Lot::with('stockLevels')->findOrFail($id);
        
        if (StockMovement::where('lot_id', $id)->exists()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Cannot delete lot with stock movements.'
                ], 422);
            }
            return back()->with('error', 'Cannot delete lot with stock movements.');
        }
        
        $currentStock = $lot->stockLevels ? $lot->stockLevels->sum('qty') : 0;
        if ($currentStock > 0) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Cannot delete lot with existing stock.'
                ], 422);
            }
            return back()->with('error', 'Cannot delete lot with existing stock.');
        }
        
        $lot->delete();
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Lot deleted successfully']);
        }
        
        return redirect()
            ->route('inventory.lots.index')
            ->with('success', 'Lot deleted successfully!');
    }

    // ==================== STATUS ACTIONS ====================
    
    public function deactivate($id)
    {
        $lot = Lot::findOrFail($id);
        $lot->update(['status' => 'CONSUMED']);
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Lot marked as consumed']);
        }
        return back()->with('success', 'Lot marked as consumed');
    }

    public function markExpired($id)
    {
        $lot = Lot::findOrFail($id);
        $lot->update(['status' => 'EXPIRED']);
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Lot marked as expired']);
        }
        return back()->with('success', 'Lot marked as expired');
    }

    public function markRecalled(Request $request, $id)
    {
        $lot = Lot::findOrFail($id);
        $reason = $request->get('reason', 'Manual recall');
        
        $notes = $lot->notes ?? '';
        $notes .= ($notes ? "\n" : '') . "[RECALLED] {$reason} - " . now()->format('Y-m-d H:i');
        
        $lot->update(['status' => 'RECALLED', 'notes' => $notes]);
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Lot marked as recalled']);
        }
        return back()->with('success', 'Lot marked as recalled');
    }

    // ==================== AJAX: LOTS BY PRODUCT ====================

    public function byProduct($productId, Request $request)
    {
        $product = Product::with('unit')->find($productId);
        
        if (!$product) {
            return response()->json([]);
        }
        
        $variationId = $request->get('variation_id');
        $unitName = $product->unit?->short_name ?? 'PCS';
        
        $query = Lot::with(['stockLevels', 'variation'])
            ->where('product_id', $productId)
            ->where('status', 'ACTIVE');
        
        // Filter by variation if provided, or show lots without variation
        if ($variationId) {
            $query->where('variation_id', $variationId);
        }
        
        $lots = $query->orderBy('expiry_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($lot) use ($unitName) {
                $currentStock = $lot->stockLevels ? $lot->stockLevels->sum('qty') : 0;
                $displayName = $lot->lot_no . ($lot->batch_no ? ' / ' . $lot->batch_no : '');
                if ($lot->variation) {
                    $displayName .= ' [' . ($lot->variation->variation_name ?? $lot->variation->sku) . ']';
                }
                
                return [
                    'id' => $lot->id,
                    'lot_no' => $lot->lot_no,
                    'batch_no' => $lot->batch_no,
                    'variation_id' => $lot->variation_id,
                    'variation_name' => $lot->variation?->variation_name ?? $lot->variation?->sku,
                    'display_name' => $displayName,
                    'expiry_date' => $lot->expiry_date?->format('Y-m-d'),
                    'expiry_display' => $lot->expiry_date?->format('d M Y'),
                    'current_stock' => $currentStock,
                    'stock_display' => number_format($currentStock, 2) . ' ' . $unitName,
                    'purchase_price' => $lot->purchase_price,
                    'sale_price' => $lot->sale_price,
                ];
            });
        
        return response()->json($lots);
    }

    // ==================== AJAX: LOTS WITH STOCK BY PRODUCT ====================
    
    public function withStockByProduct($productId, Request $request)
    {
        $warehouseId = $request->get('warehouse_id');
        $rackId = $request->get('rack_id');
        $variationId = $request->get('variation_id');
        
        $product = Product::with('unit')->find($productId);
        
        if (!$product || !$product->is_batch_managed) {
            return response()->json(['is_batch_managed' => false, 'lots' => []]);
        }
        
        $unitName = $product->unit?->short_name ?? 'PCS';
        
        $query = Lot::with(['stockLevels', 'variation'])
            ->where('product_id', $productId)
            ->where('status', 'ACTIVE');
        
        // Filter by variation if provided
        if ($variationId) {
            $query->where('variation_id', $variationId);
        }
        
        if ($warehouseId) {
            $query->whereHas('stockLevels', function ($q) use ($warehouseId, $rackId, $variationId) {
                $q->where('warehouse_id', $warehouseId)->where('qty', '>', 0);
                if ($rackId) $q->where('rack_id', $rackId);
                if ($variationId) $q->where('variation_id', $variationId);
            });
        }
        
        $lots = $query->orderBy('expiry_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($lot) use ($unitName, $warehouseId, $rackId, $variationId) {
                $stock = 0;
                if ($lot->stockLevels) {
                    $filtered = $lot->stockLevels;
                    if ($warehouseId) {
                        $filtered = $filtered->where('warehouse_id', $warehouseId);
                    }
                    if ($rackId) {
                        $filtered = $filtered->where('rack_id', $rackId);
                    }
                    if ($variationId) {
                        $filtered = $filtered->where('variation_id', $variationId);
                    }
                    $stock = $filtered->sum('qty');
                }
                
                $displayName = $lot->lot_no . ($lot->batch_no ? ' / ' . $lot->batch_no : '');
                if ($lot->variation) {
                    $displayName .= ' [' . ($lot->variation->variation_name ?? $lot->variation->sku) . ']';
                }
                
                return [
                    'id' => $lot->id,
                    'lot_no' => $lot->lot_no,
                    'batch_no' => $lot->batch_no,
                    'variation_id' => $lot->variation_id,
                    'variation_name' => $lot->variation?->variation_name ?? $lot->variation?->sku,
                    'display_name' => $displayName,
                    'expiry_date' => $lot->expiry_date?->format('Y-m-d'),
                    'stock' => $stock,
                    'stock_display' => number_format($stock, 2) . ' ' . $unitName,
                ];
            })
            ->filter(fn($lot) => $lot['stock'] > 0)
            ->values();
        
        return response()->json(['is_batch_managed' => true, 'unit_name' => $unitName, 'lots' => $lots]);
    }

    // ==================== AJAX: CHECK LOT NUMBER ====================
    
    public function check(Request $request)
    {
        $lotNo = $request->get('lot_no');
        $productId = $request->get('product_id');
        $variationId = $request->get('variation_id');
        $excludeId = $request->get('exclude_id');
        
        if (!$lotNo) return response()->json(['exists' => false]);
        
        $query = Lot::where('lot_no', $lotNo);
        if ($productId) $query->where('product_id', $productId);
        
        // Check variation: if variation_id is provided, check that specific variation
        // If not provided, check for lots without variation
        if ($variationId) {
            $query->where('variation_id', $variationId);
        } else {
            $query->whereNull('variation_id');
        }
        
        if ($excludeId) $query->where('id', '!=', $excludeId);
        
        $existing = $query->with(['product', 'variation'])->first();
        
        if ($existing) {
            return response()->json([
                'exists' => true,
                'lot_id' => $existing->id,
                'lot_no' => $existing->lot_no,
                'product_name' => $existing->product?->name,
                'variation_name' => $existing->variation?->variation_name ?? $existing->variation?->sku,
                'status' => $existing->status,
            ]);
        }
        
        return response()->json(['exists' => false]);
    }

    // ==================== AJAX: GENERATE LOT NUMBER ====================
    
    public function generateLotNo(Request $request)
    {
        $productId = $request->get('product_id');
        $prefix = $request->get('prefix') ?: 'LOT-' . date('Ymd') . '-';
        
        $query = Lot::where('lot_no', 'like', $prefix . '%');
        if ($productId) $query->where('product_id', $productId);
        
        $last = $query->orderBy('id', 'desc')->first();
        
        $num = 1;
        if ($last && preg_match('/(\d+)$/', $last->lot_no, $matches)) {
            $num = (int) $matches[1] + 1;
        }
        
        $lotNo = $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
        
        return response()->json(['success' => true, 'lot_no' => $lotNo]);
    }

    // ==================== AJAX: GET PRODUCT INFO ====================
    
    public function getProductInfo($productId)
    {
        $product = Product::with(['images', 'unit'])->find($productId);
        
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }
        
        $primaryImage = $product->images?->where('is_primary', true)->first() ?? $product->images?->first();
        
        return response()->json([
            'success' => true,
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'purchase_price' => $product->purchase_price,
            'sale_price' => $product->sale_price,
            'unit_name' => $product->unit?->short_name ?? 'PCS',
            'is_batch_managed' => $product->is_batch_managed,
            'image' => $primaryImage ? asset('storage/' . $primaryImage->image_path) : null,
        ]);
    }

    // ==================== AJAX: EXPIRING SOON LIST ====================
    
    public function expiringSoon(Request $request)
    {
        $days = (int) $request->get('days', 30);
        
        $lots = Lot::with(['product.unit', 'stockLevels'])
            ->where('status', 'ACTIVE')
            ->whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [now(), now()->addDays($days)])
            ->whereHas('stockLevels', fn($q) => $q->where('qty', '>', 0))
            ->orderBy('expiry_date', 'asc')
            ->get();
        
        $mapped = $lots->map(fn($lot) => [
            'id' => $lot->id,
            'lot_no' => $lot->lot_no,
            'batch_no' => $lot->batch_no,
            'product_id' => $lot->product_id,
            'product_name' => $lot->product?->name,
            'product_sku' => $lot->product?->sku,
            'expiry_date' => $lot->expiry_date?->format('Y-m-d'),
            'expiry_display' => $lot->expiry_date?->format('d M Y'),
            'days_remaining' => (int) now()->diffInDays($lot->expiry_date, false),
            'current_stock' => $lot->stockLevels?->sum('qty') ?? 0,
            'unit' => $lot->product?->unit?->short_name ?? 'PCS',
        ]);
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'days' => $days,
                'count' => $lots->count(),
                'lots' => $mapped,
            ]);
        }
        
        return view('inventory::lots.expiring', ['lots' => $lots, 'days' => $days]);
    }

    // ==================== CRON: UPDATE STATUSES ====================
    
    public function updateStatuses()
    {
        $expired = Lot::where('status', 'ACTIVE')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now()->startOfDay())
            ->update(['status' => 'EXPIRED']);
        
        $consumed = 0;
        Lot::where('status', 'ACTIVE')
            ->with('stockLevels')
            ->chunk(100, function ($lots) use (&$consumed) {
                foreach ($lots as $lot) {
                    $totalStock = $lot->stockLevels?->sum('qty') ?? 0;
                    if ($totalStock <= 0 && $lot->stockLevels->count() > 0) {
                        $lot->update(['status' => 'CONSUMED']);
                        $consumed++;
                    }
                }
            });
        
        return response()->json([
            'success' => true,
            'expired_count' => $expired,
            'consumed_count' => $consumed,
            'message' => "Updated: {$expired} expired, {$consumed} consumed",
        ]);
    }
}