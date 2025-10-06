<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    // Show upload form
    public function index()
    {
        $students = Student::orderBy('name')->paginate(20);
        return view('admin.dashboard', compact('students'));
    }

    // Handle CSV Upload
    public function uploadCSV(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $path = $request->file('file')->getRealPath();
        $file = fopen($path, 'r');

        $header = fgetcsv($file);
        $expected = ['Name', 'Attendance Status', 'Fees Status', 'Exam Status', 'Graduation Status'];

        // Validate header
        if ($header !== $expected) {
            return back()->with('error', 'Invalid CSV format. Please use the correct column headers.');
        }

        $count = 0;
        while (($row = fgetcsv($file)) !== false) {
            [$name, $attendance, $fees, $exam, $grad] = $row;

            if (!in_array($attendance, ['Yes', 'No']) ||
                !in_array($fees, ['Yes', 'No']) ||
                !in_array($exam, ['Yes', 'No']) ||
                !in_array($grad, ['Graduating', 'Not Graduating'])) {
                Log::warning("Invalid data row skipped: " . implode(',', $row));
                continue;
            }

            Student::updateOrCreate(
                ['name' => trim($name)],
                [
                    'attendance_status' => $attendance,
                    'fees_status' => $fees,
                    'exam_status' => $exam,
                    'graduation_status' => $grad,
                ]
            );
            $count++;
        }

        fclose($file);

        return back()->with('success', "$count records imported/updated successfully!");
    }

    // Delete student
    public function destroy($id)
    {
        Student::findOrFail($id)->delete();
        return back()->with('success', 'Student deleted successfully.');
    }

    // Update individual record
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'attendance_status' => 'required|in:Yes,No',
            'fees_status' => 'required|in:Yes,No',
            'exam_status' => 'required|in:Yes,No',
            'graduation_status' => 'required|in:Graduating,Not Graduating',
        ]);

        Student::findOrFail($id)->update($validated);

        return back()->with('success', 'Student updated successfully.');
    }
}
