<?php

namespace Modules\StudentSponsorship\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\StudentSponsorship\Models\Sponsor;
use Modules\StudentSponsorship\Models\SchoolStudent;
use Modules\StudentSponsorship\Models\UniversityStudent;
use Modules\StudentSponsorship\Models\SponsorTransaction;
use Modules\StudentSponsorship\Models\SponsorPayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends AdminController
{
    /**
     * Display dashboard
     */
    public function index()
    {
        // Quick stats
        $stats = $this->getQuickStats();
        
        // Recent activity
        $recentPayments = SponsorPayment::with('transaction.sponsor')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        $recentTransactions = SponsorTransaction::with('sponsor')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Top sponsors by total paid
        $topSponsors = Sponsor::select('sponsors.*')
            ->selectRaw('COALESCE(SUM(st.amount_paid), 0) as total_paid')
            ->leftJoin('sponsor_transactions as st', 'sponsors.id', '=', 'st.sponsor_id')
            ->groupBy('sponsors.id')
            ->orderByDesc('total_paid')
            ->take(5)
            ->get();
        
        return view('studentsponsorship::dashboard.index', compact(
            'stats',
            'recentPayments',
            'recentTransactions',
            'topSponsors'
        ));
    }

    /**
     * Get quick stats
     */
    protected function getQuickStats(): array
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfYear = $now->copy()->startOfYear();
        
        // Student counts
        $schoolStudents = SchoolStudent::where('status', 'inprogress')->count();
        $universityStudents = UniversityStudent::where('status', 'inprogress')->count();
        $completedSchool = SchoolStudent::where('status', 'complete')->count();
        $completedUniversity = UniversityStudent::where('status', 'complete')->count();
        
        // Sponsor counts
        $totalSponsors = Sponsor::count();
        $activeSponsors = Sponsor::whereHas('transactions', function($q) {
            $q->whereIn('status', ['pending', 'partial']);
        })->count();
        
        // Transaction stats
        $totalTransactions = SponsorTransaction::count();
        $pendingTransactions = SponsorTransaction::whereIn('status', ['pending', 'partial'])->count();
        $completedTransactions = SponsorTransaction::where('status', 'completed')->count();
        
        // Payment stats
        $totalCollected = SponsorPayment::sum('amount');
        $thisMonthCollected = SponsorPayment::where('payment_date', '>=', $startOfMonth)->sum('amount');
        $thisYearCollected = SponsorPayment::where('payment_date', '>=', $startOfYear)->sum('amount');
        
        // Outstanding balance
        $totalExpected = SponsorTransaction::sum('total_amount');
        $totalPaid = SponsorTransaction::sum('amount_paid');
        $outstandingBalance = max(0, $totalExpected - $totalPaid);
        
        // Payments today
        $todayPayments = SponsorPayment::whereDate('payment_date', today())->count();
        $todayAmount = SponsorPayment::whereDate('payment_date', today())->sum('amount');
        
        return [
            'school_students' => $schoolStudents,
            'university_students' => $universityStudents,
            'completed_school' => $completedSchool,
            'completed_university' => $completedUniversity,
            'total_students' => $schoolStudents + $universityStudents,
            'total_completed' => $completedSchool + $completedUniversity,
            
            'total_sponsors' => $totalSponsors,
            'active_sponsors' => $activeSponsors,
            
            'total_transactions' => $totalTransactions,
            'pending_transactions' => $pendingTransactions,
            'completed_transactions' => $completedTransactions,
            
            'total_collected' => $totalCollected,
            'this_month_collected' => $thisMonthCollected,
            'this_year_collected' => $thisYearCollected,
            'outstanding_balance' => $outstandingBalance,
            
            'today_payments' => $todayPayments,
            'today_amount' => $todayAmount,
        ];
    }

    /**
     * Get chart data via AJAX
     */
    public function chartData(): JsonResponse
    {
        // Monthly payments for last 12 months
        $monthlyPayments = SponsorPayment::select(
                DB::raw('YEAR(payment_date) as year'),
                DB::raw('MONTH(payment_date) as month'),
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->where('payment_date', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Student status distribution
        $studentDistribution = [
            'school_inprogress' => SchoolStudent::where('status', 'inprogress')->count(),
            'school_completed' => SchoolStudent::where('status', 'complete')->count(),
            'university_inprogress' => UniversityStudent::where('status', 'inprogress')->count(),
            'university_completed' => UniversityStudent::where('status', 'complete')->count(),
        ];
        
        // Transaction status
        $transactionStatus = [
            'pending' => SponsorTransaction::where('status', 'pending')->count(),
            'partial' => SponsorTransaction::where('status', 'partial')->count(),
            'completed' => SponsorTransaction::where('status', 'completed')->count(),
            'cancelled' => SponsorTransaction::where('status', 'cancelled')->count(),
        ];
        
        // Payment methods distribution
        $paymentMethods = SponsorPayment::select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();
        
        return response()->json([
            'monthly_payments' => $monthlyPayments,
            'student_distribution' => $studentDistribution,
            'transaction_status' => $transactionStatus,
            'payment_methods' => $paymentMethods,
        ]);
    }
}
