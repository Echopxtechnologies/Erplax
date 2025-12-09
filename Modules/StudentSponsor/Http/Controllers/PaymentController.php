<?php

namespace Modules\StudentSponsor\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\StudentSponsor\Models\SponsorPayment;
use Modules\StudentSponsor\Models\SponsorTransaction;
use Modules\StudentSponsor\Models\Sponsor;
use Illuminate\Http\Request;
use Modules\Core\Traits\DataTableTrait;

class PaymentController extends AdminController
{
    use DataTableTrait;

    protected $model = SponsorPayment::class;
    protected $searchable = ['note', 'sponsor.name', 'amount'];
    protected $exportable = ['id', 'transaction_id', 'sponsor_id', 'payment_date', 'amount', 'currency', 'note'];
    protected $routePrefix = 'admin.studentsponsor.payment';

    /**
     * Display payments list
     */
    public function index(Request $request)
    {
        $totalPayments = SponsorPayment::count();
        $totalAmount = SponsorPayment::sum('amount');

        return $this->moduleView('studentsponsor::payment.index', compact(
            'totalPayments',
            'totalAmount'
        ));
    }

    /**
     * DataTable data
     */
    public function dataTable(Request $request)
    {
        $query = SponsorPayment::with(['transaction', 'sponsor', 'transaction.schoolStudent', 'transaction.universityStudent'])
            ->select('tblsponsor_payments.*');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('note', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhereHas('sponsor', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('transaction.schoolStudent', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('transaction.universityStudent', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filters
        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('payment_date', '>=', $dateFrom);
        }
        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('payment_date', '<=', $dateTo);
        }
        if ($currency = $request->get('currency')) {
            $query->where('currency', $currency);
        }

        // Sorting
        $sortField = $request->get('sort', 'payment_date');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        // Pagination
        $perPage = $request->get('per_page', 25);
        $payments = $query->paginate($perPage);

        $data = $payments->map(function($payment) {
            $studentName = '-';
            $studentType = 'none';
            if ($payment->transaction) {
                if ($payment->transaction->school_student_id && $payment->transaction->schoolStudent) {
                    $studentName = $payment->transaction->schoolStudent->name;
                    $studentType = 'school';
                } elseif ($payment->transaction->university_student_id && $payment->transaction->universityStudent) {
                    $studentName = $payment->transaction->universityStudent->name;
                    $studentType = 'university';
                }
            }

            return [
                'id' => $payment->id,
                'payment_date' => $payment->payment_date ? $payment->payment_date->format('Y-m-d') : '-',
                'sponsor_name' => $payment->sponsor?->name ?? '-',
                'student_name' => $studentName,
                'student_type' => $studentType,
                'amount' => number_format($payment->amount, 2) . ' ' . $payment->currency,
                'note' => $payment->note ?? '-',
                'transaction_id' => $payment->transaction_id,
                '_show_url' => route('admin.studentsponsor.transaction.edit', ['id' => $payment->transaction_id, 'tab' => 'payments']),
                '_edit_url' => route('admin.studentsponsor.transaction.edit', ['id' => $payment->transaction_id, 'tab' => 'payments']),
            ];
        });

        return response()->json([
            'data' => $data,
            'total' => $payments->total(),
            'current_page' => $payments->currentPage(),
            'last_page' => $payments->lastPage(),
        ]);
    }
}
