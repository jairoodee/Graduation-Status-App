<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Graduation Status</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 flex flex-col items-center justify-center min-h-screen">

    {{-- Logo --}}
    <div class="mb-8">
        <img src="{{ asset('images/kfc_logo.jpg') }}" alt="School Logo" class="h-16">
    </div>

    {{-- Search Section --}}
    <div 
        x-data="studentSearch()" 
        class="w-full max-w-2xl text-center"
    >
        <h1 class="text-3xl font-semibold mb-6 text-gray-700">Check Your Graduation Status</h1>

        <div class="relative w-full">
            <input 
                type="text" 
                x-model="query" 
                @input.debounce.300ms="searchStudents" 
                placeholder="Type your name..." 
                class="w-full p-4 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm text-lg"
            >
            <button 
                @click="searchStudents" 
                class="absolute right-2 top-2 bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700"
            >
                Search
            </button>

            {{-- Autocomplete Results --}}
            <ul 
                x-show="results.length > 0" 
                class="absolute z-10 bg-white border border-gray-200 mt-2 w-full rounded-xl shadow-lg max-h-60 overflow-y-auto text-left"
            >
                <template x-for="student in results" :key="student.id">
                    <li 
                        @click="selectStudent(student)" 
                        class="px-4 py-3 hover:bg-blue-50 cursor-pointer"
                        x-text="student.name"
                    ></li>
                </template>
            </ul>
        </div>

        {{-- Selected Student Result --}}
        <div 
            x-show="selectedStudent" 
            x-transition.opacity.duration.500ms
            :class="selectedStudent.graduation_status === 'Graduating' 
                ? 'mt-8 bg-white shadow-lg p-6 rounded-2xl w-full border-l-4 border-green-400' 
                : 'mt-8 bg-white shadow-lg p-6 rounded-2xl w-full border-l-4 border-red-400'"
        >

            <h2 class="text-2xl font-semibold text-gray-800 mb-3" x-text="selectedStudent.name"></h2>
            <div class="text-lg leading-relaxed" x-html="statusMessage"></div>
        </div>
    </div>

    {{-- AlpineJS Logic --}}
    <script>
        function studentSearch() {
            return {
                query: '',
                results: [],
                selectedStudent: null,
                statusMessage: '',

                async searchStudents() {
                    if (this.query.length < 2) {
                        this.results = [];
                        return;
                    }
                    const response = await fetch(`/api/students/search?query=${this.query}`);
                    this.results = await response.json();
                },

                async selectStudent(student) {
                    this.selectedStudent = student;
                    this.results = [];
                    this.query = student.name;

                if (student.graduation_status === "Graduating") {
                    this.statusMessage = `
                        <div class='text-green-700 font-medium'>
                            🎓 Congratulations! You've met all the requirements and are cleared for graduation.
                        </div>
                    `;
                } else {
                    let reasons = [];

                    if (student.exam_status === "No") {
                        reasons.push("Exam requirements are still pending review or completion.");
                    }

                    if (student.attendance_status === "No") {
                        reasons.push("Attendance requirements haven't been fully met.");
                    }

                    if (student.fees_status === "No") {
                        reasons.push("There are outstanding fee payments that need to be cleared.");
                    }

                    let reasonList = reasons
                        .map(r => `<li class='ml-4 pl-3.5 list-disc text-left text-gray-700'>${r}</li>`)
                        .join("");

                    this.statusMessage = `
                        <div class='text-red-700 font-semibold mb-2'>
                            Unfortunately, you're not yet cleared for graduation.
                        </div>
                        <ul class='mb-3'>
                            ${reasonList}
                        </ul>
                        <div class='text-gray-700'>
                            Kindly contact the registrar's office for assistance and next steps.
                        </div>
                    `;
                }


                }
            }
        }
    </script>
</body>
</html>
