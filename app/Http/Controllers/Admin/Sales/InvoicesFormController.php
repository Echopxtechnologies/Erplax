<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Admin;
use App\Models\Tax;
use Modules\Inventory\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;


class InvoicesFormController extends AdminController
{
    /**
     * Generate unique invoice number using MAX to find the highest number
     */
    protected function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . date('Y') . '-';
        $prefixLength = strlen($prefix);
        
        // Use raw query to get the MAX numeric value after the prefix
        $maxNumber = DB::table('invoices')
            ->where('invoice_number', 'like', $prefix . '%')
            ->selectRaw('MAX(CAST(SUBSTRING(invoice_number, ?) AS UNSIGNED)) as max_num', [$prefixLength + 1])
            ->value('max_num');
        
        $nextNumber = ($maxNumber ?? 0) + 1;
        
        return $prefix . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $admins = Admin::orderBy('name')->get();
        $products = Product::all();
        $taxes = Tax::where('active', 1)->orderBy('name')->get();
        $invoice = null;
        
        // Generate preview invoice number (actual number generated on save)
        $invoiceNumber = $this->generateInvoiceNumber();

        return view('admin.sales.invoices.form', compact('customers', 'admins', 'products', 'taxes', 'invoice', 'invoiceNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'subject' => 'required|string|max:255',
            'date' => 'required|date',
            'due_date' => 'nullable|date',
            'currency' => 'required|string|max:10',
            'status' => 'required|string',
            'assigned_to' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'content' => 'nullable|string',
            'admin_note' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'items' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            // Generate fresh invoice number inside transaction to prevent duplicates
            $validated['invoice_number'] = $this->generateInvoiceNumber();
            $validated['created_by'] = auth()->user()->name ?? null;

            // Load all taxes for calculation
            $taxesMap = Tax::pluck('rate', 'id')->toArray();
            
            // Calculate totals from items (including per-item multiple taxes)
            $subtotal = 0;
            $totalTax = 0;
            
            $items = $request->input('items', []);
            
            foreach ($items as $item) {
                if (($item['item_type'] ?? '') === 'product') {
                    $qty = floatval($item['quantity'] ?? 0);
                    $rate = floatval($item['rate'] ?? 0);
                    $amount = $qty * $rate;
                    $subtotal += $amount;
                    
                    // Calculate tax from multiple tax_ids
                    $taxIds = $this->parseTaxIds($item['tax_ids'] ?? '');
                    
                    foreach ($taxIds as $taxId) {
                        $taxRate = $taxesMap[$taxId] ?? 0;
                        $totalTax += ($amount * $taxRate) / 100;
                    }
                }
            }

            $discountPercent = floatval($validated['discount_percent'] ?? 0);
            $discountAmount = $subtotal * ($discountPercent / 100);
            $afterDiscount = $subtotal - $discountAmount;

            $validated['subtotal'] = $subtotal;
            $validated['discount'] = $discountAmount;
            $validated['tax'] = $totalTax;
            $validated['total'] = $afterDiscount + $totalTax;
            $validated['amount_due'] = $validated['total'];
            $validated['amount_paid'] = 0;
            $validated['payment_status'] = 'unpaid';

            $invoice = Invoice::create($validated);

            // Save items with per-item multiple tax_ids
            if (!empty($items)) {
                $this->saveItems($invoice, $items);
            }

            DB::commit();
            return redirect()->route('admin.sales.invoices.show', $invoice->id)
                ->with('success', 'Invoice created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invoice creation error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Error creating invoice: ' . $e->getMessage());
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'items', 'payments']);
        return view('admin.sales.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $customers = Customer::orderBy('name')->get();
        $admins = Admin::orderBy('name')->get();
        $products = Product::all();
        $taxes = Tax::where('active', 1)->orderBy('name')->get();
        $invoice->load(['items']);
        $invoiceNumber = $invoice->invoice_number;

        return view('admin.sales.invoices.form', compact('customers', 'admins', 'products', 'taxes', 'invoice', 'invoiceNumber'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'subject' => 'required|string|max:255',
            'date' => 'required|date',
            'due_date' => 'nullable|date',
            'currency' => 'required|string|max:10',
            'status' => 'required|string',
            'assigned_to' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'content' => 'nullable|string',
            'admin_note' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'items' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            // Load all taxes for calculation
            $taxesMap = Tax::pluck('rate', 'id')->toArray();
            
            // Calculate totals from items
            $subtotal = 0;
            $totalTax = 0;
            
            $items = $request->input('items', []);
            
            foreach ($items as $item) {
                if (($item['item_type'] ?? '') === 'product') {
                    $qty = floatval($item['quantity'] ?? 0);
                    $rate = floatval($item['rate'] ?? 0);
                    $amount = $qty * $rate;
                    $subtotal += $amount;
                    
                    // Calculate tax from multiple tax_ids
                    $taxIds = $this->parseTaxIds($item['tax_ids'] ?? '');
                    
                    foreach ($taxIds as $taxId) {
                        $taxRate = $taxesMap[$taxId] ?? 0;
                        $totalTax += ($amount * $taxRate) / 100;
                    }
                }
            }

            $discountPercent = floatval($validated['discount_percent'] ?? 0);
            $discountAmount = $subtotal * ($discountPercent / 100);
            $afterDiscount = $subtotal - $discountAmount;

            $validated['subtotal'] = $subtotal;
            $validated['discount'] = $discountAmount;
            $validated['tax'] = $totalTax;
            $validated['total'] = $afterDiscount + $totalTax;
            $validated['amount_due'] = $validated['total'] - $invoice->amount_paid;

            $invoice->update($validated);

            // Delete existing items and recreate
            $invoice->items()->delete();
            if (!empty($items)) {
                $this->saveItems($invoice, $items);
            }

            DB::commit();
            return redirect()->route('admin.sales.invoices.show', $invoice->id)
                ->with('success', 'Invoice updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invoice update error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Error updating invoice: ' . $e->getMessage());
        }
    }

    public function destroy(Invoice $invoice)
    {
        try {
            $invoice->delete();
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice deleted successfully.'
                ]);
            }
            
