<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Estimation;
use App\Models\EstimationItem;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Modules\Inventory\Models\Product;
class EstimationsController extends AdminController
{
    /**
     * Update estimation status
     */
 public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|string|in:draft,sent,accepted,declined,rejected,expired,approved'
    ]);

    try {
        $estimation = Estimation::findOrFail($id);
        $estimation->status = $request->status;
        $estimation->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error updating status: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Bulk delete estimations
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            
            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No estimations selected'
                ], 400);
            }

            Estimation::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' estimation(s) deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting estimations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplicate an estimation
     */
    public function duplicate(Estimation $estimation)
    {
        try {
            // Create new estimation
            $newEstimation = $estimation->replicate();
            $newEstimation->estimation_number = Estimation::generateEstimationNumber();
            $newEstimation->status = 'draft';
            $newEstimation->date = now()->format('Y-m-d');
            $newEstimation->save();

            // Duplicate items
            foreach ($estimation->items as $item) {
                $newItem = $item->replicate();
                $newItem->estimation_id = $newEstimation->id;
                $newItem->save();
            }

            // Recalculate totals
            $newEstimation->calculateTotals();

            return redirect()->route('admin.sales.estimations.edit', $newEstimation->id)
                ->with('success', 'Estimation duplicated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error duplicating estimation: ' . $e->getMessage());
        }
    }





    
    /**
     * Create estimation from proposal
     */
    public function createFromProposal(Proposal $proposal)
    {
        try {
            // Create new estimation from proposal
            $estimation = new Estimation();
            $estimation->estimation_number = Estimation::generateEstimationNumber();
            $estimation->proposal_id = $proposal->id;
            $estimation->subject = $proposal->subject;
            $estimation->customer_id = $proposal->customer_id;
            $estimation->status = 'draft';
            $estimation->assigned_to = $proposal->assigned_to;
            $estimation->date = now()->format('Y-m-d');
            $estimation->valid_until = $proposal->valid_until;
            $estimation->address = $proposal->address;
            $estimation->city = $proposal->city;
            $estimation->state = $proposal->state;
            $estimation->country = $proposal->country;
            $estimation->zip_code = $proposal->zip_code;
            $estimation->email = $proposal->email;
            $estimation->phone = $proposal->phone;
            $estimation->currency = $proposal->currency;
            $estimation->subtotal = $proposal->subtotal;
            $estimation->discount_type = $proposal->discount_type;
            $estimation->discount_percent = $proposal->discount_percent;
            $estimation->discount_amount = $proposal->discount_amount;
            $estimation->tax_amount = $proposal->tax_amount;
            $estimation->adjustment = $proposal->adjustment;
            $estimation->total = $proposal->total;
            $estimation->content = $proposal->content;
            $estimation->tags = $proposal->tags;
            $estimation->admin_note = $proposal->admin_note;
            $estimation->save();

            // Copy items from proposal
            foreach ($proposal->items as $item) {
                EstimationItem::create([
                    'estimation_id' => $estimation->id,
                    'item_type' => $item->item_type,
                    'product_id' => $item->product_id,
                    'description' => $item->description,
                    'long_description' => $item->long_description,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'rate' => $item->rate,
                    'tax_rate' => $item->tax_rate,
                    'tax_name' => $item->tax_name,
                    'tax_amount' => $item->tax_amount,
                    'amount' => $item->amount,
                    'total' => $item->total,
                    'sort_order' => $item->sort_order,
                ]);
            }

            return redirect()->route('admin.sales.estimations.edit', $estimation->id)
                ->with('success', 'Estimation created from proposal successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating estimation: ' . $e->getMessage());
        }
    }

    /**
     * Search products (for form)
     */
   public function searchProducts(Request $request)
{
    $search = $request->input('q', '');
    
    $products = Product::with('tax')
        ->where('name', 'like', "%{$search}%")
        ->orWhere('sku', 'like', "%{$search}%")
        ->limit(20)
        ->get()
        ->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => $product->price ?? 0,
                'unit' => $product->unit ?? 'pcs',
                'tax_rate' => $product->tax ? $product->tax->rate : 0,
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
     * Get single product details
     */
    public function getProduct($id)
    {
        $product = \App\Models\Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->sale_price,
            'description' => $product->short_description ?? '',
            'unit' => '',
            'tax_rate' => $product->total_tax_rate ?? 0,
        ]);
    }
}