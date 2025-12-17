<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Option;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePdfController extends Controller
{
    /**
     * Generate PDF for invoice
     */
    public function generatePdf(Invoice $invoice)
    {
        $invoice->load(['customer', 'items', 'payments']);

        // Fetch company details from options table
        $company = $this->getCompanyDetails();

        // Fetch general settings
        $settings = $this->getGeneralSettings();

        $pdf = Pdf::loadView('admin.sales.invoices.pdf', [
            'invoice' => $invoice,
            'company' => $company,
            'settings' => $settings,
        ]);

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Return PDF for download or view
        return $pdf->stream('Invoice-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Download PDF
     */
    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['customer', 'items', 'payments']);

        $company = $this->getCompanyDetails();
        $settings = $this->getGeneralSettings();

        $pdf = Pdf::loadView('admin.sales.invoices.pdf', [
            'invoice' => $invoice,
            'company' => $company,
            'settings' => $settings,
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Invoice-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Get company details from options table
     */
  private function getCompanyDetails()
{
    return [
        'name' => Option::get('company_name', 'Your Company Name'),
        'email' => Option::get('company_email', ''),
        'phone' => Option::get('company_phone', ''),
        'address' => Option::get('company_address', ''),
        'website' => Option::get('company_website', ''),
        'gst' => Option::get('company_gst', ''),
        'logo' => Option::get('company_logo', ''),
    ];
}

    /**
     * Get general settings from options table
     */
 private function getGeneralSettings()
{
    return [
        'currency_symbol' => Option::get('currency_symbol', '₹'),
        'currency_code' => Option::get('currency_code', 'INR'),
        'date_format' => Option::get('date_format', 'd/m/Y'),
        'timezone' => Option::get('site_timezone', 'Asia/Kolkata'),
    ];
}
}
