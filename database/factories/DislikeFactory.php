<?php

namespace Database\Factories;

use App\Models\Dislike;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DislikeFactory extends Factory
{
    protected $model = Dislike::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),

            'dislikeable_type' => $this->dislikeableType(...),
            'dislikeable_id' => Post::factory(),

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    protected function dislikeableType(array $values)
    {
        $type = $values['dislikeable_id'];

        if (is_int($type)) {
            return Post::class;
        }

        $modelName = $type instanceof Factory
            ? $type->modelName()
            : $type::class;

        return (new $modelName)->getMorphClass();
    }
}
