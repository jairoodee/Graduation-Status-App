@extends('layouts.app')

@section('title', 'Upload Student CSV')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 fw-bold">📂 Upload Student CSV</h1>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">Back to Students</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-3 p-4">
        <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="csv_file" class="form-label fw-semibold">Select CSV File</label>
                <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv" required>
                <div class="form-text mt-2">
                    The CSV must have the following columns:
                    <code>Name, Exam Status, Attendance Status, Fees Status, Graduation Status</code>
                </div>
            </div>

            <button type="submit" class="btn btn-primary px-4 mt-3">
                Upload and Update Records
            </button>
        </form>
    </div>
</div>
@endsection
