<?php

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
        $comments = $post->comments()->with('user')->latest()->latest('id')->paginate(5);

        return to_route('posts.show', ['post' => $post])
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

        return to_route('posts.show', ['post' => $post, 'page' => $validatedData['page'] ?? null])
            ->with('success', 'Comment updated.');
    }

    public function destroy(Post $post, Comment $comment, Request $request)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return to_route('posts.show', ['post' => $post, 'page' => $request->page])
            ->with('success', 'Comment deleted.');
    }
}
