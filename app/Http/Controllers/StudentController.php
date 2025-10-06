<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function uploadForm()
    {
        return view('admin.upload');
    }

    public function uploadCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = fopen($request->file('csv_file')->getRealPath(), 'r');
        $header = fgetcsv($file);

        $added = 0;
        $updated = 0;

        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);

            $student = Student::updateOrCreate(
                ['name' => trim($data['Name'])],
                [
                    'exam_status' => trim($data['Exam Status']),
                    'attendance_status' => trim($data['Attendance Status']),
                    'fees_status' => trim($data['Fees Status']),
                    'graduation_status' => trim($data['Graduation Status']),
                ]
            );

            if ($student->wasRecentlyCreated) {
                $added++;
            } else {
                $updated++;
            }
        }

        fclose($file);

        return back()->with('success', "{$added} students added, {$updated} updated successfully.");
    }
}
