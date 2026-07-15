<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;
use Override;

/** @mixin Post */
class PostResource extends JsonResource
{
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'html' => $this->html,
            'likes_count' => Number::abbreviate($this->likes_count),
            'dislikes_count' => Number::abbreviate($this->dislikes_count),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,

            'user' => UserResource::make($this->whenLoaded('user')),
            'topic' => TopicResource::make($this->whenLoaded('topic')),

            'routes' => [
                'show' => $this->showRoute(),
            ],
        ];
    }
}
