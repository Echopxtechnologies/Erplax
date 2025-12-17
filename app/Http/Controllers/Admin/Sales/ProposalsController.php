<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Proposal;
use App\Models\ProposalItem;
use Modules\Inventory\Models\Product;
use App\Models\Tax;
use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProposalsController extends AdminController
{



    
    /**
     * Bulk delete proposals
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:proposals,id'
        ]);

        try {
            Proposal::whereIn('id', $request->ids)->delete();
            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' proposal(s) deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting proposals.'
            ], 500);
        }
    }

    /**
     * Duplicate a proposal
     */
    public function duplicate(Proposal $proposal)
    {
        DB::beginTransaction();
        try {
            $newProposal = $proposal->replicate();
            $newProposal->proposal_number = Proposal::generateProposalNumber();
            $newProposal->status = Proposal::STATUS_DRAFT;
            $newProposal->date = now();
            $newProposal->open_till = now()->addDays(30);
            $newProposal->sent_at = null;
            $newProposal->accepted_at = null;
            $newProposal->declined_at = null;
            $newProposal->created_by = null;
            $newProposal->save();

            // Duplicate items
            foreach ($proposal->items as $item) {
                $newItem = $item->replicate();
                $newItem->proposal_id = $newProposal->id;
                $newItem->save();
            }

            $newProposal->calculateTotals();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Proposal duplicated successfully.',
                'redirect' => route('admin.sales.proposals.edit', $newProposal->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error duplicating proposal.'
            ], 500);
        }
    }





public function searchProducts(Request $request)
{
    $search = $request->input('q', '');
    
    $query = \Modules\Inventory\Models\Product::where('is_active', true);
    
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
            'description' => $product->short_description ?? '',
            'unit' => $product->unit ? $product->unit->short_name : 'PCS',
        ];
    });

    return response()->json($products);
}

public function getCustomer($id)
{
    $customer = Customer::find($id);
    return response()->json($customer);
}







    /**
     * Update proposal status
     */
    public function updateStatus(Request $request, Proposal $proposal)
    {
        $request->validate([
            'status' => 'required|string|in:' . implode(',', array_keys(Proposal::getStatuses()))
        ]);

        try {
            $oldStatus = $proposal->status;
            $proposal->status = $request->status;

            // Track status timestamps
            if ($request->status === Proposal::STATUS_SENT && $oldStatus !== Proposal::STATUS_SENT) {
                $proposal->sent_at = now();
            } elseif ($request->status === Proposal::STATUS_ACCEPTED) {
                $proposal->accepted_at = now();
            } elseif ($request->status === Proposal::STATUS_DECLINED) {
                $proposal->declined_at = now();
            }

            $proposal->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status.'
            ], 500);
        }
    }

    /**
     * Search products for order lines
     */
   

    /**
     * Get product details
     */
    public function getProduct(Product $product)
    {
        $product->load('taxes');
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'description' => $product->description,
            'long_description' => $product->long_description ?? '',
            'price' => $product->price,
            'unit' => $product->unit ?? '',
            'tax_ids' => $product->taxes->pluck('id')->toArray(),
        ]);
    }


    public function storeUser(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ]);
    
    try {
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'message' => 'User created successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error creating user: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Get taxes list
     */
    public function getTaxes()
    {
        $taxes = Tax::where('active', true)->orderBy('name')->get(['id', 'name', 'rate']);
        return response()->json($taxes);
    }

    /**
     * Send proposal via email
     */
    public function send(Request $request, Proposal $proposal)
    {
        $request->validate([
            'email' => 'required|email',
            'cc' => 'nullable|string',
            'subject' => 'required|string',
            'message' => 'nullable|string',
        ]);

        try {
            // Email sending logic - implement based on your email service
            // Mail::to($request->email)->send(new ProposalMail($proposal, $request->message));

            $proposal->status = Proposal::STATUS_SENT;
            $proposal->sent_at = now();
            $proposal->save();

            return response()->json([
                'success' => true,
                'message' => 'Proposal sent successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error sending proposal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customer details for autofill
     */
    public function getCustomerDetails(Customer $customer)
    {
        return response()->json([
            'id' => $customer->id,
            'company' => $customer->company,
            'name' => $customer->name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'address' => $customer->address,
            'city' => $customer->city,
            'state' => $customer->state,
            'country' => $customer->country,
            'zip_code' => $customer->zip_code,
        ]);
    }

    /**
     * Convert proposal to invoice (placeholder for future)
     */
    public function convertToInvoice(Proposal $proposal)
    {
        // Implementation for converting proposal to invoice
        return response()->json([
            'success' => false,
            'message' => 'This feature will be available soon.'
        ]);
    }
}
