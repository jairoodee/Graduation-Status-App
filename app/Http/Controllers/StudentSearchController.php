<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentSearchController extends Controller
{
    // Show search page
    public function index()
    {
        return view('student.search');
    }

    // Handle autocomplete suggestions
    public function search(Request $request)
    {
        $term = $request->get('term', '');
        $students = Student::where('name', 'like', "%$term%")
            ->limit(10)
            ->pluck('name', 'id');

        $results = [];
        foreach ($students as $id => $name) {
            $results[] = ['id' => $id, 'name' => $name];
        }

        return response()->json($results);
    }

    // Show student details
    public function show($id)
    {
        $student = Student::findOrFail($id);
        $message = '';

        if ($student->graduating === 'Not Graduating') {
            $reasons = [];

            if (strtolower($student->exam) === 'no') {
                $reasons[] = "You did not meet the required exam qualifications.";
            }

            if (strtolower($student->attendance) === 'no') {
                $reasons[] = "You did not meet the attendance requirements.";
            }

            if (strtolower($student->fees) === 'no') {
                $reasons[] = "Your fee balance has not yet been cleared.";
            }

            $message = implode(' ', $reasons);
        }

        return response()->json([
            'name' => $student->name,
            'graduating' => $student->graduating,
            'message' => $message,
        ]);
    }
}
