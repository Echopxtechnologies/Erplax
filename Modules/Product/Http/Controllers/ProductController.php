<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductController extends AdminController
{
    /**
     * Display listing
     */
    public function index()
    {
        $this->authorize('product.products-list.read');
        $stats = [
            'total' => Product::count(),
            'active' => Product::active()->count(),
            'inactive' => Product::where('is_active', false)->count(),
        ];
        return view('product::index', compact('stats'));
    }

    /**
     * DataTable endpoint - handles list, export, import, template
     */
    public function dataTable(Request $request)
    {
        // Import
        if ($request->isMethod('post') && $request->hasFile('file')) {
            return $this->importProducts($request);
        }

        // Template Download
        if ($request->has('template')) {
            return $this->downloadTemplate();
        }

        // Export - supports CSV, XLSX, PDF
        if ($request->has('export')) {
            return $this->exportProducts($request);
        }

        // List with pagination
        return $this->listProducts($request);
    }

    /**
     * List products with search, filters, sorting, pagination
     */
    protected function listProducts(Request $request): JsonResponse
    {
        $query = Product::query();

        // Search
        if ($search = $request->input('search')) {
            $query->search($search);
        }

        // Filters
        $this->applyFilters($query, $request);

        // Sort
        $sortCol = $request->input('sort', 'id');
        $sortDir = $request->input('dir', 'desc');
        $sortable = ['id', 'name', 'sku', 'purchase_price', 'sale_price', 'mrp', 'is_active', 'created_at'];
        if (in_array($sortCol, $sortable)) {
            $query->orderBy($sortCol, $sortDir);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Paginate
        $perPage = $request->input('per_page', 15);
        $data = $query->paginate($perPage);

        // Map rows
        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'sku' => $item->sku,
                'description' => $item->description,
                'purchase_price' => $item->purchase_price,
                'sale_price' => $item->sale_price,
                'mrp' => $item->mrp,
                'is_active' => $item->is_active,
                'status_label' => $item->status_label,
                '_edit_url' => route('admin.product.edit', $item->id),
                '_show_url' => route('admin.product.show', $item->id),
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
     * Apply filters from request
     */
    protected function applyFilters($query, Request $request)
    {
        // Status filter
        if ($request->has('is_active') && $request->input('is_active') !== '') {
            $query->where('is_active', $request->input('is_active'));
        }

        // Price range filters
        if ($minPrice = $request->input('min_price')) {
            $query->where('sale_price', '>=', $minPrice);
        }
        if ($maxPrice = $request->input('max_price')) {
            $query->where('sale_price', '<=', $maxPrice);
        }

        // Date range filters
        if ($fromDate = $request->input('from_date')) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate = $request->input('to_date')) {
            $query->whereDate('created_at', '<=', $toDate);
        }
    }

    /**
     * Export products - CSV, Excel, PDF
     */
    protected function exportProducts(Request $request)
    {
        $format = strtolower($request->get('export', 'csv'));
        
        $query = Product::query();

        // Apply filters
        $this->applyFilters($query, $request);

        // Search
        if ($search = $request->input('search')) {
            $query->search($search);
        }

        // Sort
        $sortCol = $request->input('sort', 'id');
        $sortDir = $request->input('dir', 'desc');
        $query->orderBy($sortCol, $sortDir);

        // Selected IDs only
        if ($request->filled('ids')) {
            $ids = array_filter(explode(',', $request->ids));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        $data = $query->get();
        $filename = 'products_' . date('Y-m-d_His');
        $title = 'Products Export';

        switch ($format) {
            case 'xlsx':
            case 'excel':
                return $this->exportToExcel($data, $filename, $title);
            case 'pdf':
                return $this->exportToPdf($data, $filename, $title);
            case 'csv':
            default:
                return $this->exportToCsv($data, $filename);
        }
    }

    /**
     * Get export headers and rows
     */
    protected function getExportData($data)
    {
        $headers = ['ID', 'Name', 'SKU', 'Description', 'Purchase Price', 'Sale Price', 'MRP', 'Status', 'Created At'];
        
        $rows = $data->map(function ($item) {
            return [
                'ID' => $item->id,
                'Name' => $item->name,
                'SKU' => $item->sku,
                'Description' => $item->description,
                'Purchase Price' => number_format($item->purchase_price, 2),
                'Sale Price' => number_format($item->sale_price, 2),
                'MRP' => $item->mrp ? number_format($item->mrp, 2) : '-',
                'Status' => $item->status_label,
                'Created At' => $item->created_at?->format('Y-m-d H:i'),
            ];
        })->toArray();

        return [$headers, $rows];
    }

    /**
     * Export to CSV
     */
    protected function exportToCsv($data, $filename)
    {
        [$headers, $rows] = $this->getExportData($data);

        $callback = function () use ($headers, $rows) {
            $file = fopen('php://output', 'w');
            
            // BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            fputcsv($file, $headers);
            foreach ($rows as $row) {
                fputcsv($file, array_values($row));
            }
            
            fclose($file);
        };

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}.csv",
        ]);
    }

    /**
     * Export to Excel (XLSX)
     */
    protected function exportToExcel($data, $filename, $title)
    {
        [$headers, $rows] = $this->getExportData($data);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Products');

        if (empty($rows)) {
            $sheet->setCellValue('A1', 'No data to export');
            return $this->streamExcel($spreadsheet, $filename . '.xlsx');
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
            $sheet->setCellValue("{$colLetter}3", $header);
            
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
        $rowNum++;
        $sheet->setCellValue("A{$rowNum}", "Total Records: " . count($rows));
        $sheet->getStyle("A{$rowNum}")->getFont()->setBold(true)->setItalic(true);

        return $this->streamExcel($spreadsheet, $filename . '.xlsx');
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
    protected function exportToPdf($data, $filename, $title)
    {
        [$headers, $rows] = $this->getExportData($data);

        $html = $this->generatePdfHtml($title, $headers, $rows, 'products');

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download($filename . '.pdf');
    }

    /**
     * Generate PDF HTML content
     */
    protected function generatePdfHtml($title, $headers, $rows, $modelName = 'products')
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
                .status-active { color: #16a34a; font-weight: bold; }
                .status-inactive { color: #dc2626; font-weight: bold; }
                .price { text-align: right; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>' . e($companyName) . '</h1>
                <div class="subtitle">' . e($title) . '</div>
            </div>
            
            <div class="meta">
                <span>Generated: ' . $date . '</span> | 
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
                foreach ($row as $key => $value) {
                    $class = '';
                    if ($key === 'Status') {
                        $class = ' class="status-' . strtolower($value) . '"';
                    } elseif (in_array($key, ['Purchase Price', 'Sale Price', 'MRP'])) {
                        $class = ' class="price"';
                    }
                    
                    $displayValue = is_string($value) && strlen($value) > 50 
                        ? substr($value, 0, 47) . '...' 
                        : $value;
                    $html .= '<td' . $class . '>' . e($displayValue) . '</td>';
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
     * Download Import Template
     */
    protected function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Import Data');

        $headers = ['name', 'sku', 'description', 'purchase_price', 'sale_price', 'mrp', 'is_active'];
        $hints = [
            'Required, Text (max 191)',
            'Required, Unique SKU (max 100)',
            'Optional, Text',
            'Required, Number (e.g., 100.00)',
            'Required, Number (e.g., 150.00)',
            'Optional, Number (e.g., 200.00)',
            'Optional: 1=Active, 0=Inactive (default: 1)',
        ];

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
        foreach ($hints as $index => $hint) {
            $colLetter = Coordinate::stringFromColumnIndex($index + 1);
            $cell = $sheet->getCell("{$colLetter}2");
            $cell->setValue($hint);
            $cell->getStyle()->getFont()->setItalic(true)->getColor()->setRGB('9CA3AF');
        }

        // Sample data rows
        $sampleData = [
            ['Sample Product 1', 'SKU-001', 'Description here', '100.00', '150.00', '200.00', '1'],
            ['Sample Product 2', 'SKU-002', 'Another description', '200.00', '300.00', '350.00', '1'],
        ];
        
        $rowNum = 3;
        foreach ($sampleData as $sample) {
            foreach ($sample as $index => $value) {
                $colLetter = Coordinate::stringFromColumnIndex($index + 1);
                $sheet->setCellValue("{$colLetter}{$rowNum}", $value);
            }
            $rowNum++;
        }

        // Add Instructions sheet
        $this->addInstructionsSheet($spreadsheet);

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'products_import_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Add Instructions sheet for import template
     */
    protected function addInstructionsSheet($spreadsheet)
    {
        $infoSheet = $spreadsheet->createSheet();
        $infoSheet->setTitle('Instructions');
        
        $instructions = [
            'PRODUCT IMPORT INSTRUCTIONS',
            '',
            'Required Fields:',
            '• name - Product name (max 191 characters)',
            '• sku - Unique product SKU (max 100 characters)',
            '• purchase_price - Purchase/cost price (number)',
            '• sale_price - Selling price (number)',
            '',
            'Optional Fields:',
            '• description - Product description',
            '• mrp - Maximum retail price (number)',
            '• is_active - 1 for Active, 0 for Inactive (default: 1)',
            '',
            'Notes:',
            '• SKU must be unique - duplicate SKUs will update existing products',
            '• Prices should be numbers without currency symbols',
            '• Row 2 contains hints - it will be ignored during import',
            '• Delete sample rows (3-4) before importing your data',
        ];

        $row = 1;
        foreach ($instructions as $line) {
            $infoSheet->setCellValue("A{$row}", $line);
            if ($row === 1) {
                $infoSheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(14);
            } elseif (in_array($line, ['Required Fields:', 'Optional Fields:', 'Notes:'])) {
                $infoSheet->getStyle("A{$row}")->getFont()->setBold(true);
            }
            $row++;
        }

        $infoSheet->getColumnDimension('A')->setWidth(60);
    }

    /**
     * Import products from Excel/CSV
     */
    protected function importProducts(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $rows = $this->parseFile($request->file('file'));
            
            if (empty($rows)) {
                return response()->json(['success' => false, 'message' => 'No data found in file'], 400);
            }

            $results = ['total' => 0, 'success' => 0, 'failed' => 0, 'updated' => 0, 'errors' => []];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNum = $index + 3; // Account for header + hint rows
                
                if ($this->isRowEmpty($row)) continue;
                if ($index === 0 && $this->isHintRow($row)) continue;

                $results['total']++;

                // Validation rules
                $rules = [
                    'name' => 'required|string|max:191',
                    'sku' => 'required|string|max:100',
                    'description' => 'nullable|string',
                    'purchase_price' => 'required|numeric|min:0',
                    'sale_price' => 'required|numeric|min:0',
                    'mrp' => 'nullable|numeric|min:0',
                    'is_active' => 'nullable|in:0,1,true,false',
                ];

                $validator = Validator::make($row, $rules);
                
                if ($validator->fails()) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNum}: " . $validator->errors()->first();
                    continue;
                }

                try {
                    $data = array_filter($row, fn($v) => $v !== '' && $v !== null);
                    
                    // Convert is_active to boolean
                    $data['is_active'] = isset($data['is_active']) 
                        ? in_array(strtolower($data['is_active']), ['1', 'true', 'yes']) 
                        : true;

                    // Check if SKU exists - update or create
                    $existing = Product::where('sku', $data['sku'])->first();
                    
                    if ($existing) {
                        $existing->update($data);
                        $results['updated']++;
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
                    'message' => 'Import failed - no records imported',
                    'results' => $results
                ], 422);
            }

            DB::commit();

            $message = "{$results['success']} of {$results['total']} products imported";
            if ($results['updated'] > 0) {
                $message .= " ({$results['updated']} updated)";
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
     * Parse uploaded file (Excel or CSV)
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

    /**
     * Show create form
     */
    public function create()
    {
        $this->authorize('product.products-list.create');
        return view('product::create');
    }

    /**
     * Store new product
     */
    public function store(Request $request)
    {
        $this->authorize('product.products-list.create');
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'sku' => 'required|string|max:100|unique:products,sku',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        Product::create($validated);

        return redirect()->route('admin.product.index')->with('success', 'Product created!');
    }

    /**
     * Show single product
     */
    public function show($id)
    {
        $this->authorize('product.products-list.show');
        $product = Product::findOrFail($id);
        return view('product::show', compact('product'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $this->authorize('product.products-list.edit');
        $product = Product::findOrFail($id);
        return view('product::edit', compact('product'));
    }

    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        $this->authorize('product.products-list.edit');
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'sku' => 'required|string|max:100|unique:products,sku,' . $id,
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $product->update($validated);

        return redirect()->route('admin.product.index')->with('success', 'Product updated!');
    }

    /**
     * Delete product
     */
    public function destroy($id)
    {
        $this->authorize('product.products-list.delete');
        Product::findOrFail($id)->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Product deleted!']);
        }
        return redirect()->route('admin.product.index')->with('success', 'Product deleted!');
    }

    /**
     * Bulk delete
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }
        $deleted = Product::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => "{$deleted} product(s) deleted!"]);
    }

    /**
     * Toggle product status
     */
    public function toggleStatus($id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->is_active = !$product->is_active;
        $product->save();
        return response()->json(['success' => true, 'is_active' => $product->is_active]);
    }

    /**
     * Search products (for dropdowns)
     */
    public function search(Request $request): JsonResponse
    {
        $products = Product::active()
            ->search($request->input('q', ''))
            ->limit(10)
            ->get(['id', 'name', 'sku', 'sale_price', 'purchase_price']);

        return response()->json([
            'results' => $products->map(fn($p) => [
                'id' => $p->id,
                'text' => "{$p->name} ({$p->sku})",
                'name' => $p->name,
                'sku' => $p->sku,
                'sale_price' => $p->sale_price,
                'purchase_price' => $p->purchase_price,
            ])
        ]);
    }
}
