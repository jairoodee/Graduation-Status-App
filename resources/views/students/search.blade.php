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
            <ul id="autocomplete-list" class="absolute z-10 w-full bg-white border border-gray-200 rounded-lg mt-1 hidden shadow">
                <!-- Suggestions appear here -->
            </ul>
        </div>

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

            let timeout = null;

            searchInput.on('input', function () {
                const query = $(this).val().trim();

                clearTimeout(timeout);

                if (query.length < 2) {
                    autocompleteList.empty().hide();
                    return;
                }

                timeout = setTimeout(() => {
                    axios.get(`/search-students?term=${encodeURIComponent(query)}`)
                        .then(res => {
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
                                autocompleteList.show();
                            } else {
                                autocompleteList.append('<li class="px-3 py-2 text-gray-400">No matching names found</li>');
                                autocompleteList.show();
                            }
                        });
                }, 300);
            });

            autocompleteList.on('click', 'li', function () {
                const studentId = $(this).data('id');
                const studentName = $(this).text();

                searchInput.val(studentName);
                autocompleteList.empty().hide();

                axios.get(`/student/${studentId}`)
                    .then(res => {
                        const data = res.data;

                        nameEl.text(data.name);
                        statusEl.text(`Graduation Status: ${data.graduating}`);

                        if (data.graduating === 'Not Graduating') {
                            reasonEl.text(data.message);
                        } else {
                            reasonEl.text('');
                        }

                        resultBox.removeClass('hidden');
                    });
            });
        });
    </script>
</body>
</html>
