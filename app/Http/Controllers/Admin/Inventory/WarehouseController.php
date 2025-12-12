<?php

namespace App\Http\Controllers\Admin\Inventory;

use Illuminate\Http\Request;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\Rack;
use App\Models\Inventory\Unit;
use App\Models\Inventory\StockLevel;
use App\Models\Inventory\StockMovement;
use App\Traits\DataTable;
use Illuminate\Support\Facades\DB;

class WarehouseController extends BaseController
{
    use DataTable;

    // ==================== DATATABLE CONFIGURATION ====================
    protected $model = Warehouse::class;
    
    protected $with = ['racks'];
    
    protected $searchable = ['name', 'code', 'city', 'state', 'contact_person'];
    
    protected $sortable = ['id', 'name', 'code', 'city', 'type', 'created_at'];
    
    protected $filterable = ['type', 'is_active', 'is_default'];
    
    protected $routePrefix = 'admin.inventory.warehouses';
    
    protected $uniqueField = 'code';
    
    protected $exportTitle = 'Warehouses Export';

    // Import validation rules
    protected $importable = [
        'name'           => 'required|string|max:100',
        'code'           => 'required|string|max:20',
        'address'        => 'nullable|string',
        'city'           => 'nullable|string|max:50',
        'state'          => 'nullable|string|max:50',
        'country'        => 'nullable|string|max:50',
        'contact_person' => 'nullable|string|max:100',
        'phone'          => 'nullable|string|max:20',
        'type'           => 'required|in:STORAGE,SHOP,RETURN_CENTER',
    ];

    // ==================== CUSTOM ROW MAPPING FOR LIST ====================
    protected function mapRow($item)
    {
        return [
            'id' => $item->id,
            'code' => $item->code,
            'name' => $item->name,
            'address' => $item->address ?? '-',
            'city' => $item->city ?? '-',
            'state' => $item->state ?? '-',
            'country' => $item->country ?? '-',
            'type' => $item->type,
            'racks_count' => $item->racks_count ?? $item->racks->count(),
            'contact_person' => $item->contact_person ?? '-',
            'phone' => $item->phone ?? '-',
            'is_default' => $item->is_default,
            'is_active' => $item->is_active,
            'status' => $item->is_active ? 'Active' : 'Inactive',
            '_edit_url' => route('admin.inventory.warehouses.edit', $item->id),
            '_delete_url' => route('admin.inventory.warehouses.destroy', $item->id),
        ];
    }

    // ==================== CUSTOM EXPORT ROW MAPPING ====================
    protected function mapExportRow($item)
    {
        return [
            'ID' => $item->id,
            'Code' => $item->code,
            'Name' => $item->name,
            'Address' => $item->address ?? '',
            'City' => $item->city ?? '',
            'State' => $item->state ?? '',
            'Country' => $item->country ?? '',
            'Type' => $item->type,
            'Contact Person' => $item->contact_person ?? '',
            'Phone' => $item->phone ?? '',
            'Racks Count' => $item->racks->count(),
            'Is Default' => $item->is_default ? 'Yes' : 'No',
            'Status' => $item->is_active ? 'Active' : 'Inactive',
        ];
    }

    // ==================== CUSTOM IMPORT ROW HANDLER ====================
    protected function importRow($data, $row)
    {
        $data['is_active'] = true;
        
        $existing = Warehouse::where('code', $data['code'])->first();
        
        if ($existing) {
            $existing->update($data);
            return $existing;
        }
        
        return Warehouse::create($data);
    }

    // ==================== DATA ENDPOINT ====================
    public function data(Request $request)
    {
        return $this->handleData($request);
    }

    // ==================== INDEX ====================
    public function index()
    {
        $stats = [
            'total' => Warehouse::count(),
            'active' => Warehouse::where('is_active', true)->count(),
            'inactive' => Warehouse::where('is_active', false)->count(),
        ];
        
        return view('admin.inventory.warehouses.index', compact('stats'));
    }

    // ==================== CREATE ====================
    public function create()
    {
        return view('admin.inventory.warehouses.create');
    }

    // ==================== STORE ====================
    public function store(Request $request)
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

    // ==================== EDIT ====================
    public function edit($id)
    {
        $warehouse = Warehouse::with('racks')->findOrFail($id);
        return view('admin.inventory.warehouses.edit', compact('warehouse'));
    }

    // ==================== UPDATE ====================
    public function update(Request $request, $id)
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

