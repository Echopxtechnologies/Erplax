<?php

namespace Modules\StudentSponsorship\Helpers;

use Modules\StudentSponsorship\Models\PaymentReceiptTemplate;
use Modules\StudentSponsorship\Models\SponsorPayment;
use Modules\StudentSponsorship\Models\Sponsor;

class PaymentReceiptHelper
{
    /**
     * Generate HTML receipt for a payment
     */
    public static function generateReceiptHtml(SponsorPayment $payment): string
    {
        $sponsor = $payment->transaction?->sponsor;
        $template = PaymentReceiptTemplate::getOrCreateByCurrency($payment->currency);
        
        $receiptNumber = 'RCP-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT);
        $paymentDate = $payment->payment_date ? date('F d, Y', strtotime($payment->payment_date)) : date('F d, Y');
        $amount = $template->formatAmount($payment->amount);
        
        // Get students sponsored
        $students = [];
        if ($sponsor) {
            $schoolStudents = $sponsor->schoolStudents()->take(5)->get(['full_name', 'grade']);
            $uniStudents = $sponsor->universityStudents()->take(5)->get(['name', 'university_year_of_study']);
            
            foreach ($schoolStudents as $s) {
                $students[] = $s->full_name . ' (Grade ' . $s->grade . ')';
            }
            foreach ($uniStudents as $s) {
                $students[] = $s->name . ' (Year ' . $s->university_year_of_study . ')';
            }
        }
        
        $primaryColor = $template->primary_color ?: '#2563eb';
        $secondaryColor = $template->secondary_color ?: '#1e40af';
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt - ' . $receiptNumber . '</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", Arial, sans-serif; font-size: 14px; color: #333; background: #f5f5f5; padding: 20px; }
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
        
        @media print {
            body { background: #fff; padding: 0; }
            .receipt { box-shadow: none; }
        }
    </style>
</head>
<body>
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
                        <span class="info-value">' . htmlspecialchars($sponsor->name ?? 'N/A') . '</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">' . htmlspecialchars($sponsor->email ?? 'N/A') . '</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Sponsor ID</span>
                        <span class="info-value">SP-' . str_pad($sponsor->id ?? 0, 5, '0', STR_PAD_LEFT) . '</span>
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
                        <span class="info-value">' . htmlspecialchars(ucfirst($payment->payment_method ?? 'N/A')) . '</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Reference</span>
                        <span class="info-value">' . htmlspecialchars($payment->reference_number ?: '-') . '</span>
                    </div>
                </div>
            </div>
            
            <div class="amount-section">
                <div class="amount-label">Amount Received</div>
                <div class="amount-value">' . $amount . '</div>
                <div class="amount-words">' . htmlspecialchars($template->currency_name ?? $payment->currency) . '</div>
            </div>';
        
        if (!empty($students)) {
            $html .= '
            <div class="students-section">
                <div class="students-title">Students Sponsored</div>
                <div class="students-list">';
            foreach ($students as $student) {
                $html .= '<div class="student-item">' . htmlspecialchars($student) . '</div>';
            }
            $html .= '</div>
            </div>';
        }
        
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

    /**
     * Generate PDF receipt and return path
     */
    public static function generateReceiptPdf(SponsorPayment $payment): ?string
    {
        $html = self::generateReceiptHtml($payment);
        
        $receiptNumber = 'RCP-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT);
        $filename = 'receipt_' . $receiptNumber . '_' . date('Ymd') . '.pdf';
        $path = storage_path('app/receipts/' . $filename);
        
        // Ensure directory exists
        if (!file_exists(storage_path('app/receipts'))) {
            mkdir(storage_path('app/receipts'), 0755, true);
        }
        
        // Try to use dompdf if available
        if (class_exists('\Dompdf\Dompdf')) {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            file_put_contents($path, $dompdf->output());
            return $path;
        }
        
        // Try to use mpdf if available
        if (class_exists('\Mpdf\Mpdf')) {
            $mpdf = new \Mpdf\Mpdf(['tempDir' => storage_path('app/temp')]);
            $mpdf->WriteHTML($html);
            $mpdf->Output($path, 'F');
            return $path;
        }
        
        // Try to use snappy/wkhtmltopdf if available
        if (class_exists('\Knp\Snappy\Pdf')) {
            $snappy = new \Knp\Snappy\Pdf('/usr/local/bin/wkhtmltopdf');
            $snappy->generateFromHtml($html, $path);
            return $path;
        }
        
        // Fallback: save HTML and let browser print to PDF
        $htmlPath = storage_path('app/receipts/' . str_replace('.pdf', '.html', $filename));
        file_put_contents($htmlPath, $html);
        return $htmlPath;
    }

    /**
     * Get receipt as downloadable response
     */
    public static function downloadReceipt(SponsorPayment $payment)
    {
        $path = self::generateReceiptPdf($payment);
        
        if (!$path || !file_exists($path)) {
            // Return HTML if PDF generation failed
            $html = self::generateReceiptHtml($payment);
            return response($html)->header('Content-Type', 'text/html');
        }
        
        $filename = basename($path);
        $contentType = str_ends_with($path, '.pdf') ? 'application/pdf' : 'text/html';
        
        return response()->download($path, $filename, [
            'Content-Type' => $contentType,
        ])->deleteFileAfterSend(true);
    }
}
