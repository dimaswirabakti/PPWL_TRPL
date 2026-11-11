<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Pertemuan8Controller;
use App\Http\Controllers\Pertemuan9Controller;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectGalleryController;
use Illuminate\Support\Facades\Route;

// Publik
Route::get('/', fn () => view('home'))->name('home');
Route::get('/about', fn () => view('about'))->name('about');
Route::get('/education', fn () => view('education'))->name('education');

Route::get('pertemuan-8', [Pertemuan8Controller::class, 'index'])->name('pertemuan8.index');
Route::get('pertemuan-9', [Pertemuan9Controller::class, 'index'])->name('pertemuan9.index');

// Pertemuan 10: portfolio publik, hanya project published
Route::get('portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('portfolio/{project}', [PortfolioController::class, 'show'])->name('portfolio.show');

// Wajib login
// Semua route projects dikunci, user yang membukanya dialihkan ke /login.
Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile-saya', fn () => view('profile-saya'))->name('profile-saya');
    Route::get('projects/trash', [ProjectController::class, 'trash'])->name('projects.trash');
    Route::patch('projects/{id}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
    Route::delete('projects/{id}/force', [ProjectController::class, 'forceDelete'])->name('projects.force-delete');

    // Pertemuan 10: galeri gambar per project
    Route::post('projects/{project}/images', [ProjectGalleryController::class, 'store'])->name('projects.images.store');
    Route::put('projects/{project}/images/{image}', [ProjectGalleryController::class, 'update'])->name('projects.images.update');
    Route::delete('projects/{project}/images/{image}', [ProjectGalleryController::class, 'destroy'])->name('projects.images.destroy');

    Route::resource('projects', ProjectController::class);
});

// Login + admin
// Dua middleware berurutan: auth dulu, lalu admin (EnsureAdmin)
Route::get('pertemuan-9/admin', [Pertemuan9Controller::class, 'admin'])
    ->middleware(['auth', 'admin'])
    ->name('pertemuan9.admin');

// Route login/logout dari Breeze. Tanpa baris ini muncul "Route [login] not defined"
require __DIR__.'/auth.php';
