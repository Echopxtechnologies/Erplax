<?php

namespace App\Crons;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class InvoiceCron
{
    /**
     * Send invoice payment reminders
     * 
     * @return string Status message
     */
    public function sendPaymentReminders(): string
    {
        Log::info("[Cron] Starting invoice payment reminders");
        
        $sentCount = 0;
        
        // Get overdue invoices
        // $overdueInvoices = \App\Models\Invoice::where('status', 'unpaid')
        //     ->where('due_date', '<', now())
        //     ->whereNull('reminder_sent_at')
        //     ->get();
        
        // foreach ($overdueInvoices as $invoice) {
        //     try {
        //         // Send reminder email
        //         send_mail(
        //             $invoice->customer->email,
        //             'Payment Reminder: Invoice #' . $invoice->number,
        //             view('emails.invoice-reminder', ['invoice' => $invoice])->render()
        //         );
        //         
        //         $invoice->update(['reminder_sent_at' => now()]);
        //         $sentCount++;
        //         
        //     } catch (\Exception $e) {
        //         Log::error("Failed to send reminder for invoice #{$invoice->id}: " . $e->getMessage());
        //     }
        // }
        
        Log::info("[Cron] Invoice reminders completed. Sent: {$sentCount}");
        
        return "Sent {$sentCount} payment reminders";
    }

    /**
     * Generate recurring invoices
     * 
     * @return string Status message
     */
    public function generateRecurringInvoices(): string
    {
        Log::info("[Cron] Generating recurring invoices");
        
        $generatedCount = 0;
        
        // Get recurring invoices due today
        // $recurringInvoices = \App\Models\RecurringInvoice::where('is_active', true)
        //     ->where('next_create_date', '<=', now())
        //     ->get();
        
        // foreach ($recurringInvoices as $recurring) {
        //     try {
        //         // Generate new invoice from recurring template
        //         $invoice = $recurring->generateInvoice();
        //         
        //         // Update next create date
        //         $recurring->updateNextCreateDate();
        //         
        //         $generatedCount++;
        //         
        //     } catch (\Exception $e) {
        //         Log::error("Failed to generate recurring invoice #{$recurring->id}: " . $e->getMessage());
        //     }
        // }
        
        Log::info("[Cron] Recurring invoices completed. Generated: {$generatedCount}");
        
        return "Generated {$generatedCount} recurring invoices";
    }

    /**
     * Mark overdue invoices
     * 
     * @return string Status message
     */
    public function markOverdueInvoices(): string
    {
        Log::info("[Cron] Marking overdue invoices");
        
        // $updated = \App\Models\Invoice::where('status', 'unpaid')
        //     ->where('due_date', '<', now())
        //     ->update(['status' => 'overdue']);
        
        $updated = 0; // Placeholder
        
        Log::info("[Cron] Marked {$updated} invoices as overdue");
        
        return "Marked {$updated} invoices as overdue";
    }
}