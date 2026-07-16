<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;
use Override;

/** @mixin Comment */
class CommentResource extends JsonResource
{
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'html' => $this->html,

            'likes_count' => Number::abbreviate($this->likes_count),
            'dislikes_count' => Number::abbreviate($this->dislikes_count),
            'isLiked' => $request->user()?->hasLiked($this->resource) ?? false,
            'isDisliked' => $request->user()?->hasDisliked($this->resource) ?? false,

            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,

            'post' => PostResource::make($this->whenLoaded('post')),
            'user' => UserResource::make($this->whenLoaded('user')),

            'can' => [
                'update' => $request->user()?->can('update', $this->resource),
                'delete' => $request->user()?->can('delete', $this->resource),
            ],
        ];
    }
}
