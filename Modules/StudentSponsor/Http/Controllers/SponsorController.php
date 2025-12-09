<?php

namespace Modules\StudentSponsor\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\StudentSponsor\Models\Sponsor;
use Modules\StudentSponsor\Models\Bank;
use Modules\StudentSponsor\Models\Country;
use Modules\StudentSponsor\Models\SchoolStudent;
use Modules\StudentSponsor\Models\UniversityStudent;
use Illuminate\Http\Request;

class SponsorController extends AdminController
{
    /**
     * Display sponsor list
     */
    public function index()
    {
        $stats = [
            'total' => Sponsor::count(),
            'active' => Sponsor::where('active', 1)->count(),
            'inactive' => Sponsor::where('active', 0)->count(),
            'total_school_students' => SchoolStudent::whereNotNull('sponsor_id')->count(),
            'total_university_students' => UniversityStudent::whereNotNull('sponsor_id')->count(),
        ];

        $countries = Country::orderBy('short_name')->get();

        return $this->moduleView('studentsponsor::sponsor.index', compact('stats', 'countries'));
    }

    /**
     * DataTable data
     */
    public function data(Request $request)
    {
        $query = Sponsor::with(['country', 'bank'])
            ->withCount(['schoolStudents', 'universityStudents'])
            ->select('tblsponsor_records.*');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact_no', 'like', "%{$search}%")
                  ->orWhere('sponsor_occupation', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('sponsor_type')) {
            $query->where('sponsor_type', $request->sponsor_type);
        }
        if ($request->filled('status')) {
            $query->where('active', $request->status === 'active' ? 1 : 0);
        }

        // Sorting
        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        // Pagination
        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function($item) {
            $totalStudents = ($item->school_students_count ?? 0) + ($item->university_students_count ?? 0);
            
            return [
                'id' => $item->id,
                'name' => $item->name,
                'sponsor_type' => ucfirst($item->sponsor_type ?? '-'),
                'email' => $item->email ?? '-',
                'contact_no' => $item->contact_no ?? '-',
                'occupation' => $item->sponsor_occupation ?? '-',
                'country_name' => $item->country?->short_name ?? '-',
                'city' => $item->city ?? '-',
                'frequency' => ucfirst(str_replace('_', ' ', $item->sponsor_frequency ?? '-')),
                'total_students' => $totalStudents,
                'status' => $item->active ? 'Active' : 'Inactive',
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $banks = Bank::orderBy('name')->get();
        $countries = Country::orderBy('short_name')->get();
        $sponsor = null;
        $schoolStudents = collect();
        $universityStudents = collect();

        return $this->moduleView('studentsponsor::sponsor.form', compact(
            'sponsor', 'banks', 'countries', 'schoolStudents', 'universityStudents'
        ));
    }

    /**
     * Store new sponsor
     */
    public function store(Request $request)
    {
        $validated = $this->validateSponsor($request);
        
        $validated['entity_type'] = 'sponsor';
        $validated['created_on'] = now();
        $validated['active'] = $request->has('active') ? 1 : 0;

        Sponsor::create($validated);

        return redirect()->route('admin.studentsponsor.sponsor.index')
            ->with('success', 'Sponsor created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $sponsor = Sponsor::findOrFail($id);
        $banks = Bank::orderBy('name')->get();
        $countries = Country::orderBy('short_name')->get();

        // Get sponsored students
        $schoolStudents = SchoolStudent::where('sponsor_id', $id)->get();
        $universityStudents = UniversityStudent::where('sponsor_id', $id)->get();

        return $this->moduleView('studentsponsor::sponsor.form', compact(
            'sponsor', 'banks', 'countries', 'schoolStudents', 'universityStudents'
        ));
    }

    /**
     * Update sponsor
     */
    public function update(Request $request, $id)
    {
        $sponsor = Sponsor::findOrFail($id);
        $validated = $this->validateSponsor($request, $id);
        
        $validated['active'] = $request->has('active') ? 1 : 0;

        $sponsor->update($validated);

        return redirect()->route('admin.studentsponsor.sponsor.index')
            ->with('success', 'Sponsor updated successfully!');
    }

    /**
     * Delete sponsor
     */
    public function destroy($id)
    {
        $sponsor = Sponsor::findOrFail($id);

        // Remove sponsor from students
        SchoolStudent::where('sponsor_id', $id)->update(['sponsor_id' => null]);
        UniversityStudent::where('sponsor_id', $id)->update(['sponsor_id' => null]);

        $sponsor->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Sponsor deleted successfully']);
        }

        return redirect()->route('admin.studentsponsor.sponsor.index')
            ->with('success', 'Sponsor deleted successfully!');
    }

    /**
     * Validation rules
     */
    protected function validateSponsor(Request $request, $id = null)
    {
        $emailRule = 'nullable|email|max:255';
        $accountNoRule = 'nullable|string|max:50';

        if ($id) {
            $emailRule .= '|unique:tblsponsor_records,email,' . $id;
            $accountNoRule .= '|unique:tblsponsor_records,sponsor_bank_account_no,' . $id;
        } else {
            $emailRule .= '|unique:tblsponsor_records,email';
            $accountNoRule .= '|unique:tblsponsor_records,sponsor_bank_account_no';
        }

        return $request->validate([
            'name' => 'required|string|max:255',
            'sponsor_type' => 'nullable|string|in:individual,company,organization,trust,charity',
            'sponsor_occupation' => 'nullable|string|max:255',
            'email' => $emailRule,
            'contact_no' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'country_id' => 'nullable|integer|exists:tblcountries,country_id',
            'bank_id' => 'nullable|integer|exists:tblbank,id',
            'sponsor_bank_branch_info' => 'nullable|string|max:255',
            'sponsor_bank_branch_number' => 'nullable|string|max:50',
            'sponsor_bank_account_no' => $accountNoRule,
            'sponsor_frequency' => 'nullable|string|in:monthly,quarterly,bi_annually,yearly,one_time',
            'membership_start_date' => 'nullable|date',
            'membership_end_date' => 'nullable|date|after_or_equal:membership_start_date',
        ]);
    }
}