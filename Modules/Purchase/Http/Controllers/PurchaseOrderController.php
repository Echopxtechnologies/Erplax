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
use Modules\Core\Traits\DataTableTrait;

class PurchaseOrderController extends AdminController
{
    use DataTableTrait;
    
    // DataTable Configuration
    protected $model = PurchaseOrder::class;
    protected $with = ['vendor:id,name', 'purchaseRequest:id,pr_number'];
    protected $searchable = ['po_number', 'vendor.name'];
    protected $sortable = ['id', 'po_number', 'po_date', 'grand_total', 'status', 'created_at'];
    protected $filterable = ['status', 'vendor_id'];
    protected $exportTitle = 'Purchase Orders Export';

    public function index()
    {
        $stats = [
            'total' => PurchaseOrder::count(),
            'draft' => PurchaseOrder::where('status', 'DRAFT')->count(),
            'sent' => PurchaseOrder::where('status', 'SENT')->count(),
            'confirmed' => PurchaseOrder::where('status', 'CONFIRMED')->count(),
            'received' => PurchaseOrder::whereIn('status', ['PARTIALLY_RECEIVED', 'RECEIVED'])->count(),
        ];
        
        return view('purchase::purchase-order.index', compact('stats'));
    }

    /**
     * DataTable row mapping for list view
     */
    protected function mapRow($item)
    {
        return [
            'id' => $item->id,
            'po_number' => $item->po_number,
            'po_date' => $item->po_date->format('Y-m-d'),
            'vendor_name' => $item->vendor->name ?? '-',
            'pr_number' => $item->purchaseRequest?->pr_number ?? '-',
            'items_count' => $item->items_count ?? $item->items()->count(),
            'grand_total' => number_format($item->grand_total, 2),
            'status' => $item->status,
            '_show_url' => route('admin.purchase.orders.show', $item->id),
            '_edit_url' => route('admin.purchase.orders.edit', $item->id),
        ];
    }

    /**
     * DataTable row mapping for export
     */
    protected function mapExportRow($item)
    {
        return [
            'ID' => $item->id,
            'PO Number' => $item->po_number,
            'Date' => $item->po_date->format('Y-m-d'),
            'Vendor' => $item->vendor->name ?? '',
            'PR Number' => $item->purchaseRequest?->pr_number ?? '',
            'Subtotal' => $item->subtotal,
            'Tax Amount' => $item->tax_amount,
            'Grand Total' => $item->grand_total,
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

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No orders selected!'], 400);

        $deleted = PurchaseOrder::whereIn('id', $ids)
            ->whereIn('status', ['DRAFT', 'CANCELLED'])
            ->delete();
            
        return response()->json(['success' => true, 'message' => "{$deleted} purchase order(s) deleted!"]);
    }

    private function getTaxesLookup()
    {
        $taxes = [];
        if (\Schema::hasTable('taxes')) {
            $taxRecords = DB::table('taxes')->where('is_active', true)->get(['id', 'name', 'rate']);
            foreach ($taxRecords as $t) {
                $taxes[$t->id] = ['id' => $t->id, 'name' => $t->name, 'rate' => $t->rate];
            }
        }
        return $taxes;
    }

    public function create(Request $request)
    {
        $poNumber = PurchaseOrder::generateNumber();
        $vendors = Vendor::where('status', 'ACTIVE')->orderBy('name')->get(['id', 'name', 'vendor_code']);
        $products = collect();
        $taxes = [];
        $pr = null;
        $defaultTerms = PurchaseSetting::getValue('po_terms', '');
        
        $companyAddress = ['address' => '', 'city' => '', 'state' => '', 'pincode' => ''];
        if (class_exists('\App\Models\Option')) {
            $companyAddress = [
                'address' => \App\Models\Option::get('company_address', ''),
                'city' => \App\Models\Option::get('company_city', ''),
                'state' => \App\Models\Option::get('company_state', ''),
                'pincode' => \App\Models\Option::get('company_zip', ''),
            ];
        }
        
        // Load products with variations
        if (class_exists('\Modules\Inventory\Models\Product')) {
            $products = \Modules\Inventory\Models\Product::with(['unit', 'variations' => function($q) {
                    $q->where('is_active', true);
                }])
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'sku', 'unit_id', 'sale_price', 'mrp', 'purchase_price', 'tax_1_id', 'tax_2_id', 'has_variants']);
        }
        
