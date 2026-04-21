<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::middleware(['guest'])->group(function () {
    Route::get('posts', [PostController::class, 'index'])->name('posts.index');
});

require __DIR__.'/settings.php';
