<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function store(Post $post, Request $request)
    {
        $validatedData = $request->validate([
            'body' => 'required|string|max:2500',
        ]);

        Comment::create([
            'body' => $validatedData['body'],
            'post_id' => $post->id,
            'user_id' => Auth::id(),
        ]);

        $post = $post->fresh(['user']);
        $post->comments()->with('user')->latest()->latest('id')->paginate(5);

        return redirect($post->showRoute())
            ->with('success', 'Comment created successfully.');
    }

    public function update(Post $post, Comment $comment, Request $request)
    {
        $validatedData = $request->validate([
            'body' => 'required|string|max:2500',
            'page' => 'nullable|integer',
        ]);

        Gate::authorize('update', $comment);

        $comment->update([
            'body' => $validatedData['body'],
        ]);

        return redirect($post->showRoute(['page' => $validatedData['page'] ?? null]))
            ->with('success', 'Comment updated.');
    }

    public function destroy(Post $post, Comment $comment, Request $request)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return redirect($post->showRoute(['page' => $request->page]))
            ->with('success', 'Comment deleted.');
    }
}
