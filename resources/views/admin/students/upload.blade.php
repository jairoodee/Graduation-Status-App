@extends('layouts.app')

@section('title', 'Upload Student CSV')

@section('content')
<div class="bg-white shadow rounded-xl p-6 max-w-lg mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Upload Student CSV</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.upload.post') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label class="block text-sm font-medium text-gray-700 mb-2">Select CSV File</label>
        <input type="file" name="csv_file" accept=".csv" required
               class="block w-full text-sm text-gray-700 border border-gray-300 rounded-md p-2 mb-4 focus:ring focus:ring-blue-200">

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
            Upload & Process
        </button>
    </form>
</div>
@endsection
