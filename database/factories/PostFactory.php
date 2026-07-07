<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use App\Support\PostFixtures;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'topic_id' => Topic::factory(),
            'title' => str($this->faker->sentence())->beforeLast('.')->title(),
            'body' => $this->faker->realText(600),
        ];
    }

    public function withFixture(): static
    {
        return $this->sequence(...PostFixtures::getFixturePosts());
    }
}
