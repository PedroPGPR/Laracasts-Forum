<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,

            'user' => new UserResource($this->whenLoaded('user')),

            'routes' => [
                'show' => $this->showRoute(),
            ],
        ];
    }
}
