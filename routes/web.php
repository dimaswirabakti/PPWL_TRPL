<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

Route::get('/', fn() => view('home'))->name('home');
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/education', fn() => view('education'))->name('education');

Route::get('projects/trash', [ProjectController::class, 'trash'])->name('projects.trash');
Route::patch('projects/{id}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
Route::delete('projects/{id}/force', [ProjectController::class, 'forceDelete'])->name('projects.force-delete');

Route::resource('projects', ProjectController::class);
