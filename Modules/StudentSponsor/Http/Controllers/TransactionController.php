<?php

namespace Modules\StudentSponsor\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\StudentSponsor\Models\SponsorTransaction;
use Modules\StudentSponsor\Models\SponsorPayment;
use Modules\StudentSponsor\Models\Sponsor;
use Modules\StudentSponsor\Models\SchoolStudent;
use Modules\StudentSponsor\Models\UniversityStudent;
use Illuminate\Http\Request;
use Modules\Core\Traits\DataTableTrait;
use Illuminate\Support\Facades\Mail;

class TransactionController extends AdminController
{
    use DataTableTrait;

    protected $model = SponsorTransaction::class;
    protected $searchable = ['id', 'sponsor.name', 'total_amount', 'currency'];
    protected $exportable = ['id', 'sponsor_id', 'total_amount', 'amount_paid', 'currency', 'payment_type', 'next_payment_due'];
    protected $routePrefix = 'admin.studentsponsor.transaction';

    /**
     * Display transactions list
     */
    public function index()
    {
        $totalTransactions = SponsorTransaction::count();
        $totalAmount = SponsorTransaction::sum('total_amount');
        $totalPaid = SponsorTransaction::sum('amount_paid');
        $pendingPayments = SponsorTransaction::whereRaw('total_amount > amount_paid')->count();

        return $this->moduleView('studentsponsor::transaction.index', compact(
            'totalTransactions',
            'totalAmount', 
            'totalPaid',
            'pendingPayments'
        ));
    }

