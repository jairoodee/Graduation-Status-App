@extends('layouts.app')

@section('title', 'Manage Students')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 fw-bold">📋 Student Records</h1>
        <div>
            <a href="{{ route('students.upload') }}" class="btn btn-success me-2">Upload CSV</a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($students->isEmpty())
        <div class="alert alert-info text-center">
            No students found. Upload a CSV file to add student records.
        </div>
    @else
        <div class="table-responsive shadow-sm rounded-3 bg-white p-3">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Exam Status</th>
                        <th>Attendance Status</th>
                        <th>Fees Status</th>
                        <th>Graduation Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->exam_status }}</td>
                        <td>{{ $student->attendance_status }}</td>
                        <td>{{ $student->fees_status }}</td>
                        <td>{{ $student->graduation_status }}</td>
                        <td class="text-end">
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                Edit
                            </a>
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this student?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            @if(method_exists($students, 'links'))
                <div class="d-flex justify-content-center mt-4">
                    {{ $students->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
