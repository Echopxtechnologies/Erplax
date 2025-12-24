<?php

namespace Modules\Website\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Website\Models\Ecommerce\WebsiteOrder;
use Modules\Website\Models\Ecommerce\WebsiteOrderStatusHistory;

class AdminOrderController extends AdminController
{
    /**
     * List all orders
     */
    public function index(Request $request)
    {
        $query = WebsiteOrder::with(['customer', 'items'])
            ->orderBy('created_at', 'desc');
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_no', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }
        
        $orders = $query->paginate(20)->withQueryString();
        
        // Stats
        $stats = [
            'total' => WebsiteOrder::count(),
            'pending' => WebsiteOrder::where('status', 'pending')->count(),
            'processing' => WebsiteOrder::where('status', 'processing')->count(),
            'shipped' => WebsiteOrder::where('status', 'shipped')->count(),
            'delivered' => WebsiteOrder::where('status', 'delivered')->count(),
            'cancelled' => WebsiteOrder::where('status', 'cancelled')->count(),
            'unpaid' => WebsiteOrder::where('payment_status', 'pending')->count(),
            'revenue' => WebsiteOrder::where('payment_status', 'paid')->sum('total'),
        ];
        
        return view('website::admin.orders.index', compact('orders', 'stats'));
    }
    
