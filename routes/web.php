<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pertemuan8Controller;
use App\Http\Controllers\Pertemuan9Controller;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ProjectGalleryController;

// Publik
Route::get('/', fn() => view('home'))->name('home');
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/education', fn() => view('education'))->name('education');

// Route Publik untuk Portfolio (Sesuai Modul Pertemuan 10)
Route::get('/projects', [PortfolioController::class, 'projects'])->name('projects');
Route::get('/projects/{project}', [PortfolioController::class, 'show'])->name('projects.show');

Route::get('pertemuan-8', [Pertemuan8Controller::class, 'index'])->name('pertemuan8.index');
Route::get('pertemuan-9', [Pertemuan9Controller::class, 'index'])->name('pertemuan9.index');

// Wajib login (Tanpa Prefix)
Route::middleware('auth')->group(function () {
    Route::get('profile-saya', fn() => view('profile-saya'))->name('profile-saya');
});

// Login + Admin (Middleware berlapis)
Route::get('pertemuan-9/admin', [Pertemuan9Controller::class, 'admin'])
    ->middleware(['auth', 'admin'])
    ->name('pertemuan9.admin');

// Wajib Login dengan Prefix "post" (Admin Dashboard & Projects)
Route::prefix('post')->name('post.')->middleware('auth')->group(function () {
    Route::resource('dashboard', DashboardController::class);

    // Project CRUD & Trash dipindah ke sini
    Route::get('projects/trash', [ProjectController::class, 'trash'])->name('projects.trash');
    Route::patch('projects/{id}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
    Route::delete('projects/{id}/force', [ProjectController::class, 'forceDelete'])->name('projects.force-delete');
    Route::resource('projects', ProjectController::class);

    // Route untuk Gallery (Upload & Delete)
    Route::post('projects/{project}/gallery', [ProjectGalleryController::class, 'store'])->name('projects.gallery.store');
    Route::delete('projects/{project}/gallery/{image}', [ProjectGalleryController::class, 'destroy'])->name('projects.gallery.destroy');
});

// Route login/logout dari Breeze.
require __DIR__ . '/auth.php';