    /**
     * DataTable data
     */
    public function dataTable(Request $request)
    {
        $query = SponsorTransaction::with(['sponsor', 'schoolStudent', 'universityStudent'])
            ->select('tblsponsor_transactions.*');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('total_amount', 'like', "%{$search}%")
                  ->orWhere('currency', 'like', "%{$search}%")
                  ->orWhereHas('sponsor', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('schoolStudent', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('universityStudent', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        // Pagination
        $perPage = $request->get('per_page', 25);
        $transactions = $query->paginate($perPage);

        $data = $transactions->map(function($txn) {
            $studentName = '-';
            $studentType = 'none';
            if ($txn->school_student_id && $txn->schoolStudent) {
                $studentName = $txn->schoolStudent->name;
                $studentType = 'school';
            } elseif ($txn->university_student_id && $txn->universityStudent) {
                $studentName = $txn->universityStudent->name;
                $studentType = 'university';
            }

            $balance = $txn->total_amount - $txn->amount_paid;

            return [
                'id' => $txn->id,
                'sponsor_name' => $txn->sponsor?->name ?? '-',
                'student_name' => $studentName,
                'student_type' => $studentType,
                'total_amount' => number_format($txn->total_amount, 2) . ' ' . $txn->currency,
                'amount_paid' => number_format($txn->amount_paid, 2) . ' ' . $txn->currency,
                'balance' => number_format($balance, 2) . ' ' . $txn->currency,
                'payment_type' => $txn->payment_type,
                'next_payment_due' => $txn->next_payment_due ? $txn->next_payment_due->format('Y-m-d') : '-',
                '_show_url' => route('admin.studentsponsor.transaction.edit', $txn->id),
                '_edit_url' => route('admin.studentsponsor.transaction.edit', $txn->id),
                '_delete_url' => route('admin.studentsponsor.transaction.destroy', $txn->id),
            ];
        });

        return response()->json([
            'data' => $data,
            'total' => $transactions->total(),
            'current_page' => $transactions->currentPage(),
            'last_page' => $transactions->lastPage(),
        ]);
    }

    /**
     * Create form
     */
    public function create()
    {
        $sponsors = Sponsor::orderBy('name')->get();
        $schoolStudents = SchoolStudent::orderBy('name')->get();
        $universityStudents = UniversityStudent::orderBy('name')->get();
        
        $currencies = ['LKR' => 'Sri Lankan Rupees (LKR)', 'USD' => 'US Dollars (USD)', 'CAD' => 'Canadian Dollars (CAD)', 'GBP' => 'UK Pounds (GBP)', 'AUD' => 'Australian Dollars (AUD)'];
        $paymentTypes = ['one_time' => 'One Time', 'monthly' => 'Monthly', 'quarterly' => 'Quarterly', 'yearly' => 'Yearly', 'custom' => 'Custom'];

        return $this->moduleView('studentsponsor::transaction.form', compact(
            'sponsors',
            'schoolStudents',
            'universityStudents',
            'currencies',
            'paymentTypes'
        ));
    }

    /**
     * Store new transaction
     */
    public function store(Request $request)
    {
        $request->validate([
            'sponsor_id' => 'required|exists:tblsponsor_records,id',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'payment_type' => 'required|string',
        ]);

        // Validate: exactly one student type
        if ($request->school_student_id && $request->university_student_id) {
            return back()->withErrors(['student' => 'Select either School student OR University student, not both.'])->withInput();
        }
        if (!$request->school_student_id && !$request->university_student_id) {
            return back()->withErrors(['student' => 'Please select a student (school or university).'])->withInput();
        }

        $data = $request->only([
            'sponsor_id', 'school_student_id', 'university_student_id',
            'total_amount', 'amount_paid', 'currency', 'payment_type',
            'next_payment_due', 'due_reminder_active', 'due_reminder_days_before',
            'sponsorship_start', 'sponsorship_end'
        ]);

        $data['amount_paid'] = $data['amount_paid'] ?? 0;
        $data['due_reminder_active'] = $request->has('due_reminder_active') ? 1 : 0;
        $data['due_reminder_days_before'] = $data['due_reminder_days_before'] ?? 15;

        $transaction = SponsorTransaction::create($data);

        // Compute next payment due if not set
        if (!$transaction->next_payment_due && $transaction->payment_type !== 'one_time') {
            $transaction->computeNextPaymentDue();
        }

        return redirect()->route('admin.studentsponsor.transaction.edit', $transaction->id)
            ->with('success', 'Transaction created successfully.');
    }

    /**
     * Edit form with payments
     */
    public function edit($id)
    {
        $transaction = SponsorTransaction::with(['sponsor', 'schoolStudent', 'universityStudent', 'payments'])->findOrFail($id);
        
        $sponsors = Sponsor::orderBy('name')->get();
        $schoolStudents = SchoolStudent::orderBy('name')->get();
        $universityStudents = UniversityStudent::orderBy('name')->get();
        
        $currencies = ['LKR' => 'Sri Lankan Rupees (LKR)', 'USD' => 'US Dollars (USD)', 'CAD' => 'Canadian Dollars (CAD)', 'GBP' => 'UK Pounds (GBP)', 'AUD' => 'Australian Dollars (AUD)'];
        $paymentTypes = ['one_time' => 'One Time', 'monthly' => 'Monthly', 'quarterly' => 'Quarterly', 'yearly' => 'Yearly', 'custom' => 'Custom'];

        // Get payments for this transaction
        $payments = SponsorPayment::where('transaction_id', $id)
            ->orderBy('payment_date', 'desc')
            ->get();

        // Get payment being edited (if any)
        $editingPayment = null;
        if ($editPaymentId = request('edit_payment')) {
            $editingPayment = SponsorPayment::find($editPaymentId);
        }

        // Generate email template for preview
        $emailTemplate = $this->generateDueEmailTemplate($transaction);

        return $this->moduleView('studentsponsor::transaction.form', compact(
            'transaction',
            'sponsors',
            'schoolStudents',
            'universityStudents',
            'currencies',
            'paymentTypes',
            'payments',
            'editingPayment',
            'emailTemplate'
        ));
    }

    /**
     * Update transaction
     */
    public function update(Request $request, $id)
    {
        $transaction = SponsorTransaction::findOrFail($id);

        $request->validate([
            'sponsor_id' => 'required|exists:tblsponsor_records,id',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'payment_type' => 'required|string',
        ]);

        // Validate: exactly one student type
        if ($request->school_student_id && $request->university_student_id) {
            return back()->withErrors(['student' => 'Select either School student OR University student, not both.'])->withInput();
        }
        if (!$request->school_student_id && !$request->university_student_id) {
            return back()->withErrors(['student' => 'Please select a student (school or university).'])->withInput();
        }

        $data = $request->only([
            'sponsor_id', 'school_student_id', 'university_student_id',
            'total_amount', 'currency', 'payment_type',
            'next_payment_due', 'due_reminder_active', 'due_reminder_days_before',
            'sponsorship_start', 'sponsorship_end'
        ]);

        // Handle null student IDs
        $data['school_student_id'] = $data['school_student_id'] ?: null;
        $data['university_student_id'] = $data['university_student_id'] ?: null;
        $data['due_reminder_active'] = $request->has('due_reminder_active') ? 1 : 0;
        $data['due_reminder_days_before'] = $data['due_reminder_days_before'] ?? 15;

        $transaction->update($data);

        // Recompute if payment type changed
        if ($transaction->wasChanged('payment_type') && $transaction->payment_type !== 'custom') {
            $transaction->computeNextPaymentDue();
        }

        return redirect()->route('admin.studentsponsor.transaction.edit', $id)
            ->with('success', 'Transaction updated successfully.');
    }

    /**
     * Delete transaction
     */
    public function destroy($id)
    {
        $transaction = SponsorTransaction::findOrFail($id);
        
        // Delete related payments first
        SponsorPayment::where('transaction_id', $id)->delete();
        
        $transaction->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.studentsponsor.transaction.index')
            ->with('success', 'Transaction deleted successfully.');
    }

    /* ======================== PAYMENT METHODS ======================== */

    /**
     * Add payment
     */
    public function addPayment(Request $request, $transactionId)
    {
        $transaction = SponsorTransaction::findOrFail($transactionId);

        $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|max:10',
        ]);

        $payment = SponsorPayment::create([
            'transaction_id' => $transactionId,
            'sponsor_id' => $transaction->sponsor_id,
            'student_id' => $transaction->school_student_id ?? $transaction->university_student_id,
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'note' => $request->note,
            'created_by' => auth()->id(),
        ]);

        // Recompute transaction totals
        $this->recomputeTransactionTotals($transactionId);

        // Send payment confirmation email (optional)
        // $this->sendPaymentEmail($payment);

        return redirect()->route('admin.studentsponsor.transaction.edit', ['id' => $transactionId, 'tab' => 'payments'])
            ->with('success', 'Payment added successfully.');
    }

    /**
     * Update payment
     */
    public function updatePayment(Request $request, $transactionId, $paymentId)
    {
        $payment = SponsorPayment::where('id', $paymentId)
            ->where('transaction_id', $transactionId)
            ->firstOrFail();

        $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|max:10',
        ]);

