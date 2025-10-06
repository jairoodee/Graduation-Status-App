<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class StudentApiController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        $students = Student::where('name', 'like', "%{$query}%")
            ->select('id', 'name', 'exam_status', 'attendance_status', 'fees_status', 'graduation_status')
            ->limit(10)
            ->get();

        return response()->json($students);
    }
}
