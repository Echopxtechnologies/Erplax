<?php

namespace Modules\Todo\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use App\Models\User;
use App\Models\Notification;
use Modules\Todo\Models\Todo;
use Illuminate\Http\Request;
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

class TodoController extends AdminController
{
    /**
     * DataTable endpoint - handles list, export, import, template
     */
    public function dataTable(Request $request)
    {
        // Import
        if ($request->isMethod('post') && $request->hasFile('file')) {
            return $this->importTodos($request);
        }

        // Template Download
        if ($request->has('template')) {
            return $this->downloadTemplate();
        }

        // Export - supports CSV, XLSX, PDF
        if ($request->has('export')) {
            return $this->exportTodos($request);
        }

        // List with pagination
        return $this->listTodos($request);
    }

    /**
     * List todos with search, filters, sorting, pagination
     */
    protected function listTodos(Request $request)
    {
        $user = $this->admin();
        $query = Todo::query()->with(['user', 'assignee']);

        // Filter: Admin sees all, regular user sees own + assigned to them
        if (!$user->is_admin) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Filters
        $this->applyFilters($query, $request);

        // Sort
        $sortCol = $request->input('sort', 'id');
        $sortDir = $request->input('dir', 'desc');
        $sortable = ['id', 'title', 'priority', 'status', 'due_date', 'created_at'];
        if (in_array($sortCol, $sortable)) {
            $query->orderBy($sortCol, $sortDir);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Paginate
        $perPage = $request->input('per_page', 10);
        $data = $query->paginate($perPage);

        // Map rows
        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'user_name' => $item->user->name ?? 'Unknown',
                'assignee_name' => $item->assignee->name ?? 'Unassigned',
                'priority' => $item->priority,
                'status' => $item->status,
                'due_date' => $item->due_date?->format('Y-m-d'),
                'is_overdue' => $item->is_overdue,
                '_edit_url' => route('admin.todo.edit', $item->id),
                '_show_url' => route('admin.todo.show', $item->id),
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
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Priority filter
        if ($priority = $request->input('priority')) {
            $query->where('priority', $priority);
        }

        // Assigned to filter
        if ($assignedTo = $request->input('assigned_to')) {
            $query->where('assigned_to', $assignedTo);
        }

        // User filter (admin only)
        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        // Overdue filter
        if ($request->input('overdue')) {
            $query->overdue();
        }

        // Date range filters
        if ($fromDate = $request->input('from_date')) {
            $query->whereDate('due_date', '>=', $fromDate);
        }
        if ($toDate = $request->input('to_date')) {
            $query->whereDate('due_date', '<=', $toDate);
        }
    }

    /**
     * Export todos - CSV, Excel, PDF
     */
    protected function exportTodos(Request $request)
    {
        $format = strtolower($request->get('export', 'csv'));
        $user = $this->admin();
        
        $query = Todo::query()->with(['user', 'assignee']);

        // Access control
        if (!$user->is_admin) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }

        // Apply filters
        $this->applyFilters($query, $request);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
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
        $filename = 'todos_' . date('Y-m-d_His');
        $title = 'Todo Tasks Export';

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
        $headers = ['ID', 'Title', 'Description', 'Created By', 'Assigned To', 'Priority', 'Status', 'Due Date', 'Completed At', 'Created At'];
        
        $rows = $data->map(function ($item) {
            return [
                'ID' => $item->id,
                'Title' => $item->title,
                'Description' => $item->description,
                'Created By' => $item->user->name ?? 'Unknown',
                'Assigned To' => $item->assignee->name ?? 'Unassigned',
                'Priority' => ucfirst($item->priority),
                'Status' => ucfirst(str_replace('_', ' ', $item->status)),
                'Due Date' => $item->due_date?->format('Y-m-d'),
                'Completed At' => $item->completed_at?->format('Y-m-d H:i'),
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
        $sheet->setTitle('Todos');

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

        $html = $this->generatePdfHtml($title, $headers, $rows);

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download($filename . '.pdf');
    }

    /**
     * Generate PDF HTML content
     */
    protected function generatePdfHtml($title, $headers, $rows, $modelName = 'todos')
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
                .priority-high { color: #dc2626; font-weight: bold; }
                .priority-medium { color: #d97706; font-weight: bold; }
                .priority-low { color: #16a34a; font-weight: bold; }
                .status-completed { color: #16a34a; }
                .status-pending { color: #d97706; }
                .status-in_progress { color: #2563eb; }
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
                    if ($key === 'Priority') {
                        $class = ' class="priority-' . strtolower($value) . '"';
                    } elseif ($key === 'Status') {
                        $class = ' class="status-' . strtolower(str_replace(' ', '_', $value)) . '"';
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

        $headers = ['title', 'description', 'assigned_to', 'priority', 'status', 'due_date'];
        $hints = [
            'Required, Text (max 255)',
            'Optional, Text',
            'Optional, User ID (see Reference)',
            'Optional: low, medium, high (default: medium)',
            'Optional: pending, in_progress, completed (default: pending)',
            'Optional, Date (YYYY-MM-DD)',
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

        // Sample data row
        $sampleData = ['My Task Title', 'Description of the task', '', 'medium', 'pending', date('Y-m-d', strtotime('+7 days'))];
        foreach ($sampleData as $index => $value) {
            $colLetter = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue("{$colLetter}3", $value);
        }

        // Add Reference Data sheet
        $this->addReferenceSheet($spreadsheet);

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'todos_import_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Add Reference Data sheet for import template
     */
    protected function addReferenceSheet($spreadsheet)
    {
        $infoSheet = $spreadsheet->createSheet();
        $infoSheet->setTitle('Reference Data');
        $infoSheet->setCellValue('A1', 'REFERENCE DATA (Use ID in import)');
        $infoSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Users
        $row = 3;
        $infoSheet->setCellValue("A{$row}", 'USERS (for assigned_to):');
        $infoSheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;

        $users = User::orderBy('name')->limit(100)->get(['id', 'name', 'email']);
        foreach ($users as $user) {
            $infoSheet->setCellValue("A{$row}", $user->id);
            $infoSheet->setCellValue("B{$row}", $user->name);
            $infoSheet->setCellValue("C{$row}", $user->email);
            $row++;
        }

        // Priority options
        $row += 2;
        $infoSheet->setCellValue("A{$row}", 'PRIORITY OPTIONS:');
        $infoSheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;
        foreach (['low', 'medium', 'high'] as $priority) {
            $infoSheet->setCellValue("A{$row}", $priority);
            $row++;
        }

        // Status options
        $row += 2;
        $infoSheet->setCellValue("A{$row}", 'STATUS OPTIONS:');
        $infoSheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;
        foreach (['pending', 'in_progress', 'completed'] as $status) {
            $infoSheet->setCellValue("A{$row}", $status);
            $row++;
        }

        $infoSheet->getColumnDimension('A')->setAutoSize(true);
        $infoSheet->getColumnDimension('B')->setAutoSize(true);
        $infoSheet->getColumnDimension('C')->setAutoSize(true);
    }

    /**
     * Import todos from Excel/CSV
     */
    protected function importTodos(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $user = $this->admin();

        try {
            $rows = $this->parseFile($request->file('file'));
            
            if (empty($rows)) {
                return response()->json(['success' => false, 'message' => 'No data found in file'], 400);
            }

            $results = ['total' => 0, 'success' => 0, 'failed' => 0, 'errors' => []];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNum = $index + 3; // Account for header + hint rows
                
                if ($this->isRowEmpty($row)) continue;
                if ($index === 0 && $this->isHintRow($row)) continue;

                $results['total']++;

                // Validation rules
                $rules = [
                    'title' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'assigned_to' => 'nullable|exists:users,id',
                    'priority' => 'nullable|in:low,medium,high',
                    'status' => 'nullable|in:pending,in_progress,completed',
                    'due_date' => 'nullable|date',
                ];

                $validator = Validator::make($row, $rules);
                
                if ($validator->fails()) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNum}: " . $validator->errors()->first();
                    continue;
                }

                try {
                    $data = array_filter($row, fn($v) => $v !== '' && $v !== null);
                    
                    // Set defaults
                    $data['user_id'] = $user->id;
                    $data['priority'] = $data['priority'] ?? 'medium';
                    $data['status'] = $data['status'] ?? 'pending';
                    
                    // Non-admin can't assign to others
                    if (!$user->is_admin) {
                        unset($data['assigned_to']);
                    }

                    // Set completed_at if status is completed
                    if (($data['status'] ?? 'pending') === 'completed') {
                        $data['completed_at'] = now();
                    }

                    Todo::create($data);
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
                'message' => "{$results['success']} of {$results['total']} tasks imported successfully",
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
     * Display listing
     */
    public function index()
    {
        $user = $this->admin();
        $isAdmin = $user->is_admin;

        // Stats for dashboard cards
        $baseQuery = Todo::query();
        if (!$isAdmin) {
            $baseQuery->forUser($user->id);
        }

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'overdue' => (clone $baseQuery)->overdue()->count(),
        ];

        // Get all users for assign dropdown (admin only)
        $users = $isAdmin ? User::orderBy('name')->get() : collect();

        return view('todo::index', compact('stats', 'isAdmin', 'users'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $user = $this->admin();
        $isAdmin = $user->is_admin;
        $users = $isAdmin ? User::orderBy('name')->get() : collect();
        
        return view('todo::create', compact('isAdmin', 'users'));
    }

    /**
     * Store new todo
     */
    public function store(Request $request)
    {
        $user = $this->admin();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'in:low,medium,high',
            'status' => 'in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $validated['user_id'] = $user->id;

        // Non-admin can't assign to others
        if (!$user->is_admin) {
            $validated['assigned_to'] = null;
        }

        if (($validated['status'] ?? 'pending') === 'completed') {
            $validated['completed_at'] = now();
        }

        $todo = Todo::create($validated);

        // Notify assigned user
        if ($todo->assigned_to && $todo->assigned_to !== $user->id) {
            $todo->notifyAssignee(
                'New Task Assigned',
                "{$user->name} assigned you a task: \"{$todo->title}\"",
                $user
            );
        }

        return redirect()->route('admin.todo.index')->with('success', 'Task created successfully!');
    }

    /**
     * Show single todo
     */
    public function show($id)
    {
        $todo = $this->getTodoWithAccess($id);
        return view('todo::show', compact('todo'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $todo = $this->getTodoWithAccess($id);
        $user = $this->admin();
        $isAdmin = $user->is_admin;
        $users = $isAdmin ? User::orderBy('name')->get() : collect();
        
        return view('todo::edit', compact('todo', 'isAdmin', 'users'));
    }

    /**
     * Update todo
     */
    public function update(Request $request, $id)
    {
        $todo = $this->getTodoWithAccess($id);
        $user = $this->admin();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'in:low,medium,high',
            'status' => 'in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        // Non-admin can't change assignment
        if (!$user->is_admin) {
            unset($validated['assigned_to']);
        }

        $oldAssignee = $todo->assigned_to;
        $wasOverdue = $todo->is_overdue;

        // Set completed_at when marking as completed
        if ($validated['status'] === 'completed' && $todo->status !== 'completed') {
            $validated['completed_at'] = now();
            
            if ($wasOverdue) {
                $this->deleteOverdueNotification($todo);
            }
        } elseif ($validated['status'] !== 'completed') {
            $validated['completed_at'] = null;
            if ($todo->due_date != ($validated['due_date'] ?? null) || $todo->status === 'completed') {
                $validated['overdue_notified'] = false;
            }
        }

        $todo->update($validated);

        // Notify new assignee if changed
        if (isset($validated['assigned_to']) && $validated['assigned_to'] !== $oldAssignee && $validated['assigned_to'] !== $user->id) {
            $todo->notifyAssignee(
                'Task Assigned to You',
                "{$user->name} assigned you a task: \"{$todo->title}\"",
                $user
            );
        }

        return redirect()->route('admin.todo.index')->with('success', 'Task updated successfully!');
    }

    /**
     * Delete todo
     */
    public function destroy($id)
    {
        $todo = $this->getTodoWithAccess($id);
        $this->deleteTaskNotifications($todo);
        $todo->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Task deleted successfully']);
        }

        return redirect()->route('admin.todo.index')->with('success', 'Task deleted successfully!');
    }

    /**
     * Bulk delete
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }

        $user = $this->admin();
        $query = Todo::whereIn('id', $ids);

        if (!$user->is_admin) {
            $query->forUser($user->id);
        }

        $todos = $query->get();
        foreach ($todos as $todo) {
            $this->deleteTaskNotifications($todo);
        }

        $deleted = $query->delete();

        return response()->json(['success' => true, 'message' => $deleted . ' items deleted']);
    }

    /**
     * Quick toggle status (AJAX)
     */
    public function toggleStatus(Request $request, $id)
    {
        $todo = $this->getTodoWithAccess($id);
        $wasOverdue = $todo->is_overdue;
        
        $newStatus = $request->input('status');
        $todo->status = $newStatus;
        
        if ($newStatus === 'completed') {
            $todo->completed_at = now();
            if ($wasOverdue) {
                $this->deleteOverdueNotification($todo);
            }
        } else {
            $todo->completed_at = null;
        }
        
        $todo->save();

        return response()->json(['success' => true, 'status' => $todo->status]);
    }

    /**
     * Check overdue tasks
     */
    public function checkOverdueTasks()
    {
        $overdueTodos = Todo::overdue()
            ->where('overdue_notified', false)
            ->get();

        $count = 0;
        foreach ($overdueTodos as $todo) {
            $todo->sendOverdueNotification();
            $count++;
        }

        return response()->json(['success' => true, 'notified' => $count]);
    }

    private function deleteOverdueNotification(Todo $todo)
    {
        $notifyUserId = $todo->assigned_to ?? $todo->user_id;
        
        Notification::where('user_id', $notifyUserId)
            ->where('title', 'Task Overdue!')
            ->where('url', 'LIKE', '%/todo/' . $todo->id . '%')
            ->delete();
    }

    private function deleteTaskNotifications(Todo $todo)
    {
        Notification::where('url', 'LIKE', '%/todo/' . $todo->id . '%')->delete();
    }

    private function getTodoWithAccess($id)
    {
        $user = $this->admin();
        $todo = Todo::with(['user', 'assignee'])->findOrFail($id);

        if (!$user->is_admin && $todo->user_id !== $user->id && $todo->assigned_to !== $user->id) {
            abort(403, 'You do not have permission to access this task.');
        }

        return $todo;
    }
}
