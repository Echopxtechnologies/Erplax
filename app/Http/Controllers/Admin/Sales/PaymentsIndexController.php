<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Traits\DataTable;
use Illuminate\Http\Request;

class PaymentsIndexController extends Controller
{
    use DataTable;

    protected $model = Payment::class;
    protected $with = ['invoice', 'customer'];
    protected $searchable = ['payment_number', 'invoice.invoice_number', 'customer.name', 'transaction_id'];
    protected $sortable = ['id', 'payment_number', 'amount', 'payment_date', 'payment_method', 'status'];
    protected $filterable = ['payment_method', 'status'];
    protected $uniqueField = 'payment_number';
    protected $exportTitle = 'Payments Export';

    public function index()
    {
        $stats = [
            'total' => Payment::count(),
            'total_amount' => Payment::completed()->sum('amount'),
            'today' => Payment::whereDate('payment_date', today())->sum('amount'),
            'this_month' => Payment::whereMonth('payment_date', now()->month)->whereYear('payment_date', now()->year)->sum('amount'),
            'cash' => Payment::where('payment_method', 'cash')->completed()->sum('amount'),
            'bank' => Payment::where('payment_method', 'bank_transfer')->completed()->sum('amount'),
            'upi' => Payment::where('payment_method', 'upi')->completed()->sum('amount'),
        ];
        
        return view('admin.sales.payments.index', compact('stats'));
    }

    protected function mapRow($item)
    {
        return [
            'id' => $item->id,
            'payment_number' => $item->payment_number,
            'invoice_number' => $item->invoice->invoice_number ?? '-',
            'invoice_id' => $item->invoice_id,
            'customer_name' => $item->customer?->name ?? '-',
            'amount' => number_format($item->amount, 2),
            'payment_date' => $item->payment_date ? date('d M Y', strtotime($item->payment_date)) : '-',
            'payment_method' => $item->payment_method,
            'payment_method_label' => $item->payment_method_label,
            'transaction_id' => $item->transaction_id ?? '-',
            'status' => $item->status,
            '_show_url' => route('admin.sales.payments.show', $item->id),
            '_invoice_url' => $item->invoice_id ? route('admin.sales.invoices.show', $item->invoice_id) : null,
            '_delete_url' => route('admin.sales.payments.destroy', $item->id),
        ];
    }

    protected function mapExportRow($item)
    {
        return [
            'ID' => $item->id,
            'Payment #' => $item->payment_number,
            'Invoice #' => $item->invoice->invoice_number ?? '',
            'Customer' => $item->customer?->name ?? '',
            'Amount' => $item->amount,
            'Payment Date' => $item->payment_date ? date('d M Y', strtotime($item->payment_date)) : '',
            'Payment Method' => $item->payment_method_label,
            'Transaction ID' => $item->transaction_id ?? '',
            'Status' => ucfirst($item->status),
            'Notes' => $item->notes ?? '',
        ];
    }

    public function data(Request $request)
    {
        return $this->handleData($request);
    }

    public function show(Payment $payment)
    {
        $payment->load(['invoice.customer', 'customer']);
        return view('admin.sales.payments.show', compact('payment'));
    }

    public function destroy(Payment $payment)
    {
        try {
            $invoice = $payment->invoice;
            $amount = $payment->amount;
            
            // Delete the payment
            $payment->delete();
            
            // Update invoice amounts
            if ($invoice) {
                $invoice->amount_paid -= $amount;
                $invoice->amount_due = $invoice->total - $invoice->amount_paid;
                
                if ($invoice->amount_paid <= 0) {
                    $invoice->payment_status = 'unpaid';
                    $invoice->amount_paid = 0;
                } elseif ($invoice->amount_due <= 0) {
                    $invoice->payment_status = 'paid';
                } else {
                    $invoice->payment_status = 'partial';
                }
                
                $invoice->save();
            }
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Payment deleted and invoice updated']);
            }
            
            return redirect()->route('admin.sales.payments.index')
                ->with('success', 'Payment deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}