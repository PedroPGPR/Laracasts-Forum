<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\put;

it('cannot delete other user comments', function () {
    $userWhoCreatedComment = User::factory()
        ->hasPosts(1)
        ->hasComments(1)
        ->create();

    $user = User::factory()->create();

    actingAs($user)->delete(route('posts.comments.destroy', [
        'post' => $userWhoCreatedComment->comments()->first()->post_id,
        'comment' => $userWhoCreatedComment->comments()->first()->id,
    ]))
        ->assertStatus(403);
});

it('can delete comment if created by him', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $comment = Comment::factory()->create([
        'user_id' => $user->id,
        'post_id' => $post->id,
    ]);

    actingAs($user)
        ->delete(route('posts.comments.destroy', [$post, $comment]))
        ->assertRedirect(route('posts.show', $post));

    $this->assertModelMissing($comment);
});

it('cannot delete a comment posted over an hour ago', function () {
    $this->freezeTime();
    $post = Post::factory()->create();
    $comment = Comment::factory()->create([
        'post_id' => $post->id,
    ]);
    $this->travel(1)->hour();

    actingAs($comment->user)
        ->delete(route('posts.comments.destroy', [$post, $comment]))
        ->assertForbidden();
});

// ##############################
// ########### UPDATE ###########
// ##############################

it('requires authentication to update comment', function () {
    $post = Post::factory()->create();
    $comment = Comment::factory()->create([
        'post_id' => $post->id,
    ]);

    put(route('posts.comments.update', [$post, $comment]))
        ->assertRedirect(route('login'));
});

it('can update comment', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create([
        'user_id' => $user->id,
    ]);
    $comment = Comment::factory()->create([
        'post_id' => $post->id,
        'body' => 'Este texto vai ser alterado',
    ]);

    $newBody = 'Este texto vai ser alterado - Atualizado';

    actingAs($comment->user)
        ->put(route('posts.comments.update', [
            'post' => $post->id,
            'comment' => $comment->id,
        ]), ['body' => $newBody]);

    assertDatabaseHas('comments', [
        'id' => $comment->id,
        'body' => $newBody,
    ]);
});

it('redirects to the post show route', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create([
        'user_id' => $user->id,
    ]);
    $comment = Comment::factory()->create([
        'post_id' => $post->id,
    ]);

    actingAs($comment->user)
        ->put(route('posts.comments.update', [
            'post' => $post->id,
            'comment' => $comment->id,
        ]), ['body' => 'Este texto vai ser alterado - Atualizado'])
        ->assertRedirect(route('posts.show', $post));
});

it('redirects to the correct page of comments', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create([
        'user_id' => $user->id,
    ]);
    $comment = Comment::factory()->create([
        'post_id' => $post->id,
    ]);

    actingAs($comment->user)
        ->put(route('posts.comments.update', [
            'post' => $post->id,
            'comment' => $comment->id,
            'page' => 2,
        ]), [
            'body' => 'Este texto vai ser alterado - Atualizado',
        ])
        ->assertRedirect(route('posts.show', ['post' => $post, 'page' => 2]));
});

it('cannot update a comment from another user', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create([
        'user_id' => $user->id,
    ]);
    $comment = Comment::factory()->create([
        'post_id' => $post->id,
    ]);

    actingAs($user)
        ->put(route('posts.comments.update', [
            'post' => $post->id,
            'comment' => $comment->id,
        ]), [
            'body' => 'Este texto vai ser alterado - Atualizado',
        ])
        ->assertForbidden();
});

it('requires a valid body to update a comment', function ($body) {
    $user = User::factory()->create();
    $post = Post::factory()->create([
        'user_id' => $user->id,
    ]);
    $comment = Comment::factory()->create([
        'post_id' => $post->id,
    ]);

    actingAs($comment->user)
        ->put(route('posts.comments.update', [
            'post' => $post->id,
            'comment' => $comment->id,
        ]), ['body' => $body])
        ->assertInvalid('body');
})->with([
    'null' => null,
    'integer' => 3212332,
    'array' => [[]],
    'float' => 1.89,
    'boolean' => false,
    'long_string' => str_repeat('a', 2501),
]);
