<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\Purchase\Models\PurchaseRequest;
use Modules\Purchase\Models\PurchaseRequestItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class PurchaseRequestController extends AdminController
{
    public function index()
    {
        $stats = [
            'total' => PurchaseRequest::count(),
            'draft' => PurchaseRequest::where('status', 'DRAFT')->count(),
            'pending' => PurchaseRequest::where('status', 'PENDING')->count(),
            'approved' => PurchaseRequest::where('status', 'APPROVED')->count(),
            'rejected' => PurchaseRequest::where('status', 'REJECTED')->count(),
        ];
        
        return $this->moduleView('purchase::purchase-request.index', compact('stats'));
    }

    public function dataTable(Request $request): JsonResponse
    {
        $query = PurchaseRequest::with(['requester:id,name'])->withCount('items');

        // Export selected IDs
        if ($request->has('ids') && $request->has('export')) {
            $ids = array_filter(explode(',', $request->input('ids')));
            if (!empty($ids)) $query->whereIn('id', $ids);
            return $this->export($query, $request->input('export'));
        }

        // Search
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('pr_number', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        // Filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($priority = $request->input('priority')) {
            $query->where('priority', $priority);
        }

        // Sort
        $sortCol = $request->input('sort', 'id');
        $sortDir = $request->input('dir', 'desc');
        $query->orderBy($sortCol, $sortDir);

        // Export all
        if ($request->has('export')) {
            return $this->export($query, $request->input('export'));
        }

        // Pagination
        $data = $query->paginate($request->input('per_page', 15));

        // Map data with URLs for dt-table
        $items = collect($data->items())->map(function($item) {
            return [
                'id' => $item->id,
                'pr_number' => $item->pr_number,
                'pr_date' => $item->pr_date->format('Y-m-d'),
                'department' => $item->department ?? '-',
                'items_count' => $item->items_count,
                'priority' => $item->priority,
                'requester_name' => $item->requester->name ?? '-',
                'status' => $item->status,
                '_show_url' => route('admin.purchase.requests.show', $item->id),
                '_edit_url' => route('admin.purchase.requests.edit', $item->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    protected function export($query, $format = 'csv')
    {
        $data = $query->get();
        $filename = 'purchase_requests_' . date('Y-m-d') . '.' . $format;
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($file, ['ID', 'PR Number', 'Date', 'Department', 'Priority', 'Items', 'Status', 'Requester']);
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->id, 
                    $row->pr_number, 
                    $row->pr_date->format('Y-m-d'), 
                    $row->department, 
                    $row->priority, 
                    $row->items_count ?? $row->items()->count(), 
                    $row->status,
                    $row->requester->name ?? '-'
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function bulkDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }
        
        // Only delete DRAFT, REJECTED, CANCELLED
        $deleted = PurchaseRequest::whereIn('id', $ids)
            ->whereIn('status', ['DRAFT', 'REJECTED', 'CANCELLED'])
            ->delete();
            
        return response()->json(['success' => true, 'message' => "{$deleted} purchase request(s) deleted!"]);
    }

    public function create()
    {
        $prNumber = PurchaseRequest::generateNumber();
        $products = collect();
        
        if (class_exists('\Modules\Inventory\Models\Product')) {
            $products = \Modules\Inventory\Models\Product::with('unit')
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'sku', 'unit_id', 'sale_price', 'mrp']);
        }
        
        return $this->moduleView('purchase::purchase-request.create', compact('prNumber', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pr_date' => 'required|date|before_or_equal:today',
            'required_date' => 'nullable|date|after_or_equal:pr_date',
            'department' => 'nullable|string|max:100',
            'priority' => 'required|in:LOW,NORMAL,HIGH,URGENT',
            'purpose' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.qty' => 'required|numeric|min:0.001|max:99999999',
            'items.*.unit_id' => 'nullable|integer',
            'items.*.estimated_price' => 'nullable|numeric|min:0|max:99999999',
        ], [
            'pr_date.required' => 'PR Date is required.',
            'pr_date.before_or_equal' => 'PR Date cannot be in the future.',
            'required_date.after_or_equal' => 'Required date must be on or after PR date.',
            'items.required' => 'At least one item is required.',
            'items.min' => 'At least one item is required.',
            'items.*.product_id.required' => 'Product is required for all items.',
            'items.*.qty.required' => 'Quantity is required for all items.',
            'items.*.qty.min' => 'Quantity must be greater than 0.',
        ]);

        DB::beginTransaction();
        try {
            $pr = PurchaseRequest::create([
                'pr_number' => PurchaseRequest::generateNumber(),
                'pr_date' => $validated['pr_date'],
                'required_date' => $validated['required_date'] ?? null,
                'department' => $validated['department'] ?? null,
                'priority' => $validated['priority'],
                'purpose' => $validated['purpose'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'DRAFT',
                'requested_by' => auth()->id(),
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                PurchaseRequestItem::create([
                    'purchase_request_id' => $pr->id,
                    'product_id' => $item['product_id'],
                    'unit_id' => $item['unit_id'] ?? null,
                    'qty' => $item['qty'],
                    'estimated_price' => $item['estimated_price'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.purchase.requests.show', $pr->id)->with('success', 'Purchase Request created!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $pr = PurchaseRequest::with(['items.product', 'items.unit', 'requester', 'approver'])->findOrFail($id);
        return $this->moduleView('purchase::purchase-request.show', compact('pr'));
    }

    public function edit($id)
    {
        $pr = PurchaseRequest::with(['items'])->findOrFail($id);
        
        if (!$pr->canEdit()) {
            return redirect()->route('admin.purchase.requests.show', $id)->with('error', 'Cannot edit this PR.');
        }
        
        $products = collect();
        if (class_exists('\Modules\Inventory\Models\Product')) {
            $products = \Modules\Inventory\Models\Product::with('unit')
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'sku', 'unit_id', 'sale_price', 'mrp']);
        }
        
        return $this->moduleView('purchase::purchase-request.edit', compact('pr', 'products'));
    }

    public function update(Request $request, $id)
    {
        $pr = PurchaseRequest::findOrFail($id);
        
        if (!$pr->canEdit()) {
            return redirect()->route('admin.purchase.requests.show', $id)->with('error', 'Cannot edit this PR.');
        }

        $validated = $request->validate([
            'required_date' => 'nullable|date',
            'department' => 'nullable|string|max:100',
            'priority' => 'required|in:LOW,NORMAL,HIGH,URGENT',
            'purpose' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.qty' => 'required|numeric|min:0.001',
            'items.*.unit_id' => 'nullable|integer',
            'items.*.estimated_price' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $pr->update([
                'required_date' => $validated['required_date'] ?? null,
                'department' => $validated['department'] ?? null,
                'priority' => $validated['priority'],
                'purpose' => $validated['purpose'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'DRAFT',
                'rejection_reason' => null,
            ]);

            $pr->items()->delete();
            foreach ($validated['items'] as $item) {
                PurchaseRequestItem::create([
                    'purchase_request_id' => $pr->id,
                    'product_id' => $item['product_id'],
                    'unit_id' => $item['unit_id'] ?? null,
                    'qty' => $item['qty'],
                    'estimated_price' => $item['estimated_price'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.purchase.requests.show', $pr->id)->with('success', 'Purchase Request updated!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $pr = PurchaseRequest::findOrFail($id);
        
        if (!in_array($pr->status, ['DRAFT', 'REJECTED', 'CANCELLED'])) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete this PR.'], 400);
            }
            return back()->with('error', 'Cannot delete this PR.');
        }
        
        $pr->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'PR deleted!']);
        }
        return redirect()->route('admin.purchase.requests.index')->with('success', 'Purchase Request deleted!');
    }

    public function submit($id)
    {
        $pr = PurchaseRequest::findOrFail($id);
        
        if (!$pr->canSubmit()) {
            return back()->with('error', 'Cannot submit. Please add items first.');
        }
        
        $pr->update(['status' => 'PENDING']);
        return redirect()->route('admin.purchase.requests.show', $id)->with('success', 'PR submitted for approval!');
    }

    public function approve($id)
    {
        $pr = PurchaseRequest::findOrFail($id);
        
        if (!$pr->canApprove()) {
            return back()->with('error', 'Cannot approve this PR.');
        }
        
        $pr->update([
            'status' => 'APPROVED',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        
        return redirect()->route('admin.purchase.requests.show', $id)->with('success', 'Purchase Request approved!');
    }

    public function reject(Request $request, $id)
    {
        $pr = PurchaseRequest::findOrFail($id);
        
        if (!$pr->canReject()) {
            return back()->with('error', 'Cannot reject this PR.');
        }
        
        $validated = $request->validate(['rejection_reason' => 'required|string|max:500']);
        
        $pr->update([
            'status' => 'REJECTED',
            'rejection_reason' => $validated['rejection_reason'],
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        
        return redirect()->route('admin.purchase.requests.show', $id)->with('success', 'Purchase Request rejected.');
    }

    public function cancel($id)
    {
        $pr = PurchaseRequest::findOrFail($id);
        
        if (!$pr->canCancel()) {
            return back()->with('error', 'Cannot cancel this PR.');
        }
        
        $pr->update(['status' => 'CANCELLED']);
        return redirect()->route('admin.purchase.requests.show', $id)->with('success', 'Purchase Request cancelled.');
    }
}
