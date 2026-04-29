<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        return Inertia::render('posts/Index', [
            'posts' => PostResource::collection(Post::latest()->latest('id')->paginate(15)),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required'],
            'body' => ['required'],
            'user_id' => ['required', 'exists:users'],
        ]);

        return Post::create($data);
    }

    public function show(Post $post)
    {
        $post->load(['user']);
        $comments = $post->comments()->with('user')->latest()->latest('id')->paginate(5);

        return Inertia::render('posts/Show', [
            'post' => PostResource::make($post),
            'comments' => CommentResource::collection($comments),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => ['required'],
            'body' => ['required'],
            'user_id' => ['required', 'exists:users'],
        ]);

        $post->update($data);

        return $post;
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json();
    }
}
