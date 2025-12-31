<?php

namespace Modules\StudentSponsorship\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\StudentSponsorship\Models\SponsorTransaction;
use Modules\StudentSponsorship\Models\Sponsor;
use Modules\StudentSponsorship\Models\SchoolStudent;
use Modules\StudentSponsorship\Models\UniversityStudent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SponsorTransactionController extends AdminController
{
    // =========================================
    // INDEX
    // =========================================

    public function index()
    {
        $stats = [
            'total' => SponsorTransaction::count(),
            'pending' => SponsorTransaction::where('status', 'pending')->count(),
            'partial' => SponsorTransaction::where('status', 'partial')->count(),
            'completed' => SponsorTransaction::where('status', 'completed')->count(),
        ];

        $sponsors = Sponsor::orderBy('name')->get();

        return view('studentsponsorship::transactions.index', [
            'title' => 'Transactions',
            'pageTitle' => 'Sponsor Transactions',
            'stats' => $stats,
            'sponsors' => $sponsors,
        ]);
    }

    // =========================================
    // DATATABLE
    // =========================================

    public function handleData(Request $request)
    {
        $query = SponsorTransaction::with(['sponsor', 'schoolStudent', 'universityStudent']);

        // Search
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('transaction_number', 'like', "%{$search}%")
                  ->orWhereHas('sponsor', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                         ->orWhere('sponsor_internal_id', 'like', "%{$search}%");
                  });
            });
        }

        // Filters
        if ($request->filled('sponsor_id')) {
            $query->where('sponsor_id', $request->input('sponsor_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->input('payment_type'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // Sort
        $sortCol = $request->input('sort', 'created_at');
        $sortDir = $request->input('dir', 'desc');
        $query->orderBy($sortCol, $sortDir);

        // Paginate
        $perPage = (int) $request->input('per_page', 25);
        $data = $query->paginate($perPage);

        // Transform
        $items = $data->getCollection()->map(function ($row) {
            return [
                'id' => $row->id,
                'transaction_number' => $row->transaction_number,
                'created_at' => $row->created_at?->format('d M Y'),
                'sponsor_name' => $row->sponsor?->name,
                'sponsor_id_display' => $row->sponsor?->sponsor_internal_id,
                'student_name' => $row->student_name,
                'total_amount' => $row->total_amount,
                'amount_paid' => $row->amount_paid,
                'formatted_total' => $row->formatted_total,
                'formatted_paid' => $row->formatted_paid,
                'formatted_balance' => $row->formatted_balance,
                'payment_progress' => $row->payment_progress,
                'payment_type' => $row->payment_type,
                'payment_type_display' => $row->payment_type_display,
                'status' => $row->status,
                'status_display' => $row->status_display,
                'status_badge' => $row->status_badge,
                'next_payment_due' => $row->next_payment_due?->format('d M Y'),
                '_show_url' => route('admin.studentsponsorship.transactions.show', $row->id),
                '_edit_url' => route('admin.studentsponsorship.transactions.edit', $row->id),
                '_delete_url' => route('admin.studentsponsorship.transactions.destroy', $row->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'per_page' => $data->perPage(),
        ]);
    }

    // =========================================
    // CREATE / STORE
    // =========================================

    public function create(Request $request)
    {
        $sponsors = Sponsor::active()->orderBy('name')->get();
        $schoolStudents = SchoolStudent::where('current_state', 'inprogress')->orderBy('full_name')->get();
        $universityStudents = UniversityStudent::where('current_state', 'inprogress')->orderBy('name')->get();

        $selectedSponsor = null;
        if ($request->has('sponsor_id')) {
            $selectedSponsor = Sponsor::find($request->sponsor_id);
        }

        return view('studentsponsorship::transactions.create', [
            'title' => 'New Transaction',
            'pageTitle' => 'Create Transaction',
            'sponsors' => $sponsors,
            'schoolStudents' => $schoolStudents,
            'universityStudents' => $universityStudents,
            'selectedSponsor' => $selectedSponsor,
            'isEdit' => false,
            'transaction' => null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sponsor_id' => 'required|exists:sponsors,id',
            'school_student_id' => 'nullable|exists:school_students,id',
            'university_student_id' => 'nullable|exists:university_students,id',
            'total_amount' => 'required|numeric|min:0.01',
            'currency' => 'required|in:LKR,USD,CAD,GBP,AUD',
            'payment_type' => 'required|in:one_time,monthly,quarterly,yearly,custom',
            'next_payment_due' => 'nullable|date',
            'days_before_due' => 'nullable|integer|min:1|max:90',
            'due_reminder_active' => 'nullable',
            'description' => 'nullable|string',
            'internal_note' => 'nullable|string',
        ]);

        // Both students can be selected, or just one, or none

        $validated['transaction_number'] = SponsorTransaction::generateTransactionNumber();
        $validated['due_reminder_active'] = $request->has('due_reminder_active') && $request->due_reminder_active == '1';
        $validated['status'] = 'pending';
        $validated['amount_paid'] = 0;
        $validated['created_by'] = auth()->id();

        $transaction = SponsorTransaction::create($validated);

        return redirect()
            ->route('admin.studentsponsorship.transactions.show', $transaction->id)
            ->with('success', 'Transaction created successfully. Add payments to record received amounts.');
    }

    // =========================================
    // SHOW
    // =========================================

    public function show($id)
    {
        $transaction = SponsorTransaction::with(['sponsor', 'schoolStudent', 'universityStudent', 'payments' => function($q) {
            $q->orderBy('payment_date', 'desc');
        }])->findOrFail($id);

        return view('studentsponsorship::transactions.show', [
            'title' => 'Transaction Details',
            'pageTitle' => 'Transaction: ' . $transaction->transaction_number,
            'transaction' => $transaction,
        ]);
    }

    // =========================================
    // EDIT / UPDATE
    // =========================================

    public function edit($id)
    {
        $transaction = SponsorTransaction::findOrFail($id);
        $sponsors = Sponsor::active()->orderBy('name')->get();
        $schoolStudents = SchoolStudent::where('current_state', 'inprogress')->orderBy('full_name')->get();
        $universityStudents = UniversityStudent::where('current_state', 'inprogress')->orderBy('name')->get();

        return view('studentsponsorship::transactions.create', [
            'title' => 'Edit Transaction',
            'pageTitle' => 'Edit Transaction',
            'transaction' => $transaction,
            'sponsors' => $sponsors,
            'schoolStudents' => $schoolStudents,
            'universityStudents' => $universityStudents,
            'selectedSponsor' => $transaction->sponsor,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $transaction = SponsorTransaction::findOrFail($id);

        $validated = $request->validate([
            'sponsor_id' => 'required|exists:sponsors,id',
            'school_student_id' => 'nullable|exists:school_students,id',
            'university_student_id' => 'nullable|exists:university_students,id',
            'total_amount' => 'required|numeric|min:0.01',
            'currency' => 'required|in:LKR,USD,CAD,GBP,AUD',
            'payment_type' => 'required|in:one_time,monthly,quarterly,yearly,custom',
            'next_payment_due' => 'nullable|date',
            'days_before_due' => 'nullable|integer|min:1|max:90',
            'due_reminder_active' => 'nullable',
            'description' => 'nullable|string',
            'internal_note' => 'nullable|string',
        ]);

        // Both students can be selected, or just one, or none

        $validated['due_reminder_active'] = $request->has('due_reminder_active') && $request->due_reminder_active == '1';
        $validated['updated_by'] = auth()->id();

        $transaction->update($validated);

        // Recalculate status if total changed
        $transaction->recalculateTotals();

        return redirect()
            ->route('admin.studentsponsorship.transactions.show', $transaction->id)
            ->with('success', 'Transaction updated successfully.');
    }

    // =========================================
    // DELETE
    // =========================================

    public function destroy($id)
    {
        $transaction = SponsorTransaction::findOrFail($id);
        $transaction->delete();

        return response()->json(['success' => true, 'message' => 'Transaction deleted']);
    }

    // =========================================
    // BULK ACTIONS
    // =========================================

    public function handleBulkAction(Request $request): JsonResponse
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }

        switch ($action) {
            case 'delete':
                $count = SponsorTransaction::whereIn('id', $ids)->delete();
                return response()->json(['success' => true, 'message' => "{$count} transaction(s) deleted"]);

            case 'mark_cancelled':
                $count = SponsorTransaction::whereIn('id', $ids)->update(['status' => 'cancelled']);
                return response()->json(['success' => true, 'message' => "{$count} transaction(s) cancelled"]);

            default:
                return response()->json(['success' => false, 'message' => 'Invalid action'], 400);
        }
    }

    // =========================================
    // STATUS ACTIONS
    // =========================================

    public function markCancelled($id): JsonResponse
    {
        $transaction = SponsorTransaction::findOrFail($id);
        $transaction->markCancelled();

        return response()->json(['success' => true, 'message' => 'Transaction cancelled']);
    }

    // =========================================
    // SEND EMAIL
    // =========================================

    public function sendEmail(Request $request, $id): JsonResponse
    {
        $transaction = SponsorTransaction::with(['sponsor', 'schoolStudent', 'universityStudent'])->findOrFail($id);

        $sponsor = $transaction->sponsor;
        if (!$sponsor || !$sponsor->email) {
            return response()->json(['success' => false, 'message' => 'Sponsor email not found'], 400);
        }

        $emailType = $request->input('email_type', 'reminder');
        $studentName = $transaction->student_name ?? 'General Donation';
        $dueDate = $transaction->next_payment_due?->format('d M Y') ?? 'Not Set';
        $balance = max(0, $transaction->total_amount - $transaction->amount_paid);

        // Build email based on type
        if ($emailType === 'reminder') {
            $subject = "Reminder: Payment Due on {$dueDate}";
            $body = $this->buildReminderEmail($transaction, $sponsor, $studentName, $dueDate, $balance);
        } elseif ($emailType === 'thank_you') {
            $subject = "Thank You for Your Payment - {$transaction->transaction_number}";
            $body = $this->buildThankYouEmail($transaction, $sponsor, $studentName);
        } else {
            $subject = "Payment Update - {$transaction->transaction_number}";
            $body = $this->buildReminderEmail($transaction, $sponsor, $studentName, $dueDate, $balance);
        }

        try {
            if (function_exists('send_mail')) {
                send_mail($sponsor->email, $subject, $body);
            } else {
                // Fallback using Laravel Mail
                \Illuminate\Support\Facades\Mail::html($body, function($message) use ($sponsor, $subject) {
                    $message->to($sponsor->email)
                        ->subject($subject);
                });
            }

            return response()->json([
                'success' => true,
                'message' => "Email sent successfully to {$sponsor->email}"
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('[SponsorTransaction] Email send failed', [
                'transaction_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Build reminder email HTML
     */
    protected function buildReminderEmail($transaction, $sponsor, $studentName, $dueDate, $balance): string
    {
        $currency = $transaction->currency;
        $companyName = '87 Initiative';

        return '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: #f9fafb;">
            <div style="background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); padding: 30px; text-align: center;">
                    <h1 style="color: #fff; margin: 0; font-size: 22px;">Payment Reminder</h1>
                </div>
                
                <div style="padding: 30px;">
                    <p style="font-size: 16px; color: #374151; margin-bottom: 20px;">Hello <strong>' . htmlspecialchars($sponsor->name) . '</strong>,</p>
                    
                    <p style="font-size: 15px; color: #4b5563; line-height: 1.6;">
                        This is a reminder that your next payment for <strong>' . htmlspecialchars($studentName) . '</strong> is due on <strong>' . $dueDate . '</strong>.
                    </p>
                    
                    <table style="width: 100%; border-collapse: collapse; margin: 25px 0; font-size: 14px;">
                        <thead>
                            <tr style="background: #f3f4f6;">
                                <th style="padding: 12px; text-align: left; border: 1px solid #e5e7eb;">Sponsor</th>
                                <th style="padding: 12px; text-align: left; border: 1px solid #e5e7eb;">Student</th>
                                <th style="padding: 12px; text-align: right; border: 1px solid #e5e7eb;">Total</th>
                                <th style="padding: 12px; text-align: right; border: 1px solid #e5e7eb;">Paid</th>
                                <th style="padding: 12px; text-align: right; border: 1px solid #e5e7eb;">Balance</th>
                                <th style="padding: 12px; text-align: left; border: 1px solid #e5e7eb;">Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 12px; border: 1px solid #e5e7eb;">' . htmlspecialchars($sponsor->name) . '</td>
                                <td style="padding: 12px; border: 1px solid #e5e7eb;">' . htmlspecialchars($studentName) . '</td>
                                <td style="padding: 12px; border: 1px solid #e5e7eb; text-align: right; font-weight: 600;">' . $currency . ' ' . number_format($transaction->total_amount, 2) . '</td>
                                <td style="padding: 12px; border: 1px solid #e5e7eb; text-align: right; color: #16a34a;">' . $currency . ' ' . number_format($transaction->amount_paid, 2) . '</td>
                                <td style="padding: 12px; border: 1px solid #e5e7eb; text-align: right; font-weight: 600; color: #d97706;">' . $currency . ' ' . number_format($balance, 2) . '</td>
                                <td style="padding: 12px; border: 1px solid #e5e7eb;">' . $dueDate . '</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <p style="font-size: 15px; color: #4b5563; line-height: 1.6; margin-top: 25px;">
                        Thank you for supporting <strong>' . htmlspecialchars($companyName) . '</strong> in helping underprivileged children in Sri Lanka.
                    </p>
                    
                    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center;">
                        <p style="margin: 0; color: #6b7280; font-size: 14px;"><strong>' . htmlspecialchars($companyName) . '</strong></p>
                    </div>
                </div>
            </div>
            
            <p style="text-align: center; color: #9ca3af; font-size: 12px; margin-top: 20px;">
                This is an automated email. Please do not reply directly.
            </p>
        </div>';
    }

    /**
     * Build thank you email HTML
     */
    protected function buildThankYouEmail($transaction, $sponsor, $studentName): string
    {
        $currency = $transaction->currency;
        $companyName = '87 Initiative';

        return '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: #f9fafb;">
            <div style="background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div style="background: linear-gradient(135deg, #10b981, #059669); padding: 30px; text-align: center;">
                    <h1 style="color: #fff; margin: 0; font-size: 22px;">✓ Thank You!</h1>
                </div>
                
                <div style="padding: 30px;">
                    <p style="font-size: 16px; color: #374151; margin-bottom: 20px;">Dear <strong>' . htmlspecialchars($sponsor->name) . '</strong>,</p>
                    
                    <p style="font-size: 15px; color: #4b5563; line-height: 1.6;">
                        Thank you for your generous payment for <strong>' . htmlspecialchars($studentName) . '</strong>. Your support makes a significant difference!
                    </p>
                    
                    <div style="background: #f0fdf4; border-left: 4px solid #10b981; padding: 15px; margin: 25px 0; border-radius: 0 8px 8px 0;">
                        <table style="width: 100%; font-size: 14px;">
                            <tr>
                                <td style="padding: 4px 0; color: #6b7280;">Transaction:</td>
                                <td style="padding: 4px 0; text-align: right; font-family: monospace; font-weight: 600;">' . $transaction->transaction_number . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 4px 0; color: #6b7280;">Amount Paid:</td>
                                <td style="padding: 4px 0; text-align: right; font-weight: 600; color: #059669;">' . $currency . ' ' . number_format($transaction->amount_paid, 2) . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 4px 0; color: #6b7280;">Status:</td>
                                <td style="padding: 4px 0; text-align: right;"><span style="background: #dcfce7; color: #16a34a; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">' . ucfirst($transaction->status) . '</span></td>
                            </tr>
                        </table>
                    </div>
                    
                    <p style="font-size: 15px; color: #4b5563; line-height: 1.6;">
                        We are grateful for your continued support in helping underprivileged children in Sri Lanka achieve their educational dreams.
                    </p>
                    
                    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center;">
                        <p style="margin: 0; color: #6b7280; font-size: 14px;">Warm regards,<br><strong>' . htmlspecialchars($companyName) . '</strong></p>
                    </div>
                </div>
            </div>
        </div>';
    }
}
