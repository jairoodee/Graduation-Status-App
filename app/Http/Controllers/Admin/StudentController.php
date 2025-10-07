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

    
    public function showUploadForm()
    {
        return view('admin.students.upload');
    }

    public function uploadCSV(Request $request)
{
    // Validate file
    $request->validate([
        'csv_file' => 'required|mimes:csv,txt|max:2048'
    ]);

    // Get the uploaded file (matches the input name 'csv_file')
    $file = $request->file('csv_file');
    $added = 0;
    $updated = 0;

    if (($handle = fopen($file, 'r')) === false) {
        return redirect()->back()->with('error', 'Unable to open CSV file.');
    }

    // Read header row
    $header = fgetcsv($handle);
    if (!$header) {
        return redirect()->back()->with('error', 'CSV file is empty or invalid.');
    }

    // Remove BOM and clean header
    $header = array_map(function($h) {
        $h = preg_replace('/\x{FEFF}/u', '', $h); // remove BOM
        return trim($h);
    }, $header);

    // Expected headers
    $expected = ['Name','Attendance Status','Exam Status','Fees Status','Graduation Status'];
    if (array_map('strtolower', $header) !== array_map('strtolower', $expected)) {
        return redirect()->back()->with('error', 'CSV headers do not match expected format.');
    }

    // Helper function to clean each field
    $clean = function($val) {
        $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); // UTF-8
        $val = trim($val); // remove spaces
        $val = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $val); // remove hidden chars
        return $val;
    };

    // Process each row
    while (($row = fgetcsv($handle)) !== false) {
        if (count($row) !== count($header)) continue;

        $data = array_combine($header, $row);

        // Clean each field
        $name = $clean($data['Name']);
        $attendanceRaw = $clean($data['Attendance Status']);
        $examRaw = $clean($data['Exam Status']);
        $feesRaw = $clean($data['Fees Status']);
        $graduationRaw = $clean($data['Graduation Status']);

        // Normalize values
        $attendance = in_array(strtolower($attendanceRaw), ['yes','y','true','1']) ? 'Yes' : 'No';
        $exam = in_array(strtolower($examRaw), ['yes','y','true','1']) ? 'Yes' : 'No';
        $fees = in_array(strtolower($feesRaw), ['yes','y','true','1']) ? 'Yes' : 'No';
        $graduation = in_array(strtolower($graduationRaw), ['graduating','yes','y','true','1']) ? 'Graduating' : 'Not Graduating';

        // Log each row for debugging
        \Log::info("Processing student CSV row", [
            'name' => $name,
            'attendance_raw' => $attendanceRaw,
            'attendance' => $attendance,
            'exam_raw' => $examRaw,
            'exam' => $exam,
            'fees_raw' => $feesRaw,
            'fees' => $fees,
            'graduation_raw' => $graduationRaw,
            'graduation' => $graduation,
        ]);

        // Save or update student
        $student = Student::updateOrCreate(
            ['name' => $name],
            [
                'attendance_status' => $attendance,
                'exam_status' => $exam,
                'fees_status' => $fees,
                'graduation_status' => $graduation
            ]
        );

        $student->wasRecentlyCreated ? $added++ : $updated++;
    }

    fclose($handle);

    return redirect()->back()->with('success', "Upload successful: {$added} added, {$updated} updated.");
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
