<?php

use App\Http\Controllers\PostController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('posts.index');
});

Route::get('test', function () {
    return UserResource::make(User::find(11));
});

require __DIR__.'/settings.php';
