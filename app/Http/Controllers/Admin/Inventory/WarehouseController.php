<?php

namespace App\Http\Controllers\Admin\Inventory;

use Illuminate\Http\Request;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\Rack;
use App\Models\Inventory\Unit;
use App\Models\Inventory\StockLevel;
use App\Models\Inventory\StockMovement;

class WarehouseController extends BaseController
{
    // ==================== WAREHOUSES INDEX ====================
    public function index()
    {
        $stats = [
            'total' => Warehouse::count(),
            'active' => Warehouse::where('is_active', true)->count(),
        ];
        
        return view('admin.inventory.warehouses.index', compact('stats'));
    }

    // ==================== WAREHOUSES DATA (DataTable) ====================
    public function data(Request $request)
    {
        $query = Warehouse::withCount('racks');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'city' => $item->city ?? '-',
                'state' => $item->state ?? '-',
                'type' => $item->type,
                'racks_count' => $item->racks_count,
                'contact_person' => $item->contact_person ?? '-',
                'phone' => $item->phone ?? '-',
                'is_default' => $item->is_default,
                'is_active' => $item->is_active,
                'status' => $item->is_active ? 'Active' : 'Inactive',
                '_edit_url' => route('admin.inventory.warehouses.edit', $item->id),
                '_delete_url' => route('admin.inventory.warehouses.destroy', $item->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    // ==================== WAREHOUSES CREATE ====================
    public function create()
    {
        return view('admin.inventory.warehouses.create');
    }

    // ==================== WAREHOUSES STORE ====================
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

    // ==================== WAREHOUSES EDIT ====================
    public function edit($id)
    {
        $warehouse = Warehouse::with('racks')->findOrFail($id);
        return view('admin.inventory.warehouses.edit', compact('warehouse'));
    }

    // ==================== WAREHOUSES UPDATE ====================
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

    // ==================== WAREHOUSES SET DEFAULT ====================
    public function setDefault($id)
    {
        Warehouse::where('is_default', true)->update(['is_default' => false]);
        Warehouse::where('id', $id)->update(['is_default' => true]);
        
        return response()->json(['success' => true, 'message' => 'Default warehouse updated']);
    }

    // ==================== WAREHOUSES DEACTIVATE ====================
    public function deactivate($id)
    {
        Warehouse::where('id', $id)->update(['is_active' => false]);
        return response()->json(['success' => true, 'message' => 'Warehouse deactivated']);
    }

    // ==================== WAREHOUSES DESTROY ====================
    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        
        if (StockMovement::where('warehouse_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete warehouse with stock movements'], 422);
        }
        
        if (Rack::where('warehouse_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete warehouse with racks. Delete racks first.'], 422);
        }
        
        $warehouse->delete();
        return response()->json(['success' => true, 'message' => 'Warehouse deleted successfully']);
    }

    // ==================== RACKS INDEX ====================
    public function racksIndex()
    {
        $stats = [
            'total' => Rack::count(),
            'active' => Rack::where('is_active', true)->count(),
        ];
        
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.inventory.racks.index', compact('stats', 'warehouses'));
    }

    // ==================== RACKS DATA (DataTable) ====================
    public function racksData(Request $request)
    {
        $query = Rack::with('warehouse');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('zone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'warehouse_name' => $item->warehouse?->name ?? '-',
                'zone' => $item->zone ?? '-',
                'aisle' => $item->aisle ?? '-',
                'level' => $item->level ?? '-',
                'full_location' => $item->full_location,
                'is_active' => $item->is_active,
                'status' => $item->is_active ? 'Active' : 'Inactive',
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
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
            'capacity' => 'nullable|numeric|min:0',
            'capacity_unit_id' => 'nullable|exists:units,id',
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
        $rack = Rack::findOrFail($id);
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
            'capacity' => 'nullable|numeric|min:0',
            'capacity_unit_id' => 'nullable|exists:units,id',
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
            return response()->json(['success' => false, 'message' => 'Cannot delete rack with stock'], 422);
        }
        
        $rack->delete();
        return response()->json(['success' => true, 'message' => 'Rack deleted successfully']);
    }

    // ==================== RACKS BY WAREHOUSE (AJAX) ====================
    public function racksByWarehouse($warehouseId)
    {
        $racks = Rack::where('warehouse_id', $warehouseId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get(['id', 'code', 'name', 'zone']);
        
        return response()->json($racks);
    }
}