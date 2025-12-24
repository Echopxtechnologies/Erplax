<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Option;
use App\Models\Customer;

class PaymentReceiptController extends Controller
{
    public function show(Payment $payment)
    {
        // Load payment with invoice and customer
        $payment->load(['invoice.customer', 'invoice.payments']);
        
        // ✅ Get customer with fallback
        $customer = null;
        
        if ($payment->customer_id) {
            $customer = Customer::find($payment->customer_id);
        }
        
        // Fallback to invoice customer
        if (!$customer && $payment->invoice && $payment->invoice->customer_id) {
            $customer = $payment->invoice->customer;
        }
        
        // Get company data from options table
        $company = [
            'name' => Option::where('key', 'company_name')->value('value') ?? 'Echo',
            'address' => Option::where('key', 'company_address')->value('value') ?? '',
            'phone' => Option::where('key', 'company_phone')->value('value') ?? '',
            'email' => Option::where('key', 'company_email')->value('value') ?? '',
            'gst' => Option::where('key', 'company_gst')->value('value') ?? '',
        ];
        
        // Get previous payments (excluding current one)
        $previousPayments = $payment->invoice->payments()
            ->where('id', '!=', $payment->id)
            ->where('payment_date', '<', $payment->payment_date)
            ->orderBy('payment_date', 'asc')
            ->get();
        
        return view('admin.sales.receipts.show', compact('payment', 'customer', 'company', 'previousPayments'));
    }
}