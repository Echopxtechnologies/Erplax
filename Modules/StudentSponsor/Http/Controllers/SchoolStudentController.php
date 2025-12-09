<?php

namespace Modules\StudentSponsor\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\StudentSponsor\Models\SchoolStudent;
use Modules\StudentSponsor\Models\SchoolName;
use Modules\StudentSponsor\Models\Bank;
use Modules\StudentSponsor\Models\Country;
use Modules\StudentSponsor\Models\Sponsor;
use Modules\StudentSponsor\Models\SchoolReportCard;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SchoolStudentController extends AdminController
{
    /**
     * Display student list
     */
    public function index()
    {
        $stats = [
            'total' => SchoolStudent::count(),
            'sponsored' => SchoolStudent::whereNotNull('sponsor_id')->count(),
            'unsponsored' => SchoolStudent::whereNull('sponsor_id')->count(),
        ];

        $schools = SchoolName::orderBy('name')->get();
        $sponsors = Sponsor::where('active', 1)->orderBy('name')->get();

        return $this->moduleView('studentsponsor::school.index', compact('stats', 'schools', 'sponsors'));
    }

    /**
     * DataTable AJAX data
     */
    public function data(Request $request)
    {
        $query = SchoolStudent::with(['schoolName', 'sponsor', 'country', 'bank'])
            ->select('tblschool_students.*');

        // Search filter
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('contact_no', 'like', "%{$search}%")
                    ->orWhere('school_internal_id', 'like', "%{$search}%")
                    ->orWhere('school_id', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // School filter
        if ($request->filled('school_name_id')) {
            $query->where('school_name_id', $request->school_name_id);
        }

        // Sponsor filter
        if ($request->filled('sponsor_id')) {
            $query->where('sponsor_id', $request->sponsor_id);
        }

        // Grade filter
        if ($request->filled('grade')) {
            $query->where('school_grade', $request->grade);
        }

        // Sorting
        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        // Pagination
        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'school_internal_id' => $item->school_internal_id ?? '-',
                'school_id' => $item->school_id ?? '-',
                'name' => $item->name,
                'email' => $item->email ?? '-',
                'contact_no' => $item->contact_no ?? '-',
                'school_name' => $item->schoolName?->name ?? '-',
                'school_type' => $item->school_type ?? '-',
                'school_grade' => $item->school_grade ?? '-',
                'school_age' => $item->school_age ?? '-',
                'city' => $item->city ?? '-',
                'sponsor_name' => $item->sponsor?->name ?? 'Not Sponsored',
                
                // Action URLs for DataTable
                '_edit_url' => route('admin.studentsponsor.school.edit', $item->id),
                '_show_url' => route('admin.studentsponsor.school.edit', $item->id),
                '_delete_url' => route('admin.studentsponsor.school.destroy', $item->id),
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
        $student = null;
        $schools = SchoolName::orderBy('name')->get();
        $banks = Bank::orderBy('name')->get();
        $countries = Country::orderBy('short_name')->get();
        $sponsors = Sponsor::where('active', 1)->orderBy('name')->get();
        $sponsorHistory = [];

        return $this->moduleView('studentsponsor::school.form', compact(
            'student',
            'schools',
            'banks',
            'countries',
            'sponsors',
            'sponsorHistory'
        ));
    }

    /**
     * Store new student
     */
    public function store(Request $request)
    {
        $validated = $this->validateStudent($request);

        // Calculate age from DOB
        if (!empty($validated['school_student_dob'])) {
            $validated['school_age'] = Carbon::parse($validated['school_student_dob'])->age;
        }

        // Generate internal ID if not provided
        if (empty($validated['school_internal_id'])) {
            $validated['school_internal_id'] = SchoolStudent::generateInternalId();
        }

        $validated['entity_type'] = 'school';
        $validated['created_on'] = now();

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = file_get_contents($request->file('profile_photo')->getRealPath());
        }

        $student = SchoolStudent::create($validated);

        return redirect()->route('admin.studentsponsor.school.index')
            ->with('success', 'School student created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $student = SchoolStudent::with(['schoolName', 'sponsor', 'bank', 'country'])->findOrFail($id);
        $schools = SchoolName::orderBy('name')->get();
        $banks = Bank::orderBy('name')->get();
        $countries = Country::orderBy('short_name')->get();
        $sponsors = Sponsor::where('active', 1)->orderBy('name')->get();
        $sponsorHistory = $student->getSponsorHistory();

        return $this->moduleView('studentsponsor::school.form', compact(
            'student',
            'schools',
            'banks',
            'countries',
            'sponsors',
            'sponsorHistory'
        ));
    }

    /**
     * Update student
     */
    public function update(Request $request, $id)
    {
        $student = SchoolStudent::findOrFail($id);
        $validated = $this->validateStudent($request, $id);

        // Calculate age from DOB
        if (!empty($validated['school_student_dob'])) {
            $validated['school_age'] = Carbon::parse($validated['school_student_dob'])->age;
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = file_get_contents($request->file('profile_photo')->getRealPath());
        }

        $student->update($validated);

        $activeTab = $request->input('active_tab', 'student-info');

        return redirect()->route('admin.studentsponsor.school.edit', $id)
            ->with('success', 'School student updated successfully!')
            ->with('active_tab', $activeTab);
    }

    /**
     * Delete student
     */
    public function destroy($id)
    {
        $student = SchoolStudent::findOrFail($id);
        $student->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Student deleted successfully']);
        }

        return redirect()->route('admin.studentsponsor.school.index')
            ->with('success', 'School student deleted successfully!');
    }

    /**
     * Display student photo
     */
    public function displayPhoto($id)
    {
        $student = SchoolStudent::select('profile_photo')->findOrFail($id);

        if (empty($student->profile_photo)) {
            abort(404);
        }

        return response($student->profile_photo)
            ->header('Content-Type', 'image/jpeg')
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * Add new school name (AJAX)
     */
    public function addSchoolName(Request $request)
    {
        try {
            $request->validate(['name' => 'required|string|max:255']);

            $name = trim($request->name);

            // Check if already exists
            $existing = SchoolName::where('name', $name)->first();
            if ($existing) {
                return response()->json([
                    'success' => true,
                    'id' => $existing->id,
                    'name' => $existing->name,
                    'message' => 'School already exists'
                ]);
            }

            $school = new SchoolName();
            $school->name = $name;
            $school->save();

            return response()->json([
                'success' => true,
                'id' => $school->id,
                'name' => $school->name,
                'message' => 'School added successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Add School Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding school: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add new bank (AJAX)
     */
    public function addBank(Request $request)
    {
        try {
            $request->validate(['name' => 'required|string|max:255']);

            $name = trim($request->name);

            // Check if already exists
            $existing = Bank::where('name', $name)->first();
            if ($existing) {
                return response()->json([
                    'success' => true,
                    'id' => $existing->id,
                    'name' => $existing->name,
                    'message' => 'Bank already exists'
                ]);
            }

            $bank = new Bank();
            $bank->name = $name;
            $bank->save();

            return response()->json([
                'success' => true,
                'id' => $bank->id,
                'name' => $bank->name,
                'message' => 'Bank added successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Add Bank Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding bank: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add new country (AJAX)
     */
    public function addCountry(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone_code' => 'required|string|max:10'
            ]);

            $name = trim($request->name);
            $phoneCode = trim($request->phone_code);

            // Check if already exists
            $existing = Country::where('short_name', $name)->first();
            if ($existing) {
                return response()->json([
                    'success' => true,
                    'id' => $existing->id,
                    'name' => $existing->short_name,
                    'phone_code' => $existing->calling_code ?? $existing->phone_code,
                    'message' => 'Country already exists'
                ]);
            }

            $country = new Country();
            $country->short_name = $name;
            $country->calling_code = $phoneCode;
            $country->save();

            return response()->json([
                'success' => true,
                'id' => $country->id,
                'name' => $country->short_name,
                'phone_code' => $country->calling_code,
                'message' => 'Country added successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Add Country Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding country: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get report cards for student
     */
    public function getReportCards($studentId)
    {
        try {
            $reportCards = SchoolReportCard::where('student_school_id', $studentId)
                ->orderBy('upload_date', 'desc')
                ->get()
                ->map(function ($card) {
                    return [
                        'id' => $card->id,
                        'filename' => $card->filename,
                        'term' => $card->term,
                        'upload_date' => $card->upload_date ? Carbon::parse($card->upload_date)->format('M d, Y') : null,
                        'file_size' => $card->file_size,
                        'download_url' => route('admin.studentsponsor.school.download-report-card', $card->id),
                    ];
                });

            return response()->json([
                'success' => true,
                'report_cards' => $reportCards
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'report_cards' => [],
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Upload report card
     */
    public function uploadReportCard(Request $request)
    {
        try {
            $request->validate([
                'student_school_id' => 'required|integer|exists:tblschool_students,id',
                'filename' => 'required|string|max:255',
                'term' => 'required|string|max:50',
                'upload_date' => 'required|date',
                'report_card_file' => 'required|file|mimes:pdf,jpg,jpeg,png,gif|max:10240'
            ]);

            $file = $request->file('report_card_file');
            $path = $file->store('report_cards', 'public');

            $reportCard = SchoolReportCard::create([
                'student_school_id' => $request->student_school_id,
                'filename' => $request->filename,
                'term' => $request->term,
                'upload_date' => $request->upload_date,
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Report card uploaded successfully',
                'report_card' => $reportCard
            ]);

        } catch (\Exception $e) {
            Log::error('Upload Report Card Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error uploading: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download report card
     */
    public function downloadReportCard($id)
    {
        $reportCard = SchoolReportCard::findOrFail($id);

        if (!Storage::disk('public')->exists($reportCard->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download(
            $reportCard->file_path,
            $reportCard->filename . '.' . pathinfo($reportCard->file_path, PATHINFO_EXTENSION)
        );
    }

    /**
     * Delete report card
     */
    public function deleteReportCard($id)
    {
        try {
            $reportCard = SchoolReportCard::findOrFail($id);

            if (Storage::disk('public')->exists($reportCard->file_path)) {
                Storage::disk('public')->delete($reportCard->file_path);
            }

            $reportCard->delete();

            return response()->json([
                'success' => true,
                'message' => 'Report card deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validation rules
     */
    protected function validateStudent(Request $request, $id = null)
    {
        $emailRule = 'nullable|email|max:255';
        $internalIdRule = 'nullable|string|max:100';
        $accountNoRule = 'nullable|string|max:100';

        if ($id) {
            $emailRule .= '|unique:tblschool_students,email,' . $id;
            $internalIdRule .= '|unique:tblschool_students,school_internal_id,' . $id;
            $accountNoRule .= '|unique:tblschool_students,school_bank_account_no,' . $id;
        } else {
            $emailRule .= '|unique:tblschool_students,email';
            $internalIdRule .= '|unique:tblschool_students,school_internal_id';
            $accountNoRule .= '|unique:tblschool_students,school_bank_account_no';
        }

        return $request->validate([
            // Basic Info
            'name' => 'required|string|max:255',
            'email' => $emailRule,
            'contact_no' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'city' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'country_id' => 'nullable|integer',
            'school_student_dob' => 'nullable|date|before:today',
            'school_id' => 'nullable|string|max:100',
            'school_internal_id' => $internalIdRule,

            // School Info
            'school_name_id' => 'nullable|integer',
            'school_type' => 'nullable|string|max:20',
            'school_grade' => 'nullable|string|max:20',
            'school_grade_year' => 'nullable|integer',
            'grade_mismatch_reason' => 'nullable|string|max:1000',

            // Bank Info
            'bank_id' => 'nullable|integer',
            'school_bank_branch_number' => 'nullable|string|max:100',
            'school_bank_branch_info' => 'nullable|string|max:255',
            'school_bank_account_no' => $accountNoRule,

            // Family Info
            'school_father_name' => 'nullable|string|max:255',
            'school_mother_name' => 'nullable|string|max:255',
            'school_father_income' => 'nullable|numeric|min:0',
            'school_mother_income' => 'nullable|numeric|min:0',
            'school_guardian_name' => 'nullable|string|max:255',
            'school_guardian_income' => 'nullable|numeric|min:0',
            'background_info' => 'nullable|string|max:2000',

            // Sponsorship
            'sponsor_id' => 'nullable|integer',
            'school_sponsorship_start_date' => 'nullable|date',
            'school_sponsorship_end_date' => 'nullable|date|after_or_equal:school_sponsorship_start_date',
            'school_introducedby' => 'nullable|string|max:255',
            'school_introducedph' => 'nullable|string|max:50',

            // Additional Info
            'internal_comment' => 'nullable|string|max:2000',
            'external_comment' => 'nullable|string|max:2000',
        ]);
    }
}