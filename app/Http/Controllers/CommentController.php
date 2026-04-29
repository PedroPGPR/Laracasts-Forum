<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index() {}

    public function create() {}

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

        return to_route('posts.show', $post);
    }

    public function show(Comment $comment) {}

    public function edit(Comment $comment) {}

    public function update(Request $request, Comment $comment) {}

    public function destroy(Comment $comment) {}
}
