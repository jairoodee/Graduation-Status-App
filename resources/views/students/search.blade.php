<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Graduation Status</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-lg text-center" x-data="studentSearch()">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Check Your Graduation Status</h1>

        <!-- Search -->
        <div class="relative">
            <input type="text" x-model="query" @input.debounce.300ms="search"
                   placeholder="Type your full name..."
                   class="w-full p-3 border rounded-xl focus:ring focus:ring-blue-200 outline-none">
            
            <!-- Autocomplete -->
            <ul x-show="suggestions.length" class="absolute bg-white border rounded-xl w-full mt-1 text-left z-10">
                <template x-for="(name, index) in suggestions" :key="index">
                    <li @click="selectName(name)"
                        class="px-4 py-2 hover:bg-blue-100 cursor-pointer"
                        x-text="name"></li>
                </template>
            </ul>
        </div>

        <!-- Result -->
        <div x-show="student" class="mt-6 p-4 bg-white rounded-2xl shadow">
            <h2 class="text-xl font-bold text-gray-800" x-text="student.name"></h2>
            <p class="mt-2 text-lg"
               :class="student.graduation_status === 'Graduating' ? 'text-green-600' : 'text-red-600'"
               x-text="student.graduation_status === 'Graduating' ? '🎓 Congratulations! You are graduating.' : '❗ You are not graduating yet.'">
            </p>

            <!-- Reasons -->
            <ul class="text-sm text-gray-700 mt-3 list-disc list-inside" x-show="student.reasons.length">
                <template x-for="reason in student.reasons" :key="reason">
                    <li x-text="reason"></li>
                </template>
            </ul>
        </div>

        <!-- Error / No result -->
        <p class="text-red-500 mt-4" x-show="error" x-text="error"></p>

        <!-- Footer -->
        <p class="text-gray-500 text-sm mt-8">
            This information is for guidance only. For official records, contact the registrar’s office.
        </p>
    </div>

    <script>
        function studentSearch() {
            return {
                query: '',
                suggestions: [],
                student: null,
                error: '',
                async search() {
                    this.student = null;
                    if (this.query.length < 2) {
                        this.suggestions = [];
                        return;
                    }
                    try {
                        const res = await fetch(`/autocomplete?query=${this.query}`);
                        this.suggestions = await res.json();
                    } catch {
                        this.suggestions = [];
                    }
                },
                async selectName(name) {
                    this.query = name;
                    this.suggestions = [];
                    try {
                        const res = await fetch(`/student/${encodeURIComponent(name)}`);
                        if (!res.ok) throw new Error('Not found');
                        this.student = await res.json();
                        this.error = '';
                    } catch {
                        this.error = 'Student not found.';
                        this.student = null;
                    }
                }
            }
        }
    </script>

</body>
</html>
