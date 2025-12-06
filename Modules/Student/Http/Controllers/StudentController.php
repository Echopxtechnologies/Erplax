<?php

namespace Modules\Student\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\Core\Traits\DataTableTrait;
use Modules\Student\Models\Student;
use Illuminate\Http\Request;

class StudentController extends AdminController
{
    use DataTableTrait;

    // DataTable Configuration
    protected $model = Student::class;
    protected $searchable = ['name', 'email', 'phone', 'course'];
    protected $exportable = ['id', 'name', 'email', 'phone', 'course', 'status', 'admission_date', 'created_at'];
    protected $routePrefix = 'admin.student';

    public function index()
    {
        return view('student::index');
    }

    public function create()
    {
        return view('student::create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'course' => 'nullable|string|max:255',
            'status' => 'in:active,inactive,graduated',
            'admission_date' => 'nullable|date',
        ]);

        Student::create($validated);

        return redirect()->route('admin.student.index')->with('success', 'Student created successfully');
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);
        return view('student::show', compact('student'));
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('student::edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'course' => 'nullable|string|max:255',
            'status' => 'in:active,inactive,graduated',
            'admission_date' => 'nullable|date',
        ]);

        $student->update($validated);

        return redirect()->route('admin.student.index')->with('success', 'Student updated successfully');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Student deleted successfully']);
        }

        return redirect()->route('admin.student.index')->with('success', 'Student deleted successfully');
    }
}
