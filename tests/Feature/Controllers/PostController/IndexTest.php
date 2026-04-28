<?php

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\get;

it('should return correct component', function () {
    get(route('posts.index'))
        ->assertInertia(fn (AssertableInertia $inertia) => $inertia
            ->component('posts/Index', true)
        );
});

it('should return correct component width data', function () {
    $posts = Post::latest()->latest('id')->paginate(15);
    get(route('posts.index'))
        ->assertInertia(fn (AssertableInertia $inertia) => $inertia
            ->hasPaginatedResource('posts', PostResource::collection($posts))
            ->component('posts/Index', true)
        );
});
