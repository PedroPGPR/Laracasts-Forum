<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use function Pest\Laravel\actingAs;

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


