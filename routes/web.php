<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SearchController;

// Public
Route::get('/', [SearchController::class, 'index'])->name('student.search');
Route::get('/autocomplete', [SearchController::class, 'autocomplete']);
Route::get('/student/{name}', [SearchController::class, 'show']);

// Admin auth routes
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Protected admin routes
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/upload', [StudentController::class, 'uploadForm'])->name('admin.upload');
    Route::post('/admin/upload', [StudentController::class, 'uploadCSV'])->name('admin.upload.post');
});