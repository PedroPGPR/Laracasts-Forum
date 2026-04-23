<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Comment */
class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,

            'post' => new PostResource($this->whenLoaded('post')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