    /**
     * View order details
     */
    public function show($id)
    {
        $order = WebsiteOrder::with(['items.product', 'items.variation', 'statusHistory', 'customer'])
            ->findOrFail($id);
        
        // Get invoice if exists - check for different possible column names
        $invoice = null;
        if (DB::getSchemaBuilder()->hasTable('invoices')) {
            // Try different column names that might link to order
            if (\Schema::hasColumn('invoices', 'website_order_id')) {
                $invoice = DB::table('invoices')->where('website_order_id', $order->id)->first();
            } elseif (\Schema::hasColumn('invoices', 'reference_no')) {
                $invoice = DB::table('invoices')->where('reference_no', $order->order_no)->first();
            }
            // Skip if no matching column - invoice linking is optional
        }
        
        return view('website::admin.orders.show', compact('order', 'invoice'));
    }
    
    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,returned',
            'comment' => 'nullable|string|max:500',
        ]);
        
        $order = WebsiteOrder::findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;
        
        // Don't allow changing from delivered/cancelled without special permission
        if (in_array($oldStatus, ['delivered', 'cancelled']) && $oldStatus !== $newStatus) {
            return back()->with('error', 'Cannot change status of delivered or cancelled orders');
        }
        
        DB::beginTransaction();
        try {
            // Update order status
            $order->update([
                'status' => $newStatus,
            ]);
            
            // Add tracking number if shipped
            if ($newStatus === 'shipped' && $request->filled('tracking_number')) {
                $order->update([
                    'tracking_number' => $request->tracking_number,
                    'carrier' => $request->carrier,
                    'shipped_at' => now(),
                ]);
            }
            
            // Mark delivered
            if ($newStatus === 'delivered') {
                $order->update(['delivered_at' => now()]);
                
                // If COD, prompt for payment confirmation
                if (in_array($order->payment_method, ['cash', 'cod']) && $order->payment_status === 'pending') {
                    // Keep payment pending - admin needs to confirm separately
                }
            }
            
            // Handle cancellation - restore stock
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                $this->restoreStockForOrder($order);
            }
            
            // Add status history
            WebsiteOrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $newStatus,
                'comment' => $request->comment ?? "Status changed from {$oldStatus} to {$newStatus}",
                'created_by' => Auth::id(),
            ]);
            
            DB::commit();
            return back()->with('success', 'Order status updated successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }
    
    /**
     * Update payment status
     */
    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded,partial_refund',
            'transaction_id' => 'nullable|string|max:100',
            'payment_note' => 'nullable|string|max:500',
        ]);
        
        $order = WebsiteOrder::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $updateData = [
                'payment_status' => $request->payment_status,
            ];
            
            if ($request->payment_status === 'paid') {
                $updateData['paid_at'] = now();
            }
            
            if ($request->filled('transaction_id')) {
                $updateData['transaction_id'] = $request->transaction_id;
            }
            
            $order->update($updateData);
            
            // Update invoice if exists - safely check columns
            if (DB::getSchemaBuilder()->hasTable('invoices')) {
                $query = DB::table('invoices');
                if (\Schema::hasColumn('invoices', 'website_order_id')) {
                    $query->where('website_order_id', $order->id);
                } elseif (\Schema::hasColumn('invoices', 'reference_no')) {
                    $query->where('reference_no', $order->order_no);
                } else {
                    $query = null; // No matching column
                }
                
                if ($query) {
                    $query->update([
                        'payment_status' => $request->payment_status === 'paid' ? 'paid' : 'unpaid',
                        'updated_at' => now(),
                    ]);
                }
            }
            
            // Add status history
            WebsiteOrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $order->status,
                'comment' => "Payment marked as {$request->payment_status}" . 
                    ($request->payment_note ? ": {$request->payment_note}" : ''),
                'created_by' => Auth::id(),
            ]);
            
            DB::commit();
            return back()->with('success', 'Payment status updated successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update payment status: ' . $e->getMessage());
        }
    }
    
    /**
     * Confirm delivery and payment (for COD)
     */
    public function confirmDelivery(Request $request, $id)
    {
        $order = WebsiteOrder::findOrFail($id);
        
        if ($order->status === 'delivered' && $order->payment_status === 'paid') {
            return back()->with('error', 'Order already delivered and paid');
        }
        
        DB::beginTransaction();
        try {
            $updateData = ['status' => 'delivered', 'delivered_at' => now()];
            
            // If COD and confirm payment checkbox checked
            if ($request->has('confirm_payment') && in_array($order->payment_method, ['cash', 'cod'])) {
                $updateData['payment_status'] = 'paid';
                $updateData['paid_at'] = now();
            }
            
            $order->update($updateData);
            
            // Update invoice - safely check columns
            if ($request->has('confirm_payment') && DB::getSchemaBuilder()->hasTable('invoices')) {
                $query = DB::table('invoices');
                if (\Schema::hasColumn('invoices', 'website_order_id')) {
                    $query->where('website_order_id', $order->id);
                } elseif (\Schema::hasColumn('invoices', 'reference_no')) {
                    $query->where('reference_no', $order->order_no);
                } else {
                    $query = null;
                }
                
                if ($query) {
                    $query->update([
                        'payment_status' => 'paid',
                        'status' => 'sent',
                        'updated_at' => now(),
                    ]);
                }
            }
            
            // Add status history
            $comment = 'Order delivered';
            if ($request->has('confirm_payment')) {
                $comment .= ' and payment collected';
            }
            if ($request->filled('delivery_note')) {
                $comment .= '. Note: ' . $request->delivery_note;
            }
            
            WebsiteOrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'delivered',
                'comment' => $comment,
                'created_by' => Auth::id(),
            ]);
            
            DB::commit();
            return back()->with('success', 'Delivery confirmed successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to confirm delivery: ' . $e->getMessage());
        }
    }
    
    /**
     * Add shipping info
     */
    public function addShipping(Request $request, $id)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:100',
            'carrier' => 'nullable|string|max:100',
        ]);
        
        $order = WebsiteOrder::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $order->update([
                'status' => 'shipped',
                'tracking_number' => $request->tracking_number,
                'carrier' => $request->carrier,
                'shipped_at' => now(),
            ]);
            
            WebsiteOrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'shipped',
                'comment' => "Shipped via {$request->carrier}. Tracking: {$request->tracking_number}",
                'created_by' => Auth::id(),
            ]);
            
            DB::commit();
            return back()->with('success', 'Shipping info added successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to add shipping info: ' . $e->getMessage());
        }
    }
    
    /**
     * Print invoice/packing slip
     */
    public function printInvoice($id)
    {
        $order = WebsiteOrder::with(['items.product', 'items.variation'])->findOrFail($id);
        
        // Get store info from settings
        $settings = \Modules\Website\Models\Website\WebsiteSetting::instance();
        
        return view('website::admin.orders.invoice', compact('order', 'settings'));
    }
    
    /**
     * Restore stock when order cancelled
     */
    protected function restoreStockForOrder($order)
    {
        foreach ($order->items as $item) {
            $productId = $item->product_id;
            $variationId = $item->variation_id;
            $qty = $item->quantity;
            
            // Get product for unit_id
            $product = DB::table('products')->find($productId);
            $unitId = $product->unit_id ?? 1;
            
            // Determine stock column
            $stockColumn = 'qty';
            if (\Illuminate\Support\Facades\Schema::hasTable('stock_levels')) {
                if (\Illuminate\Support\Facades\Schema::hasColumn('stock_levels', 'quantity')) {
                    $stockColumn = 'quantity';
                } elseif (\Illuminate\Support\Facades\Schema::hasColumn('stock_levels', 'qty')) {
                    $stockColumn = 'qty';
                }
            }
            
            if ($variationId) {
                if (\Illuminate\Support\Facades\Schema::hasTable('stock_levels')) {
                    DB::table('stock_levels')
                        ->where('product_id', $productId)
                        ->where('variation_id', $variationId)
                        ->increment($stockColumn, $qty);
                }
                
                if (\Illuminate\Support\Facades\Schema::hasColumn('product_variations', 'stock_qty')) {
                    DB::table('product_variations')
                        ->where('id', $variationId)
                        ->increment('stock_qty', $qty);
                }
            } else {
                if (\Illuminate\Support\Facades\Schema::hasTable('stock_levels')) {
                    DB::table('stock_levels')
                        ->where('product_id', $productId)
                        ->whereNull('variation_id')
                        ->increment($stockColumn, $qty);
                }
                
                if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'stock_qty')) {
                    DB::table('products')
                        ->where('id', $productId)
                        ->increment('stock_qty', $qty);
                }
            }
            
            // Add stock movement
            if (\Illuminate\Support\Facades\Schema::hasTable('stock_movements')) {
                DB::table('stock_movements')->insert([
                    'reference_no' => 'WEB-CAN-' . $order->order_no,
                    'product_id' => $productId,
                    'variation_id' => $variationId,
                    'warehouse_id' => 1,
                    'unit_id' => $unitId,
                    'qty' => $qty,
                    'base_qty' => $qty,
                    'movement_type' => 'IN',
                    'reference_type' => 'RETURN',
                    'reason' => 'Order Cancelled by Admin',
                    'notes' => 'Order cancelled: ' . $order->order_no,
                    'created_by' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    // ==================== REVIEW MANAGEMENT ====================

    /**
     * Reviews list
     */
    public function reviews(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = \Modules\Website\Models\Ecommerce\ProductReview::with('product')
            ->orderByDesc('created_at');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $reviews = $query->paginate(20);
        
        $stats = [
            'total' => \Modules\Website\Models\Ecommerce\ProductReview::count(),
            'pending' => \Modules\Website\Models\Ecommerce\ProductReview::where('status', 'pending')->count(),
            'approved' => \Modules\Website\Models\Ecommerce\ProductReview::where('status', 'approved')->count(),
            'rejected' => \Modules\Website\Models\Ecommerce\ProductReview::where('status', 'rejected')->count(),
        ];
        
        return view('website::admin.reviews.index', compact('reviews', 'status', 'stats'));
    }

    /**
     * Approve review
     */
    public function approveReview($id)
    {
        $review = \Modules\Website\Models\Ecommerce\ProductReview::findOrFail($id);
        $review->update(['status' => 'approved']);
        
        return redirect()->back()->with('success', 'Review approved successfully!');
    }

    /**
     * Reject review
     */
    public function rejectReview($id)
    {
        $review = \Modules\Website\Models\Ecommerce\ProductReview::findOrFail($id);
        $review->update(['status' => 'rejected']);
        
        return redirect()->back()->with('success', 'Review rejected.');
    }

    /**
     * Reply to review
     */
    public function replyReview(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);
        
        $review = \Modules\Website\Models\Ecommerce\ProductReview::findOrFail($id);
        $review->update([
            'admin_reply' => $request->reply,
            'replied_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Reply added successfully!');
    }

    /**
     * Delete review
     */
    public function deleteReview($id)
    {
        $review = \Modules\Website\Models\Ecommerce\ProductReview::findOrFail($id);
        $review->delete();
        
        return redirect()->back()->with('success', 'Review deleted.');
    }
}
