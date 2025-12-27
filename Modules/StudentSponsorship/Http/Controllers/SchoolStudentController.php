<?php

namespace Modules\StudentSponsorship\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\StudentSponsorship\Models\SchoolStudent;
use Modules\StudentSponsorship\Models\SchoolName;
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

class SchoolStudentController extends AdminController
{
    /**
     * DataTable endpoint - handles list, export, import, template
     */
    public function dataTable(Request $request)
    {
        // Import
        if ($request->isMethod('post') && $request->hasFile('file')) {
            return $this->importStudents($request);
        }

        // Template Download
        if ($request->has('template')) {
            return $this->downloadTemplate();
        }

        // Export - supports CSV, XLSX, PDF
        if ($request->has('export')) {
            return $this->exportStudents($request);
        }

        // List with pagination
        return $this->listStudents($request);
    }

    /**
     * List students with search, filters, sorting, pagination
     */
    protected function listStudents(Request $request)
    {
        $query = SchoolStudent::query()->with(['school', 'country']);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('school_internal_id', 'LIKE', "%{$search}%");
            });
        }

        // Filters
        $this->applyFilters($query, $request);

        // Sort
        $sortCol = $request->input('sort', 'id');
        $sortDir = $request->input('dir', 'desc');
        $sortable = ['id', 'school_internal_id', 'full_name', 'email', 'grade', 'age', 'status', 'created_at'];
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
                'school_internal_id' => $item->school_internal_id,
                'full_name' => $item->full_name,
                'email' => $item->email ?? '',
                'phone' => $item->phone ?? '',
                'school_name' => $item->school_name_display,
                'grade' => $item->grade ?? '',
                'current_state' => $item->current_state ?? 'inprogress',
                'age' => $item->age ?? '',
                'status' => $item->status,
                'profile_photo_url' => $item->profile_photo_url ?? '',
                '_edit_url' => route('admin.studentsponsorship.school-students.edit', $item->id),
                '_show_url' => route('admin.studentsponsorship.school-students.show', $item->id),
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
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($grade = $request->input('grade')) {
            $query->where('grade', $grade);
        }
        if ($schoolId = $request->input('school_id')) {
            $query->where('school_id', $schoolId);
        }
        if ($countryId = $request->input('country_id')) {
            $query->where('country_id', $countryId);
        }
        if ($fromDate = $request->input('from_date')) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate = $request->input('to_date')) {
            $query->whereDate('created_at', '<=', $toDate);
        }
    }

    /**
     * Export students - CSV, Excel, PDF
     */
    protected function exportStudents(Request $request)
    {
        $format = strtolower($request->get('export', 'csv'));
        $query = SchoolStudent::query()->with(['school', 'country']);

        $this->applyFilters($query, $request);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('school_internal_id', 'LIKE', "%{$search}%");
            });
        }

        $sortCol = $request->input('sort', 'id');
        $sortDir = $request->input('dir', 'desc');
        $query->orderBy($sortCol, $sortDir);

        if ($request->filled('ids')) {
            $ids = array_filter(explode(',', $request->ids));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        $data = $query->get();
        $filename = 'school_students_' . date('Y-m-d_His');
        $title = 'School Students Export';

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

    protected function getExportData($data)
    {
        $headers = ['ID', 'Student ID', 'Full Name', 'Email', 'Phone', 'DOB', 'Age', 'Country', 'City', 'Grade', 'Current State', 'School', 'Status', 'Created At'];
        
        $rows = $data->map(function ($item) {
            return [
                'ID' => $item->id,
                'Student ID' => $item->school_internal_id,
                'Full Name' => $item->full_name,
                'Email' => $item->email,
                'Phone' => $item->phone,
                'DOB' => $item->dob?->format('Y-m-d'),
                'Age' => $item->age,
                'Country' => $item->country_name,
                'City' => $item->city,
                'Grade' => $item->grade,
                'Current State' => $item->current_state === 'complete' ? 'Complete' : 'In Progress',
                'School' => $item->school_name_display,
                'Status' => $item->status ? 'Active' : 'Inactive',
                'Created At' => $item->created_at?->format('Y-m-d H:i'),
            ];
        })->toArray();

        return [$headers, $rows];
    }

    protected function exportToCsv($data, $filename)
    {
        [$headers, $rows] = $this->getExportData($data);

        $callback = function () use ($headers, $rows) {
            $file = fopen('php://output', 'w');
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

    protected function exportToExcel($data, $filename, $title)
    {
        [$headers, $rows] = $this->getExportData($data);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Students');

        if (empty($rows)) {
            $sheet->setCellValue('A1', 'No data to export');
            return $this->streamExcel($spreadsheet, $filename . '.xlsx');
        }

        $lastCol = Coordinate::stringFromColumnIndex(count($headers));
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', $title . ' - ' . date('d M Y H:i'));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(25);

        foreach ($headers as $index => $header) {
            $colLetter = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue("{$colLetter}3", $header);
            $sheet->getStyle("{$colLetter}3")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        $rowNum = 4;
        foreach ($rows as $row) {
            $colIndex = 1;
            foreach ($row as $value) {
                $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                $sheet->setCellValue("{$colLetter}{$rowNum}", $value);
                if ($rowNum % 2 === 0) {
                    $sheet->getStyle("{$colLetter}{$rowNum}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F9FAFB');
                }
                $sheet->getStyle("{$colLetter}{$rowNum}")->getBorders()
                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $colIndex++;
            }
            $rowNum++;
        }

        $rowNum++;
        $sheet->setCellValue("A{$rowNum}", "Total Records: " . count($rows));
        $sheet->getStyle("A{$rowNum}")->getFont()->setBold(true)->setItalic(true);

        return $this->streamExcel($spreadsheet, $filename . '.xlsx');
    }

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

    protected function exportToPdf($data, $filename, $title)
    {
        [$headers, $rows] = $this->getExportData($data);
        $html = $this->generatePdfHtml($title, $headers, $rows);
        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');
        return $pdf->download($filename . '.pdf');
    }

    protected function generatePdfHtml($title, $headers, $rows, $modelName = 'students')
    {
        $companyName = config('app.name', 'ERP System');
        $date = date('d M Y H:i');
        $totalRecords = count($rows);

        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>' . e($title) . '</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #333; padding: 15px; }
            .header { text-align: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #4F46E5; }
            .header h1 { font-size: 18px; color: #4F46E5; margin-bottom: 5px; }
            .header .subtitle { font-size: 12px; color: #666; }
            .meta { margin-bottom: 15px; font-size: 9px; color: #666; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
            th { background: #4F46E5; color: white; padding: 8px 6px; text-align: left; font-size: 9px; font-weight: bold; border: 1px solid #4F46E5; }
            td { padding: 6px; border: 1px solid #ddd; font-size: 9px; word-wrap: break-word; }
            tr:nth-child(even) { background: #f9fafb; }
            .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 9px; color: #666; text-align: center; }
            .total { font-weight: bold; margin-top: 10px; }
            .status-active { color: #16a34a; font-weight: bold; }
            .status-inactive { color: #dc2626; font-weight: bold; }
        </style></head><body>
        <div class="header"><h1>' . e($companyName) . '</h1><div class="subtitle">' . e($title) . '</div></div>
        <div class="meta"><span>Generated: ' . $date . '</span> | <span>Total Records: ' . $totalRecords . '</span></div>';

        if (empty($rows)) {
            $html .= '<div style="text-align:center;padding:30px;color:#666;">No data to export</div>';
        } else {
            $html .= '<table><thead><tr>';
            foreach ($headers as $header) { $html .= '<th>' . e($header) . '</th>'; }
            $html .= '</tr></thead><tbody>';
            foreach ($rows as $row) {
                $html .= '<tr>';
                foreach ($row as $key => $value) {
                    $class = ($key === 'Status') ? ' class="status-' . strtolower($value) . '"' : '';
                    $html .= '<td' . $class . '>' . e($value ?? '') . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
        }

        $html .= '<div class="footer"><div class="total">Total Records: ' . $totalRecords . '</div>
            <div>Generated by ' . e($companyName) . ' on ' . $date . '</div></div></body></html>';

        return $html;
    }

    protected function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Import Data');

        $headers = ['school_internal_id', 'full_name', 'email', 'phone', 'dob', 'age', 'country_id', 'city', 'grade', 'current_state', 'school_id'];
        $hints = [
            'Required, Unique ID',
            'Required, Text (max 255)',
            'Optional, Valid Email',
            'Optional, Phone Number',
            'Optional, Date (YYYY-MM-DD)',
            'Required, Number (1-100)',
            'Optional, Country ID (see Reference)',
            'Optional, City Name',
            'Required, Grade Code (see Reference)',
            'Optional, inprogress or complete (default: inprogress)',
            'Optional, School ID (see Reference)',
        ];

        foreach ($headers as $index => $header) {
            $colLetter = Coordinate::stringFromColumnIndex($index + 1);
            $cell = $sheet->getCell("{$colLetter}1");
            $cell->setValue($header);
            $cell->getStyle()->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
            $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4F46E5');
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        foreach ($hints as $index => $hint) {
            $colLetter = Coordinate::stringFromColumnIndex($index + 1);
            $cell = $sheet->getCell("{$colLetter}2");
            $cell->setValue($hint);
            $cell->getStyle()->getFont()->setItalic(true)->getColor()->setRGB('9CA3AF');
        }

        $sampleData = ['STU001', 'John Doe', 'john@example.com', '771234567', '2010-05-15', '14', '144', 'Colombo', '10', 'inprogress', ''];
        // Note: Age 14 = Grade 10 (correct mapping)
        foreach ($sampleData as $index => $value) {
            $colLetter = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue("{$colLetter}3", $value);
        }

        $this->addReferenceSheet($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'school_students_import_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    protected function addReferenceSheet($spreadsheet)
    {
        $infoSheet = $spreadsheet->createSheet();
        $infoSheet->setTitle('Reference Data');
        $infoSheet->setCellValue('A1', 'REFERENCE DATA (Use ID in import)');
        $infoSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $row = 3;
        
        // Age-Grade Mapping (Important for validation)
        $infoSheet->setCellValue("A{$row}", 'AGE-GRADE MAPPING (Expected age range for each grade):');
        $infoSheet->getStyle("A{$row}")->getFont()->setBold(true)->getColor()->setRGB('DC2626');
        $row++;
        $infoSheet->setCellValue("A{$row}", 'Grade');
        $infoSheet->setCellValue("B{$row}", 'Label');
        $infoSheet->setCellValue("C{$row}", 'Min Age');
        $infoSheet->setCellValue("D{$row}", 'Max Age');
        $infoSheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
        $row++;
        
        $gradeAgeMapping = config('studentsponsorship.grade_age_mapping', []);
        $grades = config('studentsponsorship.school_grades', []);
        foreach ($gradeAgeMapping as $gradeNum => $ageRange) {
            $infoSheet->setCellValue("A{$row}", $gradeNum);
            $infoSheet->setCellValue("B{$row}", $grades[(string)$gradeNum] ?? "Grade {$gradeNum}");
            $infoSheet->setCellValue("C{$row}", $ageRange['min']);
            $infoSheet->setCellValue("D{$row}", $ageRange['max']);
            $row++;
        }
        
        $row += 2;
        $infoSheet->setCellValue("A{$row}", 'SCHOOLS (for school_id):');
        $infoSheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;
        $infoSheet->setCellValue("A{$row}", 'ID');
        $infoSheet->setCellValue("B{$row}", 'Name');
        $row++;

        $schools = SchoolName::active()->orderBy('name')->limit(100)->get(['id', 'name']);
        foreach ($schools as $school) {
            $infoSheet->setCellValue("A{$row}", $school->id);
            $infoSheet->setCellValue("B{$row}", $school->name);
            $row++;
        }

        $row += 2;
        $infoSheet->setCellValue("A{$row}", 'COUNTRIES (for country_id):');
        $infoSheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;
        $infoSheet->setCellValue("A{$row}", 'ID');
        $infoSheet->setCellValue("B{$row}", 'Name');
        $infoSheet->setCellValue("C{$row}", 'Calling Code');
        $row++;

        $countries = DB::table('countries')->orderBy('short_name')->limit(50)->get(['country_id', 'short_name', 'calling_code']);
        foreach ($countries as $country) {
            $infoSheet->setCellValue("A{$row}", $country->country_id);
            $infoSheet->setCellValue("B{$row}", $country->short_name);
            $infoSheet->setCellValue("C{$row}", '+' . $country->calling_code);
            $row++;
        }

        $row += 2;
        $infoSheet->setCellValue("A{$row}", 'GRADE OPTIONS:');
        $infoSheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;
        foreach ($grades as $key => $label) {
            $infoSheet->setCellValue("A{$row}", $key);
            $infoSheet->setCellValue("B{$row}", $label);
            $row++;
        }

        $infoSheet->getColumnDimension('A')->setAutoSize(true);
        $infoSheet->getColumnDimension('B')->setAutoSize(true);
        $infoSheet->getColumnDimension('C')->setAutoSize(true);
        $infoSheet->getColumnDimension('D')->setAutoSize(true);
    }

    protected function importStudents(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:10240']);

        try {
            $rows = $this->parseFile($request->file('file'));
            
            if (empty($rows)) {
                return response()->json(['success' => false, 'message' => 'No data found in file'], 400);
            }

            $results = ['total' => 0, 'success' => 0, 'failed' => 0, 'errors' => [], 'warnings' => []];
            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNum = $index + 3;
                
                if ($this->isRowEmpty($row)) continue;
                if ($index === 0 && $this->isHintRow($row)) continue;

                $results['total']++;

                $rules = [
                    'school_internal_id' => 'required|string|max:50|unique:school_students,school_internal_id',
                    'full_name' => 'required|string|max:255',
                    'email' => 'nullable|email|unique:school_students,email',
                    'phone' => 'nullable|string|max:30',
                    'dob' => 'nullable|date',
                    'age' => 'required|integer|min:1|max:100',
                    'country_id' => 'nullable|integer',
                    'city' => 'nullable|string|max:255',
                    'grade' => 'required|string|max:20',
                    'current_state' => 'nullable|in:inprogress,complete',
                    'school_id' => 'nullable|exists:school_names,id',
                ];

                $validator = Validator::make($row, $rules);
                
                if ($validator->fails()) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNum}: " . $validator->errors()->first();
                    continue;
                }

                // Check age-grade mismatch (warning only, still imports)
                if (isset($row['age']) && isset($row['grade'])) {
                    $ageGradeCheck = $this->validateAgeGrade((int)$row['age'], $row['grade']);
                    if (!$ageGradeCheck['is_valid']) {
                        $suggested = $ageGradeCheck['suggested_grade'];
                        $results['warnings'][] = "Row {$rowNum}: Age {$row['age']} mismatch with Grade {$row['grade']} (expected {$suggested['label']})";
                    }
                }

                try {
                    $data = array_filter($row, fn($v) => $v !== '' && $v !== null);
                    $data['status'] = true;
                    // Default current_state to inprogress if not provided
                    if (!isset($data['current_state']) || empty($data['current_state'])) {
                        $data['current_state'] = 'inprogress';
                    }
                    SchoolStudent::create($data);
                    $results['success']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNum}: " . $e->getMessage();
                }
            }

            if ($results['success'] === 0 && $results['failed'] > 0) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Import failed - no records imported', 'results' => $results], 422);
            }

            DB::commit();
            
            $message = "{$results['success']} of {$results['total']} students imported successfully";
            if (count($results['warnings']) > 0) {
                $message .= ". " . count($results['warnings']) . " age-grade mismatches found.";
            }
            
            return response()->json(['success' => true, 'message' => $message, 'results' => $results]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    protected function parseFile($file)
    {
        $ext = strtolower($file->getClientOriginalExtension());
        return $ext === 'csv' ? $this->parseCsv($file) : $this->parseExcel($file);
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

    public function index()
    {
        $stats = [
            'total' => SchoolStudent::count(),
            'active' => SchoolStudent::where('status', true)->count(),
            'inactive' => SchoolStudent::where('status', false)->count(),
        ];
        $schools = SchoolName::active()->orderBy('name')->get();
        $grades = config('studentsponsorship.school_grades', []);
        return view('studentsponsorship::school-students.index', compact('stats', 'schools', 'grades'));
    }

    public function create()
    {
        $schools = SchoolName::active()->orderBy('name')->get();
        $countries = DB::table('countries')->orderBy('short_name')->get();
        $banks = DB::table('banks')->orderBy('name')->get();
        $grades = config('studentsponsorship.school_grades', []);
        $schoolTypes = config('studentsponsorship.school_types', []);
        $gradeAgeMapping = config('studentsponsorship.grade_age_mapping', []);
        return view('studentsponsorship::school-students.create', compact('schools', 'countries', 'banks', 'grades', 'schoolTypes', 'gradeAgeMapping'));
    }

    /**
     * Check if age matches expected grade range
     */
    protected function validateAgeGrade($age, $grade)
    {
        $mapping = config('studentsponsorship.grade_age_mapping', []);
        
        // Convert grade to numeric (handle strings like "O/L", "A/L1" etc)
        $gradeNum = is_numeric($grade) ? (int)$grade : null;
        if ($gradeNum === null) {
            // Handle special grade names
            $gradeMap = ['11' => 11, '12' => 12, '13' => 13, '14' => 14];
            $gradeNum = $gradeMap[$grade] ?? null;
        }
        
        if ($gradeNum && isset($mapping[$gradeNum])) {
            $expectedMin = $mapping[$gradeNum]['min'];
            $expectedMax = $mapping[$gradeNum]['max'];
            return [
                'is_valid' => $age >= $expectedMin && $age <= $expectedMax,
                'expected_min' => $expectedMin,
                'expected_max' => $expectedMax,
                'difference' => $age < $expectedMin ? ($expectedMin - $age) : ($age > $expectedMax ? ($age - $expectedMax) : 0),
                'suggested_grade' => $this->getSuggestedGrade($age)
            ];
        }
        
        return ['is_valid' => true, 'difference' => 0];
    }

    /**
     * Get suggested grade for an age
     */
    protected function getSuggestedGrade($age)
    {
        $mapping = config('studentsponsorship.grade_age_mapping', []);
        $grades = config('studentsponsorship.school_grades', []);
        
        foreach ($mapping as $grade => $range) {
            if ($age >= $range['min'] && $age <= $range['max']) {
                return [
                    'grade' => (string)$grade,
                    'label' => $grades[(string)$grade] ?? "Grade {$grade}"
                ];
            }
        }
        return null;
    }

    /**
     * AJAX: Validate age-grade combination
     */
    public function validateAgeGradeAjax(Request $request)
    {
        $age = (int)$request->input('age');
        $grade = $request->input('grade');
        
        $result = $this->validateAgeGrade($age, $grade);
        
        return response()->json($result);
    }

    public function store(Request $request)
    {
        // First check if age-grade mismatch requires reason
        $age = (int)$request->input('age');
        $grade = $request->input('grade');
        $ageGradeCheck = $this->validateAgeGrade($age, $grade);
        
        // Build validation rules
        $rules = [
            'school_internal_id' => 'required|string|max:50|unique:school_students,school_internal_id',
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:school_students,email',
            'phone' => 'nullable|string|max:30',
            'dob' => 'nullable|date',
            'age' => 'required|integer|min:1|max:100',
            'country_id' => 'nullable|integer',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'grade' => 'required|string|max:20',
            'grade_mismatch_reason' => 'nullable|string|max:255',
            'current_state' => 'required|in:inprogress,complete',
            'school_type' => 'nullable|string|max:50',
            'school_id' => 'nullable|exists:school_names,id',
            'sponsorship_start_date' => 'nullable|date',
            'sponsorship_end_date' => 'nullable|date|after_or_equal:sponsorship_start_date',
            'introduced_by' => 'nullable|string|max:255',
            'introducer_phone' => 'nullable|string|max:30',
            'bank_id' => 'nullable|integer',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_branch_number' => 'nullable|string|max:50',
            'bank_branch_info' => 'nullable|string',
            'father_name' => 'nullable|string|max:255',
            'father_income' => 'nullable|numeric|min:0',
            'mother_name' => 'nullable|string|max:255',
            'mother_income' => 'nullable|numeric|min:0',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_income' => 'nullable|numeric|min:0',
            'background_info' => 'nullable|string',
            'internal_comment' => 'nullable|string',
            'external_comment' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
            'status' => 'boolean',
        ];
        
        // If age is 1+ year off from expected, require mismatch reason
        if (!$ageGradeCheck['is_valid'] && $ageGradeCheck['difference'] >= 1) {
            $rules['grade_mismatch_reason'] = 'required|string|max:255';
        }
        
        $validated = $request->validate($rules);

        $validated['status'] = $request->boolean('status', true);
        $student = SchoolStudent::create($validated);

        if ($request->hasFile('profile_photo')) {
            $student->addMediaFromRequest('profile_photo')->toMediaCollection('profile_photo');
        }

        return redirect()->route('admin.studentsponsorship.school-students.edit', $student->id)
            ->with('success', 'Student created successfully! ID: ' . $student->school_internal_id);
    }

    public function show($id)
    {
        $student = SchoolStudent::with(['school', 'country', 'bank'])->findOrFail($id);
        return view('studentsponsorship::school-students.show', compact('student'));
    }

    public function edit($id)
    {
        $student = SchoolStudent::findOrFail($id);
        $schools = SchoolName::active()->orderBy('name')->get();
        $countries = DB::table('countries')->orderBy('short_name')->get();
        $banks = DB::table('banks')->orderBy('name')->get();
        $grades = config('studentsponsorship.school_grades', []);
        $schoolTypes = config('studentsponsorship.school_types', []);
        $gradeAgeMapping = config('studentsponsorship.grade_age_mapping', []);
        return view('studentsponsorship::school-students.edit', compact('student', 'schools', 'countries', 'banks', 'grades', 'schoolTypes', 'gradeAgeMapping'));
    }

    public function update(Request $request, $id)
    {
        $student = SchoolStudent::findOrFail($id);

        // First check if age-grade mismatch requires reason
        $age = (int)$request->input('age');
        $grade = $request->input('grade');
        $ageGradeCheck = $this->validateAgeGrade($age, $grade);

        // Build validation rules
        $rules = [
            'school_internal_id' => 'required|string|max:50|unique:school_students,school_internal_id,' . $id,
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:school_students,email,' . $id,
            'phone' => 'nullable|string|max:30',
            'dob' => 'nullable|date',
            'age' => 'required|integer|min:1|max:100',
            'country_id' => 'nullable|integer',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'grade' => 'required|string|max:20',
            'grade_mismatch_reason' => 'nullable|string|max:255',
            'current_state' => 'required|in:inprogress,complete',
            'school_type' => 'nullable|string|max:50',
            'school_id' => 'nullable|exists:school_names,id',
            'sponsorship_start_date' => 'nullable|date',
            'sponsorship_end_date' => 'nullable|date|after_or_equal:sponsorship_start_date',
            'introduced_by' => 'nullable|string|max:255',
            'introducer_phone' => 'nullable|string|max:30',
            'bank_id' => 'nullable|integer',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_branch_number' => 'nullable|string|max:50',
            'bank_branch_info' => 'nullable|string',
            'father_name' => 'nullable|string|max:255',
            'father_income' => 'nullable|numeric|min:0',
            'mother_name' => 'nullable|string|max:255',
            'mother_income' => 'nullable|numeric|min:0',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_income' => 'nullable|numeric|min:0',
            'background_info' => 'nullable|string',
            'internal_comment' => 'nullable|string',
            'external_comment' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
            'status' => 'boolean',
        ];
        
        // If age is 1+ year off from expected, require mismatch reason
        if (!$ageGradeCheck['is_valid'] && $ageGradeCheck['difference'] >= 1) {
            $rules['grade_mismatch_reason'] = 'required|string|max:255';
        }
        
        $validated = $request->validate($rules);

        $validated['status'] = $request->boolean('status', true);
        
        // Clear mismatch reason if age now matches grade
        if ($ageGradeCheck['is_valid']) {
            $validated['grade_mismatch_reason'] = null;
        }
        
        $student->update($validated);

        if ($request->hasFile('profile_photo')) {
            $student->clearMediaCollection('profile_photo');
            $student->addMediaFromRequest('profile_photo')->toMediaCollection('profile_photo');
        }

        return redirect()->route('admin.studentsponsorship.school-students.edit', $student->id)
            ->with('success', 'Student updated successfully!');
    }

    public function destroy($id)
    {
        $student = SchoolStudent::findOrFail($id);
        $student->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Student deleted successfully']);
        }
        return redirect()->route('admin.studentsponsorship.school-students.index')->with('success', 'Student deleted successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }
        $deleted = SchoolStudent::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => $deleted . ' students deleted']);
    }

    public function addSchool(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $school = SchoolName::create(['name' => $request->name, 'status' => true]);
        return response()->json(['success' => true, 'id' => $school->id, 'name' => $school->name]);
    }

    public function uploadReportCard(Request $request, $id)
    {
        $student = SchoolStudent::findOrFail($id);
        $request->validate([
            'report_card' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'title' => 'required|string|max:255',
            'term' => 'required|string|in:Term 1,Term 2,Term 3',
            'upload_date' => 'required|date',
        ]);

        $media = $student->addMediaFromRequest('report_card')
            ->usingName($request->input('title'))
            ->withCustomProperties([
                'term' => $request->input('term'),
                'upload_date' => $request->input('upload_date'),
            ])
            ->toMediaCollection('report_cards');

        return response()->json([
            'success' => true,
            'message' => 'Report card uploaded successfully',
            'media' => [
                'id' => $media->id, 
                'name' => $media->name, 
                'term' => $media->getCustomProperty('term'),
                'upload_date' => $media->getCustomProperty('upload_date'),
                'url' => $media->getUrl()
            ]
        ]);
    }

    public function deleteReportCard($id, $mediaId)
    {
        $student = SchoolStudent::findOrFail($id);
        $media = $student->getMedia('report_cards')->where('id', $mediaId)->first();
        
        if ($media) {
            $media->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Not found'], 404);
    }
}
