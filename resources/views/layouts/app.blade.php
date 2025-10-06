<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-blue-700 text-white px-6 py-3 flex justify-between items-center shadow">
        <div class="font-bold text-xl">Student Directory Admin</div>
        <div>
            @auth('admin')
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 px-3 py-1 rounded hover:bg-red-600">
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-6 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-200 text-center text-gray-600 py-3">
        <p>&copy; {{ date('Y') }} Student Directory. All rights reserved.</p>
    </footer>

</body>
</html>
