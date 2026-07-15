<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class DislikePolicy
{
    use HandlesAuthorization;

    public function create(User $user, Model $dislikeable): bool
    {
        if (! in_array($dislikeable::class, [Post::class, Comment::class])) {
            return false;
        }

        return $dislikeable->dislikes()->whereBelongsTo($user)->doesntExist();
    }

    public function delete(User $user, Model $dislikeable): bool
    {
        if (! in_array($dislikeable::class, [Post::class, Comment::class])) {
            return false;
        }

        return $dislikeable->dislikes()->whereBelongsTo($user)->exists();
    }
}
