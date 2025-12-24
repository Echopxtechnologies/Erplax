<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Estimation;
use App\Models\EstimationItem;
use App\Models\Customer;
use App\Models\Admin;
use App\Models\Tax;
use Modules\Inventory\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class EstimationsFormController extends AdminController
{
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $users = Admin::orderBy('name')->get();
        $products = Product::where('is_active', true)->get();
        $estimation = null;
        
        // Generate preview estimation number
        $nextNumber = Estimation::generateEstimationNumber();

        return view('admin.sales.estimations.form', compact('customers', 'users', 'products', 'estimation', 'nextNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'status' => 'required|string',
            'date' => 'required|date',
            'valid_until' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'currency' => 'required|string|max:10',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'content' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'admin_note' => 'nullable|string',
            'items' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $validated['estimation_number'] = Estimation::generateEstimationNumber();
            $validated['created_by'] = auth()->user()->name ?? null;

            // Load all taxes for calculation
            $taxesMap = Tax::pluck('rate', 'id')->toArray();
            
            // Calculate totals from items (including per-item taxes)
            $subtotal = 0;
            $totalTax = 0;
            
            $items = $request->input('items', []);
            
            Log::info('Estimation items received:', ['items' => $items]);
            
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
            $validated['discount_amount'] = $discountAmount;
            $validated['tax_amount'] = $totalTax;
            $validated['total'] = $afterDiscount + $totalTax;

            $estimation = Estimation::create($validated);

            // Save items with per-item tax_ids
            if (!empty($items)) {
                $this->saveItems($estimation, $items);
            }

            DB::commit();
            return redirect()->route('admin.sales.estimations.show', $estimation->id)
                ->with('success', 'Estimation created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Estimation creation error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Error creating estimation: ' . $e->getMessage());
        }
    }


/**
 * Create estimation from proposal
 */
public function fromProposal(\App\Models\Proposal $proposal)
{
    try {
        // Generate new estimation number
        $estimationNumber = \App\Models\Estimation::generateEstimationNumber();
        
        // Create the estimation from proposal data
        $estimation = \App\Models\Estimation::create([
            'estimation_number' => $estimationNumber,
            'subject' => $proposal->subject,
            'customer_id' => $proposal->customer_id,
            'proposal_id' => $proposal->id,
            'status' => 'draft',
            'assigned_to' => $proposal->assigned_to,
            'date' => now()->format('Y-m-d'),
            'valid_until' => now()->addDays(30)->format('Y-m-d'),
            'address' => $proposal->address,
            'city' => $proposal->city,
            'state' => $proposal->state,
            'country' => $proposal->country,
            'zip_code' => $proposal->zip_code,
            'email' => $proposal->email,
            'phone' => $proposal->phone,
            'currency' => $proposal->currency,
            'discount_type' => $proposal->discount_type,
            'discount_percent' => $proposal->discount_percent,
            'discount_amount' => $proposal->discount_amount,
            'subtotal' => $proposal->subtotal,
            'tax_amount' => $proposal->tax_amount ?? $proposal->total_tax ?? 0,
            'total' => $proposal->total,
            'adjustment' => $proposal->adjustment ?? 0,
            'content' => $proposal->content,
            'admin_note' => $proposal->admin_note,
            'created_by' => auth()->user()->name ?? null,
        ]);

        // Copy proposal items to estimation items
        foreach ($proposal->items as $item) {
            $estimation->items()->create([
                'item_type' => $item->item_type,
                'product_id' => $item->product_id,
                'description' => $item->description,
                'long_description' => $item->long_description,
                'quantity' => $item->quantity,
                'unit' => $item->unit,
                'rate' => $item->rate,
                'tax_ids' => $item->tax_ids,
                'tax_rate' => $item->tax_rate ?? 0,
                'tax_name' => $item->tax_name,
                'tax_amount' => $item->tax_amount ?? 0,
                'amount' => $item->amount,
                'total' => $item->total ?? $item->amount,
                'sort_order' => $item->sort_order ?? 0,
            ]);
        }

        return redirect()
            ->route('admin.sales.estimations.show', $estimation->id)
            ->with('success', "Estimation {$estimationNumber} created from Proposal {$proposal->proposal_number}");

    } catch (\Exception $e) {
        \Log::error('Error creating estimation from proposal: ' . $e->getMessage());
        
        return redirect()
            ->back()
            ->with('error', 'Failed to create estimation: ' . $e->getMessage());
    }
}







    public function show(Estimation $estimation)
    {
        $estimation->load(['customer', 'items']);
        $taxes = Tax::where('active', 1)->get();
        return view('admin.sales.estimations.show', compact('estimation', 'taxes'));
    }

    public function edit(Estimation $estimation)
    {
        $customers = Customer::orderBy('name')->get();
        $users = Admin::orderBy('name')->get();
        $products = Product::where('is_active', true)->get();
        $estimation->load(['items']);
        $nextNumber = null; // Not needed for edit

        return view('admin.sales.estimations.form', compact('customers', 'users', 'products', 'estimation', 'nextNumber'));
    }

    public function update(Request $request, Estimation $estimation)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'status' => 'required|string',
            'date' => 'required|date',
            'valid_until' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'currency' => 'required|string|max:10',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'content' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'admin_note' => 'nullable|string',
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
            
            Log::info('Estimation update items received:', ['items' => $items]);
            
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
            $validated['discount_amount'] = $discountAmount;
            $validated['tax_amount'] = $totalTax;
            $validated['total'] = $afterDiscount + $totalTax;

            $estimation->update($validated);

            // Delete existing items and recreate
            $estimation->items()->delete();
            if (!empty($items)) {
                $this->saveItems($estimation, $items);
            }

            DB::commit();
            return redirect()->route('admin.sales.estimations.show', $estimation->id)
                ->with('success', 'Estimation updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Estimation update error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Error updating estimation: ' . $e->getMessage());
        }
    }

    // public function destroy(Estimation $estimation)
    // {
    //     try {
    //         $estimation->delete();
    //         return redirect()->route('admin.sales.estimations.index')
    //             ->with('success', 'Estimation deleted successfully.');
    //     } catch (\Exception $e) {
    //         return back()->with('error', 'Error deleting estimation: ' . $e->getMessage());
    //     }
    // }
   


    public function destroy(Estimation $estimation)
{
    try {
        $estimation->delete();
        
        // ⭐ Check if request wants JSON (AJAX)
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Estimation deleted successfully.'
            ]);
        }
        
        // Regular browser request
        return redirect()->route('admin.sales.estimations.index')
            ->with('success', 'Estimation deleted successfully.');
            
    } catch (\Exception $e) {
        // ⭐ Also return JSON for errors
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting estimation: ' . $e->getMessage()
            ], 500);
        }
        
        return back()->with('error', 'Error deleting estimation: ' . $e->getMessage());
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

    protected function saveItems(Estimation $estimation, array $items): void
    {
        foreach ($items as $index => $item) {
            if (empty($item['item_type'])) continue;

            $itemData = [
                'estimation_id' => $estimation->id,
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
                $itemData['total'] = $itemData['amount'];
                
                // Store multiple tax_ids as JSON array
                $taxIds = $this->parseTaxIds($item['tax_ids'] ?? '');
                
                if (!empty($taxIds)) {
                    $itemData['tax_ids'] = json_encode(array_values($taxIds));
                } else {
                    $itemData['tax_ids'] = null;
                }
                
                Log::info('Saving estimation item with tax_ids:', [
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
                $createdItem = EstimationItem::create($itemData);
                Log::info('Estimation item created successfully:', ['id' => $createdItem->id]);
            } catch (\Exception $e) {
                Log::error('Failed to create estimation item:', [
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

    public function searchProducts(Request $request)
    {
        $search = $request->input('q', '');
        
        $query = Product::where('is_active', true);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        $products = $query->limit(20)->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku ?? '',
                'price' => $product->sale_price ?? 0,
                'tax_ids' => $this->parseTaxIds($product->tax_ids ?? ''),
            ];
        });

        return response()->json($products);
    }

 public function print(Estimation $estimation)
    {
        $estimation->load(['customer', 'items']);
        
        // Get taxes for breakdown
        $taxesMap = \App\Models\Tax::where('active', 1)->pluck('name', 'id')->toArray();
        $taxRatesMap = \App\Models\Tax::where('active', 1)->pluck('rate', 'id')->toArray();
        
        // Calculate tax breakdown from items
        $taxBreakdown = [];
        foreach ($estimation->items as $item) {
            if (($item->item_type ?? 'product') !== 'product') continue;
            
            $taxIds = $this->parseTaxIdsForPrint($item->tax_ids ?? null);
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
        
        // Fetch company details from options table
        $company = $this->getCompanyDetailsForPrint();
        
        $pdf = Pdf::loadView('admin.sales.estimations.print', [
            'estimation' => $estimation,
            'company' => $company,
            'taxBreakdown' => $taxBreakdown,
            'taxesMap' => $taxesMap,
            'taxRatesMap' => $taxRatesMap,
        ]);
        
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream("Estimation-{$estimation->estimation_number}.pdf");
    }

    /**
     * Get company details from options table for print
     */
    private function getCompanyDetailsForPrint(): array
    {
        $options = \DB::table('options')
            ->whereIn('key', [
                'company_name',
                'company_logo', 
                'company_email',
                'company_phone',
                'company_address',
                'company_city',
                'company_state',
                'company_country',
                'company_zip',
                'company_gst',
            ])
            ->pluck('value', 'key')
            ->toArray();
        
        $addressParts = [];
        if (!empty($options['company_address'])) $addressParts[] = $options['company_address'];
        if (!empty($options['company_city'])) $addressParts[] = $options['company_city'];
        if (!empty($options['company_state'])) $addressParts[] = $options['company_state'];
        if (!empty($options['company_zip'])) $addressParts[] = $options['company_zip'];
        
        return [
            'name' => $options['company_name'] ?? config('app.name', 'Your Company'),
            'logo' => $options['company_logo'] ?? null,
            'email' => $options['company_email'] ?? '',
            'phone' => $options['company_phone'] ?? '',
            'address' => implode(', ', $addressParts),
            'gst' => $options['company_gst'] ?? '',
        ];
    }

    /**
     * Parse tax IDs from various formats for print
     */
    private function parseTaxIdsForPrint($taxIds): array
    {
        if (empty($taxIds)) return [];
        
        if (is_array($taxIds)) {
            return array_map('intval', $taxIds);
        }
        
        $decoded = json_decode($taxIds, true);
        if (is_array($decoded)) {
            return array_map('intval', $decoded);
        }
        
        if (strpos($taxIds, ',') !== false) {
            return array_map('intval', array_filter(explode(',', $taxIds)));
        }
        
        return $taxIds ? [intval($taxIds)] : [];
    }











































































































    

}