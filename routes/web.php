<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('home'))->name('home');
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/education', fn() => view('education'))->name('education');

Route::get('/projects', function () {
    $projects = [
        'Aplikasi Pemetaan Sampah Laut (Cleanesia)',
        'Website Reservasi Kos (KosKita)',
        'Katalog Film (Movix)',
    ];

    return view('projects', compact('projects'));
})->name('projects');