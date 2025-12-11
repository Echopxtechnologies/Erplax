<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * DataTable Trait - Handles everything automatically
 * 
 * USAGE IN CONTROLLER:
 * ====================
 * 
 * class ProductController extends Controller
 * {
 *     use DataTable;
 * 
 *     protected $model = Product::class;
 *     protected $with = ['category', 'brand'];  // eager load
 *     protected $searchable = ['name', 'sku', 'barcode'];
 *     protected $sortable = ['id', 'name', 'price', 'created_at'];
 *     protected $filterable = ['category_id', 'brand_id', 'status'];
 * 
 *     // For Import - just define validation rules!
 *     protected $importable = [
 *         'name'        => 'required|string|max:191',
 *         'sku'         => 'required|string|max:50|unique:products,sku',
 *         'category_id' => 'nullable|exists:product_categories,id',
 *         'brand_id'    => 'nullable|exists:brands,id',
 *         'price'       => 'required|numeric|min:0',
 *     ];
 * 
 *     // Optional: customize what's exported
 *     protected $exportable = ['id', 'sku', 'name', 'price'];
 * 
 *     // Optional: customize row mapping for JSON response
 *     protected function mapRow($item) {
 *         return [
 *             'id' => $item->id,
 *             'name' => $item->name,
 *             'category_name' => $item->category?->name ?? '-',
 *             '_edit_url' => route('admin.products.edit', $item->id),
 *         ];
 *     }
 * }
 * 
 * ROUTE:
 * ======
 * Route::match(['get', 'post'], '/data', [ProductController::class, 'handleData'])->name('data');
 */
trait DataTable
{
    /**
     * Main handler - call this from your route
     * Handles: List, Search, Filter, Sort, Export, Import, Template Download
     */
    public function handleData(Request $request)
    {
        // Import
        if ($request->isMethod('post') && $request->hasFile('file')) {
            return $this->dtImport($request);
        }

        // Template Download
        if ($request->has('template')) {
            return $this->dtTemplate();
        }

        // Export
        if ($request->has('export')) {
            return $this->dtExport($request);
        }

        // List with search, filter, sort, pagination
        return $this->dtList($request);
    }

