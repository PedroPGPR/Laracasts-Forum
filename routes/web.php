<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('posts.index');
    Route::get('/{post}', [PostController::class, 'show'])->name('posts.show');

    // Comments
    Route::post('/{post}/comments', [CommentController::class, 'store'])
        ->middleware('auth')
        ->name('posts.comments.store');
});

Route::get('test', function () {
    return [
        UserResource::make(User::find(11)),
        PostResource::make(Post::find(1)),
        CommentResource::make(Comment::find(1)),
    ];
});

require __DIR__.'/settings.php';
