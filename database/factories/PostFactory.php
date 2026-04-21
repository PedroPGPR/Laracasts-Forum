<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),

            'title' => str($this->faker->sentence())->beforeLast('.')->title(),
            'body' => $this->faker->realText(600),
        ];
    }
}
