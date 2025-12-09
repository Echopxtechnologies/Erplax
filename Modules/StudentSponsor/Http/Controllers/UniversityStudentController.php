<?php

namespace Modules\StudentSponsor\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\StudentSponsor\Models\UniversityStudent;
use Modules\StudentSponsor\Models\UniversityName;
use Modules\StudentSponsor\Models\UniversityProgram;
use Modules\StudentSponsor\Models\Bank;
use Modules\StudentSponsor\Models\Country;
use Modules\StudentSponsor\Models\Sponsor;
use Illuminate\Http\Request;

class UniversityStudentController extends AdminController
{
    /**
     * Display student list
     */
    public function index()
    {
        $stats = [
            'total' => UniversityStudent::count(),
            'sponsored' => UniversityStudent::whereNotNull('sponsor_id')->count(),
            'unsponsored' => UniversityStudent::whereNull('sponsor_id')->count(),
            'active' => UniversityStudent::where('active', 1)->count(),
        ];

        $universities = UniversityName::orderBy('name')->get();
        $programs = UniversityProgram::orderBy('name')->get();
        $sponsors = Sponsor::where('active', 1)->orderBy('name')->get();

        return $this->moduleView('studentsponsor::university.index', compact('stats', 'universities', 'programs', 'sponsors'));
    }

    /**
     * DataTable data
     */
    public function data(Request $request)
    {
        $query = UniversityStudent::with(['universityName', 'program', 'sponsor', 'country', 'bank'])
            ->select('tbluniversity_students.*');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact_no', 'like', "%{$search}%")
                  ->orWhere('university_internal_id', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('university_name_id')) {
            $query->where('university_name_id', $request->university_name_id);
        }
        if ($request->filled('university_program_id')) {
            $query->where('university_program_id', $request->university_program_id);
        }
        if ($request->filled('sponsor_id')) {
            $query->where('sponsor_id', $request->sponsor_id);
        }

        // Sorting
        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        // Pagination
        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function($item) {
            return [
                'id' => $item->id,
                'university_internal_id' => $item->university_internal_id ?? '-',
                'name' => $item->name,
                'email' => $item->email ?? '-',
                'contact_no' => $item->contact_no ?? '-',
                'university_name_text' => $item->universityName?->name ?? '-',
                'program_name' => $item->program?->name ?? '-',
                'university_year_of_study' => $item->university_year_of_study ?? '-',
                'university_semester' => $item->university_semester ?? '-',
                'age' => $item->university_age ?? '-',
                'city' => $item->city ?? '-',
                'sponsor_name' => $item->sponsor?->name ?? 'Not Sponsored',
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
        $universities = UniversityName::orderBy('name')->get();
        $programs = UniversityProgram::orderBy('name')->get();
        $banks = Bank::orderBy('name')->get();
        $countries = Country::orderBy('short_name')->get();
        $sponsors = Sponsor::where('active', 1)->orderBy('name')->get();
        $student = null;

        return $this->moduleView('studentsponsor::university.form', compact(
            'student', 'universities', 'programs', 'banks', 'countries', 'sponsors'
        ));
    }

    /**
     * Store new student
     */
    public function store(Request $request)
    {
        $validated = $this->validateStudent($request);

        // Calculate age from DOB
        if (!empty($validated['university_student_dob'])) {
            $validated['university_age'] = \Carbon\Carbon::parse($validated['university_student_dob'])->age;
        }

        $validated['entity_type'] = 'university';
        $validated['created_on'] = now();
        $validated['active'] = $request->has('active') ? 1 : 0;

        UniversityStudent::create($validated);

        return redirect()->route('admin.studentsponsor.university.index')
            ->with('success', 'University student created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $student = UniversityStudent::findOrFail($id);
        $universities = UniversityName::orderBy('name')->get();
        $programs = UniversityProgram::orderBy('name')->get();
        $banks = Bank::orderBy('name')->get();
        $countries = Country::orderBy('short_name')->get();
        $sponsors = Sponsor::where('active', 1)->orderBy('name')->get();

        return $this->moduleView('studentsponsor::university.form', compact(
            'student', 'universities', 'programs', 'banks', 'countries', 'sponsors'
        ));
    }

    /**
     * Update student
     */
    public function update(Request $request, $id)
    {
        $student = UniversityStudent::findOrFail($id);
        $validated = $this->validateStudent($request, $id);

        // Calculate age from DOB
        if (!empty($validated['university_student_dob'])) {
            $validated['university_age'] = \Carbon\Carbon::parse($validated['university_student_dob'])->age;
        }

        $validated['active'] = $request->has('active') ? 1 : 0;

        $student->update($validated);

        return redirect()->route('admin.studentsponsor.university.index')
            ->with('success', 'University student updated successfully!');
    }

    /**
     * Delete student
     */
    public function destroy($id)
    {
        $student = UniversityStudent::findOrFail($id);
        $student->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Student deleted successfully']);
        }

        return redirect()->route('admin.studentsponsor.university.index')
            ->with('success', 'University student deleted successfully!');
    }

    /**
     * Validation rules
     */
    protected function validateStudent(Request $request, $id = null)
    {
        $emailRule = 'nullable|email|max:255';
        $internalIdRule = 'nullable|string|max:100';
        $accountNoRule = 'nullable|string|max:50';

        if ($id) {
            $emailRule .= '|unique:tbluniversity_students,email,' . $id;
            $internalIdRule .= '|unique:tbluniversity_students,university_internal_id,' . $id;
            $accountNoRule .= '|unique:tbluniversity_students,university_bank_account_no,' . $id;
        } else {
            $emailRule .= '|unique:tbluniversity_students,email';
            $internalIdRule .= '|unique:tbluniversity_students,university_internal_id';
            $accountNoRule .= '|unique:tbluniversity_students,university_bank_account_no';
        }

        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => $emailRule,
            'contact_no' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'country_id' => 'nullable|integer|exists:tblcountries,country_id',
            'university_student_dob' => 'nullable|date|before:today',
            'university_age' => 'nullable|integer|min:16|max:80',
            'university_internal_id' => $internalIdRule,
            'university_name_id' => 'nullable|integer|exists:tbluniversity_name,id',
            'university_program_id' => 'nullable|integer|exists:tbluniversity_program,id',
            'university_year_of_study' => 'nullable|string|max:50',
            'university_semester' => 'nullable|string|max:50',
            'bank_id' => 'nullable|integer|exists:tblbank,id',
            'university_bank_branch_number' => 'nullable|string|max:50',
            'university_bank_branch_info' => 'nullable|string|max:255',
            'university_bank_account_no' => $accountNoRule,
            'sponsor_id' => 'nullable|integer|exists:tblsponsor_records,id',
            'university_sponsorship_start_date' => 'nullable|date',
            'university_sponsorship_end_date' => 'nullable|date|after_or_equal:university_sponsorship_start_date',
            'university_introducedby' => 'nullable|string|max:255',
            'university_introducedph' => 'nullable|string|max:50',
            'university_father_name' => 'nullable|string|max:255',
            'university_mother_name' => 'nullable|string|max:255',
            'university_father_income' => 'nullable|numeric|min:0',
            'university_mother_income' => 'nullable|numeric|min:0',
            'university_guardian_name' => 'nullable|string|max:255',
            'university_guardian_income' => 'nullable|numeric|min:0',
            'background_info' => 'nullable|string|max:2000',
            'internal_comment' => 'nullable|string|max:2000',
            'external_comment' => 'nullable|string|max:2000',
        ]);
    }
}