    /**
     * List data with search, filters, sorting, pagination
     */
    protected function dtList(Request $request)
    {
        $query = $this->model::query();

        // Eager load
        if (property_exists($this, 'with') && !empty($this->with)) {
            $query->with($this->with);
        }

        // Search
        if ($search = $request->get('search')) {
            $searchable = $this->searchable ?? ['name'];
            $query->where(function ($q) use ($search, $searchable) {
                foreach ($searchable as $col) {
                    if (str_contains($col, '.')) {
                        // Relation search: 'category.name'
                        [$relation, $field] = explode('.', $col);
                        $q->orWhereHas($relation, fn($q2) => $q2->where($field, 'LIKE', "%{$search}%"));
                    } else {
                        $q->orWhere($col, 'LIKE', "%{$search}%");
                    }
                }
            });
        }

        // Filters (auto-detect from request)
        $this->applyFilters($query, $request);

        // Sorting
        $sortCol = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        $sortable = $this->sortable ?? ['id', 'created_at'];
        if (in_array($sortCol, $sortable)) {
            $query->orderBy($sortCol, $sortDir);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Paginate
        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        // Map rows
        $items = collect($data->items())->map(function ($item) {
            if (method_exists($this, 'mapRow')) {
                return $this->mapRow($item);
            }
            return $this->defaultMapRow($item);
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    /**
     * Default row mapping
     */
    protected function defaultMapRow($item)
    {
        $row = $item->toArray();
        
        // Add action URLs
        $prefix = $this->routePrefix ?? null;
        if ($prefix) {
            try {
                $row['_edit_url'] = route("{$prefix}.edit", $item->id);
                $row['_show_url'] = route("{$prefix}.show", $item->id);
                $row['_delete_url'] = route("{$prefix}.destroy", $item->id);
            } catch (\Exception $e) {}
        }
        
        return $row;
    }

    /**
     * Apply filters from request
     */
    protected function applyFilters($query, Request $request)
    {
        $skip = ['page', 'per_page', 'search', 'sort', 'dir', 'export', 'template', 'ids', '_'];
        $filterable = $this->filterable ?? [];

        foreach ($request->all() as $key => $value) {
            if (in_array($key, $skip) || $value === '' || $value === null) continue;

            // Check if in filterable list or is a common pattern
            if (in_array($key, $filterable) || str_ends_with($key, '_id') || in_array($key, ['status', 'type', 'is_active'])) {
                $query->where($key, $value);
            }

            // Date range
            if ($key === 'from_date') {
                $query->whereDate('created_at', '>=', $value);
            }
            if ($key === 'to_date') {
                $query->whereDate('created_at', '<=', $value);
            }
        }
    }

    /**
     * Export to CSV
     */
    protected function dtExport(Request $request)
    {
        $query = $this->model::query();

        if (property_exists($this, 'with') && !empty($this->with)) {
            $query->with($this->with);
        }

        // Apply same filters as list
        $this->applyFilters($query, $request);

        // Selected IDs only
        if ($request->filled('ids')) {
            $ids = array_filter(explode(',', $request->ids));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        $data = $query->get();
        $modelName = strtolower(class_basename($this->model));
        $filename = "{$modelName}_" . date('Y-m-d') . '.csv';

        // Get export columns
        $exportable = $this->exportable ?? null;

        $callback = function () use ($data, $exportable) {
            $file = fopen('php://output', 'w');
            
            if ($data->count()) {
                $first = $data->first();
                
                if ($exportable) {
                    // Custom columns
                    fputcsv($file, $exportable);
                    foreach ($data as $row) {
                        $csvRow = [];
                        foreach ($exportable as $col) {
                            $csvRow[] = data_get($row, $col) ?? '';
                        }
                        fputcsv($file, $csvRow);
                    }
                } else {
                    // All columns
                    fputcsv($file, array_keys($first->toArray()));
                    foreach ($data as $row) {
                        fputcsv($file, $row->toArray());
                    }
                }
            }
            
            fclose($file);
        };

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

    /**
     * Download Import Template - Auto-generates from $importable
     */
    protected function dtTemplate()
    {
        $columns = $this->importable ?? [];
        
        if (empty($columns)) {
            return response()->json(['error' => 'Import not configured. Add $importable property.'], 400);
        }

        $modelName = strtolower(class_basename($this->model));
        $filename = "{$modelName}_import_template.xlsx";

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Import Data');

        $headers = array_keys($columns);

        // Header row
        foreach ($headers as $index => $header) {
            $colLetter = Coordinate::stringFromColumnIndex($index + 1);
            $cell = $sheet->getCell("{$colLetter}1");
            $cell->setValue($header);
            $cell->getStyle()->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
            $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4F46E5');
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // Hints row
        $colIndex = 0;
        foreach ($columns as $colName => $rules) {
            $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
            $cell = $sheet->getCell("{$colLetter}2");
            $cell->setValue($this->buildHint($rules));
            $cell->getStyle()->getFont()->setItalic(true)->getColor()->setRGB('9CA3AF');
            $colIndex++;
        }

        // Auto-generate Reference Data sheet from 'exists' rules
        $this->addAutoReferenceSheet($spreadsheet, $columns);

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Auto-generate Reference Data from exists rules
     */
    protected function addAutoReferenceSheet($spreadsheet, $columns)
    {
        $references = [];
        
        // Extract exists rules
        foreach ($columns as $col => $rules) {
            if (preg_match('/exists:([^,|]+),(\w+)/', $rules, $m)) {
                $table = $m[1];
                $references[$col] = $table;
            }
        }

        // Add custom references if defined
        if (property_exists($this, 'templateReferences')) {
            $references = array_merge($references, $this->templateReferences);
        }

        if (empty($references)) return;

        $infoSheet = $spreadsheet->createSheet();
        $infoSheet->setTitle('Reference Data');
        $infoSheet->setCellValue('A1', 'REFERENCE DATA (Use ID in import)');
        $infoSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $row = 3;
        foreach ($references as $column => $table) {
            // Section header
            $tableName = strtoupper(str_replace('_', ' ', $table));
            $infoSheet->setCellValue("A{$row}", "{$tableName} (for {$column}):");
            $infoSheet->getStyle("A{$row}")->getFont()->setBold(true);
            $row++;

            // Get data from table
            try {
                $nameCol = $this->guessNameColumn($table);
                $items = DB::table($table)
                    ->select(['id', $nameCol])
                    ->where(function($q) use ($table) {
                        if (Schema::hasColumn($table, 'is_active')) {
                            $q->where('is_active', true);
                        }
                    })
                    ->orderBy($nameCol)
                    ->limit(100)
                    ->get();

                foreach ($items as $item) {
                    $infoSheet->setCellValue("A{$row}", $item->id);
                    $infoSheet->setCellValue("B{$row}", $item->{$nameCol});
                    $row++;
                }
            } catch (\Exception $e) {
                $infoSheet->setCellValue("A{$row}", "Could not load {$table}");
                $row++;
            }

            $row++; // Gap between sections
        }

        $infoSheet->getColumnDimension('A')->setAutoSize(true);
        $infoSheet->getColumnDimension('B')->setAutoSize(true);
    }

    /**
     * Guess the name column for a table
     */
    protected function guessNameColumn($table)
    {
        $possibilities = ['name', 'title', 'label', 'short_name', 'code'];
        
        foreach ($possibilities as $col) {
            if (Schema::hasColumn($table, $col)) {
                return $col;
            }
        }
        
        return 'id';
    }

    /**
     * Import from Excel/CSV
     */
    protected function dtImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $columns = $this->importable ?? [];
        
        if (empty($columns)) {
            return response()->json(['success' => false, 'message' => 'Import not configured'], 400);
        }

        try {
            $rows = $this->parseFile($request->file('file'));
            
            if (empty($rows)) {
                return response()->json(['success' => false, 'message' => 'No data found'], 400);
            }

            $results = ['total' => 0, 'success' => 0, 'failed' => 0, 'errors' => []];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNum = $index + 3;
                
                if ($this->isRowEmpty($row)) continue;
                if ($index === 0 && $this->isHintRow($row)) continue;

                $results['total']++;

                // Adjust unique rules for updates
                $rules = $this->adjustRulesForUpdate($columns, $row);

                $validator = Validator::make($row, $rules);
                
                if ($validator->fails()) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNum}: " . $validator->errors()->first();
                    continue;
                }

                try {
                    $data = array_filter($row, fn($v) => $v !== '' && $v !== null);
                    
                    // Custom import handler or default
                    if (method_exists($this, 'importRow')) {
                        $this->importRow($data, $row);
                    } else {
                        $this->defaultImportRow($data);
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
                    'message' => 'Import failed - no records imported',
                    'results' => $results
                ], 422);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$results['success']} of {$results['total']} imported",
                'results' => $results
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Adjust unique rules for existing records
     */
    protected function adjustRulesForUpdate($rules, $row)
    {
        $adjusted = $rules;
        $uniqueField = $this->uniqueField ?? 'sku'; // Default unique field

        if (isset($row[$uniqueField]) && !empty($row[$uniqueField])) {
            $existing = $this->model::where($uniqueField, $row[$uniqueField])->first();
            if ($existing) {
                foreach ($adjusted as $col => $rule) {
                    if (str_contains($rule, 'unique:')) {
                        // Add exception for existing record
                        $adjusted[$col] = preg_replace(
                            '/unique:([^|,]+),([^|,]+)/',
                            'unique:$1,$2,' . $existing->id,
                            $rule
                        );
                    }
                }
            }
        }
        
        return $adjusted;
    }

    /**
     * Default import row - creates or updates
     */
    protected function defaultImportRow($data)
    {
        $uniqueField = $this->uniqueField ?? 'sku';
        
        if (isset($data[$uniqueField])) {
            $existing = $this->model::where($uniqueField, $data[$uniqueField])->first();
            if ($existing) {
                $existing->update($data);
                return $existing;
            }
        }
        
        return $this->model::create($data);
    }

    /**
     * Parse uploaded file
     */
    protected function parseFile($file)
    {
        $ext = strtolower($file->getClientOriginalExtension());
        
        if ($ext === 'csv') {
            return $this->parseCsv($file);
        }
        
        return $this->parseExcel($file);
    }

    protected function parseExcel($file)
    {
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
            
            $assoc = [];
            foreach ($headers as $i => $h) {
                if (!empty($h)) {
                    $assoc[$h] = $rowData[$i] ?? '';
                }
            }
            $rows[] = $assoc;
        }
        
        return $rows;
    }

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

    protected function isRowEmpty($row)
    {
        foreach ($row as $v) {
            if (!empty(trim($v))) return false;
        }
        return true;
    }

    protected function isHintRow($row)
    {
        $first = reset($row);
        return str_contains(strtolower($first), 'required') || str_contains(strtolower($first), 'optional');
    }

    protected function buildHint($rules)
    {
        $req = str_contains($rules, 'required') ? 'Required' : 'Optional';
        
        if (str_contains($rules, 'email')) return "{$req}, Email";
        if (str_contains($rules, 'integer')) return "{$req}, Integer";
        if (str_contains($rules, 'numeric')) return "{$req}, Number";
        if (str_contains($rules, 'date')) return "{$req}, Date (YYYY-MM-DD)";
        if (str_contains($rules, 'boolean')) return "{$req}, 1 or 0";
        if (preg_match('/in:([^|]+)/', $rules, $m)) return "{$req}, One of: {$m[1]}";
        if (preg_match('/exists:([^,]+)/', $rules, $m)) return "{$req}, ID from " . str_replace('_', ' ', $m[1]);
        
        return "{$req}, Text";
    }

    /**
     * Bulk Delete
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }

        try {
            $this->model::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => count($ids) . ' items deleted']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}