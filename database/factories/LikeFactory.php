<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class LikeFactory extends Factory
{
    protected $model = Like::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),

            'likeable_type' => $this->likeableType(...),
            'likeable_id' => Post::factory(),

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    protected function likeableType(array $values)
    {
        $type = $values['likeable_id'];

        if (is_int($type)) {
            return Post::class;
        }

        $modelName = $type instanceof Factory
            ? $type->modelName()
            : $type::class;

        return (new $modelName)->getMorphClass();
    }
}
