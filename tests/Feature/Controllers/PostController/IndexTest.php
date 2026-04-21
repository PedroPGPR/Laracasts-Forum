<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\get;

it('should return correct component', function () {
    get(route('posts.index'))
        ->assertInertia(fn (AssertableInertia $inertia) => $inertia
            ->component('posts/Index', true)
        );
});

it('should return correct component width data', function () {
    get(route('posts.index'))
        ->assertInertia(fn (AssertableInertia $inertia) => $inertia
            ->has('posts')
            ->component('posts/Index', true)
        );
});