    // ==================== SET DEFAULT ====================
    public function setDefault($id)
    {
        Warehouse::where('is_default', true)->update(['is_default' => false]);
        Warehouse::where('id', $id)->update(['is_default' => true]);
        
        return response()->json(['success' => true, 'message' => 'Default warehouse updated']);
    }

    // ==================== DEACTIVATE ====================
    public function deactivate($id)
    {
        Warehouse::where('id', $id)->update(['is_active' => false]);
        return response()->json(['success' => true, 'message' => 'Warehouse deactivated']);
    }

    // ==================== DESTROY ====================
    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        
        if (StockMovement::where('warehouse_id', $id)->exists()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete warehouse with stock movements'], 422);
            }
            return back()->with('error', 'Cannot delete warehouse with stock movements');
        }
        
        if (Rack::where('warehouse_id', $id)->exists()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete warehouse with racks. Delete racks first.'], 422);
            }
            return back()->with('error', 'Cannot delete warehouse with racks');
        }
        
        $warehouse->delete();
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Warehouse deleted successfully']);
        }
        
        return redirect()->route('admin.inventory.warehouses.index')
            ->with('success', 'Warehouse deleted successfully!');
    }

    // ==================== BULK DELETE ====================
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }

        $hasMovements = StockMovement::whereIn('warehouse_id', $ids)->exists();
        if ($hasMovements) {
            return response()->json([
                'success' => false, 
                'message' => 'Cannot delete warehouses with stock movements.'
            ], 422);
        }

        $hasRacks = Rack::whereIn('warehouse_id', $ids)->exists();
        if ($hasRacks) {
            return response()->json([
                'success' => false, 
                'message' => 'Cannot delete warehouses with racks.'
            ], 422);
        }

        Warehouse::whereIn('id', $ids)->delete();
        
        return response()->json([
            'success' => true, 
            'message' => count($ids) . ' warehouses deleted'
        ]);
    }

    // ==================== RACKS INDEX ====================
    public function racksIndex()
    {
        $stats = [
            'total' => Rack::count(),
            'active' => Rack::where('is_active', true)->count(),
            'inactive' => Rack::where('is_active', false)->count(),
        ];
        
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.racks.index', compact('stats', 'warehouses'));
    }

    // ==================== RACKS DATA ====================
    public function racksData(Request $request)
    {
        $query = Rack::with(['warehouse', 'capacityUnit']);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('zone', 'like', "%{$search}%")
                    ->orWhereHas('warehouse', function($wq) use ($search) {
                        $wq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filters
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Sorting
        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        
        // Handle relation sorting
        if ($sortField === 'warehouse_name') {
            $query->join('warehouses', 'racks.warehouse_id', '=', 'warehouses.id')
                  ->orderBy('warehouses.name', $sortDir)
                  ->select('racks.*');
        } else {
            $query->orderBy($sortField, $sortDir);
        }

        // Pagination
        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        // Map rows
        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'warehouse_id' => $item->warehouse_id,
                'warehouse_name' => $item->warehouse?->name ?? '-',
                'zone' => $item->zone ?? '-',
                'aisle' => $item->aisle ?? '-',
                'level' => $item->level ?? '-',
                'full_location' => $item->full_location,
                'max_capacity' => $item->max_capacity 
                    ? number_format($item->max_capacity, 2) . ' ' . ($item->capacityUnit?->short_name ?? '') 
                    : '-',
                'max_weight' => $item->max_weight 
                    ? number_format($item->max_weight, 2) . ' kg' 
                    : '-',
                'description' => $item->description ?? '',
                'is_active' => $item->is_active,
                'status' => $item->is_active ? 'Active' : 'Inactive',
                '_edit_url' => route('admin.inventory.racks.edit', $item->id),
                '_delete_url' => route('admin.inventory.racks.destroy', $item->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    // ==================== RACKS EXPORT ====================
    public function racksExport(Request $request)
    {
        $format = $request->get('format', 'xlsx');
        
        $query = Rack::with(['warehouse', 'capacityUnit']);
        
        // Apply same filters as data
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }
        
        $items = $query->get();
        
        $exportData = $items->map(function ($item) {
            return [
                'ID' => $item->id,
                'Code' => $item->code,
                'Name' => $item->name,
                'Warehouse' => $item->warehouse?->name ?? '',
                'Zone' => $item->zone ?? '',
                'Aisle' => $item->aisle ?? '',
                'Level' => $item->level ?? '',
                'Max Capacity' => $item->max_capacity ?? '',
                'Capacity Unit' => $item->capacityUnit?->short_name ?? '',
                'Max Weight (kg)' => $item->max_weight ?? '',
                'Description' => $item->description ?? '',
                'Status' => $item->is_active ? 'Active' : 'Inactive',
            ];
        })->toArray();
        
        return $this->exportData($exportData, 'Racks_Export', $format);
    }

    // ==================== RACKS CREATE ====================
    public function racksCreate()
    {
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.racks.create', compact('warehouses', 'units'));
    }

    // ==================== RACKS STORE ====================
    public function racksStore(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:191',
            'zone' => 'nullable|string|max:50',
            'aisle' => 'nullable|string|max:50',
            'level' => 'nullable|string|max:50',
            'max_capacity' => 'nullable|numeric|min:0',
            'capacity_unit_id' => 'nullable|exists:units,id',
            'max_weight' => 'nullable|numeric|min:0',
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

    // ==================== RACKS EDIT ====================
    public function racksEdit($id)
    {
        $rack = Rack::with(['warehouse', 'capacityUnit', 'stockLevels'])->findOrFail($id);
        $rack->stock_count = $rack->stockLevels->where('qty', '>', 0)->count();
        
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.racks.edit', compact('rack', 'warehouses', 'units'));
    }

    // ==================== RACKS UPDATE ====================
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
            'max_capacity' => 'nullable|numeric|min:0',
            'capacity_unit_id' => 'nullable|exists:units,id',
            'max_weight' => 'nullable|numeric|min:0',
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

    // ==================== RACKS DEACTIVATE ====================
    public function racksDeactivate($id)
    {
        Rack::where('id', $id)->update(['is_active' => false]);
        return response()->json(['success' => true, 'message' => 'Rack deactivated']);
    }

    // ==================== RACKS DESTROY ====================
    public function racksDestroy($id)
    {
        $rack = Rack::findOrFail($id);
        
        if (StockLevel::where('rack_id', $id)->where('qty', '>', 0)->exists()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete rack with stock'], 422);
            }
            return back()->with('error', 'Cannot delete rack with stock');
        }
        
        $rack->delete();
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Rack deleted successfully']);
        }
        
        return redirect()->route('admin.inventory.racks.index')
            ->with('success', 'Rack deleted successfully!');
    }

    // ==================== RACKS BULK DESTROY ====================
    public function racksBulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }

        $hasStock = StockLevel::whereIn('rack_id', $ids)->where('qty', '>', 0)->exists();
        if ($hasStock) {
            return response()->json([
                'success' => false, 
                'message' => 'Cannot delete racks with existing stock.'
            ], 422);
        }

        Rack::whereIn('id', $ids)->delete();
        
        return response()->json([
            'success' => true, 
            'message' => count($ids) . ' racks deleted'
        ]);
    }

    // ==================== AJAX: RACKS BY WAREHOUSE ====================
    public function racksByWarehouse($warehouseId)
    {
        $racks = Rack::where('warehouse_id', $warehouseId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get(['id', 'code', 'name', 'zone', 'aisle', 'level']);
        
        return response()->json($racks);
    }

    // ==================== AJAX: WAREHOUSES LIST ====================
    public function warehousesList()
    {
        $warehouses = Warehouse::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'code', 'name', 'type', 'is_default']);
        
        return response()->json($warehouses);
    }

    // ==================== HELPER: EXPORT DATA ====================
    protected function exportData($data, $filename, $format = 'xlsx')
    {
        if (empty($data)) {
            return back()->with('error', 'No data to export');
        }

        $headers = array_keys($data[0]);

        if ($format === 'csv') {
            $callback = function() use ($data, $headers) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($file, $headers);
                foreach ($data as $row) {
                    fputcsv($file, array_values($row));
                }
                fclose($file);
            };

            return response()->stream($callback, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            ]);
        }

        if ($format === 'xlsx' || $format === 'excel') {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            $sheet->fromArray($headers, null, 'A1');
            
            $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
            $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '7C3AED']
                ],
            ]);
            
            $rowNum = 2;
            foreach ($data as $row) {
                $sheet->fromArray(array_values($row), null, "A{$rowNum}");
                $rowNum++;
            }
            
            foreach (range('A', $lastCol) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            $sheet->freezePane('A2');

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            
            $tempFile = tempnam(sys_get_temp_dir(), 'export_');
            $writer->save($tempFile);
            
            return response()->download($tempFile, "{$filename}.xlsx", [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
        }

        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.table', [
                'title' => $filename,
                'headers' => $headers,
                'data' => $data,
            ])->setPaper('a4', 'landscape');
            
            return $pdf->download("{$filename}.pdf");
        }

        return back()->with('error', 'Invalid export format');
    }
}