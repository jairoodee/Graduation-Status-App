<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class SearchController extends Controller
{
    public function index()
    {
        return view('students.search');
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('query', '');
        $results = Student::where('name', 'LIKE', "%{$query}%")
            ->orderBy('name')
            ->limit(10)
            ->pluck('name');
        return response()->json($results);
    }

    public function show($name)
    {
        $student = Student::whereRaw('LOWER(name) = ?', [strtolower($name)])->first();

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $reasons = [];
        if ($student->graduation_status === 'Not Graduating') {
            if ($student->exam_status === 'No') $reasons[] = "Did not successfully complete all required exams.";
            if ($student->attendance_status === 'No') $reasons[] = "Attendance requirements not fully met.";
            if ($student->fees_status === 'No') $reasons[] = "Outstanding school fees pending.";
        }

        return response()->json([
            'name' => $student->name,
            'graduation_status' => $student->graduation_status,
            'reasons' => $reasons,
        ]);
    }
}
