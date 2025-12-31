<?php

namespace Modules\StudentSponsorship\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\StudentSponsorship\Models\PaymentReceiptTemplate;
use Modules\StudentSponsorship\Models\SponsorPayment;
use Modules\StudentSponsorship\Helpers\PaymentReceiptHelper;
use Illuminate\Http\Request;

class PaymentReceiptController extends AdminController
{
    /**
     * Show receipt templates management page
     */
    public function index()
    {
        $templates = PaymentReceiptTemplate::orderBy('currency')->get();
        
        return view('studentsponsorship::receipts.index', compact('templates'));
    }

    /**
     * Edit a receipt template
     */
    public function edit($currency)
    {
        $template = PaymentReceiptTemplate::getOrCreateByCurrency($currency);
        
        return view('studentsponsorship::receipts.edit', compact('template'));
    }

    /**
     * Update a receipt template
     */
    public function update(Request $request, $currency)
    {
        $template = PaymentReceiptTemplate::getOrCreateByCurrency($currency);
        
        $validated = $request->validate([
            'currency_name' => 'nullable|string|max:100',
            'currency_symbol' => 'nullable|string|max:10',
            'organization_name' => 'required|string|max:255',
            'organization_address' => 'nullable|string|max:500',
            'organization_phone' => 'nullable|string|max:50',
            'organization_email' => 'nullable|email|max:255',
            'organization_website' => 'nullable|string|max:255',
            'receipt_title' => 'required|string|max:100',
            'header_text' => 'nullable|string|max:500',
            'footer_text' => 'nullable|string|max:500',
            'thank_you_message' => 'nullable|string|max:500',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:100',
            'bank_branch' => 'nullable|string|max:255',
            'bank_swift_code' => 'nullable|string|max:20',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);
        
        $template->update($validated);
        
        return redirect()
            ->route('admin.studentsponsorship.receipts.index')
            ->with('success', "Receipt template for {$currency} updated successfully!");
    }

    /**
     * Create a new receipt template
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'currency' => 'required|string|size:3|unique:payment_receipt_templates,currency',
            'currency_name' => 'nullable|string|max:100',
            'currency_symbol' => 'nullable|string|max:10',
        ]);
        
        $template = PaymentReceiptTemplate::create([
            'currency' => strtoupper($validated['currency']),
            'currency_name' => $validated['currency_name'] ?? $validated['currency'],
            'currency_symbol' => $validated['currency_symbol'] ?? $validated['currency'],
            'organization_name' => 'Student Sponsorship Program',
            'receipt_title' => 'Payment Receipt',
            'thank_you_message' => 'Thank you for your generous contribution.',
            'is_active' => true,
        ]);
        
        return redirect()
            ->route('admin.studentsponsorship.receipts.edit', $template->currency)
            ->with('success', "Receipt template for {$template->currency} created! Please complete the configuration.");
    }

    /**
     * Delete a receipt template
     */
    public function destroy($currency)
    {
        $template = PaymentReceiptTemplate::where('currency', strtoupper($currency))->first();
        
        if ($template) {
            $template->delete();
            return response()->json(['success' => true, 'message' => "Template for {$currency} deleted"]);
        }
        
        return response()->json(['success' => false, 'message' => 'Template not found'], 404);
    }

    /**
     * Preview receipt template with sample data
     */
    public function preview($currency)
    {
        $template = PaymentReceiptTemplate::getOrCreateByCurrency($currency);
        
        // Create a mock payment for preview
        $mockPayment = new SponsorPayment();
        $mockPayment->id = 12345;
        $mockPayment->amount = 50000;
        $mockPayment->currency = $currency;
        $mockPayment->payment_date = date('Y-m-d');
        $mockPayment->payment_method = 'bank_transfer';
        $mockPayment->reference_number = 'REF-2024-001';
        
        // Create mock sponsor
        $mockSponsor = new \stdClass();
        $mockSponsor->id = 100;
        $mockSponsor->name = 'John Smith';
        $mockSponsor->email = 'john.smith@example.com';
        
        // Generate HTML using template
        $html = $this->generatePreviewHtml($template, $mockPayment, $mockSponsor);
        
        return response($html)->header('Content-Type', 'text/html');
    }

    /**
     * Preview actual payment receipt
     */
    public function previewPayment($paymentId)
    {
        $payment = SponsorPayment::with('transaction.sponsor')->findOrFail($paymentId);
        
        $html = PaymentReceiptHelper::generateReceiptHtml($payment);
        
        return response($html)->header('Content-Type', 'text/html');
    }

    /**
     * Download payment receipt
     */
    public function downloadPayment($paymentId)
    {
        $payment = SponsorPayment::with('transaction.sponsor')->findOrFail($paymentId);
        
        return PaymentReceiptHelper::downloadReceipt($payment);
    }

    /**
     * Generate preview HTML for template
     */
    private function generatePreviewHtml($template, $mockPayment, $mockSponsor): string
    {
        $receiptNumber = 'RCP-' . str_pad($mockPayment->id, 6, '0', STR_PAD_LEFT);
        $paymentDate = date('F d, Y', strtotime($mockPayment->payment_date));
        $amount = $template->formatAmount($mockPayment->amount);
        
        $primaryColor = $template->primary_color ?: '#2563eb';
        $secondaryColor = $template->secondary_color ?: '#1e40af';
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt Preview - ' . $template->currency . '</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", Arial, sans-serif; font-size: 14px; color: #333; background: #f5f5f5; padding: 20px; }
        .preview-banner { background: #fef3c7; border: 1px solid #f59e0b; color: #92400e; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: 600; max-width: 800px; margin-left: auto; margin-right: auto; }
        .receipt { max-width: 800px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, ' . $primaryColor . ', ' . $secondaryColor . '); color: #fff; padding: 30px 40px; }
        .header-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
        .org-name { font-size: 24px; font-weight: 700; }
        .org-details { font-size: 12px; opacity: 0.9; margin-top: 8px; line-height: 1.6; }
        .receipt-badge { background: rgba(255,255,255,0.2); padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .receipt-title { font-size: 32px; font-weight: 700; text-align: center; margin-top: 10px; }
        .receipt-number { text-align: center; font-size: 14px; opacity: 0.9; margin-top: 5px; }
        
        .body { padding: 40px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px; }
        .info-box { background: #f8fafc; border-radius: 10px; padding: 20px; }
        .info-box-title { font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 600; letter-spacing: 1px; margin-bottom: 12px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #64748b; }
        .info-value { font-weight: 600; color: #1e293b; }
        
        .amount-section { background: linear-gradient(135deg, ' . $primaryColor . '10, ' . $primaryColor . '05); border: 2px solid ' . $primaryColor . '; border-radius: 12px; padding: 30px; text-align: center; margin-bottom: 30px; }
        .amount-label { font-size: 12px; text-transform: uppercase; color: #64748b; letter-spacing: 1px; margin-bottom: 10px; }
        .amount-value { font-size: 42px; font-weight: 700; color: ' . $primaryColor . '; }
        .amount-words { font-size: 13px; color: #64748b; margin-top: 8px; font-style: italic; }
        
        .students-section { margin-bottom: 30px; }
        .students-title { font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 600; letter-spacing: 1px; margin-bottom: 12px; }
        .students-list { background: #f8fafc; border-radius: 10px; padding: 15px 20px; }
        .student-item { padding: 8px 0; border-bottom: 1px dashed #e2e8f0; }
        .student-item:last-child { border-bottom: none; }
        
        .thank-you { background: #f0fdf4; border-left: 4px solid #22c55e; padding: 20px; border-radius: 0 10px 10px 0; margin-bottom: 30px; }
        .thank-you-text { color: #166534; font-size: 15px; }
        
        .footer { background: #f8fafc; padding: 20px 40px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0; }
        .footer-note { margin-bottom: 10px; }
        .footer-date { font-size: 11px; }
    </style>
</head>
<body>
    <div class="preview-banner">⚠️ PREVIEW MODE - This is a sample receipt using template settings</div>
    
    <div class="receipt">
        <div class="header">
            <div class="header-top">
                <div>
                    <div class="org-name">' . htmlspecialchars($template->organization_name) . '</div>
                    <div class="org-details">';
        
        if ($template->organization_address) {
            $html .= htmlspecialchars($template->organization_address) . '<br>';
        }
        if ($template->organization_phone) {
            $html .= 'Tel: ' . htmlspecialchars($template->organization_phone) . '<br>';
        }
        if ($template->organization_email) {
            $html .= 'Email: ' . htmlspecialchars($template->organization_email);
        }
        
        $html .= '</div>
                </div>
                <div class="receipt-badge">PAID</div>
            </div>
            <div class="receipt-title">' . htmlspecialchars($template->receipt_title) . '</div>
            <div class="receipt-number">' . $receiptNumber . '</div>
        </div>
        
        <div class="body">
            <div class="info-grid">
                <div class="info-box">
                    <div class="info-box-title">Sponsor Information</div>
                    <div class="info-row">
                        <span class="info-label">Name</span>
                        <span class="info-value">' . htmlspecialchars($mockSponsor->name) . '</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">' . htmlspecialchars($mockSponsor->email) . '</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Sponsor ID</span>
                        <span class="info-value">SP-' . str_pad($mockSponsor->id, 5, '0', STR_PAD_LEFT) . '</span>
                    </div>
                </div>
                
                <div class="info-box">
                    <div class="info-box-title">Payment Details</div>
                    <div class="info-row">
                        <span class="info-label">Date</span>
                        <span class="info-value">' . $paymentDate . '</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Method</span>
                        <span class="info-value">' . htmlspecialchars(ucfirst($mockPayment->payment_method)) . '</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Reference</span>
                        <span class="info-value">' . htmlspecialchars($mockPayment->reference_number) . '</span>
                    </div>
                </div>
            </div>
            
            <div class="amount-section">
                <div class="amount-label">Amount Received</div>
                <div class="amount-value">' . $amount . '</div>
                <div class="amount-words">' . htmlspecialchars($template->currency_name ?? $mockPayment->currency) . '</div>
            </div>
            
            <div class="students-section">
                <div class="students-title">Students Sponsored (Sample)</div>
                <div class="students-list">
                    <div class="student-item">Student Name (Grade 5)</div>
                    <div class="student-item">Another Student (Year 2)</div>
                </div>
            </div>';
        
        if ($template->thank_you_message) {
            $html .= '
            <div class="thank-you">
                <div class="thank-you-text">' . htmlspecialchars($template->thank_you_message) . '</div>
            </div>';
        }
        
        $html .= '
        </div>
        
        <div class="footer">
            <div class="footer-note">' . htmlspecialchars($template->footer_text ?? 'Thank you for your support.') . '</div>
            <div class="footer-date">Generated on ' . date('F d, Y \a\t h:i A') . '</div>
        </div>
    </div>
</body>
</html>';
        
        return $html;
    }
}
