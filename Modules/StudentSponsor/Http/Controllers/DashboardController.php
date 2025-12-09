<?php

namespace Modules\StudentSponsor\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\StudentSponsor\Models\SchoolStudent;
use Modules\StudentSponsor\Models\UniversityStudent;
use Modules\StudentSponsor\Models\Sponsor;
use Modules\StudentSponsor\Models\SponsorTransaction;
use Modules\StudentSponsor\Models\SponsorPayment;

class DashboardController extends AdminController
{
    public function index()
    {
        $stats = [
            'school_students' => SchoolStudent::count(),
            'university_students' => UniversityStudent::count(),
            'sponsors' => Sponsor::count(),
            'active_sponsors' => Sponsor::where('active', 1)->count(),
            'transactions' => SponsorTransaction::count(),
            'total_payments' => SponsorPayment::sum('amount') ?? 0,
        ];

        return $this->moduleView('studentsponsor::dashboard', compact('stats'));
    }
}
