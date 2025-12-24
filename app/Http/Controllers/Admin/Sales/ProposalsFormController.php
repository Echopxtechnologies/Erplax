<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Proposal;
use App\Models\ProposalItem;
use App\Models\ProposalTax;
use App\Models\Customer;
use App\Models\Admin;
use App\Models\Tax;
use Modules\Inventory\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class ProposalsFormController extends AdminController
{
    protected $currencies = [
        'INR' => 'Indian Rupee',
        'USD' => 'US Dollar',
        'EUR' => 'Euro',
        'GBP' => 'British Pound',
        'AED' => 'UAE Dirham',
    ];

    protected $statuses = [
        'draft' => 'Draft',
        'sent' => 'Sent',
        'open' => 'Open',
        'revised' => 'Revised',
        'declined' => 'Declined',
        'accepted' => 'Accepted',
    ];

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $users = Admin::orderBy('name')->get();
        $products = Product::where('is_active', true)->get();
        $taxes = Tax::where('active', 1)->orderBy('name')->get();
        $proposal = null;
        
        // Generate preview proposal number
        $nextNumber = Proposal::generateProposalNumber();

        return view('admin.sales.proposals.form', compact(
            'customers', 
            'users', 
            'products', 
            'taxes',
            'proposal', 
            'nextNumber'
        ))->with([
            'currencies' => $this->currencies,
            'statuses' => $this->statuses,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'status' => 'required|string',
            'date' => 'required|date',
            'open_till' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'currency' => 'required|string|max:10',
            'discount_type' => 'nullable|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'content' => 'nullable|string',
            'admin_note' => 'nullable|string',
            'assigned_to' => 'nullable|string',
            'tags' => 'nullable|string',
            'items' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $validated['proposal_number'] = Proposal::generateProposalNumber();
            $validated['created_by'] = auth()->id();

            // Load all taxes for calculation
            $taxesMap = Tax::pluck('rate', 'id')->toArray();
            
            // Calculate totals from items (including per-item taxes)
            $subtotal = 0;
            $totalTax = 0;
            $taxBreakdown = [];
            
            $items = $request->input('items', []);
            
            Log::info('Proposal items received:', ['items' => $items]);
            
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
                        $taxAmount = ($amount * $taxRate) / 100;
                        $totalTax += $taxAmount;
                        
                        // Track breakdown per tax
                        if (!isset($taxBreakdown[$taxId])) {
                            $taxBreakdown[$taxId] = 0;
                        }
                        $taxBreakdown[$taxId] += $taxAmount;
                    }
                }
            }

            $discountType = $validated['discount_type'] ?? 'no_discount';
            $discountPercent = floatval($validated['discount_percent'] ?? 0);
            $discountAmount = 0;

            if ($discountType === 'before_tax') {
                $discountAmount = $subtotal * ($discountPercent / 100);
            } elseif ($discountType === 'after_tax') {
                $discountAmount = ($subtotal + $totalTax) * ($discountPercent / 100);
            }

            $validated['subtotal'] = $subtotal;
            $validated['discount_amount'] = $discountAmount;
            $validated['tax_amount'] = $totalTax;
            $validated['total'] = $subtotal + $totalTax - $discountAmount;

            $proposal = Proposal::create($validated);

            // Save items with per-item tax_ids
            if (!empty($items)) {
                $this->saveItems($proposal, $items);
            }

            // Save tax breakdown to proposal_taxes table
            $taxNames = Tax::pluck('name', 'id')->toArray();
            foreach ($taxBreakdown as $taxId => $amount) {
                ProposalTax::create([
                    'proposal_id' => $proposal->id,
                    'tax_id' => $taxId,
                    'name' => $taxNames[$taxId] ?? 'Tax',
                    'rate' => $taxesMap[$taxId] ?? 0,
                    'amount' => $amount,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.sales.proposals.show', $proposal->id)
                ->with('success', 'Proposal created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Proposal creation error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Error creating proposal: ' . $e->getMessage());
        }
    }

    public function show(Proposal $proposal)
    {
        $proposal->load(['customer', 'items', 'taxes']);
        $taxes = Tax::where('active', 1)->get();
        return view('admin.sales.proposals.show', compact('proposal', 'taxes'));
    }

    public function edit(Proposal $proposal)
    {
        $customers = Customer::orderBy('name')->get();
        $users = Admin::orderBy('name')->get();
        $products = Product::where('is_active', true)->get();
        $taxes = Tax::where('active', 1)->orderBy('name')->get();
        $proposal->load(['items', 'taxes']);
        $nextNumber = null;

        return view('admin.sales.proposals.form', compact(
            'customers', 
            'users', 
            'products', 
            'taxes',
            'proposal', 
            'nextNumber'
        ))->with([
            'currencies' => $this->currencies,
            'statuses' => $this->statuses,
        ]);
    }

    public function update(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'status' => 'required|string',
            'date' => 'required|date',
            'open_till' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'currency' => 'required|string|max:10',
            'discount_type' => 'nullable|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'content' => 'nullable|string',
            'admin_note' => 'nullable|string',
            'assigned_to' => 'nullable|string',
            'tags' => 'nullable|string',
            'items' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            // Load all taxes for calculation
            $taxesMap = Tax::pluck('rate', 'id')->toArray();
            
            // Calculate totals from items
            $subtotal = 0;
            $totalTax = 0;
            $taxBreakdown = [];
            
            $items = $request->input('items', []);
            
            Log::info('Proposal update items received:', ['items' => $items]);
            
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
                        $taxAmount = ($amount * $taxRate) / 100;
                        $totalTax += $taxAmount;
                        
                        if (!isset($taxBreakdown[$taxId])) {
                            $taxBreakdown[$taxId] = 0;
                        }
                        $taxBreakdown[$taxId] += $taxAmount;
                    }
                }
            }

            $discountType = $validated['discount_type'] ?? 'no_discount';
            $discountPercent = floatval($validated['discount_percent'] ?? 0);
            $discountAmount = 0;

            if ($discountType === 'before_tax') {
                $discountAmount = $subtotal * ($discountPercent / 100);
            } elseif ($discountType === 'after_tax') {
                $discountAmount = ($subtotal + $totalTax) * ($discountPercent / 100);
            }

            $validated['subtotal'] = $subtotal;
            $validated['discount_amount'] = $discountAmount;
            $validated['tax_amount'] = $totalTax;
            $validated['total'] = $subtotal + $totalTax - $discountAmount;

            $proposal->update($validated);

            // Delete existing items and taxes, then recreate
            $proposal->items()->delete();
            $proposal->taxes()->delete();
            
            if (!empty($items)) {
                $this->saveItems($proposal, $items);
            }

            // Save tax breakdown to proposal_taxes table
            $taxNames = Tax::pluck('name', 'id')->toArray();
            foreach ($taxBreakdown as $taxId => $amount) {
                ProposalTax::create([
                    'proposal_id' => $proposal->id,
                    'tax_id' => $taxId,
                    'name' => $taxNames[$taxId] ?? 'Tax',
                    'rate' => $taxesMap[$taxId] ?? 0,
                    'amount' => $amount,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.sales.proposals.show', $proposal->id)
                ->with('success', 'Proposal updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Proposal update error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Error updating proposal: ' . $e->getMessage());
        }
    }

    public function destroy(Proposal $proposal)
    {
        try {
            $proposal->items()->delete();
            $proposal->taxes()->delete();
            $proposal->delete();
            
            return response()->json(['success' => true, 'message' => 'Proposal deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting proposal: ' . $e->getMessage()]);
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

    protected function saveItems(Proposal $proposal, array $items): void
    {
        foreach ($items as $index => $item) {
            if (empty($item['item_type'])) continue;

            $itemData = [
                'proposal_id' => $proposal->id,
                'item_type' => $item['item_type'],
                'sort_order' => $index,
            ];

            if ($item['item_type'] === 'product') {
                $productId = $item['product_id'] ?? null;
                $itemData['product_id'] = (!empty($productId) && $productId !== '') ? (int)$productId : null;
                
                $itemData['description'] = $item['description'] ?? '';
                $itemData['long_description'] = $item['long_description'] ?? null;
                $itemData['quantity'] = floatval($item['quantity'] ?? 1);
                $itemData['unit'] = $item['unit'] ?? 'PCS';
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
                
                Log::info('Saving proposal item with tax_ids:', [
                    'description' => $itemData['description'],
                    'original_tax_ids' => $item['tax_ids'] ?? '',
                    'parsed_tax_ids' => $taxIds,
                    'stored_tax_ids' => $itemData['tax_ids']
                ]);
                
            } elseif ($item['item_type'] === 'section') {
                $itemData['description'] = $item['description'] ?? 'Section';
            } elseif ($item['item_type'] === 'note') {
                $itemData['description'] = $item['description'] ?? '';
                $itemData['long_description'] = $item['long_description'] ?? $item['description'] ?? '';
            }

            try {
                $createdItem = ProposalItem::create($itemData);
                Log::info('Proposal item created successfully:', ['id' => $createdItem->id]);
            } catch (\Exception $e) {
                Log::error('Failed to create proposal item:', [
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



public function print(Proposal $proposal)
{
    $proposal->load(['customer', 'items']);
    
    // Get taxes for breakdown
    $taxesMap = \App\Models\Tax::where('active', 1)->pluck('name', 'id')->toArray();
    $taxRatesMap = \App\Models\Tax::where('active', 1)->pluck('rate', 'id')->toArray();
    
    // Calculate tax breakdown from items
    $taxBreakdown = [];
    foreach ($proposal->items as $item) {
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
    $company = $this->getCompanyDetails();
    
    $pdf = Pdf::loadView('admin.sales.proposals.print', [
        'proposal' => $proposal,
        'company' => $company,
        'taxBreakdown' => $taxBreakdown,
        'taxesMap' => $taxesMap,
        'taxRatesMap' => $taxRatesMap,
    ]);
    
    $pdf->setPaper('a4', 'portrait');
    
    return $pdf->stream("Proposal-{$proposal->proposal_number}.pdf");
}

/**
 * Get company details from options table
 */
private function getCompanyDetails(): array
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
 * Parse tax IDs from various formats
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










































































































    

    public function updateStatus(Request $request, Proposal $proposal)
    {
        $status = $request->input('status');
        
        $validStatuses = array_keys($this->statuses);
        if (!in_array($status, $validStatuses)) {
            return response()->json(['success' => false, 'message' => 'Invalid status']);
        }

        $proposal->status = $status;
        
        // Set timestamp fields
        if ($status === 'sent' && !$proposal->sent_at) {
            $proposal->sent_at = now();
        } elseif ($status === 'accepted' && !$proposal->accepted_at) {
            $proposal->accepted_at = now();
        } elseif ($status === 'declined' && !$proposal->declined_at) {
            $proposal->declined_at = now();
        }
        
        $proposal->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function duplicate(Proposal $proposal)
    {
        DB::beginTransaction();
        try {
            $newProposal = $proposal->replicate();
            $newProposal->proposal_number = Proposal::generateProposalNumber();
            $newProposal->status = 'draft';
            $newProposal->date = now();
            $newProposal->open_till = now()->addDays(30);
            $newProposal->sent_at = null;
            $newProposal->accepted_at = null;
            $newProposal->declined_at = null;
            $newProposal->save();

            // Duplicate items
            foreach ($proposal->items as $item) {
                $newItem = $item->replicate();
                $newItem->proposal_id = $newProposal->id;
                $newItem->save();
            }

            // Duplicate taxes
            foreach ($proposal->taxes as $tax) {
                $newTax = $tax->replicate();
                $newTax->proposal_id = $newProposal->id;
                $newTax->save();
            }

            DB::commit();
            return response()->json([
                'success' => true, 
                'message' => 'Proposal duplicated successfully',
                'redirect' => route('admin.sales.proposals.edit', $newProposal->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error duplicating proposal: ' . $e->getMessage()]);
        }
    }
}