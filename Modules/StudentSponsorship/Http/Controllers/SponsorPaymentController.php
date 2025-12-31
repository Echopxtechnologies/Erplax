<?php

namespace Modules\StudentSponsorship\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\StudentSponsorship\Models\SponsorPayment;
use Modules\StudentSponsorship\Models\SponsorTransaction;
use Modules\StudentSponsorship\Helpers\PaymentReceiptHelper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SponsorPaymentController extends AdminController
{
    /**
     * Display payments index page
     */
    public function index(): View
    {
        $stats = [
            'total' => SponsorPayment::count(),
            'this_month' => SponsorPayment::whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->count(),
            'today' => SponsorPayment::whereDate('payment_date', today())->count(),
        ];

        return view('studentsponsorship::payments.index', compact('stats'));
    }

    /**
     * Handle DataTable requests
     */
    public function handleData(Request $request): JsonResponse
    {
        $query = SponsorPayment::with(['transaction.sponsor', 'transaction.schoolStudent', 'transaction.universityStudent', 'createdBy'])
            ->select('sponsor_payments.*');

        // Search
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('receipt_number', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhereHas('transaction', function ($tq) use ($search) {
                      $tq->where('transaction_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('transaction.sponsor', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Date filter
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('payment_date', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('payment_date', '<=', $dateTo);
        }

        // Payment method filter
        if ($method = $request->input('payment_method')) {
            $query->where('payment_method', $method);
        }

        $totalRecords = SponsorPayment::count();
        $filteredRecords = $query->count();

        // Sorting
        $orderColumn = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');

        $columns = ['payment_date', 'amount', 'transaction_id', 'payment_method', 'reference_number', 'created_at'];
        $orderBy = $columns[$orderColumn] ?? 'payment_date';

        $query->orderBy($orderBy, $orderDir);

        // Pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $payments = $query->skip($start)->take($length)->get();

        $data = $payments->map(function ($payment) {
            $transaction = $payment->transaction;
            $sponsor = $transaction?->sponsor;
            $studentName = $transaction?->student_name ?? '-';

            return [
                'id' => $payment->id,
                'payment_date' => $payment->payment_date?->format('d M Y'),
                'amount' => $payment->formatted_amount,
                'amount_raw' => $payment->amount,
                'currency' => $payment->currency,
                'sponsor_name' => $sponsor?->name ?? '-',
                'sponsor_id' => $sponsor?->id,
                'student_name' => $studentName,
                'transaction_number' => $transaction?->transaction_number ?? '-',
                'transaction_id' => $transaction?->id,
                'payment_method' => $payment->payment_method_display ?? '-',
                'reference_number' => $payment->reference_number ?? '-',
                'created_by' => $payment->created_by_name ?? 'System',
                'created_at' => $payment->created_at?->format('d M Y, h:i A'),
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }

    /**
     * Store a new payment
     * NO max restriction - sponsors can pay any amount including extra contributions
     * Even ₹1 payment is valid and creates sponsorship relation
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:sponsor_transactions,id',
            'amount' => 'required|numeric|min:0.01', // Min 0.01, NO max - allow extra payments
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|in:cash,bank_transfer,cheque,upi,online,card',
            'reference_number' => 'nullable|string|max:100',
            'receipt_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'send_email' => 'boolean', // Option to send email with receipt
        ]);

        $transaction = SponsorTransaction::findOrFail($validated['transaction_id']);

        // NO restriction on amount - sponsors can pay:
        // - Partial amounts (even ₹1)
        // - Full balance
        // - Extra amounts beyond balance (extra contribution)

        $validated['created_by'] = auth()->id();

        $payment = SponsorPayment::create($validated);

        // The model's boot method will auto-update the transaction totals

        // Determine message based on payment situation
        $balance = $transaction->fresh()->balance;
        $message = 'Payment recorded successfully';
        
        if ($balance < 0) {
            $extra = abs($balance);
            $symbol = $transaction->currency_symbol;
            $message = "Payment recorded! Extra contribution of {$symbol}" . number_format($extra, 2) . " received. Thank you!";
        }

        // Send email with receipt if requested
        $emailSent = false;
        if ($request->input('send_email')) {
            $emailSent = $this->sendPaymentReceipt($payment);
            if ($emailSent) {
                $message .= ' Receipt sent to sponsor.';
            }
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'payment' => $payment,
            'email_sent' => $emailSent,
        ]);
    }

    /**
     * Send payment receipt email to sponsor
     */
    public function sendPaymentReceipt(SponsorPayment $payment): bool
    {
        $payment->load('transaction.sponsor');
        
        $sponsor = $payment->transaction?->sponsor;
        
        if (!$sponsor || !$sponsor->email) {
            Log::warning('[Payment] Cannot send receipt - no sponsor email', ['payment_id' => $payment->id]);
            return false;
        }

        try {
            $receiptHtml = PaymentReceiptHelper::generateReceiptHtml($payment);
            $receiptNumber = 'RCP-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT);
            
            $subject = "Payment Receipt - {$receiptNumber}";
            $body = $this->buildReceiptEmailBody($payment, $sponsor, $receiptNumber);
            
            // Try to generate PDF
            $pdfPath = PaymentReceiptHelper::generateReceiptPdf($payment);
            
            Mail::send([], [], function($message) use ($sponsor, $subject, $body, $pdfPath, $receiptNumber) {
                $message->to($sponsor->email)
                    ->subject($subject)
                    ->setBody($body, 'text/html');
                
                // Attach receipt PDF if available
                if ($pdfPath && file_exists($pdfPath)) {
                    $message->attach($pdfPath, [
                        'as' => $receiptNumber . (str_ends_with($pdfPath, '.pdf') ? '.pdf' : '.html'),
                        'mime' => str_ends_with($pdfPath, '.pdf') ? 'application/pdf' : 'text/html',
                    ]);
                }
            });
            
            // Clean up temporary PDF file
            if ($pdfPath && file_exists($pdfPath)) {
                @unlink($pdfPath);
            }
            
            Log::info('[Payment] Receipt email sent', ['payment_id' => $payment->id, 'email' => $sponsor->email]);
            return true;
            
        } catch (\Exception $e) {
            Log::error('[Payment] Receipt email failed', ['payment_id' => $payment->id, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Build receipt email body
     */
    protected function buildReceiptEmailBody(SponsorPayment $payment, $sponsor, string $receiptNumber): string
    {
        $studentName = $payment->transaction?->student_name ?? 'General Donation';
        $currency = $payment->currency;
        $amount = number_format($payment->amount, 2);
        $paymentDate = $payment->payment_date?->format('d M Y') ?? date('d M Y');
        
        return '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: #f9fafb;">
            <div style="background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); padding: 30px; text-align: center;">
                    <h1 style="color: #fff; margin: 0; font-size: 22px;">Payment Receipt</h1>
                    <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0 0; font-size: 14px;">' . $receiptNumber . '</p>
                </div>
                
                <div style="padding: 30px;">
                    <p style="font-size: 16px; color: #374151; margin-bottom: 20px;">Dear <strong>' . htmlspecialchars($sponsor->name) . '</strong>,</p>
                    
                    <p style="font-size: 15px; color: #4b5563; line-height: 1.6;">
                        Thank you for your payment! We have received your contribution and attached is your payment receipt for your records.
                    </p>
                    
                    <div style="background: #f3f4f6; border-radius: 10px; padding: 20px; margin: 25px 0;">
                        <table style="width: 100%; font-size: 14px;">
                            <tr>
                                <td style="padding: 8px 0; color: #6b7280;">Amount:</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 700; color: #059669; font-size: 18px;">' . $currency . ' ' . $amount . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #6b7280;">Date:</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 600;">' . $paymentDate . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #6b7280;">Student:</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 600;">' . htmlspecialchars($studentName) . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #6b7280;">Method:</td>
                                <td style="padding: 8px 0; text-align: right;">' . htmlspecialchars(ucfirst($payment->payment_method ?? 'N/A')) . '</td>
                            </tr>
                        </table>
                    </div>
                    
                    <p style="font-size: 14px; color: #6b7280; line-height: 1.6;">
                        Your generous support makes a real difference in the lives of students. Thank you for your continued commitment to education.
                    </p>
                </div>
                
                <div style="background: #f9fafb; padding: 20px; text-align: center; border-top: 1px solid #e5e7eb;">
                    <p style="font-size: 13px; color: #6b7280; margin: 0;">Student Sponsorship Program</p>
                </div>
            </div>
        </div>';
    }

    /**
     * Send receipt email for existing payment
     */
    public function sendReceipt($id): JsonResponse
    {
        $payment = SponsorPayment::with('transaction.sponsor')->findOrFail($id);
        
        $sent = $this->sendPaymentReceipt($payment);
        
        if ($sent) {
            return response()->json(['success' => true, 'message' => 'Receipt sent successfully']);
        }
        
        return response()->json(['success' => false, 'message' => 'Failed to send receipt'], 500);
    }

    /**
     * Delete a payment
     */
    public function destroy($id): JsonResponse
    {
        $payment = SponsorPayment::findOrFail($id);
        $payment->delete();

        // The model's boot method will auto-update the transaction totals

        return response()->json([
            'success' => true,
            'message' => 'Payment deleted',
        ]);
    }
}
