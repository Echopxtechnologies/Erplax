<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Admin;
use App\Models\Tax;
use Modules\Inventory\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoicesFormController extends Controller
{
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $admins = Admin::orderBy('name')->get();
        $products = Product::all();
        $taxes = Tax::where('active', 1)->orderBy('name')->get();
        $invoice = null;
        $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad((Invoice::count() + 1), 6, '0', STR_PAD_LEFT);

        return view('admin.sales.invoices.form', compact('customers', 'admins', 'products', 'taxes', 'invoice', 'invoiceNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'subject' => 'required|string|max:255',
            'invoice_number' => 'nullable|string|max:50',
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
            if (empty($validated['invoice_number'])) {
                $validated['invoice_number'] = 'INV-' . date('Y') . '-' . str_pad((Invoice::count() + 1), 6, '0', STR_PAD_LEFT);
            }

            $validated['created_by'] = auth()->user()->name ?? null;

            // Load all taxes for calculation
            $taxesMap = Tax::pluck('rate', 'id')->toArray();
            
            // Calculate totals from items (including per-item multiple taxes)
            $subtotal = 0;
            $totalTax = 0;
            
            $items = $request->input('items', []);
            
            // Debug log
            Log::info('Invoice items received:', ['items' => $items]);
            
            foreach ($items as $item) {
                if (($item['item_type'] ?? '') === 'product') {
                    $qty = floatval($item['quantity'] ?? 0);
                    $rate = floatval($item['rate'] ?? 0);
                    $amount = $qty * $rate;
                    $subtotal += $amount;
                    
                    // Calculate tax from multiple tax_ids
                    $taxIds = $this->parseTaxIds($item['tax_ids'] ?? '');
                    Log::info('Parsed tax_ids for item:', ['original' => $item['tax_ids'] ?? '', 'parsed' => $taxIds]);
                    
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
            
            // Debug log
            Log::info('Invoice update items received:', ['items' => $items]);
            
            foreach ($items as $item) {
                if (($item['item_type'] ?? '') === 'product') {
                    $qty = floatval($item['quantity'] ?? 0);
                    $rate = floatval($item['rate'] ?? 0);
                    $amount = $qty * $rate;
                    $subtotal += $amount;
                    
                    // Calculate tax from multiple tax_ids
                    $taxIds = $this->parseTaxIds($item['tax_ids'] ?? '');
                    Log::info('Update - Parsed tax_ids for item:', ['original' => $item['tax_ids'] ?? '', 'parsed' => $taxIds]);
                    
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
            return redirect()->route('admin.sales.invoices.index')
                ->with('success', 'Invoice deleted successfully.');
        } catch (\Exception $e) {
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
            // Clean the string
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
                // Handle product_id - convert empty string to null
                $productId = $item['product_id'] ?? null;
                $itemData['product_id'] = (!empty($productId) && $productId !== '') ? (int)$productId : null;
                
                $itemData['description'] = $item['description'] ?? '';
                $itemData['long_description'] = $item['long_description'] ?? null;
                $itemData['quantity'] = floatval($item['quantity'] ?? 1);
                $itemData['rate'] = floatval($item['rate'] ?? 0);
                $itemData['amount'] = $itemData['quantity'] * $itemData['rate'];
                
                // Store multiple tax_ids as JSON array
                $taxIds = $this->parseTaxIds($item['tax_ids'] ?? '');
                
                // Store as JSON string
                if (!empty($taxIds)) {
                    $itemData['tax_ids'] = json_encode(array_values($taxIds));
                } else {
                    $itemData['tax_ids'] = null;
                }
                
                Log::info('Saving item with tax_ids:', [
                    'description' => $itemData['description'],
                    'original_tax_ids' => $item['tax_ids'] ?? '',
                    'parsed_tax_ids' => $taxIds,
                    'stored_tax_ids' => $itemData['tax_ids']
                ]);
                
            } elseif ($item['item_type'] === 'section') {
                $itemData['description'] = $item['description'] ?? 'Section';
            } elseif ($item['item_type'] === 'note') {
                $itemData['long_description'] = $item['long_description'] ?? '';
            }

            try {
                $createdItem = InvoiceItem::create($itemData);
                Log::info('Item created successfully:', ['id' => $createdItem->id]);
            } catch (\Exception $e) {
                Log::error('Failed to create invoice item:', [
                    'error' => $e->getMessage(),
                    'itemData' => $itemData
                ]);
                throw $e;
            }
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

    // Search products - include tax_ids info
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
            // Parse product tax_ids to return as array
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