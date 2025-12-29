<?php

namespace Modules\StudentSponsorship\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\StudentSponsorship\Models\SchoolName;
use Modules\StudentSponsorship\Models\UniversityName;
use Modules\StudentSponsorship\Models\UniversityProgram;

class MasterDataController extends AdminController
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'schools');
        
        // Schools with student count
        $schools = SchoolName::withCount('students')->orderBy('name')->get();
        
        // Universities with student count
        $universities = UniversityName::withCount('students')->orderBy('name')->get();
        
        // Programs with student count
        $programs = UniversityProgram::withCount('students')->orderBy('name')->get();
        
        // Banks - count from both school_students and university_students
        $banks = DB::table('banks')
            ->select('banks.id', 'banks.name', 'banks.created_on')
            ->selectRaw('(SELECT COUNT(*) FROM school_students WHERE bank_id = banks.id) + (SELECT COUNT(*) FROM university_students WHERE bank_id = banks.id) as students_count')
            ->orderBy('name')
            ->get();
        
        return view('studentsponsorship::master-data.index', compact(
            'schools', 'universities', 'programs', 'banks', 'tab'
        ));
    }
    
    // Store School
    public function storeSchool(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:school_names,name']);
        SchoolName::create(['name' => $request->name]);
        return redirect()->route('admin.studentsponsorship.master-data.index', ['tab' => 'schools'])
            ->with('success', 'School created successfully');
    }
    
    // Update School
    public function updateSchool(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255|unique:school_names,name,' . $id]);
        SchoolName::findOrFail($id)->update(['name' => $request->name]);
        return redirect()->route('admin.studentsponsorship.master-data.index', ['tab' => 'schools'])
            ->with('success', 'School updated successfully');
    }
    
    // Delete School
    public function deleteSchool($id)
    {
        $school = SchoolName::findOrFail($id);
        $count = DB::table('school_students')->where('school_id', $id)->count();
        if ($count > 0) {
            return redirect()->route('admin.studentsponsorship.master-data.index', ['tab' => 'schools'])
                ->with('error', 'Cannot delete - ' . $count . ' students linked');
        }
        $school->delete();
        return redirect()->route('admin.studentsponsorship.master-data.index', ['tab' => 'schools'])
            ->with('success', 'School deleted successfully');
    }
    
    // Store University
    public function storeUniversity(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:university_names,name']);
        UniversityName::create(['name' => $request->name]);
        return redirect()->route('admin.studentsponsorship.master-data.index', ['tab' => 'universities'])
            ->with('success', 'University created successfully');
    }
    
    // Update University
    public function updateUniversity(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255|unique:university_names,name,' . $id]);
        UniversityName::findOrFail($id)->update(['name' => $request->name]);
        return redirect()->route('admin.studentsponsorship.master-data.index', ['tab' => 'universities'])
            ->with('success', 'University updated successfully');
    }
    
    // Delete University
    public function deleteUniversity($id)
    {
        $uni = UniversityName::findOrFail($id);
        $count = DB::table('university_students')->where('university_name_id', $id)->count();
        if ($count > 0) {
            return redirect()->route('admin.studentsponsorship.master-data.index', ['tab' => 'universities'])
                ->with('error', 'Cannot delete - ' . $count . ' students linked');
        }
        $uni->delete();
        return redirect()->route('admin.studentsponsorship.master-data.index', ['tab' => 'universities'])
            ->with('success', 'University deleted successfully');
    }
    
    // Store Program
    public function storeProgram(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:university_programs,name']);
        UniversityProgram::create(['name' => $request->name]);
        return redirect()->route('admin.studentsponsorship.master-data.index', ['tab' => 'programs'])
            ->with('success', 'Program created successfully');
    }
    
    // Update Program
    public function updateProgram(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255|unique:university_programs,name,' . $id]);
        UniversityProgram::findOrFail($id)->update(['name' => $request->name]);
        return redirect()->route('admin.studentsponsorship.master-data.index', ['tab' => 'programs'])
            ->with('success', 'Program updated successfully');
    }
    
    // Delete Program
    public function deleteProgram($id)
    {
        $program = UniversityProgram::findOrFail($id);
        $count = DB::table('university_students')->where('university_program_id', $id)->count();
        if ($count > 0) {
            return redirect()->route('admin.studentsponsorship.master-data.index', ['tab' => 'programs'])
                ->with('error', 'Cannot delete - ' . $count . ' students linked');
        }
        $program->delete();
        return redirect()->route('admin.studentsponsorship.master-data.index', ['tab' => 'programs'])
            ->with('success', 'Program deleted successfully');
    }
    
    // Store Bank
    public function storeBank(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:banks,name']);
        DB::table('banks')->insert(['name' => $request->name, 'created_on' => now()]);
        return redirect()->route('admin.studentsponsorship.master-data.index', ['tab' => 'banks'])
            ->with('success', 'Bank created successfully');
    }
    
    // Update Bank
    public function updateBank(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255|unique:banks,name,' . $id]);
        DB::table('banks')->where('id', $id)->update(['name' => $request->name]);
        return redirect()->route('admin.studentsponsorship.master-data.index', ['tab' => 'banks'])
            ->with('success', 'Bank updated successfully');
    }
    
    // Delete Bank
    public function deleteBank($id)
    {
        $count = DB::table('school_students')->where('bank_id', $id)->count() 
               + DB::table('university_students')->where('bank_id', $id)->count();
        if ($count > 0) {
            return redirect()->route('admin.studentsponsorship.master-data.index', ['tab' => 'banks'])
                ->with('error', 'Cannot delete - ' . $count . ' students linked');
        }
        DB::table('banks')->where('id', $id)->delete();
        return redirect()->route('admin.studentsponsorship.master-data.index', ['tab' => 'banks'])
            ->with('success', 'Bank deleted successfully');
    }
}
