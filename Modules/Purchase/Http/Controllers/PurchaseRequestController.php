<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\Purchase\Models\PurchaseRequest;
use Modules\Purchase\Models\PurchaseRequestItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Modules\Core\Traits\DataTableTrait;

class PurchaseRequestController extends AdminController
{
    use DataTableTrait;
    
    // DataTable Configuration
    protected $model = PurchaseRequest::class;
    protected $with = ['requester:id,name'];
    protected $searchable = ['pr_number', 'department', 'purpose'];
    protected $sortable = ['id', 'pr_number', 'pr_date', 'priority', 'status', 'created_at'];
    protected $filterable = ['status', 'priority'];
    protected $exportTitle = 'Purchase Requests Export';

    /**
     * Get products with their variations for Select2 dropdown
     */
    protected function getProductsWithVariations()
    {
        if (!class_exists('\Modules\Inventory\Models\Product')) {
            return collect();
        }

        return \Modules\Inventory\Models\Product::with(['unit', 'variations' => function($q) {
            $q->where('is_active', true)->orderBy('variation_name');
        }])
        ->where('is_active', true)
        ->orderBy('name')
        ->get();
    }

    public function index()
    {
        $stats = [
            'total' => PurchaseRequest::count(),
            'draft' => PurchaseRequest::where('status', 'DRAFT')->count(),
            'pending' => PurchaseRequest::where('status', 'PENDING')->count(),
            'approved' => PurchaseRequest::where('status', 'APPROVED')->count(),
            'rejected' => PurchaseRequest::where('status', 'REJECTED')->count(),
        ];
        
        return view('purchase::purchase-request.index', compact('stats'));
    }

    /**
     * DataTable row mapping for list view
     */
    protected function mapRow($item)
    {
        return [
            'id' => $item->id,
            'pr_number' => $item->pr_number,
            'pr_date' => $item->pr_date->format('Y-m-d'),
            'department' => $item->department ?? '-',
            'items_count' => $item->items_count ?? $item->items()->count(),
            'priority' => $item->priority,
            'requester_name' => $item->requester->name ?? '-',
            'status' => $item->status,
            '_show_url' => route('admin.purchase.requests.show', $item->id),
            '_edit_url' => route('admin.purchase.requests.edit', $item->id),
        ];
    }

    /**
     * DataTable row mapping for export
     */
    protected function mapExportRow($item)
    {
        return [
            'ID' => $item->id,
            'PR Number' => $item->pr_number,
            'Date' => $item->pr_date->format('Y-m-d'),
            'Department' => $item->department ?? '',
            'Priority' => $item->priority,
            'Items Count' => $item->items_count ?? $item->items()->count(),
            'Requester' => $item->requester->name ?? '',
            'Status' => $item->status,
        ];
    }

    /**
     * DataTable endpoint
     */
    public function dataTable(Request $request)
    {
        return $this->handleData($request);
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
        $products = $this->getProductsWithVariations();
        
        return view('purchase::purchase-request.create', compact('prNumber', 'products'));
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
            'items.*.variation_id' => 'nullable|integer',
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
                    'variation_id' => !empty($item['variation_id']) ? $item['variation_id'] : null,
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
        $pr = PurchaseRequest::with(['items.product', 'items.variation', 'items.unit', 'requester', 'approver'])->findOrFail($id);
        return view('purchase::purchase-request.show', compact('pr'));
    }

    public function edit($id)
    {
        $pr = PurchaseRequest::with(['items.variation'])->findOrFail($id);
        
        if (!$pr->canEdit()) {
            return redirect()->route('admin.purchase.requests.show', $id)->with('error', 'Cannot edit this PR.');
        }
        
        $products = $this->getProductsWithVariations();
        
        return view('purchase::purchase-request.edit', compact('pr', 'products'));
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
            'items.*.variation_id' => 'nullable|integer',
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
                    'variation_id' => !empty($item['variation_id']) ? $item['variation_id'] : null,
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

    /**
     * Search products for Select2 dropdown
     */
    public function searchProducts(Request $request)
    {
        $term = $request->get('q', '');
        $results = [];

        $products = \DB::table('products')
            ->where('is_active', 1)
            ->where('can_be_purchased', 1)
            ->where(function($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('sku', 'like', "%{$term}%")
                  ->orWhere('barcode', 'like', "%{$term}%");
            })
            ->limit(20)
            ->get();

        foreach ($products as $product) {
            $unit = $product->unit_id ? \DB::table('units')->find($product->unit_id) : null;
            $image = \DB::table('product_images')->where('product_id', $product->id)->orderByDesc('is_primary')->first();
            
            // Add main product
            $results[] = [
                'id' => $product->id,
                'text' => ($product->sku ? $product->sku . ' - ' : '') . $product->name,
                'sku' => $product->sku,
                'variation_id' => null,
                'variation_name' => null,
                'unit_id' => $product->unit_id,
                'unit_name' => $unit ? ($unit->short_name ?? $unit->name) : '-',
                'price' => $product->purchase_price ?? $product->sale_price ?? 0,
                'tax_1_id' => $product->tax_1_id,
                'tax_2_id' => $product->tax_2_id,
                'image' => $image ? asset('storage/' . $image->image_path) : null,
            ];

            // Add variations if product has variants
            if ($product->has_variants) {
                $variations = \DB::table('product_variations')
                    ->where('product_id', $product->id)
                    ->where('is_active', 1)
                    ->get();

                foreach ($variations as $var) {
                    $results[] = [
                        'id' => $product->id,
                        'text' => ($product->sku ? $product->sku . ' - ' : '') . $product->name,
                        'sku' => $var->sku ?? $product->sku,
                        'variation_id' => $var->id,
                        'variation_name' => $var->variation_name ?? $var->sku,
                        'unit_id' => $product->unit_id,
                        'unit_name' => $unit ? ($unit->short_name ?? $unit->name) : '-',
                        'price' => $var->purchase_price ?? $product->purchase_price ?? $product->sale_price ?? 0,
                        'tax_1_id' => $product->tax_1_id,
                        'tax_2_id' => $product->tax_2_id,
                        'image' => $var->image_path ? asset('storage/' . $var->image_path) : ($image ? asset('storage/' . $image->image_path) : null),
                    ];
                }
            }
        }

        return response()->json($results);
    }
}
