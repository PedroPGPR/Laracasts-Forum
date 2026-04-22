<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->when($this->id === $request->user()?->id, $this->email),
            'comments_count' => $this->comments_count,
            'notifications_count' => $this->notifications_count,
            'posts_count' => $this->posts_count,
            'read_notifications_count' => $this->read_notifications_count,
            'unread_notifications_count' => $this->unread_notifications_count,
        ];
    }
}