        $payment->update([
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'note' => $request->note,
        ]);

        // Recompute transaction totals
        $this->recomputeTransactionTotals($transactionId);

        return redirect()->route('admin.studentsponsor.transaction.edit', ['id' => $transactionId, 'tab' => 'payments'])
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Delete payment
     */
    public function deletePayment($transactionId, $paymentId)
    {
        $payment = SponsorPayment::where('id', $paymentId)
            ->where('transaction_id', $transactionId)
            ->firstOrFail();

        $payment->delete();

        // Recompute transaction totals
        $this->recomputeTransactionTotals($transactionId);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.studentsponsor.transaction.edit', ['id' => $transactionId, 'tab' => 'payments'])
            ->with('success', 'Payment deleted successfully.');
    }

    /**
     * Recompute transaction totals after payment changes
     */
    protected function recomputeTransactionTotals($transactionId)
    {
        $transaction = SponsorTransaction::findOrFail($transactionId);
        
        $payments = SponsorPayment::where('transaction_id', $transactionId);
        
        $amountPaid = $payments->sum('amount');
        $lastPayment = $payments->orderBy('payment_date', 'desc')->first();

        $transaction->amount_paid = $amountPaid;
        $transaction->last_payment_date = $lastPayment?->payment_date;

        // Compute next payment due based on last payment
        if ($transaction->payment_type !== 'one_time' && $transaction->payment_type !== 'custom') {
            $transaction->computeNextPaymentDue();
        }

        // Update scheduled reminder date
        if ($transaction->due_reminder_active && $transaction->next_payment_due && $transaction->due_reminder_days_before > 0) {
            $transaction->scheduled_due_reminder_date = $transaction->next_payment_due->subDays($transaction->due_reminder_days_before);
        } else {
            $transaction->scheduled_due_reminder_date = null;
        }

        $transaction->save();
    }

