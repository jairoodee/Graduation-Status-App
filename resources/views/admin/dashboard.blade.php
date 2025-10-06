@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold">🎓 Admin Dashboard</h1>
        <a href="{{ route('admin.logout') }}" 
           class="btn btn-outline-danger"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        <!-- Students Management -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body text-center py-4">
                    <h5 class="fw-bold mb-2">Manage Students</h5>
                    <p class="text-muted small mb-3">View, edit, or delete student records.</p>
                    <a href="{{ route('students.index') }}" class="btn btn-primary w-100">Go to Students</a>
                </div>
            </div>
        </div>

        <!-- Upload CSV -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body text-center py-4">
                    <h5 class="fw-bold mb-2">Upload CSV</h5>
                    <p class="text-muted small mb-3">Upload a CSV file to update or add students.</p>
                    <a href="{{ route('students.upload') }}" class="btn btn-success w-100">Upload Now</a>
                </div>
            </div>
        </div>

        <!-- Dashboard Stats -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body text-center py-4">
                    <h5 class="fw-bold mb-2">Reports & Stats</h5>
                    <p class="text-muted small mb-3">See summaries of student statuses.</p>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary w-100">View Reports</a>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <div class="text-center">
        <p class="text-muted small mb-0">© {{ date('Y') }} Student Management Portal</p>
        <p class="text-muted small">Built with ❤️ using Laravel</p>
    </div>
</div>
@endsection