            return redirect()->route('admin.sales.invoices.index')
                ->with('success', 'Invoice deleted successfully.');
                
        } catch (\Exception $e) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting invoice: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error deleting invoice: ' . $e->getMessage());
        }
    }

    /**
     * Parse tax_ids from various formats (JSON array, comma-separated, single value)
     */
    protected function parseTaxIds($taxIds): array
    {
        if (empty($taxIds)) return [];
        
        if (is_array($taxIds)) {
            return array_map('intval', array_filter($taxIds, function($v) { return $v !== '' && $v !== null; }));
        }
        
        if (is_string($taxIds)) {
            $taxIds = trim($taxIds);
            
            // Try JSON decode first
            $decoded = json_decode($taxIds, true);
            if (is_array($decoded)) {
                return array_map('intval', array_filter($decoded, function($v) { return $v !== '' && $v !== null; }));
            }
            
            // Try comma-separated
            if (strpos($taxIds, ',') !== false) {
                return array_map('intval', array_filter(explode(',', $taxIds), function($v) { return trim($v) !== ''; }));
            }
            
            // Single numeric value
            if (is_numeric($taxIds)) {
                return [intval($taxIds)];
            }
            
            return [];
        }
        
        return is_numeric($taxIds) ? [intval($taxIds)] : [];
    }

    protected function saveItems(Invoice $invoice, array $items): void
    {
        foreach ($items as $index => $item) {
            if (empty($item['item_type'])) continue;

            $itemData = [
                'invoice_id' => $invoice->id,
                'item_type' => $item['item_type'],
                'sort_order' => $index,
            ];

            if ($item['item_type'] === 'product') {
                $productId = $item['product_id'] ?? null;
                $itemData['product_id'] = (!empty($productId) && $productId !== '') ? (int)$productId : null;
                
                $itemData['description'] = $item['description'] ?? '';
                $itemData['long_description'] = $item['long_description'] ?? null;
                $itemData['quantity'] = floatval($item['quantity'] ?? 1);
                $itemData['rate'] = floatval($item['rate'] ?? 0);
                $itemData['amount'] = $itemData['quantity'] * $itemData['rate'];
                
                $taxIds = $this->parseTaxIds($item['tax_ids'] ?? '');
                $itemData['tax_ids'] = !empty($taxIds) ? json_encode(array_values($taxIds)) : null;
                
            } elseif ($item['item_type'] === 'section') {
                $itemData['description'] = $item['description'] ?? 'Section';
            } elseif ($item['item_type'] === 'note') {
                $itemData['long_description'] = $item['long_description'] ?? '';
            }

            InvoiceItem::create($itemData);
        }
    }

    public function getCustomer(Customer $customer)
    {
        return response()->json([
            'email' => $customer->email,
            'phone' => $customer->phone,
            'address' => $customer->address,
            'city' => $customer->city,
            'state' => $customer->state,
            'country' => $customer->country,
            'zip_code' => $customer->zip_code,
        ]);
    }
    
    public function print(Invoice $invoice)
    {
        $invoice->load(['customer', 'items']);
        
        $taxesMap = \App\Models\Tax::where('active', 1)->pluck('name', 'id')->toArray();
        $taxRatesMap = \App\Models\Tax::where('active', 1)->pluck('rate', 'id')->toArray();
        
        $taxBreakdown = [];
        foreach ($invoice->items as $item) {
            if (($item->item_type ?? 'product') !== 'product') continue;
            
            $taxIds = $this->parseTaxIds($item->tax_ids);
            foreach ($taxIds as $taxId) {
                $taxName = $taxesMap[$taxId] ?? 'Tax';
                $taxRate = $taxRatesMap[$taxId] ?? 0;
                $taxAmount = ($item->amount * $taxRate) / 100;
                $key = $taxId;
                
                if (!isset($taxBreakdown[$key])) {
                    $taxBreakdown[$key] = [
                        'name' => $taxName,
                        'rate' => $taxRate,
                        'amount' => 0
                    ];
                }
                $taxBreakdown[$key]['amount'] += $taxAmount;
            }
        }
        
        $company = [
            'name' => \App\Models\Option::get('company_name', 'Your Company'),
            'email' => \App\Models\Option::get('company_email', ''),
            'phone' => \App\Models\Option::get('company_phone', ''),
            'address' => \App\Models\Option::get('company_address', ''),
            'gst' => \App\Models\Option::get('company_gst', ''),
            'logo' => \App\Models\Option::get('company_logo', ''),
        ];
        
        $pdf = Pdf::loadView('admin.sales.invoices.print', [
            'invoice' => $invoice,
            'company' => $company,
            'taxBreakdown' => $taxBreakdown,
            'taxesMap' => $taxesMap,
            'taxRatesMap' => $taxRatesMap,
        ]);
        
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream("invoice-{$invoice->invoice_number}.pdf");
    }

    public function searchProducts(Request $request)
    {
        $search = $request->input('q', '');
        
        $query = Product::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        $products = $query->limit(20)->get()->map(function ($product) {
            $taxIds = [];
            if ($product->tax_ids) {
                $taxIds = $this->parseTaxIds($product->tax_ids);
            }
            
            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku ?? '',
                'price' => $product->sale_price ?? 0,
                'tax_ids' => $taxIds,
            ];
        });

        return response()->json($products);
    }
}