    /**
     * Send due reminder email
     */
    public function sendDueEmail($transactionId)
    {
        $transaction = SponsorTransaction::with(['sponsor', 'schoolStudent', 'universityStudent'])->findOrFail($transactionId);

        if (!$transaction->sponsor || !$transaction->sponsor->email) {
            return redirect()->back()->with('error', 'Sponsor does not have an email address.');
        }

        $template = $this->generateDueEmailTemplate($transaction);

        try {
            Mail::html($template['body'], function($message) use ($transaction, $template) {
                $message->to($transaction->sponsor->email)
                    ->subject($template['subject']);
            });

            $transaction->update([
                'due_reminder_sent' => 1,
                'due_reminder_sent_at' => now(),
                'scheduled_due_reminder_date' => null,
            ]);

            return redirect()->back()->with('success', 'Email sent successfully to: ' . $transaction->sponsor->email);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Generate due email template
     */
    protected function generateDueEmailTemplate($transaction)
    {
        if (!$transaction) return null;

        $sponsorName = $transaction->sponsor?->name ?? 'Sponsor';
        $studentName = $transaction->schoolStudent?->name ?? $transaction->universityStudent?->name ?? 'Student';
        $dueDate = $transaction->next_payment_due ? $transaction->next_payment_due->format('m/d/Y') : 'Not Set';
        $amountDue = $transaction->total_amount - $transaction->amount_paid;
        $currency = strtoupper($transaction->currency);

        $subject = "Reminder: Payment Due on " . $dueDate;

        $body = '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
            <p style="color: #4a90e2; font-size: 18px; margin-bottom: 20px;">Hello ' . e($sponsorName) . ',</p>
            
            <p style="color: #333; line-height: 1.6; margin-bottom: 20px;">
                This is a reminder that your next payment for <strong>' . e($studentName) . '</strong> is due on <strong>' . $dueDate . '</strong>.
            </p>
            
            <table style="width: 100%; border-collapse: collapse; margin: 20px 0; background-color: #f9f9f9;">
                <thead>
                    <tr style="background-color: #e8e8e8;">
                        <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Sponsor</th>
                        <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Student</th>
                        <th style="border: 1px solid #ddd; padding: 12px; text-align: right;">Total</th>
                        <th style="border: 1px solid #ddd; padding: 12px; text-align: right;">Paid</th>
                        <th style="border: 1px solid #ddd; padding: 12px; text-align: right;">Remaining</th>
                        <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 12px;">' . e($sponsorName) . '</td>
                        <td style="border: 1px solid #ddd; padding: 12px;">' . e($studentName) . '</td>
                        <td style="border: 1px solid #ddd; padding: 12px; text-align: right;">' . number_format($transaction->total_amount, 2) . ' ' . $currency . '</td>
                        <td style="border: 1px solid #ddd; padding: 12px; text-align: right;">' . number_format($transaction->amount_paid, 2) . ' ' . $currency . '</td>
                        <td style="border: 1px solid #ddd; padding: 12px; text-align: right;">' . number_format($amountDue, 2) . ' ' . $currency . '</td>
                        <td style="border: 1px solid #ddd; padding: 12px;">' . $dueDate . '</td>
                    </tr>
                </tbody>
            </table>
            
            <p style="color: #333; line-height: 1.6; margin-top: 20px;">
                Thank you for your support.<br>
                <strong>' . config('app.name') . '</strong>
            </p>
        </div>';

        return [
            'subject' => $subject,
            'body' => $body,
        ];
    }

    /* ======================== AJAX SEARCH METHODS ======================== */

    /**
     * Search sponsors (for Select2)
     */
    public function searchSponsors(Request $request)
    {
        $q = $request->get('q', '');
        $page = $request->get('page', 1);
        $perPage = 20;

        $query = Sponsor::query();
        
        if ($q) {
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
        }

        $total = $query->count();
        $sponsors = $query->orderBy('name')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $results = $sponsors->map(function($sponsor) {
            return [
                'id' => $sponsor->id,
                'text' => $sponsor->name,
                'name' => $sponsor->name,
                'meta' => $sponsor->email ?? '',
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => ['more' => ($page * $perPage) < $total]
        ]);
    }

    /**
     * Search school students (for Select2)
     */
    public function searchSchoolStudents(Request $request)
    {
        $q = $request->get('q', '');
        $page = $request->get('page', 1);
        $perPage = 20;

        $query = SchoolStudent::query();
        
        if ($q) {
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('school_internal_id', 'like', "%{$q}%");
        }

        $total = $query->count();
        $students = $query->orderBy('name')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $results = $students->map(function($student) {
            return [
                'id' => $student->id,
                'text' => $student->name,
                'name' => $student->name,
                'meta' => $student->school_internal_id ?? '',
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => ['more' => ($page * $perPage) < $total]
        ]);
    }

    /**
     * Search university students (for Select2)
     */
    public function searchUniversityStudents(Request $request)
    {
        $q = $request->get('q', '');
        $page = $request->get('page', 1);
        $perPage = 20;

        $query = UniversityStudent::query();
        
        if ($q) {
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('university_internal_id', 'like', "%{$q}%");
        }

        $total = $query->count();
        $students = $query->orderBy('name')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $results = $students->map(function($student) {
            return [
                'id' => $student->id,
                'text' => $student->name,
                'name' => $student->name,
                'meta' => $student->university_internal_id ?? '',
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => ['more' => ($page * $perPage) < $total]
        ]);
    }
}
