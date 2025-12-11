<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;

trait DataTable
{
    /**
     * Main DataTable handler
     * 
     * GET  /data              → List data (JSON)
     * GET  /data?export=csv   → Export CSV
     * GET  /data?template=1   → Download import template
     * POST /data (with file)  → Import data
     */
    public function dataTable(Request $request)
    {
        // =====================
        // IMPORT (POST with file)
        // =====================
        if ($request->isMethod('post') && $request->hasFile('file')) {
            return $this->handleImport($request);
        }

        // =====================
        // TEMPLATE DOWNLOAD
        // =====================
        if ($request->has('template')) {
            return $this->downloadTemplate();
        }

        // Build query
        $query = $this->model::query();

        // Eager load relationships
        if (property_exists($this, 'with') && !empty($this->with)) {
            $query->with($this->with);
        }

        // =====================
        // EXPORT SELECTED IDs
        // =====================
        if ($request->has('ids') && $request->has('export')) {
            $ids = array_filter(explode(',', $request->input('ids')));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
            return $this->dtExport($query, $request->input('export'));
        }

        // =====================
        // SEARCH
        // =====================
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                foreach ($this->searchable ?? [] as $col) {
                    $q->orWhere($col, 'LIKE', "%{$search}%");
                }
            });
        }

        // =====================
        // FILTERS (direct params - same as search)
        // =====================
        if (property_exists($this, 'filterable')) {
            foreach ($this->filterable as $column) {
                if ($request->filled($column)) {
                    $query->where($column, $request->input($column));
                }
            }
        }

        // Also check common filter patterns
        foreach ($request->all() as $key => $value) {
            if ($value !== '' && $value !== null && !in_array($key, ['page', 'per_page', 'search', 'sort', 'dir', 'export', 'template', 'ids'])) {
                // Check if column exists (basic check)
                if (str_ends_with($key, '_id') || in_array($key, ['status', 'type', 'is_active'])) {
                    $query->where($key, $value);
                }
            }
        }

        // =====================
        // DATE RANGE FILTERS
        // =====================
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // =====================
        // SORTING
        // =====================
        $sortCol = $request->input('sort', 'id');
        $sortDir = $request->input('dir', 'desc');
        $query->orderBy($sortCol, $sortDir);

        // =====================
        // EXPORT ALL
        // =====================
        if ($request->has('export')) {
            return $this->dtExport($query, $request->input('export'));
        }

        // =====================
        // PAGINATE & RETURN
        // =====================
        $perPage = $request->input('per_page', 10);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            $prefix = $this->routePrefix ?? 'admin';
            try {
                $item->_edit_url = route("{$prefix}.edit", $item->id);
                $item->_show_url = route("{$prefix}.show", $item->id);
            } catch (\Exception $e) {
                $item->_edit_url = '#';
                $item->_show_url = '#';
            }
            return $item;
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
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
            return response()->json(['success' => false, 'message' => 'Delete failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Export to CSV
     */
    protected function dtExport($query, $type)
    {
        $columns = $this->exportable ?? ['*'];
        $data = $query->get($columns);
        $modelName = strtolower(class_basename($this->model));
        $filename = $modelName . '_' . date('Y-m-d');

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}.csv",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            if ($data->count()) {
                fputcsv($file, array_keys($data->first()->toArray()));
            }
            foreach ($data as $row) {
                fputcsv($file, $row->toArray());
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Download Import Template (Excel with hints)
     */
    protected function downloadTemplate()
    {
        $columns = $this->importable ?? [];
        
        if (empty($columns)) {
            return response()->json(['error' => 'Import not configured'], 400);
        }

        $modelName = strtolower(class_basename($this->model));
        $filename = $modelName . '_import_template.xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Import Data');

        // Header row - Blue background, white bold text
        $col = 1;
        foreach (array_keys($columns) as $header) {
            $cell = $sheet->getCellByColumnAndRow($col, 1);
            $cell->setValue($header);
            $style = $cell->getStyle();
            $style->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4F46E5');
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
            $col++;
        }

        // Hints row - Gray italic text
        $col = 1;
        foreach ($columns as $colName => $rules) {
            $hint = $this->buildHint($rules);
            $cell = $sheet->getCellByColumnAndRow($col, 2);
            $cell->setValue($hint);
            $cell->getStyle()->getFont()->setItalic(true)->getColor()->setRGB('9CA3AF');
            $col++;
        }

        // Instructions sheet
        $infoSheet = $spreadsheet->createSheet();
        $infoSheet->setTitle('Instructions');
        $infoSheet->setCellValue('A1', 'IMPORT INSTRUCTIONS');
        $infoSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        $infoSheet->setCellValue('A3', 'Column');
        $infoSheet->setCellValue('B3', 'Required');
        $infoSheet->setCellValue('C3', 'Format');
        $infoSheet->getStyle('A3:C3')->getFont()->setBold(true);
        
        $row = 4;
        foreach ($columns as $colName => $rules) {
            $infoSheet->setCellValue('A' . $row, $colName);
            $infoSheet->setCellValue('B' . $row, str_contains($rules, 'required') ? 'YES' : 'No');
            $infoSheet->setCellValue('C' . $row, $this->buildHint($rules));
            $row++;
        }

        $infoSheet->getColumnDimension('A')->setAutoSize(true);
        $infoSheet->getColumnDimension('B')->setAutoSize(true);
        $infoSheet->getColumnDimension('C')->setAutoSize(true);

        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Handle Import from Excel/CSV
     */
    protected function handleImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $columns = $this->importable ?? [];
        
        if (empty($columns)) {
            return response()->json(['success' => false, 'message' => 'Import not configured'], 400);
        }

        $file = $request->file('file');
        
        try {
            $rows = $this->parseFile($file);
            
            if (empty($rows)) {
                return response()->json(['success' => false, 'message' => 'No data found in file'], 400);
            }

            $results = ['total' => 0, 'success' => 0, 'failed' => 0, 'errors' => []];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNum = $index + 3; // +3 because row 1=header, row 2=hints
                
                // Skip empty rows
                if ($this->isRowEmpty($row)) continue;
                
                // Skip hint row (first data row might be hints)
                if ($index === 0 && $this->isHintRow($row)) continue;

                $results['total']++;

                // Validate
                $validator = Validator::make($row, $columns);
                
                if ($validator->fails()) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNum}: " . $validator->errors()->first();
                    continue;
                }

                try {
                    // Filter out empty values and create
                    $data = array_filter($row, fn($v) => $v !== '' && $v !== null);
                    
                    // Call custom import method if exists
                    if (method_exists($this, 'importRow')) {
                        $this->importRow($data, $row);
                    } else {
                        $this->model::create($data);
                    }
                    
                    $results['success']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNum}: " . $e->getMessage();
                }
            }

            // Rollback if all failed
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
                'message' => "{$results['success']} of {$results['total']} records imported successfully",
                'results' => $results
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Import error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Parse Excel or CSV file
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
            
            $row = [];
            foreach ($headers as $i => $h) {
                if (!empty($h)) {
                    $row[$h] = $rowData[$i] ?? '';
                }
            }
            $rows[] = $row;
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
     * Build hint text from validation rules
     */
    protected function buildHint($rules)
    {
        $req = str_contains($rules, 'required') ? 'Required' : 'Optional';
        
        if (str_contains($rules, 'email')) return "{$req}, Email";
        if (str_contains($rules, 'integer')) return "{$req}, Integer";
        if (str_contains($rules, 'numeric')) return "{$req}, Number";
        if (str_contains($rules, 'date')) return "{$req}, Date (YYYY-MM-DD)";
        if (str_contains($rules, 'boolean')) return "{$req}, 1 or 0";
        if (preg_match('/in:([^|]+)/', $rules, $m)) return "{$req}, Options: {$m[1]}";
        if (preg_match('/exists:([^,]+),(\w+)/', $rules, $m)) return "{$req}, ID from {$m[1]}";
        
        return "{$req}, Text";
    }
}