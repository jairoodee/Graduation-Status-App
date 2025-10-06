<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Graduation Status Checker</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-8">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">
            Student Graduation Status Checker
        </h1>

        <!-- Search Field -->
        <div class="relative">
            <input 
                type="text" 
                id="student-search" 
                placeholder="Start typing your name..." 
                class="w-full border border-gray-300 rounded-lg py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                autocomplete="off"
            >

            <!-- Loading Spinner -->
            <div id="loading-spinner" class="absolute right-3 top-2.5 hidden">
                <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
            </div>

            <ul id="autocomplete-list" class="absolute z-10 w-full bg-white border border-gray-200 rounded-lg mt-1 hidden shadow">
                <!-- Suggestions appear here -->
            </ul>
        </div>

        <!-- Error message -->
        <div id="error-message" class="mt-4 hidden text-center text-red-600 bg-red-50 border border-red-200 rounded-lg p-3"></div>

        <!-- Student Result -->
        <div id="student-result" class="mt-6 hidden bg-gray-50 border border-gray-200 rounded-lg p-4">
            <h2 class="text-lg font-semibold text-gray-700" id="student-name"></h2>
            <p id="graduation-status" class="mt-2 text-gray-600"></p>
            <p id="not-graduating-reason" class="mt-2 text-red-600 font-medium"></p>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            const searchInput = $('#student-search');
            const autocompleteList = $('#autocomplete-list');
            const resultBox = $('#student-result');
            const nameEl = $('#student-name');
            const statusEl = $('#graduation-status');
            const reasonEl = $('#not-graduating-reason');
            const errorBox = $('#error-message');
            const spinner = $('#loading-spinner');

            let timeout = null;

            // --- Handle Search Typing ---
            searchInput.on('input', function () {
                const query = $(this).val().trim();
                autocompleteList.empty().hide();
                errorBox.hide();
                resultBox.hide();

                clearTimeout(timeout);

                if (query.length < 2) return;

                spinner.show();

                timeout = setTimeout(() => {
                    axios.get(`/search-students?term=${encodeURIComponent(query)}`)
                        .then(res => {
                            spinner.hide();
                            const results = res.data;
                            autocompleteList.empty();

                            if (results.length > 0) {
                                results.forEach(student => {
                                    autocompleteList.append(`
                                        <li class="px-3 py-2 hover:bg-blue-50 cursor-pointer" data-id="${student.id}">
                                            ${student.name}
                                        </li>
                                    `);
                                });
                            } else {
                                autocompleteList.append(`
                                    <li class="px-3 py-2 text-gray-500 italic">
                                        No matching students found.
                                    </li>
                                `);
                            }
                            autocompleteList.show();
                        })
                        .catch(() => {
                            spinner.hide();
                            errorBox.text("An error occurred while searching. Please try again later.").show();
                        });
                }, 300);
            });

            // --- Handle Selection ---
            autocompleteList.on('click', 'li', function () {
                const studentId = $(this).data('id');
                const studentName = $(this).text();

                if (!studentId) return; // ignore "no match" message

                searchInput.val(studentName);
                autocompleteList.empty().hide();
                spinner.show();

                axios.get(`/student/${studentId}`)
                    .then(res => {
                        spinner.hide();
                        const data = res.data;

                        nameEl.text(data.name);
                        statusEl.text(`Graduation Status: ${data.graduating}`);

                        if (data.graduating === 'Not Graduating') {
                            reasonEl.text(data.message);
                        } else {
                            reasonEl.text('');
                        }

                        resultBox.removeClass('hidden').show();
                    })
                    .catch(() => {
                        spinner.hide();
                        errorBox.text("Failed to fetch student details. Please try again.").show();
                    });
            });

            // Hide list when clicking outside
            $(document).on('click', function (e) {
                if (!$(e.target).closest('#student-search, #autocomplete-list').length) {
                    autocompleteList.hide();
                }
            });
        });
    </script>
</body>
</html>
