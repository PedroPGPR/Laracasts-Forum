<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

// Posts & Comments
Route::prefix('posts')->group(function () {
    // Rotas estáticas/fixas protegidas por autenticação
    Route::middleware('auth')->group(function () {
        Route::get('/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('/', [PostController::class, 'store'])->name('posts.store');

        // Comentários
        Route::post('/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
        Route::put('/{post}/comments/{comment}', [CommentController::class, 'update'])->name('posts.comments.update');
        Route::delete('/{post}/comments/{comment}', [CommentController::class, 'destroy'])->name('posts.comments.destroy');
    });

    // Rotas dinâmicas públicas
    Route::get('/{post}/{slug?}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/{topic?}', [PostController::class, 'index'])->name('posts.index');
});

require __DIR__.'/settings.php';
