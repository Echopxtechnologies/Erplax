<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\Estimation;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoicesController extends Controller
{
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No invoices selected.'], 400);
        }

        try {
            // Delete items first
            InvoiceItem::whereIn('invoice_id', $ids)->delete();
            Invoice::whereIn('id', $ids)->delete();

            return response()->json(['success' => true, 'message' => count($ids) . ' invoice(s) deleted.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:draft,sent,paid,partially_paid,overdue,cancelled'
        ]);

        try {
            DB::beginTransaction();

            $invoice->update(['status' => $request->status]);

            // If marked as paid, create a payment record and update payment status
            if ($request->status === 'paid' && $invoice->amount_due > 0) {
                // Create payment record
                Payment::create([
                    'payment_number' => Payment::generatePaymentNumber(),
                    'invoice_id' => $invoice->id,
                    'customer_id' => $invoice->customer_id,
                    'amount' => $invoice->amount_due,
                    'payment_date' => now(),
                    'payment_method' => 'other',
                    'transaction_id' => null,
                    'notes' => 'Marked as paid manually',
                    'status' => 'completed',
                    'created_by' => auth()->user()->name ?? 'System',
                ]);

                $invoice->update([
                    'payment_status' => 'paid',
                    'amount_paid' => $invoice->total,
                    'amount_due' => 0,
                ]);
            }

            // If cancelled, keep the amounts as is but update status
            if ($request->status === 'cancelled') {
                $invoice->update([
                    'payment_status' => $invoice->amount_paid > 0 ? 'partial' : 'unpaid',
                ]);
            }

            // If reverted to draft
            if ($request->status === 'draft') {
                // Optionally reset payment status based on current amounts
                if ($invoice->amount_due <= 0) {
                    $invoice->update(['payment_status' => 'paid']);
                } elseif ($invoice->amount_paid > 0) {
                    $invoice->update(['payment_status' => 'partial']);
                } else {
                    $invoice->update(['payment_status' => 'unpaid']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice status updated to ' . ucfirst($request->status)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function recordPayment(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Create payment record
            Payment::create([
                'payment_number' => Payment::generatePaymentNumber(),
                'invoice_id' => $invoice->id,
                'customer_id' => $invoice->customer_id,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method ?? 'cash',
                'transaction_id' => $request->transaction_id,
                'notes' => $request->notes,
                'status' => 'completed',
                'created_by' => auth()->user()->name ?? 'System',
            ]);

            $newAmountPaid = $invoice->amount_paid + $request->amount;
            $newAmountDue = $invoice->total - $newAmountPaid;

            $paymentStatus = 'unpaid';
            if ($newAmountDue <= 0) {
                $paymentStatus = 'paid';
                $newAmountDue = 0;
            } elseif ($newAmountPaid > 0) {
                $paymentStatus = 'partial';
            }

            $invoice->update([
                'amount_paid' => $newAmountPaid,
                'amount_due' => $newAmountDue,
                'payment_status' => $paymentStatus,
                'status' => $paymentStatus === 'paid' ? 'paid' : $invoice->status,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment of ' . number_format($request->amount, 2) . ' recorded successfully.',
                'amount_paid' => $newAmountPaid,
                'amount_due' => $newAmountDue,
                'payment_status' => $paymentStatus,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error recording payment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function duplicate(Invoice $invoice)
    {
        $newInvoice = $invoice->replicate();
        $newInvoice->invoice_number = Invoice::generateInvoiceNumber();
        $newInvoice->status = 'draft';
        $newInvoice->payment_status = 'unpaid';
        $newInvoice->amount_paid = 0;
        $newInvoice->amount_due = $invoice->total;
        $newInvoice->date = now();
        $newInvoice->due_date = now()->addDays(30);
        $newInvoice->created_at = now();
        $newInvoice->save();

        // Duplicate items
        foreach ($invoice->items as $item) {
            $newItem = $item->replicate();
            $newItem->invoice_id = $newInvoice->id;
            $newItem->save();
        }

        return redirect()->route('admin.sales.invoices.edit', $newInvoice->id)
            ->with('success', 'Invoice duplicated successfully.');
    }

    public function createFromEstimation(Estimation $estimation)
    {
        try {
            DB::beginTransaction();

            // Generate invoice number
            $invoiceNumber = Invoice::generateInvoiceNumber();

            // Create invoice from estimation
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $estimation->customer_id,
                'estimation_id' => $estimation->id,
                'subject' => $estimation->subject,
                'date' => now(),
                'due_date' => now()->addDays(30),
                'currency' => $estimation->currency ?? 'INR',
                'subtotal' => $estimation->subtotal,
                'discount' => $estimation->discount ?? 0,
                'discount_type' => $estimation->discount_type ?? 'percentage',
                'tax' => $estimation->tax ?? 0,
                'total' => $estimation->total,
                'amount_paid' => 0,
                'amount_due' => $estimation->total,
                'status' => 'draft',
                'payment_status' => 'unpaid',
                'content' => $estimation->content,
                'terms_conditions' => $estimation->terms_conditions,
                'admin_note' => $estimation->admin_note ?? null,
                'email' => $estimation->email,
                'phone' => $estimation->phone,
                'address' => $estimation->address,
                'city' => $estimation->city,
                'state' => $estimation->state,
                'zip_code' => $estimation->zip_code,
                'country' => $estimation->country,
                'assigned_to' => $estimation->assigned_to,
                'created_by' => auth()->user()->name ?? 'System',
            ]);

            // Copy all items (products, sections, notes)
            foreach ($estimation->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_type' => $item->item_type ?? 'product',
                    'product_id' => $item->product_id,
                    'description' => $item->description,
                    'long_description' => $item->long_description,
                    'quantity' => $item->quantity ?? 0,
                    'unit' => $item->unit ?? 'pcs',
                    'rate' => $item->rate ?? 0,
                    'tax_rate' => $item->tax_rate ?? 0,
                    'amount' => $item->amount ?? 0,
                ]);
            }

            // Update estimation status to accepted/converted
            $estimation->update(['status' => 'accepted']);

            DB::commit();

            return redirect()->route('admin.sales.invoices.show', $invoice->id)
                ->with('success', 'Invoice created successfully from estimation.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating invoice: ' . $e->getMessage());
        }
    }

    public function searchProducts(Request $request)
    {
        $search = $request->input('q', '');
        
        $products = Product::where('is_active', true)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->limit(20)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->sale_price,
                    'description' => $product->short_description,
                    'tax_rate' => $product->total_tax_rate ?? 0,
                    'unit' => '',
                ];
            });

        return response()->json($products);
    }

    public function getProduct(Product $product)
    {
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->sale_price,
            'description' => $product->short_description,
            'tax_rate' => $product->total_tax_rate ?? 0,
            'unit' => '',
        ]);
    }










}