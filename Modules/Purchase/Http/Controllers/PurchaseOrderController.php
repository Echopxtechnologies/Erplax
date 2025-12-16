<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\Purchase\Models\PurchaseOrder;
use Modules\Purchase\Models\PurchaseOrderItem;
use Modules\Purchase\Models\PurchaseRequest;
use Modules\Purchase\Models\Vendor;
use Modules\Purchase\Models\PurchaseSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class PurchaseOrderController extends AdminController
{
    public function index()
    {
        $stats = [
            'total' => PurchaseOrder::count(),
            'draft' => PurchaseOrder::where('status', 'DRAFT')->count(),
            'sent' => PurchaseOrder::where('status', 'SENT')->count(),
            'confirmed' => PurchaseOrder::where('status', 'CONFIRMED')->count(),
            'received' => PurchaseOrder::whereIn('status', ['PARTIALLY_RECEIVED', 'RECEIVED'])->count(),
        ];
        
        return $this->moduleView('purchase::purchase-order.index', compact('stats'));
    }

    public function dataTable(Request $request): JsonResponse
    {
        $query = PurchaseOrder::with(['vendor:id,name', 'purchaseRequest:id,pr_number'])->withCount('items');

        // Export selected IDs
        if ($request->has('ids') && $request->has('export')) {
            $ids = array_filter(explode(',', $request->input('ids')));
            if (!empty($ids)) $query->whereIn('id', $ids);
            return $this->export($query, $request->input('export'));
        }

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                  ->orWhereHas('vendor', fn($v) => $v->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $sortCol = $request->input('sort', 'id');
        $sortDir = $request->input('dir', 'desc');
        $query->orderBy($sortCol, $sortDir);

        // Export all
        if ($request->has('export')) {
            return $this->export($query, $request->input('export'));
        }

        // Pagination
        $data = $query->paginate($request->input('per_page', 15));

        $items = collect($data->items())->map(function($item) {
            return [
                'id' => $item->id,
                'po_number' => $item->po_number,
                'po_date' => $item->po_date->format('Y-m-d'),
                'vendor_name' => $item->vendor->name ?? '-',
                'pr_number' => $item->purchaseRequest?->pr_number ?? '-',
                'items_count' => $item->items_count,
                'total_amount' => number_format($item->total_amount, 2),
                'status' => $item->status,
                '_show_url' => route('admin.purchase.orders.show', $item->id),
                '_edit_url' => route('admin.purchase.orders.edit', $item->id),
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
        $filename = 'purchase_orders_' . date('Y-m-d') . '.' . $format;
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($file, ['ID', 'PO Number', 'Date', 'Vendor', 'PR Number', 'Items', 'Total Amount', 'Status']);
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->id, 
                    $row->po_number, 
                    $row->po_date->format('Y-m-d'), 
                    $row->vendor->name ?? '-', 
                    $row->purchaseRequest?->pr_number ?? '-', 
                    $row->items_count ?? $row->items()->count(), 
                    $row->total_amount,
                    $row->status
                ]);
            }
            fclose($file);
        };

        return \Illuminate\Support\Facades\Response::stream($callback, 200, $headers);
    }

    public function bulkDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }
        
        // Only delete DRAFT or CANCELLED
        $deleted = PurchaseOrder::whereIn('id', $ids)
            ->whereIn('status', ['DRAFT', 'CANCELLED'])
            ->delete();
            
        return response()->json(['success' => true, 'message' => "{$deleted} purchase order(s) deleted!"]);
    }

    public function create(Request $request)
    {
        $poNumber = PurchaseOrder::generateNumber();
        $vendors = Vendor::where('status', 'ACTIVE')->orderBy('name')->get(['id', 'name', 'vendor_code']);
        $products = collect();
        $taxes = [];
        $pr = null;
        $defaultTax = PurchaseSetting::getValue('default_tax_percent', 18);
        $defaultTerms = PurchaseSetting::getValue('po_terms', '');
        
        if (class_exists('\Modules\Inventory\Models\Product')) {
            $products = \Modules\Inventory\Models\Product::with('unit')
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'sku', 'unit_id', 'sale_price', 'mrp', 'purchase_price']);
        }
        
        // Get taxes from taxes table
        if (\Schema::hasTable('taxes')) {
            $taxes = DB::table('taxes')->where('is_active', true)->orderBy('name')->get(['id', 'name', 'rate']);
        }

        // If converting from PR
        if ($prId = $request->query('pr_id')) {
            $pr = PurchaseRequest::with(['items.product.unit'])->find($prId);
        }
        
        return $this->moduleView('purchase::purchase-order.create', compact('poNumber', 'vendors', 'products', 'taxes', 'pr', 'defaultTax', 'defaultTerms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'po_date' => 'required|date|before_or_equal:today',
            'expected_date' => 'nullable|date|after_or_equal:po_date',
            'purchase_request_id' => 'nullable|exists:purchase_requests,id',
            'shipping_address' => 'nullable|string|max:500',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_state' => 'nullable|string|max:100',
            'shipping_pincode' => 'nullable|string|max:10',
            'payment_terms' => 'nullable|string|max:100',
            'shipping_charge' => 'nullable|numeric|min:0|max:99999999',
            'discount_amount' => 'nullable|numeric|min:0|max:99999999',
            'terms_conditions' => 'nullable|string|max:2000',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.qty' => 'required|numeric|min:0.001|max:99999999',
            'items.*.rate' => 'required|numeric|min:0|max:99999999',
            'items.*.unit_id' => 'nullable|integer',
            'items.*.tax_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.pr_item_id' => 'nullable|integer',
        ], [
            'vendor_id.required' => 'Please select a vendor.',
            'po_date.required' => 'PO Date is required.',
            'po_date.before_or_equal' => 'PO Date cannot be in the future.',
            'expected_date.after_or_equal' => 'Expected date must be on or after PO date.',
            'items.required' => 'At least one item is required.',
            'items.min' => 'At least one item is required.',
            'items.*.product_id.required' => 'Product is required for all items.',
            'items.*.qty.required' => 'Quantity is required for all items.',
            'items.*.qty.min' => 'Quantity must be greater than 0.',
            'items.*.rate.required' => 'Rate is required for all items.',
            'items.*.rate.min' => 'Rate cannot be negative.',
        ]);

        DB::beginTransaction();
        try {
            $po = PurchaseOrder::create([
                'po_number' => PurchaseOrder::generateNumber(),
                'vendor_id' => $validated['vendor_id'],
                'po_date' => $validated['po_date'],
                'expected_date' => $validated['expected_date'] ?? null,
                'purchase_request_id' => $validated['purchase_request_id'] ?? null,
                'shipping_address' => $validated['shipping_address'] ?? null,
                'shipping_city' => $validated['shipping_city'] ?? null,
                'shipping_state' => $validated['shipping_state'] ?? null,
                'shipping_pincode' => $validated['shipping_pincode'] ?? null,
                'payment_terms' => $validated['payment_terms'] ?? null,
                'shipping_charge' => $validated['shipping_charge'] ?? 0,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'terms_conditions' => $validated['terms_conditions'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'DRAFT',
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $poItem = PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'purchase_request_item_id' => $item['pr_item_id'] ?? null,
                    'product_id' => $item['product_id'],
                    'unit_id' => $item['unit_id'] ?? null,
                    'qty' => $item['qty'],
                    'rate' => $item['rate'],
                    'tax_percent' => $item['tax_percent'] ?? 0,
                    'discount_percent' => $item['discount_percent'] ?? 0,
                ]);
                $poItem->calculateTotal();
            }

            $po->calculateTotals();

            // If from PR, mark PR as converted
            if (!empty($validated['purchase_request_id'])) {
                PurchaseRequest::where('id', $validated['purchase_request_id'])
                    ->update(['status' => 'CONVERTED']);
            }

            DB::commit();
            return redirect()->route('admin.purchase.orders.show', $po->id)->with('success', 'Purchase Order created!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $po = PurchaseOrder::with(['items.product', 'items.unit', 'vendor', 'purchaseRequest', 'creator'])->findOrFail($id);
        return $this->moduleView('purchase::purchase-order.show', compact('po'));
    }

    public function edit($id)
    {
        $po = PurchaseOrder::with(['items'])->findOrFail($id);
        
        if (!$po->canEdit()) {
            return redirect()->route('admin.purchase.orders.show', $id)->with('error', 'Cannot edit this PO.');
        }
        
        $vendors = Vendor::where('status', 'ACTIVE')->orderBy('name')->get(['id', 'name', 'vendor_code']);
        $products = collect();
        $taxes = [];
        
        if (class_exists('\Modules\Inventory\Models\Product')) {
            $products = \Modules\Inventory\Models\Product::with('unit')
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'sku', 'unit_id', 'sale_price', 'mrp', 'purchase_price']);
        }
        
        // Get taxes from taxes table
        if (\Schema::hasTable('taxes')) {
            $taxes = DB::table('taxes')->where('is_active', true)->orderBy('name')->get(['id', 'name', 'rate']);
        }
        
        return $this->moduleView('purchase::purchase-order.edit', compact('po', 'vendors', 'products', 'taxes'));
    }

    public function update(Request $request, $id)
    {
        $po = PurchaseOrder::findOrFail($id);
        
        if (!$po->canEdit()) {
            return redirect()->route('admin.purchase.orders.show', $id)->with('error', 'Cannot edit this PO.');
        }

        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'expected_date' => 'nullable|date',
            'shipping_address' => 'nullable|string',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_state' => 'nullable|string|max:100',
            'shipping_pincode' => 'nullable|string|max:10',
            'payment_terms' => 'nullable|string|max:100',
            'shipping_charge' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'terms_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.qty' => 'required|numeric|min:0.001',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.unit_id' => 'nullable|integer',
            'items.*.tax_percent' => 'nullable|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            $po->update([
                'vendor_id' => $validated['vendor_id'],
                'expected_date' => $validated['expected_date'] ?? null,
                'shipping_address' => $validated['shipping_address'] ?? null,
                'shipping_city' => $validated['shipping_city'] ?? null,
                'shipping_state' => $validated['shipping_state'] ?? null,
                'shipping_pincode' => $validated['shipping_pincode'] ?? null,
                'payment_terms' => $validated['payment_terms'] ?? null,
                'shipping_charge' => $validated['shipping_charge'] ?? 0,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'terms_conditions' => $validated['terms_conditions'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $po->items()->delete();
            foreach ($validated['items'] as $item) {
                $poItem = PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $item['product_id'],
                    'unit_id' => $item['unit_id'] ?? null,
                    'qty' => $item['qty'],
                    'rate' => $item['rate'],
                    'tax_percent' => $item['tax_percent'] ?? 0,
                    'discount_percent' => $item['discount_percent'] ?? 0,
                ]);
                $poItem->calculateTotal();
            }

            $po->calculateTotals();

            DB::commit();
            return redirect()->route('admin.purchase.orders.show', $po->id)->with('success', 'Purchase Order updated!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        
        if (!in_array($po->status, ['DRAFT', 'CANCELLED'])) {
            return back()->with('error', 'Cannot delete this PO.');
        }
        
        $po->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'PO deleted!']);
        }
        return redirect()->route('admin.purchase.orders.index')->with('success', 'Purchase Order deleted!');
    }

    public function send($id)
    {
        $po = PurchaseOrder::with(['vendor', 'items.product'])->findOrFail($id);
        
        if (!$po->canSend()) {
            return back()->with('error', 'Cannot send this PO.');
        }
        
        // Send email if vendor has email
        if ($po->vendor->email) {
            try {
                // Generate PDF
                // Mail::to($po->vendor->email)->send(new \Modules\Purchase\Mail\PurchaseOrderMail($po));
            } catch (\Exception $e) {
                // Log error but continue
            }
        }
        
        $po->update([
            'status' => 'SENT',
            'sent_at' => now(),
        ]);
        
        return redirect()->route('admin.purchase.orders.show', $id)->with('success', 'PO sent to vendor!');
    }

    public function confirm($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        
        if (!$po->canConfirm()) {
            return back()->with('error', 'Cannot confirm this PO.');
        }
        
        $po->update([
            'status' => 'CONFIRMED',
            'confirmed_at' => now(),
        ]);
        
        return redirect()->route('admin.purchase.orders.show', $id)->with('success', 'PO confirmed by vendor!');
    }

    public function cancel($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        
        if (!$po->canCancel()) {
            return back()->with('error', 'Cannot cancel this PO.');
        }
        
        $po->update(['status' => 'CANCELLED']);
        
        return redirect()->route('admin.purchase.orders.show', $id)->with('success', 'PO cancelled.');
    }

    public function pdf($id)
    {
        $po = PurchaseOrder::with(['items.product', 'items.unit', 'vendor', 'purchaseRequest', 'creator'])->findOrFail($id);
        
        // Get company details from Option model
        $companyName = \App\Models\Option::companyName();
        $companyLogo = \App\Models\Option::companyLogo();
        $companyAddress = \App\Models\Option::companyAddress();
        $companyPhone = \App\Models\Option::companyPhone();
        $companyEmail = \App\Models\Option::companyEmail();
        $companyGst = \App\Models\Option::get('company_gst', '');
        
        // PDF Settings
        $pdfSettings = [
            'primary_color' => PurchaseSetting::getValue('pdf_primary_color', '#1e40af'),
            'secondary_color' => PurchaseSetting::getValue('pdf_secondary_color', '#f3f4f6'),
            'show_logo' => PurchaseSetting::getValue('pdf_show_logo', '1'),
            'show_gst' => PurchaseSetting::getValue('pdf_show_gst', '1'),
            'show_terms' => PurchaseSetting::getValue('pdf_show_terms', '1'),
            'show_signature' => PurchaseSetting::getValue('pdf_show_signature', '1'),
            'show_notes' => PurchaseSetting::getValue('pdf_show_notes', '1'),
            'compact_mode' => PurchaseSetting::getValue('pdf_compact_mode', '1'),
            'font_size' => PurchaseSetting::getValue('pdf_font_size', '9'),
        ];
        
        // Amount in words
        $amountInWords = $this->convertToWords($po->total_amount);
        
        $pdf = Pdf::loadView('purchase::purchase-order.pdf', compact(
            'po', 
            'companyName',
            'companyLogo',
            'companyAddress',
            'companyPhone',
            'companyEmail',
            'companyGst',
            'amountInWords',
            'pdfSettings'
        ));
        $pdf->setPaper('a4', 'portrait');
        
        // Stream or Download based on request
        if (request()->has('download')) {
            return $pdf->download("PO-{$po->po_number}.pdf");
        }
        
        return $pdf->stream("PO-{$po->po_number}.pdf");
    }
    
    /**
     * Convert number to words (Indian format)
     */
    protected function convertToWords($number)
    {
        $number = floor($number);
        
        $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 
                 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 
                 'Eighteen', 'Nineteen'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        
        if ($number == 0) return 'Zero Rupees Only';
        
        $words = '';
        
        // Crores
        if ($number >= 10000000) {
            $words .= $this->convertToWords(floor($number / 10000000)) . ' Crore ';
            $number %= 10000000;
        }
        
        // Lakhs
        if ($number >= 100000) {
            $words .= $this->convertToWords(floor($number / 100000)) . ' Lakh ';
            $number %= 100000;
        }
        
        // Thousands
        if ($number >= 1000) {
            $words .= $this->convertToWords(floor($number / 1000)) . ' Thousand ';
            $number %= 1000;
        }
        
        // Hundreds
        if ($number >= 100) {
            $words .= $ones[floor($number / 100)] . ' Hundred ';
            $number %= 100;
        }
        
        // Tens and ones
        if ($number > 0) {
            if ($number < 20) {
                $words .= $ones[$number];
            } else {
                $words .= $tens[floor($number / 10)];
                if ($number % 10 > 0) {
                    $words .= ' ' . $ones[$number % 10];
                }
            }
        }
        
        return 'Rupees ' . trim($words) . ' Only';
    }

    public function duplicate($id)
    {
        $po = PurchaseOrder::with(['items'])->findOrFail($id);
        
        DB::beginTransaction();
        try {
            $newPo = $po->replicate();
            $newPo->po_number = PurchaseOrder::generateNumber();
            $newPo->status = 'DRAFT';
            $newPo->sent_at = null;
            $newPo->confirmed_at = null;
            $newPo->po_date = now();
            $newPo->save();
            
            foreach ($po->items as $item) {
                $newItem = $item->replicate();
                $newItem->purchase_order_id = $newPo->id;
                $newItem->received_qty = 0;
                $newItem->save();
            }
            
            DB::commit();
            return redirect()->route('admin.purchase.orders.edit', $newPo->id)->with('success', 'PO duplicated!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
