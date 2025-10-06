<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::count() > 20
            ? Student::orderBy('name')->paginate(10)
            : Student::orderBy('name')->get();
        return view('admin.students.index', compact('students'));
    }

    public function showUploadForm()
    {
        return view('admin.students.upload');
    }

    public function uploadCSV(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('file');
        $rows = array_map('str_getcsv', file($file));
        $header = array_map('trim', array_shift($rows));

        $added = 0;
        $updated = 0;

        foreach ($rows as $row) {
            $data = array_combine($header, $row);

            $student = Student::updateOrCreate(
                ['name' => trim($data['Name'])],
                [
                    'exam_status' => trim($data['Exam Status']),
                    'attendance_status' => trim($data['Attendance Status']),
                    'fees_status' => trim($data['Fees Status']),
                    'graduation_status' => trim($data['Graduation Status'])
                ]
            );

            $student->wasRecentlyCreated ? $added++ : $updated++;
        }

        return redirect()
            ->back()
            ->with('success', "Upload successful. {$added} new students added, {$updated} updated.");
    }

    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'exam_status' => 'required|in:Yes,No',
            'attendance_status' => 'required|in:Yes,No',
            'fees_status' => 'required|in:Yes,No',
            'graduation_status' => 'required|in:Graduating,Not Graduating',
        ]);

        $student->update($validated);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
