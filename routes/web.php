<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pertemuan8Controller;
use App\Http\Controllers\Pertemuan9Controller;
use App\Http\Controllers\ProjectController;

// Publik
Route::get('/', fn() => view('home'))->name('home');
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/education', fn() => view('education'))->name('education');

Route::get('pertemuan-8', [Pertemuan8Controller::class, 'index'])->name('pertemuan8.index');
Route::get('pertemuan-9', [Pertemuan9Controller::class, 'index'])->name('pertemuan9.index');

// Wajib login
// Semua route projects dikunci, user yang membukanya dialihkan ke /login.
Route::middleware('auth')->group(function () {
    Route::get('profile-saya', fn() => view('profile-saya'))->name('profile-saya');
    Route::get('projects/trash', [ProjectController::class, 'trash'])->name('projects.trash');
    Route::patch('projects/{id}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
    Route::delete('projects/{id}/force', [ProjectController::class, 'forceDelete'])->name('projects.force-delete');

    Route::resource('projects', ProjectController::class);
});

// Login + admin
// Dua middleware berurutan: auth dulu, lalu admin (EnsureAdmin)
Route::get('pertemuan-9/admin', [Pertemuan9Controller::class, 'admin'])
    ->middleware(['auth', 'admin'])
    ->name('pertemuan9.admin');

// Route login/logout dari Breeze. Tanpa baris ini muncul "Route [login] not defined"
require __DIR__ . '/auth.php';
