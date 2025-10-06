@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 fw-bold">✏️ Edit Student</h1>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">Back to Students</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-3 p-4">
        <form action="{{ route('students.update', $student->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ $student->name }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Exam Status</label>
                <select name="exam_status" class="form-select">
                    <option value="Yes" {{ $student->exam_status == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $student->exam_status == 'No' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Attendance Status</label>
                <select name="attendance_status" class="form-select">
                    <option value="Yes" {{ $student->attendance_status == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $student->attendance_status == 'No' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Fees Status</label>
                <select name="fees_status" class="form-select">
                    <option value="Yes" {{ $student->fees_status == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $student->fees_status == 'No' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Graduation Status</label>
                <select name="graduation_status" class="form-select">
                    <option value="Graduating" {{ $student->graduation_status == 'Graduating' ? 'selected' : '' }}>Graduating</option>
                    <option value="Not Graduating" {{ $student->graduation_status == 'Not Graduating' ? 'selected' : '' }}>Not Graduating</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3 px-4">Update Student</button>
        </form>
    </div>
</div>
@endsection
