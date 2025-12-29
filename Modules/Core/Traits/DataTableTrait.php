<?php

namespace Modules\Core\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv as CsvWriter;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * DataTable Trait v2.0
 * 
 * Features:
 * - List with search, filter, sort, pagination
 * - Export to CSV, Excel, PDF
 * - Import from Excel/CSV with auto-lookup
 * - Multiple bulk actions
 * - Template download with reference data
 * 
 * ===========================================
 * USAGE IN CONTROLLER:
 * ===========================================
 * 
 * class ProductController extends Controller
 * {
 *     use DataTableTrait;
 * 
 *     protected $model = Product::class;
 *     protected $with = ['category', 'brand'];
 *     protected $searchable = ['name', 'sku', 'barcode'];
 *     protected $sortable = ['id', 'name', 'price', 'created_at'];
 *     protected $filterable = ['category_id', 'brand_id', 'status'];
 *     protected $exportable = ['id', 'sku', 'name', 'price'];
 *     protected $routePrefix = 'admin.products';
 * 
 *     // Import with validation rules
 *     protected $importable = [
 *         'name'        => 'required|string|max:191',
 *         'sku'         => 'required|string|max:50|unique:products,sku',
 *         'category_id' => 'nullable|exists:product_categories,id',
 *         'price'       => 'required|numeric|min:0',
 *     ];
 * 
 *     // Import lookups - auto convert names to IDs
 *     protected $importLookups = [
 *         'category_name' => [
 *             'table'   => 'product_categories',
 *             'search'  => 'name',
 *             'return'  => 'id',
 *             'save_as' => 'category_id',
 *             'create'  => true,  // Auto-create if not found (optional)
 *             'create_data' => ['status' => 1],  // Extra fields when creating (optional)
 *         ],
 *         'brand_name' => [
 *             'table'   => 'brands',
 *             'search'  => 'name',
 *             'return'  => 'id',
 *             'save_as' => 'brand_id',
 *         ],
 *     ];
 * 
 *     // Import defaults - values when column is empty
 *     protected $importDefaults = [
 *         'status' => 'active',
 *         'qty'    => 0,
 *     ];
 * 
 *     // Bulk actions
 *     protected $bulkActions = [
 *         'delete'     => ['label' => 'Delete',     'confirm' => true, 'color' => 'red'],
 *         'activate'   => ['label' => 'Activate',   'confirm' => false, 'color' => 'green'],
 *         'deactivate' => ['label' => 'Deactivate', 'confirm' => false, 'color' => 'yellow'],
 *     ];
 *
 *     // Optional: customize row mapping
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
 * ===========================================
 * ROUTES:
 * ===========================================
 * 
 * Route::match(['get', 'post'], '/data', [ProductController::class, 'handleData'])->name('data');
 * Route::post('/bulk-action', [ProductController::class, 'handleBulkAction'])->name('bulk-action');
 */

trait DataTableTrait
{
    /**
     * Main handler - call this from your route
     * Handles: List, Search, Filter, Sort, Export, Import, Template, Bulk Actions Config
     */
    public function handleData(Request $request)
    {
        // Import (POST with file)
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

        // Get bulk actions config (for frontend dropdown)
        if ($request->has('bulk_actions')) {
            return $this->getBulkActionsConfig();
        }

        // List with search, filter, sort, pagination
        return $this->dtList($request);
    }

    /**
     * Handle bulk actions
     */
    public function handleBulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }

        if (empty($action)) {
            return response()->json(['success' => false, 'message' => 'No action specified'], 400);
        }

        // Check if action exists
        $bulkActions = $this->bulkActions ?? ['delete' => ['label' => 'Delete', 'confirm' => true]];
        
        if (!array_key_exists($action, $bulkActions)) {
            return response()->json(['success' => false, 'message' => 'Invalid action'], 400);
        }

        // Method name: bulk{Action} e.g., bulkDelete, bulkActivate
        $method = 'bulk' . ucfirst($action);

        if (method_exists($this, $method)) {
            return $this->$method($request);
        }

        // Default handlers for common actions
        return $this->defaultBulkAction($action, $ids);
    }

    /**
     * Get bulk actions config for frontend
     */
    protected function getBulkActionsConfig()
    {
        $actions = $this->bulkActions ?? ['delete' => ['label' => 'Delete', 'confirm' => true, 'color' => 'red']];
        
        return response()->json(['actions' => $actions]);
    }

    /**
     * Default bulk action handler
     */
    protected function defaultBulkAction($action, $ids)
    {
        $count = 0;

        switch ($action) {
            case 'delete':
                $count = $this->model::whereIn('id', $ids)->delete();
                $message = "{$count} items deleted";
                break;

            case 'activate':
                $count = $this->model::whereIn('id', $ids)->update(['status' => 'active']);
                $message = "{$count} items activated";
                break;

            case 'deactivate':
                $count = $this->model::whereIn('id', $ids)->update(['status' => 'inactive']);
                $message = "{$count} items deactivated";
                break;

            default:
                return response()->json(['success' => false, 'message' => 'Action not implemented'], 400);
        }

        return response()->json(['success' => true, 'message' => $message, 'count' => $count]);
    }

    /**
     * Bulk Delete (legacy support)
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }

        try {
            $count = $this->model::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => "{$count} items deleted"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * List data with search, filters, sorting, pagination
     */
    protected function dtList(Request $request)
    {
        $query = $this->model::query();

        // Eager load relations
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

        // Apply filters
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
        $perPage = min($request->get('per_page', 10), 100);
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
            'per_page' => $data->perPage(),
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
                if (\Route::has("{$prefix}.show")) {
                    $row['_show_url'] = route("{$prefix}.show", $item->id);
                }
                if (\Route::has("{$prefix}.edit")) {
                    $row['_edit_url'] = route("{$prefix}.edit", $item->id);
                }
                if (\Route::has("{$prefix}.destroy")) {
                    $row['_delete_url'] = route("{$prefix}.destroy", $item->id);
                }
            } catch (\Exception $e) {
                // Route doesn't exist
            }
        }
        
        return $row;
    }

    /**
     * Apply filters from request
     */
    protected function applyFilters($query, Request $request)
    {
        $skip = ['page', 'per_page', 'search', 'sort', 'dir', 'export', 'template', 'ids', '_', 'bulk_actions'];
        $filterable = $this->filterable ?? [];

        foreach ($request->all() as $key => $value) {
            if (in_array($key, $skip) || $value === '' || $value === null) {
                continue;
            }

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

    // =========================================
    // EXPORT METHODS
    // =========================================

    /**
     * Export - supports CSV, Excel (XLSX), PDF
     */
    protected function dtExport(Request $request)
    {
        $format = strtolower($request->get('export', 'csv'));
        $data = $this->getExportData($request);
        $modelName = strtolower(class_basename($this->model));
        $title = $this->exportTitle ?? ucfirst(str_replace('_', ' ', $modelName)) . ' Export';
        
        switch ($format) {
            case 'xlsx':
            case 'excel':
                return $this->exportToExcel($data, $modelName, $title);
            case 'pdf':
                return $this->exportToPdf($data, $modelName, $title);
            case 'csv':
            default:
                return $this->exportToCsv($data, $modelName);
        }
    }

    /**
     * Get export data with filters applied
     */
    protected function getExportData(Request $request)
    {
        $query = $this->model::query();

        if (property_exists($this, 'with') && !empty($this->with)) {
            $query->with($this->with);
        }

        // Apply filters
        $this->applyFilters($query, $request);

        // Search
        if ($search = $request->get('search')) {
            $searchable = $this->searchable ?? ['name'];
            $query->where(function ($q) use ($search, $searchable) {
                foreach ($searchable as $col) {
                    if (str_contains($col, '.')) {
                        [$relation, $field] = explode('.', $col);
                        $q->orWhereHas($relation, fn($q2) => $q2->where($field, 'LIKE', "%{$search}%"));
                    } else {
                        $q->orWhere($col, 'LIKE', "%{$search}%");
                    }
                }
            });
        }

        // Sorting
        $sortCol = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        $sortable = $this->sortable ?? ['id', 'created_at'];
        if (in_array($sortCol, $sortable)) {
            $query->orderBy($sortCol, $sortDir);
        }

        // Selected IDs only
        if ($request->filled('ids')) {
            $ids = array_filter(explode(',', $request->ids));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        return $query->get();
    }

    /**
     * Get export columns and data
     */
    protected function getExportColumnsAndData($data)
    {
        if ($data->isEmpty()) {
            return [[], []];
        }

        // Custom export mapping
        if (method_exists($this, 'mapExportRow')) {
            $rows = $data->map(fn($item) => $this->mapExportRow($item))->values()->toArray();
            $headers = !empty($rows) ? array_keys($rows[0]) : [];
            return [$headers, $rows];
        }

        // Use exportable columns if defined
        $exportable = $this->exportable ?? null;
        
        if ($exportable) {
            $headers = $exportable;
            $rows = $data->map(function ($item) use ($exportable) {
                $row = [];
                foreach ($exportable as $col) {
                    $row[$col] = data_get($item, $col) ?? '';
                }
                return $row;
            })->values()->toArray();
        } else {
            // All columns
            $first = $data->first();
            $headers = array_keys($first->toArray());
            $rows = $data->map(fn($item) => $item->toArray())->values()->toArray();
        }

        return [$headers, $rows];
    }

    /**
     * Export to CSV
     */
    protected function exportToCsv($data, $modelName)
    {
        $filename = "{$modelName}_" . date('Y-m-d_His') . '.csv';
        [$headers, $rows] = $this->getExportColumnsAndData($data);

        $callback = function () use ($headers, $rows) {
            $file = fopen('php://output', 'w');
            
            // BOM for Excel UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            if (!empty($headers)) {
                fputcsv($file, array_map([$this, 'formatHeaderName'], $headers));
                foreach ($rows as $row) {
                    fputcsv($file, array_values($row));
                }
            }
            
            fclose($file);
        };

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

    /**
     * Export to Excel (XLSX)
     */
    protected function exportToExcel($data, $modelName, $title)
    {
        $filename = "{$modelName}_" . date('Y-m-d_His') . '.xlsx';
        [$headers, $rows] = $this->getExportColumnsAndData($data);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(substr($title, 0, 31));

        if (empty($headers)) {
            $sheet->setCellValue('A1', 'No data to export');
            return $this->streamExcel($spreadsheet, $filename);
        }

        // Title row
        $lastCol = Coordinate::stringFromColumnIndex(count($headers));
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', $title . ' - ' . date('d M Y H:i'));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Header row (row 3)
        foreach ($headers as $index => $header) {
            $colLetter = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue("{$colLetter}3", $this->formatHeaderName($header));
            
            $sheet->getStyle("{$colLetter}3")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);
            
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // Data rows
        $rowNum = 4;
        foreach ($rows as $row) {
            $colIndex = 1;
            foreach ($row as $value) {
                $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                $sheet->setCellValue("{$colLetter}{$rowNum}", $value);
                
                if ($rowNum % 2 === 0) {
                    $sheet->getStyle("{$colLetter}{$rowNum}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('F9FAFB');
                }
                
                $sheet->getStyle("{$colLetter}{$rowNum}")->getBorders()
                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                
                $colIndex++;
            }
            $rowNum++;
        }

        // Summary
        $rowNum++;
        $sheet->setCellValue("A{$rowNum}", "Total Records: " . count($rows));
        $sheet->getStyle("A{$rowNum}")->getFont()->setBold(true)->setItalic(true);

        return $this->streamExcel($spreadsheet, $filename);
    }

    /**
     * Stream Excel file
     */
    protected function streamExcel($spreadsheet, $filename)
    {
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Export to PDF
     */
    protected function exportToPdf($data, $modelName, $title)
    {
        $filename = "{$modelName}_" . date('Y-m-d_His') . '.pdf';
        [$headers, $rows] = $this->getExportColumnsAndData($data);

        $formattedHeaders = array_map([$this, 'formatHeaderName'], $headers);
        $html = $this->generatePdfHtml($title, $formattedHeaders, $rows, $modelName);

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', count($headers) > 6 ? 'landscape' : 'portrait');

        return $pdf->download($filename);
    }

    /**
     * Generate PDF HTML
     */
    protected function generatePdfHtml($title, $headers, $rows, $modelName)
    {
        $companyName = config('app.name', 'ERP System');
        $date = date('d M Y H:i');
        $totalRecords = count($rows);

        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>' . e($title) . '</title>
        <style>
            body { font-family: Arial, sans-serif; font-size: 10px; margin: 20px; color: #333; }
            .header { text-align: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #4F46E5; }
            .header h1 { margin: 0 0 5px 0; color: #4F46E5; font-size: 18px; }
            .header .subtitle { font-size: 14px; color: #666; }
            .meta { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 9px; color: #666; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
            th { background: #4F46E5; color: white; padding: 8px 6px; text-align: left; font-size: 9px; font-weight: bold; border: 1px solid #4F46E5; }
            td { padding: 6px; border: 1px solid #ddd; font-size: 9px; word-wrap: break-word; }
            tr:nth-child(even) { background: #f9fafb; }
            .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 9px; color: #666; text-align: center; }
            .total { font-weight: bold; margin-top: 10px; }
            .no-data { text-align: center; padding: 30px; color: #666; }
        </style></head><body>
        <div class="header"><h1>' . e($companyName) . '</h1><div class="subtitle">' . e($title) . '</div></div>
        <div class="meta"><span>Generated: ' . $date . '</span><span>Total Records: ' . $totalRecords . '</span></div>';

        if (empty($rows)) {
            $html .= '<div class="no-data">No data to export</div>';
        } else {
            $html .= '<table><thead><tr>';
            foreach ($headers as $header) {
                $html .= '<th>' . e($header) . '</th>';
            }
            $html .= '</tr></thead><tbody>';
            
            foreach ($rows as $row) {
                $html .= '<tr>';
                foreach ($row as $value) {
                    $displayValue = is_string($value) && strlen($value) > 50 ? substr($value, 0, 47) . '...' : $value;
                    $html .= '<td>' . e($displayValue) . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
        }

        $html .= '<div class="footer"><div class="total">Total Records: ' . $totalRecords . '</div>
            <div>Generated by ' . e($companyName) . ' on ' . $date . '</div></div></body></html>';

        return $html;
    }

    /**
     * Format header name
     */
    protected function formatHeaderName($header)
    {
        return ucwords(str_replace(['_', '-'], ' ', $header));
    }

    // =========================================
    // IMPORT METHODS
    // =========================================

    /**
     * Download Import Template
     */
    protected function dtTemplate()
    {
        $columns = $this->importable ?? [];
        $lookups = $this->importLookups ?? [];
        
        if (empty($columns) && empty($lookups)) {
            return response()->json(['error' => 'Import not configured. Add $importable property.'], 400);
        }

        // Merge lookup columns into importable for template
        $allColumns = $columns;
        foreach ($lookups as $colName => $config) {
            if (!isset($allColumns[$colName])) {
                $allColumns[$colName] = 'nullable';
            }
        }

        $modelName = strtolower(class_basename($this->model));
        $filename = "{$modelName}_import_template.xlsx";

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Import Data');

        $headers = array_keys($allColumns);

        // Header row
        foreach ($headers as $index => $header) {
            $colLetter = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue("{$colLetter}1", $header);
            $sheet->getStyle("{$colLetter}1")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
            $sheet->getStyle("{$colLetter}1")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4F46E5');
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // Hints row
        $colIndex = 0;
        foreach ($allColumns as $colName => $rules) {
            $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
            
            // Check if this is a lookup column
            if (isset($lookups[$colName])) {
                $lookupTable = $lookups[$colName]['table'];
                $hint = "Enter {$lookups[$colName]['search']} from {$lookupTable}";
            } else {
                $hint = $this->buildHint(is_array($rules) ? ($rules['rules'] ?? 'nullable') : $rules);
            }
            
            $sheet->setCellValue("{$colLetter}2", $hint);
            $sheet->getStyle("{$colLetter}2")->getFont()->setItalic(true)->getColor()->setRGB('9CA3AF');
            $colIndex++;
        }

        // Add reference sheet
        $this->addReferenceSheet($spreadsheet, $columns, $lookups);

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Add reference data sheet
     */
    protected function addReferenceSheet($spreadsheet, $columns, $lookups)
    {
        $references = [];
        
        // From exists rules
        foreach ($columns as $col => $rules) {
            $ruleStr = is_array($rules) ? ($rules['rules'] ?? '') : $rules;
            if (preg_match('/exists:([^,|]+),(\w+)/', $ruleStr, $m)) {
                $references[$col] = ['table' => $m[1], 'column' => $m[2]];
            }
        }

        // From lookups
        foreach ($lookups as $colName => $config) {
            $references[$colName] = [
                'table' => $config['table'],
                'column' => $config['search'],
            ];
        }

        if (empty($references)) return;

        $infoSheet = $spreadsheet->createSheet();
        $infoSheet->setTitle('Reference Data');
        $infoSheet->setCellValue('A1', 'REFERENCE DATA');
        $infoSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $row = 3;
        foreach ($references as $column => $config) {
            $tableName = strtoupper(str_replace('_', ' ', $config['table']));
            $infoSheet->setCellValue("A{$row}", "{$tableName} (for {$column}):");
            $infoSheet->getStyle("A{$row}")->getFont()->setBold(true);
            $row++;

            try {
                $nameCol = $this->guessNameColumn($config['table']);
                $items = DB::table($config['table'])
                    ->select(['id', $nameCol])
                    ->when(Schema::hasColumn($config['table'], 'is_active'), fn($q) => $q->where('is_active', true))
                    ->orderBy($nameCol)
                    ->limit(100)
                    ->get();

                foreach ($items as $item) {
                    $infoSheet->setCellValue("A{$row}", $item->id);
                    $infoSheet->setCellValue("B{$row}", $item->{$nameCol});
                    $row++;
                }
            } catch (\Exception $e) {
                $infoSheet->setCellValue("A{$row}", "Could not load {$config['table']}");
                $row++;
            }

            $row++;
        }

        $infoSheet->getColumnDimension('A')->setAutoSize(true);
        $infoSheet->getColumnDimension('B')->setAutoSize(true);
    }

    /**
     * Guess name column
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
        $lookups = $this->importLookups ?? [];
        $defaults = $this->importDefaults ?? [];
        
        if (empty($columns) && empty($lookups)) {
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

                // Apply lookups - convert names to IDs
                $row = $this->applyImportLookups($row, $lookups, $rowNum, $results);
                
                // Apply defaults
                foreach ($defaults as $col => $defaultValue) {
                    if (!isset($row[$col]) || $row[$col] === '' || $row[$col] === null) {
                        $row[$col] = $defaultValue;
                    }
                }

                // Adjust unique rules for updates
                $rules = $this->adjustRulesForUpdate($columns, $row);

                $validator = Validator::make($row, $rules);
                
                if ($validator->fails()) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNum}: " . $validator->errors()->first();
                    continue;
                }

                try {
                    // Filter empty values and remove system fields that should never be imported
                    $excludeFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
                    $data = array_filter($row, fn($v) => $v !== '' && $v !== null);
                    $data = array_diff_key($data, array_flip($excludeFields));
                    
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

            $message = "{$results['success']} of {$results['total']} imported";
            if (!empty($results['created'])) {
                $createdCount = count($results['created']);
                $message .= " ({$createdCount} lookup records auto-created)";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'results' => $results
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Apply import lookups - convert names to IDs
     */
    protected function applyImportLookups($row, $lookups, $rowNum, &$results)
    {
        foreach ($lookups as $colName => $config) {
            if (!isset($row[$colName]) || $row[$colName] === '') {
                continue;
            }

            $searchValue = trim($row[$colName]);
            $table = $config['table'];
            $searchCol = $config['search'];
            $returnCol = $config['return'] ?? 'id';
            $saveAs = $config['save_as'] ?? $colName;
            $autoCreate = $config['create'] ?? false;
            $createData = $config['create_data'] ?? [];

            try {
                // Try exact match first
                $found = DB::table($table)
                    ->where($searchCol, $searchValue)
                    ->first();

                // Try case-insensitive search if not found
                if (!$found) {
                    $found = DB::table($table)
                        ->whereRaw("LOWER({$searchCol}) = ?", [strtolower($searchValue)])
                        ->first();
                }

                if ($found) {
                    $row[$saveAs] = $found->{$returnCol};
                } else {
                    // Not found - either auto-create or report error
                    if ($autoCreate) {
                        // Build insert data
                        $insertData = array_merge(
                            [$searchCol => $searchValue],
                            $createData
                        );
                        
                        // Add timestamps if table has them
                        if (Schema::hasColumn($table, 'created_at')) {
                            $insertData['created_at'] = now();
                            $insertData['updated_at'] = now();
                        }
                        
                        // Insert and get ID
                        $newId = DB::table($table)->insertGetId($insertData);
                        $row[$saveAs] = $newId;
                        
                        // Track created records
                        if (!isset($results['created'])) {
                            $results['created'] = [];
                        }
                        $results['created'][] = "Row {$rowNum}: Created '{$searchValue}' in {$table} (ID: {$newId})";
                    } else {
                        $results['errors'][] = "Row {$rowNum}: '{$searchValue}' not found in {$table}";
                    }
                }
            } catch (\Exception $e) {
                $results['errors'][] = "Row {$rowNum}: Lookup error for {$colName} - " . $e->getMessage();
            }

            // Remove the lookup column if it's different from save_as
            if ($colName !== $saveAs) {
                unset($row[$colName]);
            }
        }

        return $row;
    }

    /**
     * Adjust unique rules for existing records
     */
    protected function adjustRulesForUpdate($rules, $row)
    {
        $adjusted = [];
        $uniqueField = $this->uniqueField ?? 'sku';

        foreach ($rules as $col => $rule) {
            $ruleStr = is_array($rule) ? ($rule['rules'] ?? 'nullable') : $rule;
            $adjusted[$col] = $ruleStr;
        }

        if (isset($row[$uniqueField]) && !empty($row[$uniqueField])) {
            $existing = $this->model::where($uniqueField, $row[$uniqueField])->first();
            if ($existing) {
                foreach ($adjusted as $col => $rule) {
                    if (str_contains($rule, 'unique:')) {
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
        
        // Use updateOrCreate for proper upsert (handles duplicates within same batch)
        if (isset($data[$uniqueField]) && !empty($data[$uniqueField])) {
            return $this->model::updateOrCreate(
                [$uniqueField => $data[$uniqueField]],  // Find by unique field
                $data                                     // Update with all data
            );
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

    /**
     * Parse Excel file
     */
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
     * Check if row is empty
     */
    protected function isRowEmpty($row)
    {
        foreach ($row as $v) {
            if (!empty(trim($v))) return false;
        }
        return true;
    }

    /**
     * Check if row is hint row
     */
    protected function isHintRow($row)
    {
        $first = reset($row);
        return str_contains(strtolower($first), 'required') || str_contains(strtolower($first), 'optional');
    }

    /**
     * Build hint for template
     */
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
}