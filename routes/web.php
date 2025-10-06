<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController; // ✅ aliased to avoid conflict
use App\Http\Controllers\StudentController; // public-facing student search
use App\Http\Controllers\StudentSearchController;
use App\Http\Controllers\Api\StudentApiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/api/students/search', [StudentApiController::class, 'search']);

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Homepage or search page for students (no login required)
Route::get('/', [StudentSearchController::class, 'index'])->name('search.index');
Route::get('/search', [StudentSearchController::class, 'search'])->name('search.student');
Route::get('/search-students', [StudentSearchController::class, 'search'])->name('search.autocomplete');
Route::get('/student/{id}', [StudentSearchController::class, 'show'])->name('search.show');

/*
|--------------------------------------------------------------------------
| Admin Authentication
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');


/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
|
| These routes require admin authentication.
|
*/
Route::middleware('auth:admin')->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Student Management
    Route::get('/students/upload', [AdminStudentController::class, 'showUploadForm'])->name('students.upload');
    Route::post('/students/upload', [AdminStudentController::class, 'uploadCSV'])->name('students.upload.post');
    Route::post('/students/import', [AdminStudentController::class, 'import'])->name('students.import');
    Route::get('/students', [AdminStudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}/edit', [AdminStudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [AdminStudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [AdminStudentController::class, 'destroy'])->name('students.destroy');
});
