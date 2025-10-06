<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use League\Csv\Exception;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::count() > 20
            ? Student::orderBy('name')->paginate(10)
            : Student::orderBy('name')->get();
        return view('admin.students.index', compact('students'));
    }

    public function import(Request $request)
{
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt|max:2048',
    ]);

    try {
        $path = $request->file('csv_file')->getRealPath();

        // Handle UTF-8 BOM and Windows line endings gracefully
        $file = fopen($path, 'r');
        if (!$file) {
            throw new \Exception("Cannot open the file.");
        }

        $header = fgetcsv($file);
        if (!$header) {
            throw new \Exception("CSV file appears to be empty or invalid.");
        }

        // Clean header names (remove BOM, trim spaces)
        $header = array_map(fn($h) => trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $h)), $header);

        $expectedHeaders = ['Name', 'Attendance Status', 'Exam Status', 'Fees Status', 'Graduation Status'];
        if ($header !== $expectedHeaders) {
            throw new \Exception("CSV headers do not match expected format. Found: " . implode(',', $header));
        }

        $rows = [];
        while (($data = fgetcsv($file)) !== false) {
            if (count($data) < 5) continue; // skip incomplete rows

            $rows[] = array_combine($header, $data);
        }
        fclose($file);

        foreach ($rows as $row) {
            $student = Student::updateOrCreate(
                ['name' => $row['Name']],
                [
                    'attendance' => $row['Attendance Status'],
                    'exam' => $row['Exam Status'],
                    'fees' => $row['Fees Status'],
                    'graduating' => strtolower(trim($row['Graduation Status'])) === 'graduating' ? 'Graduating' : 'Not Graduating',
                ]
            );
            
        }

        return redirect()->back()->with('success', 'Students imported successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error reading the CSV file: ' . $e->getMessage());
    }
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
