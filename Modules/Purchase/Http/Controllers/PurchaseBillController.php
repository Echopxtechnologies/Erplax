<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\Purchase\Models\PurchaseBill;
use Modules\Purchase\Models\PurchaseBillItem;
use Modules\Purchase\Models\PurchasePayment;
use Modules\Purchase\Models\GoodsReceiptNote;
use Modules\Purchase\Models\Vendor;
use Modules\Purchase\Models\PurchaseSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PurchaseBillController extends AdminController
{
    public function index()
    {
        $stats = [
            'total' => PurchaseBill::count(),
            'draft' => PurchaseBill::where('status', 'DRAFT')->count(),
            'pending' => PurchaseBill::where('status', 'PENDING')->count(),
            'approved' => PurchaseBill::where('status', 'APPROVED')->count(),
            'unpaid' => PurchaseBill::where('payment_status', 'UNPAID')->where('status', 'APPROVED')->count(),
            'total_amount' => PurchaseBill::where('status', 'APPROVED')->sum('grand_total'),
            'balance_due' => PurchaseBill::where('status', 'APPROVED')->sum('balance_due'),
        ];
        
        return $this->moduleView('purchase::bill.index', compact('stats'));
    }

    public function dataTable(Request $request): JsonResponse
    {
        $query = PurchaseBill::with(['vendor:id,name', 'purchaseOrder:id,po_number', 'grn:id,grn_number']);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('bill_number', 'like', "%{$search}%")
                  ->orWhere('vendor_invoice_no', 'like', "%{$search}%")
                  ->orWhereHas('vendor', fn($v) => $v->where('name', 'like', "%{$search}%"));
            });
        }

        // Filters
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($paymentStatus = $request->input('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }
        if ($vendorId = $request->input('vendor_id')) {
            $query->where('vendor_id', $vendorId);
        }

        // Sorting
        $sortCol = $request->input('sort', 'id');
        $sortDir = $request->input('dir', 'desc');
        $query->orderBy($sortCol, $sortDir);

        // Pagination
        $data = $query->paginate($request->input('per_page', 15));

        $items = collect($data->items())->map(function($item) {
            return [
                'id' => $item->id,
                'bill_number' => $item->bill_number,
                'vendor_name' => $item->vendor->name ?? '-',
                'vendor_invoice_no' => $item->vendor_invoice_no ?? '-',
                'bill_date' => $item->bill_date->format('d M Y'),
                'due_date' => $item->due_date?->format('d M Y') ?? '-',
                'grand_total' => '₹' . number_format($item->grand_total, 2),
                'paid_amount' => '₹' . number_format($item->paid_amount, 2),
                'balance_due' => '₹' . number_format($item->balance_due, 2),
                'status' => $item->status,
                'payment_status' => $item->payment_status,
                'is_overdue' => $item->is_overdue,
                '_show_url' => route('admin.purchase.bills.show', $item->id),
                '_edit_url' => route('admin.purchase.bills.edit', $item->id),
                '_pdf_url' => route('admin.purchase.bills.pdf', $item->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'per_page' => $data->perPage(),
        ]);
    }

    public function create(Request $request)
    {
        $billNumber = PurchaseBill::generateNumber();
        $vendors = Vendor::where('status', 'ACTIVE')->orderBy('name')->get(['id', 'name']);
        $warehouses = collect();

        if (class_exists('\Modules\Inventory\Models\Warehouse')) {
            $warehouses = \Modules\Inventory\Models\Warehouse::where('is_active', true)->orderBy('name')->get(['id', 'name']);
        }

        // Get approved GRNs that don't have bills yet (or allow multiple bills per GRN)
        $grns = GoodsReceiptNote::with(['vendor:id,name', 'purchaseOrder:id,po_number', 'warehouse:id,name'])
            ->where('status', 'APPROVED')
            ->orderBy('grn_date', 'desc')
            ->get();
        
        return $this->moduleView('purchase::bill.create', compact('billNumber', 'vendors', 'grns', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'grn_id' => 'nullable|exists:goods_receipt_notes,id',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'vendor_invoice_no' => 'nullable|string|max:100',
            'vendor_invoice_date' => 'nullable|date',
            'bill_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:bill_date',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'shipping_charge' => 'nullable|numeric|min:0',
            'adjustment' => 'nullable|numeric',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|numeric|min:0.001',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.tax_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            $bill = PurchaseBill::create([
                'bill_number' => PurchaseBill::generateNumber(),
                'vendor_id' => $validated['vendor_id'],
                'grn_id' => $validated['grn_id'] ?? null,
                'purchase_order_id' => $validated['purchase_order_id'] ?? null,
                'vendor_invoice_no' => $validated['vendor_invoice_no'] ?? null,
                'vendor_invoice_date' => $validated['vendor_invoice_date'] ?? null,
                'bill_date' => $validated['bill_date'],
                'due_date' => $validated['due_date'] ?? null,
                'warehouse_id' => $validated['warehouse_id'] ?? null,
                'shipping_charge' => $validated['shipping_charge'] ?? 0,
                'adjustment' => $validated['adjustment'] ?? 0,
                'notes' => $validated['notes'] ?? null,
                'status' => 'DRAFT',
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['items'] as $itemData) {
                $lineTotal = $itemData['qty'] * $itemData['rate'];
                $discountPercent = $itemData['discount_percent'] ?? 0;
                $discountAmount = $lineTotal * ($discountPercent / 100);
                $afterDiscount = $lineTotal - $discountAmount;
                $taxPercent = $itemData['tax_percent'] ?? 0;
                $taxAmount = $afterDiscount * ($taxPercent / 100);
                $total = $afterDiscount + $taxAmount;

                PurchaseBillItem::create([
                    'purchase_bill_id' => $bill->id,
                    'grn_item_id' => $itemData['grn_item_id'] ?? null,
                    'product_id' => $itemData['product_id'],
                    'variation_id' => $itemData['variation_id'] ?? null,
                    'unit_id' => $itemData['unit_id'] ?? null,
                    'description' => $itemData['description'] ?? null,
                    'qty' => $itemData['qty'],
                    'rate' => $itemData['rate'],
                    'tax_percent' => $taxPercent,
                    'tax_amount' => $taxAmount,
                    'discount_percent' => $discountPercent,
                    'discount_amount' => $discountAmount,
                    'total' => $total,
                ]);
            }

            $bill->calculateTotals();

            DB::commit();
            return redirect()->route('admin.purchase.bills.show', $bill->id)->with('success', 'Bill created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $bill = PurchaseBill::with([
            'vendor', 'purchaseOrder', 'grn.warehouse', 'warehouse',
            'items.product', 'items.unit',
            'payments.creator', 'payments.paymentMethod', 'creator', 'approver'
        ])->findOrFail($id);
        
        // Get payment methods from database
        $paymentMethods = collect();
        if (\Schema::hasTable('payment_methods')) {
            $paymentMethods = DB::table('payment_methods')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'name', 'slug', 'icon']);
        }
        
        // Get vendor bank details
        $vendorBank = null;
        if (\Schema::hasTable('bank_details')) {
            $vendorBank = DB::table('bank_details')
                ->where('holder_type', 'vendor')
                ->where('holder_id', $bill->vendor_id)
                ->where('is_primary', true)
                ->first();
        }
        
        return $this->moduleView('purchase::bill.show', compact('bill', 'paymentMethods', 'vendorBank'));
    }

    public function edit($id)
    {
        $bill = PurchaseBill::with(['items.product', 'items.unit'])->findOrFail($id);
        
        if (!$bill->canEdit()) {
            return redirect()->route('admin.purchase.bills.show', $id)->with('error', 'Cannot edit this bill.');
        }

        $vendors = Vendor::where('status', 'ACTIVE')->orderBy('name')->get(['id', 'name']);
        $products = collect();
        $warehouses = collect();

        if (class_exists('\Modules\Inventory\Models\Product')) {
            $products = \Modules\Inventory\Models\Product::with('unit')
                ->where('is_active', true)->orderBy('name')
                ->get(['id', 'name', 'sku', 'unit_id', 'purchase_price']);
        }
        if (class_exists('\Modules\Inventory\Models\Warehouse')) {
            $warehouses = \Modules\Inventory\Models\Warehouse::where('is_active', true)->orderBy('name')->get(['id', 'name']);
        }
        
        return $this->moduleView('purchase::bill.edit', compact('bill', 'vendors', 'products', 'warehouses'));
    }

    public function update(Request $request, $id)
    {
        $bill = PurchaseBill::findOrFail($id);
        
        if (!$bill->canEdit()) {
            return redirect()->route('admin.purchase.bills.show', $id)->with('error', 'Cannot edit this bill.');
        }

        $validated = $request->validate([
            'vendor_invoice_no' => 'nullable|string|max:100',
            'vendor_invoice_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'shipping_charge' => 'nullable|numeric|min:0',
            'adjustment' => 'nullable|numeric',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|numeric|min:0.001',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.tax_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            $bill->update([
                'vendor_invoice_no' => $validated['vendor_invoice_no'] ?? null,
                'vendor_invoice_date' => $validated['vendor_invoice_date'] ?? null,
                'due_date' => $validated['due_date'] ?? null,
                'warehouse_id' => $validated['warehouse_id'] ?? null,
                'shipping_charge' => $validated['shipping_charge'] ?? 0,
                'adjustment' => $validated['adjustment'] ?? 0,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Delete old items and recreate
            $bill->items()->delete();

            foreach ($validated['items'] as $itemData) {
                $lineTotal = $itemData['qty'] * $itemData['rate'];
                $discountPercent = $itemData['discount_percent'] ?? 0;
                $discountAmount = $lineTotal * ($discountPercent / 100);
                $afterDiscount = $lineTotal - $discountAmount;
                $taxPercent = $itemData['tax_percent'] ?? 0;
                $taxAmount = $afterDiscount * ($taxPercent / 100);
                $total = $afterDiscount + $taxAmount;

                PurchaseBillItem::create([
                    'purchase_bill_id' => $bill->id,
                    'product_id' => $itemData['product_id'],
                    'variation_id' => $itemData['variation_id'] ?? null,
                    'unit_id' => $itemData['unit_id'] ?? null,
                    'qty' => $itemData['qty'],
                    'rate' => $itemData['rate'],
                    'tax_percent' => $taxPercent,
                    'tax_amount' => $taxAmount,
                    'discount_percent' => $discountPercent,
                    'discount_amount' => $discountAmount,
                    'total' => $total,
                ]);
            }

            $bill->calculateTotals();

            DB::commit();
            return redirect()->route('admin.purchase.bills.show', $bill->id)->with('success', 'Bill updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $bill = PurchaseBill::findOrFail($id);
        
        if ($bill->status === 'APPROVED' && $bill->paid_amount > 0) {
            return back()->with('error', 'Cannot delete bill with payments.');
        }

        $bill->delete();
        return redirect()->route('admin.purchase.bills.index')->with('success', 'Bill deleted.');
    }

    // Submit for approval
    public function submit($id)
    {
        $bill = PurchaseBill::findOrFail($id);
        
        if (!$bill->canSubmit()) {
            return back()->with('error', 'Cannot submit this bill.');
        }

        $bill->update(['status' => 'PENDING']);
        return back()->with('success', 'Bill submitted for approval.');
    }

    // Approve bill
    public function approve($id)
    {
        $bill = PurchaseBill::findOrFail($id);
        
        if (!$bill->canApprove()) {
            return back()->with('error', 'Cannot approve this bill.');
        }

        $bill->update([
            'status' => 'APPROVED',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Bill approved.');
    }

    // Reject bill
    public function reject(Request $request, $id)
    {
        $bill = PurchaseBill::findOrFail($id);
        
        if (!$bill->canApprove()) {
            return back()->with('error', 'Cannot reject this bill.');
        }

        $bill->update([
            'status' => 'REJECTED',
            'rejection_reason' => $request->input('reason'),
        ]);

        return back()->with('success', 'Bill rejected.');
    }

    // Cancel bill
    public function cancel($id)
    {
        $bill = PurchaseBill::findOrFail($id);
        
        if ($bill->paid_amount > 0) {
            return back()->with('error', 'Cannot cancel bill with payments.');
        }

        $bill->update(['status' => 'CANCELLED']);
        return back()->with('success', 'Bill cancelled.');
    }

    // Record payment
    public function recordPayment(Request $request, $id)
    {
        $bill = PurchaseBill::findOrFail($id);
        
        if (!$bill->canPay()) {
            return back()->with('error', 'Cannot record payment for this bill.');
        }

        $validated = $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01|max:' . $bill->balance_due,
            'payment_method_id' => 'required|exists:payment_methods,id',
            'reference_no' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:100',
            'cheque_no' => 'nullable|string|max:50',
            'cheque_date' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        PurchasePayment::create([
            'payment_number' => PurchasePayment::generateNumber(),
            'purchase_bill_id' => $bill->id,
            'vendor_id' => $bill->vendor_id,
            'payment_date' => $validated['payment_date'],
            'amount' => $validated['amount'],
            'payment_method_id' => $validated['payment_method_id'],
            'reference_no' => $validated['reference_no'] ?? null,
            'bank_name' => $validated['bank_name'] ?? null,
            'cheque_no' => $validated['cheque_no'] ?? null,
            'cheque_date' => $validated['cheque_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'COMPLETED',
            'created_by' => auth()->id(),
        ]);

        $bill->updatePaymentStatus();

        return back()->with('success', 'Payment recorded successfully.');
    }
    
    // PDF Download
    public function pdf($id)
    {
        $bill = PurchaseBill::with([
            'vendor', 'purchaseOrder', 'grn.warehouse', 'warehouse',
            'items.product', 'items.unit', 'payments.paymentMethod'
        ])->findOrFail($id);
        
        // Get settings
        $settings = PurchaseSetting::getAll();
        
        // PDF Settings with defaults
        $pdfSettings = [
            'primary_color' => $settings['pdf_primary_color'] ?? '#1e40af',
            'secondary_color' => $settings['pdf_secondary_color'] ?? '#f1f5f9',
            'font_size' => $settings['pdf_font_size'] ?? 9,
            'show_logo' => $settings['pdf_show_logo'] ?? true,
            'show_gst' => $settings['pdf_show_gst'] ?? true,
            'show_notes' => $settings['pdf_show_notes'] ?? true,
            'show_terms' => $settings['pdf_show_terms'] ?? true,
            'show_signature' => $settings['pdf_show_signature'] ?? true,
            'compact_mode' => $settings['pdf_compact_mode'] ?? false,
        ];
        
        // Get company info from Options
        $companyName = '';
        $companyAddress = '';
        $companyPhone = '';
        $companyEmail = '';
        $companyGst = '';
        $companyLogo = null;
        
        if (class_exists('\App\Models\Option')) {
            $companyName = \App\Models\Option::get('company_name', '');
            $companyAddress = \App\Models\Option::get('company_address', '');
            if ($city = \App\Models\Option::get('company_city')) {
                $companyAddress .= ', ' . $city;
            }
            if ($state = \App\Models\Option::get('company_state')) {
                $companyAddress .= ', ' . $state;
            }
            if ($pincode = \App\Models\Option::get('company_zip')) {
                $companyAddress .= ' - ' . $pincode;
            }
            $companyPhone = \App\Models\Option::get('company_phone', '');
            $companyEmail = \App\Models\Option::get('company_email', '');
            $companyGst = \App\Models\Option::get('company_gst', '');
            
            if ($logo = \App\Models\Option::get('company_logo')) {
                $logoPath = public_path('uploads/' . $logo);
                if (file_exists($logoPath)) {
                    $companyLogo = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
                }
            }
        }
        
        // Get vendor bank details
        $vendorBank = null;
        if (\Schema::hasTable('bank_details')) {
            $vendorBank = DB::table('bank_details')
                ->where('holder_type', 'vendor')
                ->where('holder_id', $bill->vendor_id)
                ->where('is_primary', true)
                ->first();
        }
        
        // Amount in words
        $amountInWords = $this->convertNumberToWords($bill->grand_total);
        
        $html = view('purchase::bill.pdf', compact(
            'bill', 'pdfSettings', 'vendorBank', 'amountInWords',
            'companyName', 'companyAddress', 'companyPhone', 'companyEmail', 'companyGst', 'companyLogo'
        ))->render();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("invoice-{$bill->bill_number}.pdf");
    }
    
    // Payment Receipt PDF
    public function paymentReceipt($paymentId)
    {
        $payment = PurchasePayment::with([
            'vendor', 'bill', 'paymentMethod'
        ])->findOrFail($paymentId);
        
        // Get settings
        $settings = PurchaseSetting::getAll();
        
        // Get company info
        $company = [
            'name' => '',
            'address' => '',
            'phone' => '',
            'email' => '',
        ];
        
        if (class_exists('\App\Models\Option')) {
            $company = [
                'name' => \App\Models\Option::get('company_name', ''),
                'address' => \App\Models\Option::get('company_address', ''),
                'phone' => \App\Models\Option::get('company_phone', ''),
                'email' => \App\Models\Option::get('company_email', ''),
            ];
        }
        
        // Convert amount to words
        $amountInWords = $this->convertNumberToWords($payment->amount);
        
        $html = view('purchase::bill.payment-receipt', compact('payment', 'company', 'settings', 'amountInWords'))->render();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("receipt-{$payment->payment_number}.pdf");
    }
    
    // Helper: Convert number to words (Indian format)
    private function convertNumberToWords($number)
    {
        $number = round($number, 2);
        $rupees = floor($number);
        $paise = round(($number - $rupees) * 100);
        
        if ($rupees == 0) {
            $words = 'Zero';
        } else {
            $words = $this->convertToWords($rupees);
        }
        
        $words = trim($words) . ' Rupees';
        
        if ($paise > 0) {
            $words .= ' and ' . $this->convertToWords($paise) . ' Paise';
        }
        
        return $words . ' Only';
    }
    
    // Helper: Convert integer to words (Indian numbering)
    private function convertToWords($num)
    {
        $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 
                 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        
        if ($num < 20) {
            return $ones[$num];
        }
        
        if ($num < 100) {
            return $tens[floor($num / 10)] . ($num % 10 ? ' ' . $ones[$num % 10] : '');
        }
        
        if ($num < 1000) {
            return $ones[floor($num / 100)] . ' Hundred' . ($num % 100 ? ' ' . $this->convertToWords($num % 100) : '');
        }
        
        if ($num < 100000) {
            return $this->convertToWords(floor($num / 1000)) . ' Thousand' . ($num % 1000 ? ' ' . $this->convertToWords($num % 1000) : '');
        }
        
        if ($num < 10000000) {
            return $this->convertToWords(floor($num / 100000)) . ' Lakh' . ($num % 100000 ? ' ' . $this->convertToWords($num % 100000) : '');
        }
        
        return $this->convertToWords(floor($num / 10000000)) . ' Crore' . ($num % 10000000 ? ' ' . $this->convertToWords($num % 10000000) : '');
    }

    // Bulk delete
    public function bulkDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        $deleted = PurchaseBill::whereIn('id', $ids)
            ->where('status', '!=', 'APPROVED')
            ->whereDoesntHave('payments')
            ->delete();

        return response()->json(['success' => true, 'deleted' => $deleted]);
    }
}