        $taxes = $this->getTaxesLookup();

        if ($prId = $request->query('pr_id')) {
            $pr = PurchaseRequest::with(['items.product.unit', 'items.variation'])->find($prId);
        }
        
        return view('purchase::purchase-order.create', compact('poNumber', 'vendors', 'products', 'taxes', 'pr', 'defaultTerms', 'companyAddress'));
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
            'items.*.tax_1_id' => 'nullable|integer',
            'items.*.tax_2_id' => 'nullable|integer',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.variation_id' => 'nullable|integer',
            'items.*.pr_item_id' => 'nullable|integer',
        ]);

        $taxesLookup = $this->getTaxesLookup();

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
                $tax1 = !empty($item['tax_1_id']) && isset($taxesLookup[$item['tax_1_id']]) ? $taxesLookup[$item['tax_1_id']] : null;
                $tax2 = !empty($item['tax_2_id']) && isset($taxesLookup[$item['tax_2_id']]) ? $taxesLookup[$item['tax_2_id']] : null;
                
                $qty = $item['qty'];
                $rate = $item['rate'];
                $discountPercent = $item['discount_percent'] ?? 0;
                
                $lineTotal = $qty * $rate;
                $discountAmount = $lineTotal * ($discountPercent / 100);
                $taxableAmount = $lineTotal - $discountAmount;
                
                $tax1Rate = $tax1 ? $tax1['rate'] : 0;
                $tax2Rate = $tax2 ? $tax2['rate'] : 0;
                $tax1Amount = $taxableAmount * ($tax1Rate / 100);
                $tax2Amount = $taxableAmount * ($tax2Rate / 100);
                $taxAmount = $tax1Amount + $tax2Amount;
                $total = $taxableAmount + $taxAmount;
                
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'purchase_request_item_id' => $item['pr_item_id'] ?? null,
                    'product_id' => $item['product_id'],
                    'variation_id' => !empty($item['variation_id']) ? $item['variation_id'] : null,
                    'unit_id' => $item['unit_id'] ?? null,
                    'qty' => $qty,
                    'rate' => $rate,
                    'tax_percent' => $tax1Rate + $tax2Rate,
                    'discount_percent' => $discountPercent,
                    'tax_1_id' => $tax1 ? $tax1['id'] : null,
                    'tax_1_name' => $tax1 ? $tax1['name'] : null,
                    'tax_1_rate' => $tax1Rate,
                    'tax_1_amount' => $tax1Amount,
                    'tax_2_id' => $tax2 ? $tax2['id'] : null,
                    'tax_2_name' => $tax2 ? $tax2['name'] : null,
                    'tax_2_rate' => $tax2Rate,
                    'tax_2_amount' => $tax2Amount,
                    'tax_amount' => $taxAmount,
                    'total' => $total,
                ]);
            }

            $po->calculateTotals();

            if (!empty($validated['purchase_request_id'])) {
                PurchaseRequest::where('id', $validated['purchase_request_id'])->update(['status' => 'CONVERTED']);
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
        $po = PurchaseOrder::with(['items.product', 'items.variation', 'items.unit', 'vendor', 'purchaseRequest', 'creator'])->findOrFail($id);
        return view('purchase::purchase-order.show', compact('po'));
    }

    public function edit($id)
    {
        $po = PurchaseOrder::with(['items.product', 'items.variation', 'items.unit'])->findOrFail($id);
        
        if (!$po->canEdit()) {
            return redirect()->route('admin.purchase.orders.show', $id)->with('error', 'Cannot edit this PO.');
        }
        
        $vendors = Vendor::where('status', 'ACTIVE')->orderBy('name')->get(['id', 'name', 'vendor_code']);
        $products = collect();
        
        // Load products with variations
        if (class_exists('\Modules\Inventory\Models\Product')) {
            $products = \Modules\Inventory\Models\Product::with(['unit', 'variations' => function($q) {
                    $q->where('is_active', true);
                }])
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'sku', 'unit_id', 'sale_price', 'mrp', 'purchase_price', 'tax_1_id', 'tax_2_id', 'has_variants']);
        }
        
        $taxes = $this->getTaxesLookup();
        
        return view('purchase::purchase-order.edit', compact('po', 'vendors', 'products', 'taxes'));
    }

    public function update(Request $request, $id)
    {
        $po = PurchaseOrder::findOrFail($id);
        
        if (!$po->canEdit()) {
            return redirect()->route('admin.purchase.orders.show', $id)->with('error', 'Cannot edit this PO.');
        }

        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'po_date' => 'required|date|before_or_equal:today',
            'expected_date' => 'nullable|date|after_or_equal:po_date',
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
            'items.*.tax_1_id' => 'nullable|integer',
            'items.*.tax_2_id' => 'nullable|integer',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.variation_id' => 'nullable|integer',
        ]);

        $taxesLookup = $this->getTaxesLookup();

        DB::beginTransaction();
        try {
            $po->update([
                'vendor_id' => $validated['vendor_id'],
                'po_date' => $validated['po_date'],
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
                $tax1 = !empty($item['tax_1_id']) && isset($taxesLookup[$item['tax_1_id']]) ? $taxesLookup[$item['tax_1_id']] : null;
                $tax2 = !empty($item['tax_2_id']) && isset($taxesLookup[$item['tax_2_id']]) ? $taxesLookup[$item['tax_2_id']] : null;
                
                $qty = $item['qty'];
                $rate = $item['rate'];
                $discountPercent = $item['discount_percent'] ?? 0;
                
                $lineTotal = $qty * $rate;
                $discountAmount = $lineTotal * ($discountPercent / 100);
                $taxableAmount = $lineTotal - $discountAmount;
                
                $tax1Rate = $tax1 ? $tax1['rate'] : 0;
                $tax2Rate = $tax2 ? $tax2['rate'] : 0;
                $tax1Amount = $taxableAmount * ($tax1Rate / 100);
                $tax2Amount = $taxableAmount * ($tax2Rate / 100);
                $taxAmount = $tax1Amount + $tax2Amount;
                $total = $taxableAmount + $taxAmount;
                
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $item['product_id'],
                    'variation_id' => !empty($item['variation_id']) ? $item['variation_id'] : null,
                    'unit_id' => $item['unit_id'] ?? null,
                    'qty' => $qty,
                    'rate' => $rate,
                    'tax_percent' => $tax1Rate + $tax2Rate,
                    'discount_percent' => $discountPercent,
                    'tax_1_id' => $tax1 ? $tax1['id'] : null,
                    'tax_1_name' => $tax1 ? $tax1['name'] : null,
                    'tax_1_rate' => $tax1Rate,
                    'tax_1_amount' => $tax1Amount,
                    'tax_2_id' => $tax2 ? $tax2['id'] : null,
                    'tax_2_name' => $tax2 ? $tax2['name'] : null,
                    'tax_2_rate' => $tax2Rate,
                    'tax_2_amount' => $tax2Amount,
                    'tax_amount' => $taxAmount,
                    'total' => $total,
                ]);
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
            return redirect()->route('admin.purchase.orders.index')->with('error', 'Only draft/cancelled orders can be deleted.');
        }

        $po->items()->delete();
        $po->delete();
        
        return redirect()->route('admin.purchase.orders.index')->with('success', 'Purchase Order deleted!');
    }

    public function updateStatus(Request $request, $id)
    {
        $po = PurchaseOrder::findOrFail($id);
        $newStatus = $request->input('status');
        
        $validTransitions = [
            'DRAFT' => ['SENT', 'CANCELLED'],
            'SENT' => ['CONFIRMED', 'CANCELLED'],
            'CONFIRMED' => ['PARTIALLY_RECEIVED', 'RECEIVED', 'CANCELLED'],
            'PARTIALLY_RECEIVED' => ['RECEIVED', 'CANCELLED'],
        ];
        
        if (!isset($validTransitions[$po->status]) || !in_array($newStatus, $validTransitions[$po->status])) {
            return back()->with('error', "Cannot change status from {$po->status} to {$newStatus}");
        }
        
        $po->update(['status' => $newStatus]);
        
        return back()->with('success', "Status changed to {$newStatus}");
    }

    /**
     * Confirm PO (vendor confirmed)
     */
    public function confirm($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        
        if (!in_array($po->status, ['DRAFT', 'SENT'])) {
            return back()->with('error', 'Only Draft or Sent orders can be confirmed.');
        }
        
        $po->update([
            'status' => 'CONFIRMED',
            'confirmed_at' => now(),
        ]);
        
        return back()->with('success', 'Purchase Order confirmed successfully!');
    }

    /**
     * Cancel PO
     */
    public function cancel($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        
        if (in_array($po->status, ['RECEIVED', 'CANCELLED'])) {
            return back()->with('error', 'This order cannot be cancelled.');
        }
        
        // Check if any GRN exists
        if ($po->grns()->exists()) {
            return back()->with('error', 'Cannot cancel - GRN already created for this PO.');
        }
        
        $po->update(['status' => 'CANCELLED']);
        
        return back()->with('success', 'Purchase Order cancelled!');
    }

    public function pdf($id)
    {
        $po = PurchaseOrder::with(['items.product', 'items.unit', 'vendor'])->findOrFail($id);
        
        $settings = [
            'show_logo' => PurchaseSetting::getValue('pdf_show_logo', true),
            'show_signature' => PurchaseSetting::getValue('pdf_show_signature', true),
            'signature_text' => PurchaseSetting::getValue('pdf_signature_text', 'Authorized Signatory'),
            'footer_text' => PurchaseSetting::getValue('pdf_footer_text', ''),
        ];
        
        $company = [];
        if (class_exists('\App\Models\Option')) {
            $company = [
                'name' => \App\Models\Option::get('company_name', config('app.name')),
                'address' => \App\Models\Option::get('company_address', ''),
                'city' => \App\Models\Option::get('company_city', ''),
                'state' => \App\Models\Option::get('company_state', ''),
                'zip' => \App\Models\Option::get('company_zip', ''),
                'phone' => \App\Models\Option::get('company_phone', ''),
                'email' => \App\Models\Option::get('company_email', ''),
                'gst' => \App\Models\Option::get('company_gst', ''),
                'logo' => \App\Models\Option::get('company_logo', ''),
            ];
        }
        
        $pdf = Pdf::loadView('purchase::purchase-order.pdf', compact('po', 'settings', 'company'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream("PO_{$po->po_number}.pdf");
    }

    public function sendToVendor(Request $request, $id)
    {
        $po = PurchaseOrder::with(['items.product', 'items.unit', 'vendor'])->findOrFail($id);
        
        if (!$po->vendor || !$po->vendor->email) {
            return back()->with('error', 'Vendor email not available!');
        }
        
        $settings = [
            'show_logo' => PurchaseSetting::getValue('pdf_show_logo', true),
            'show_signature' => PurchaseSetting::getValue('pdf_show_signature', true),
            'signature_text' => PurchaseSetting::getValue('pdf_signature_text', 'Authorized Signatory'),
            'footer_text' => PurchaseSetting::getValue('pdf_footer_text', ''),
        ];
        
        $company = [];
        if (class_exists('\App\Models\Option')) {
            $company = [
                'name' => \App\Models\Option::get('company_name', config('app.name')),
                'address' => \App\Models\Option::get('company_address', ''),
                'city' => \App\Models\Option::get('company_city', ''),
                'state' => \App\Models\Option::get('company_state', ''),
                'zip' => \App\Models\Option::get('company_zip', ''),
                'phone' => \App\Models\Option::get('company_phone', ''),
                'email' => \App\Models\Option::get('company_email', ''),
                'gst' => \App\Models\Option::get('company_gst', ''),
                'logo' => \App\Models\Option::get('company_logo', ''),
            ];
        }
        
        $pdf = Pdf::loadView('purchase::purchase-order.pdf', compact('po', 'settings', 'company'));
        $pdfContent = $pdf->output();
        
        try {
            $vendorEmail = $po->vendor->email;
            $vendorName = $po->vendor->name;
            $companyName = $company['name'] ?? config('app.name');
            
            Mail::raw(
                "Dear {$vendorName},\n\nPlease find attached Purchase Order {$po->po_number}.\n\nRegards,\n{$companyName}",
                function ($message) use ($vendorEmail, $vendorName, $po, $pdfContent, $companyName) {
                    $message->to($vendorEmail, $vendorName)
                        ->subject("Purchase Order: {$po->po_number}")
                        ->attachData($pdfContent, "PO_{$po->po_number}.pdf", ['mime' => 'application/pdf']);
                    
                    if ($fromEmail = config('mail.from.address')) {
                        $message->from($fromEmail, $companyName);
                    }
                }
            );
            
            if ($po->status === 'DRAFT') {
                $po->update(['status' => 'SENT']);
            }
            
            return back()->with('success', "PO sent to {$vendorEmail}!");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    public function duplicate($id)
    {
        $po = PurchaseOrder::with('items')->findOrFail($id);
        
        DB::beginTransaction();
        try {
            $newPo = $po->replicate();
            $newPo->po_number = PurchaseOrder::generateNumber();
            $newPo->status = 'DRAFT';
            $newPo->po_date = now();
            $newPo->created_by = auth()->id();
            $newPo->save();
            
            foreach ($po->items as $item) {
                $newItem = $item->replicate();
                $newItem->purchase_order_id = $newPo->id;
                $newItem->received_qty = 0;
                $newItem->save();
            }
            
            DB::commit();
            return redirect()->route('admin.purchase.orders.edit', $newPo->id)->with('success', 'Purchase Order duplicated!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Send PO to Vendor via Email
     */
    public function send($id)
    {
        $po = PurchaseOrder::with(['items.product', 'items.unit', 'vendor'])->findOrFail($id);
        
        if (!$po->vendor || !$po->vendor->email) {
            return back()->with('error', 'Vendor email not available!');
        }
        
        // Get company details from Options
        $company = [];
        if (class_exists('\App\Models\Option')) {
            $company = [
                'name' => \App\Models\Option::get('company_name', config('app.name')),
                'address' => \App\Models\Option::get('company_address', ''),
                'city' => \App\Models\Option::get('company_city', ''),
                'state' => \App\Models\Option::get('company_state', ''),
                'zip' => \App\Models\Option::get('company_zip', ''),
                'phone' => \App\Models\Option::get('company_phone', ''),
                'email' => \App\Models\Option::get('company_email', ''),
                'gst' => \App\Models\Option::get('company_gst', ''),
                'logo' => \App\Models\Option::get('company_logo', ''),
            ];
        }
        
        // Generate PDF
        $settings = [
            'show_logo' => PurchaseSetting::getValue('pdf_show_logo', true),
            'show_signature' => PurchaseSetting::getValue('pdf_show_signature', true),
            'signature_text' => PurchaseSetting::getValue('pdf_signature_text', 'Authorized Signatory'),
            'footer_text' => PurchaseSetting::getValue('pdf_footer_text', ''),
        ];
        
        $pdf = Pdf::loadView('purchase::purchase-order.pdf', compact('po', 'settings', 'company'));
        $pdfContent = $pdf->output();
        
        // Get mail settings using Option model (has proper decryption)
        $mailConfig = \App\Models\Option::mailConfig();
        
        // Set runtime mail config
        config([
            'mail.default' => $mailConfig['mailer'] ?? 'smtp',
            'mail.mailers.smtp.host' => $mailConfig['host'],
            'mail.mailers.smtp.port' => $mailConfig['port'],
            'mail.mailers.smtp.username' => $mailConfig['username'],
            'mail.mailers.smtp.password' => $mailConfig['password'],
            'mail.mailers.smtp.encryption' => $mailConfig['encryption'],
            'mail.from.address' => $mailConfig['from_address'],
            'mail.from.name' => $mailConfig['from_name'],
        ]);
        
        try {
            $vendorEmail = $po->vendor->email;
            $vendorName = $po->vendor->name;
            $companyName = $company['name'] ?? config('app.name');
            $companyEmail = $company['email'] ?? config('mail.from.address');
            
            // Build HTML email body
            $emailBody = $this->buildPoEmailHtml($po, $company);
            
            Mail::send([], [], function ($message) use ($vendorEmail, $vendorName, $po, $pdfContent, $companyName, $companyEmail, $emailBody) {
                $message->to($vendorEmail, $vendorName)
                    ->subject("Purchase Order: {$po->po_number} from {$companyName}")
                    ->html($emailBody)
                    ->attachData($pdfContent, "PO_{$po->po_number}.pdf", ['mime' => 'application/pdf']);
                
                if ($companyEmail) {
                    $message->from($companyEmail, $companyName);
                    $message->replyTo($companyEmail, $companyName);
                }
            });
            
            // Update status to SENT if currently DRAFT
            if ($po->status === 'DRAFT') {
                $po->update([
                    'status' => 'SENT',
                    'sent_at' => now(),
                ]);
            }
            
            return back()->with('success', "Purchase Order sent successfully to {$vendorEmail}!");
            
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Build HTML email for PO
     */
    protected function buildPoEmailHtml($po, $company)
    {
        $companyName = $company['name'] ?? config('app.name');
        $companyAddress = trim(implode(', ', array_filter([
            $company['address'] ?? '',
            $company['city'] ?? '',
            $company['state'] ?? '',
            $company['zip'] ?? '',
        ])));
        $companyPhone = $company['phone'] ?? '';
        $companyEmail = $company['email'] ?? '';
        
        $itemsHtml = '';
        foreach ($po->items as $i => $item) {
            $productName = $item->product->name ?? $item->description ?? 'N/A';
            if ($item->variation) {
                $productName .= ' - ' . ($item->variation->variation_name ?: $item->variation->sku ?: '');
            }
            $itemsHtml .= "
            <tr>
                <td style='padding:8px;border:1px solid #ddd;'>" . ($i + 1) . "</td>
                <td style='padding:8px;border:1px solid #ddd;'>" . htmlspecialchars($productName) . "</td>
                <td style='padding:8px;border:1px solid #ddd;text-align:center;'>" . ($item->unit->short_name ?? '-') . "</td>
                <td style='padding:8px;border:1px solid #ddd;text-align:right;'>" . number_format($item->qty, 2) . "</td>
                <td style='padding:8px;border:1px solid #ddd;text-align:right;'>₹" . number_format($item->rate, 2) . "</td>
                <td style='padding:8px;border:1px solid #ddd;text-align:right;'>₹" . number_format($item->total, 2) . "</td>
            </tr>";
        }
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <title>Purchase Order {$po->po_number}</title>
        </head>
        <body style='font-family:Arial,sans-serif;line-height:1.6;color:#333;max-width:800px;margin:0 auto;padding:20px;'>
            <div style='background:#1e40af;color:#fff;padding:20px;border-radius:8px 8px 0 0;'>
                <h1 style='margin:0;font-size:24px;'>{$companyName}</h1>
                <p style='margin:5px 0 0;opacity:0.9;font-size:14px;'>{$companyAddress}</p>
            </div>
            
            <div style='background:#f8fafc;padding:20px;border:1px solid #e2e8f0;'>
                <h2 style='color:#1e40af;margin-top:0;'>Purchase Order: {$po->po_number}</h2>
                
                <p>Dear <strong>{$po->vendor->name}</strong>,</p>
                
                <p>Please find attached our Purchase Order <strong>{$po->po_number}</strong> dated <strong>{$po->po_date->format('d M Y')}</strong>.</p>
                
                <div style='background:#fff;padding:15px;border-radius:6px;margin:20px 0;border:1px solid #e2e8f0;'>
                    <table style='width:100%;border-collapse:collapse;'>
                        <tr>
                            <td style='padding:5px 0;'><strong>PO Number:</strong></td>
                            <td style='padding:5px 0;'>{$po->po_number}</td>
                            <td style='padding:5px 0;'><strong>PO Date:</strong></td>
                            <td style='padding:5px 0;'>{$po->po_date->format('d M Y')}</td>
                        </tr>
                        <tr>
                            <td style='padding:5px 0;'><strong>Expected Delivery:</strong></td>
                            <td style='padding:5px 0;'>" . ($po->expected_date ? $po->expected_date->format('d M Y') : '-') . "</td>
                            <td style='padding:5px 0;'><strong>Payment Terms:</strong></td>
                            <td style='padding:5px 0;'>{$po->payment_terms}</td>
                        </tr>
                    </table>
                </div>
                
                <h3 style='color:#1e40af;border-bottom:2px solid #1e40af;padding-bottom:5px;'>Order Items</h3>
                <table style='width:100%;border-collapse:collapse;margin:15px 0;'>
                    <thead>
                        <tr style='background:#1e40af;color:#fff;'>
                            <th style='padding:10px;border:1px solid #1e40af;text-align:left;'>#</th>
                            <th style='padding:10px;border:1px solid #1e40af;text-align:left;'>Item Description</th>
                            <th style='padding:10px;border:1px solid #1e40af;text-align:center;'>Unit</th>
                            <th style='padding:10px;border:1px solid #1e40af;text-align:right;'>Qty</th>
                            <th style='padding:10px;border:1px solid #1e40af;text-align:right;'>Rate</th>
                            <th style='padding:10px;border:1px solid #1e40af;text-align:right;'>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        {$itemsHtml}
                    </tbody>
                    <tfoot>
                        <tr style='background:#f1f5f9;'>
                            <td colspan='5' style='padding:10px;border:1px solid #ddd;text-align:right;'><strong>Subtotal:</strong></td>
                            <td style='padding:10px;border:1px solid #ddd;text-align:right;'><strong>₹" . number_format($po->subtotal, 2) . "</strong></td>
                        </tr>
                        <tr>
                            <td colspan='5' style='padding:10px;border:1px solid #ddd;text-align:right;'>Tax:</td>
                            <td style='padding:10px;border:1px solid #ddd;text-align:right;'>₹" . number_format($po->tax_amount, 2) . "</td>
                        </tr>
                        <tr style='background:#1e40af;color:#fff;'>
                            <td colspan='5' style='padding:10px;border:1px solid #1e40af;text-align:right;'><strong>Total Amount:</strong></td>
                            <td style='padding:10px;border:1px solid #1e40af;text-align:right;'><strong>₹" . number_format($po->total_amount, 2) . "</strong></td>
                        </tr>
                    </tfoot>
                </table>
                
                <p style='margin-top:20px;'>Please confirm receipt of this order and expected delivery date.</p>
                
                <p>If you have any questions, please contact us at <a href='mailto:{$companyEmail}'>{$companyEmail}</a>" . ($companyPhone ? " or call {$companyPhone}" : "") . ".</p>
                
                <p style='margin-top:30px;'>
                    Best regards,<br>
                    <strong>{$companyName}</strong>
                </p>
            </div>
            
            <div style='background:#1e293b;color:#94a3b8;padding:15px;text-align:center;font-size:12px;border-radius:0 0 8px 8px;'>
                <p style='margin:0;'>This is an automated email from {$companyName}</p>
                <p style='margin:5px 0 0;'>Please do not reply directly to this email</p>
            </div>
        </body>
        </html>";
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
