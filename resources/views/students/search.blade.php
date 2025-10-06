{{-- resources/views/search.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graduation Status Checker</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 flex flex-col justify-center items-center min-h-screen">
    
    {{-- School Logo --}}
    <div class="mb-8">
        @if(file_exists(public_path('images/school-logo.png')))
            <img src="{{ asset('images/school-logo.png') }}" alt="School Logo" class="h-24 mx-auto">
        @else
            <h1 class="text-3xl font-semibold text-gray-700">Graduation Status Portal</h1>
        @endif
    </div>

    {{-- Search Form --}}
    <form action="{{ route('search.student') }}" method="GET" class="w-full max-w-xl">
        <div class="flex items-center bg-white rounded-full shadow-lg p-2">
            <input 
                type="text" 
                name="query" 
                placeholder="Enter student name..." 
                value="{{ request('query') }}"
                class="flex-grow px-6 py-3 rounded-l-full text-lg focus:outline-none"
                required
            >
            <button 
                type="submit"
                class="bg-blue-600 text-white px-6 py-3 rounded-full text-lg hover:bg-blue-700 transition-all"
            >
                Search
            </button>
        </div>
    </form>

    {{-- Footer --}}
    <div class="mt-12 text-gray-500 text-sm">
        <p>&copy; {{ date('Y') }} Your School Name. All rights reserved.</p>
    </div>
</body>
</html>
