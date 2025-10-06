@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="bg-white shadow rounded-xl p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Welcome, Admin!</h1>
    <p class="text-gray-600 mb-6">
        You are logged in as <strong>{{ Auth::guard('admin')->user()->email }}</strong>.
    </p>

    <div class="grid gap-4 md:grid-cols-2">
        <a href="#" class="bg-blue-600 text-white px-6 py-4 rounded-lg text-center hover:bg-blue-700 transition">
            Upload Student CSV
        </a>

        <a href="#" class="bg-green-600 text-white px-6 py-4 rounded-lg text-center hover:bg-green-700 transition">
            View Student Directory
        </a>
    </div>
</div>
@endsection
