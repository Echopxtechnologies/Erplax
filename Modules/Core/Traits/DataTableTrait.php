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
 *     // Optional: custom export title
 *     protected $exportTitle = 'Products Report';
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
 *
 *     // Optional: custom export row mapping
 *     protected function mapExportRow($item) {
 *         return [
 *             'ID' => $item->id,
 *             'Name' => $item->name,
 *             'Category' => $item->category?->name ?? '-',
 *         ];
 *     }
 * }
 * 
 * ROUTE:
 * ======
 * Route::match(['get', 'post'], '/data', [ProductController::class, 'handleData'])->name('data');
 */

trait DataTableTrait
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

        // Export - supports multiple formats
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
     * Export - supports CSV, Excel (XLSX), PDF
     */
    protected function dtExport(Request $request)
    {
        $format = strtolower($request->get('export', 'csv'));
        
        // Get export data
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

        // Apply same filters as list
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

        // Check if custom export mapping exists
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
            
            // BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            if (!empty($headers)) {
                fputcsv($file, $headers);
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
        $sheet->setTitle(substr($title, 0, 31)); // Max 31 chars for sheet name

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
            $cell = $sheet->getCell("{$colLetter}3");
            $cell->setValue($this->formatHeaderName($header));
            
            // Header styling
            $sheet->getStyle("{$colLetter}3")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                ]
            ]);
            
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // Data rows (starting from row 4)
        $rowNum = 4;
        foreach ($rows as $row) {
            $colIndex = 1;
            foreach ($row as $value) {
                $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                $sheet->setCellValue("{$colLetter}{$rowNum}", $value);
                
                // Alternate row colors
                if ($rowNum % 2 === 0) {
                    $sheet->getStyle("{$colLetter}{$rowNum}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('F9FAFB');
                }
                
                // Border
                $sheet->getStyle("{$colLetter}{$rowNum}")->getBorders()
                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                
                $colIndex++;
            }
            $rowNum++;
        }

        // Summary row
        $sheet->setCellValue("A{$rowNum}", '');
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

        // Format headers for display
        $formattedHeaders = array_map([$this, 'formatHeaderName'], $headers);

        $html = $this->generatePdfHtml($title, $formattedHeaders, $rows, $modelName);

        $pdf = Pdf::loadHTML($html);
        
        // Auto orientation based on columns
        if (count($headers) > 6) {
            $pdf->setPaper('a4', 'landscape');
        } else {
            $pdf->setPaper('a4', 'portrait');
        }

        return $pdf->download($filename);
    }

    /**
     * Generate PDF HTML content
     */
    protected function generatePdfHtml($title, $headers, $rows, $modelName)
    {
        $companyName = config('app.name', 'ERP System');
        $date = date('d M Y H:i');
        $totalRecords = count($rows);

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . e($title) . '</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { 
                    font-family: DejaVu Sans, sans-serif; 
                    font-size: 10px; 
                    color: #333;
                    padding: 15px;
                }
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                    padding-bottom: 10px;
                    border-bottom: 2px solid #4F46E5;
                }
                .header h1 {
                    font-size: 18px;
                    color: #4F46E5;
                    margin-bottom: 5px;
                }
                .header .subtitle {
                    font-size: 12px;
                    color: #666;
                }
                .meta {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 15px;
                    font-size: 9px;
                    color: #666;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 15px;
                }
                th {
                    background: #4F46E5;
                    color: white;
                    padding: 8px 6px;
                    text-align: left;
                    font-size: 9px;
                    font-weight: bold;
                    border: 1px solid #4F46E5;
                }
                td {
                    padding: 6px;
                    border: 1px solid #ddd;
                    font-size: 9px;
                    word-wrap: break-word;
                }
                tr:nth-child(even) {
                    background: #f9fafb;
                }
                tr:hover {
                    background: #f3f4f6;
                }
                .footer {
                    margin-top: 20px;
                    padding-top: 10px;
                    border-top: 1px solid #ddd;
                    font-size: 9px;
                    color: #666;
                    text-align: center;
                }
                .total {
                    font-weight: bold;
                    margin-top: 10px;
                }
                .no-data {
                    text-align: center;
                    padding: 30px;
                    color: #666;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>' . e($companyName) . '</h1>
                <div class="subtitle">' . e($title) . '</div>
            </div>
            
            <div class="meta">
                <span>Generated: ' . $date . '</span>
                <span>Total Records: ' . $totalRecords . '</span>
            </div>';

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
                    // Truncate long values for PDF
                    $displayValue = is_string($value) && strlen($value) > 50 
                        ? substr($value, 0, 47) . '...' 
                        : $value;
                    $html .= '<td>' . e($displayValue) . '</td>';
                }
                $html .= '</tr>';
            }
            
            $html .= '</tbody></table>';
        }

        $html .= '
            <div class="footer">
                <div class="total">Total Records: ' . $totalRecords . '</div>
                <div>Generated by ' . e($companyName) . ' on ' . $date . '</div>
            </div>
        </body>
        </html>';

        return $html;
    }

    /**
     * Format header name for display
     */
    protected function formatHeaderName($header)
    {
        return ucwords(str_replace(['_', '-'], ' ', $header));
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