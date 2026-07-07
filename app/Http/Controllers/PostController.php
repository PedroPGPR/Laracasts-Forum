<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PostController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('view-any', Post::class);

        return Inertia::render('posts/Index', [
            'posts' => PostResource::collection(Post::with(['user', 'topic'])->latest()->latest('id')->paginate(15)),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Post::class);

        return Inertia::render('posts/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120', 'min:10'],
            'body' => ['required', 'string', 'min:100', 'max:10000'],
        ]);

        $newPost = $request->user()->posts()->create($data);
        $newPost->load('user');

        return redirect($newPost->showRoute())
            ->with('success', 'Post created successfully.');
    }

    public function show(Request $request, Post $post)
    {
        $this->authorize('view', $post);

        if (! Str::contains($post->showRoute(), $request->path())) {
            return redirect($post->showRoute($request->query()), 301);
        }

        $post->load(['user', 'topic']);
        $comments = $post->comments()->with('user')->latest()->latest('id')->paginate(5);

        return Inertia::render('posts/Show', [
            'post' => PostResource::make($post),
            'comments' => CommentResource::collection($comments),
        ]);
    }

    public function update(Request $request, Post $post): Post
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
