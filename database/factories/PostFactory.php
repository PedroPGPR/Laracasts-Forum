<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

class PostFactory extends Factory
{
    protected $model = Post::class;

    private static Collection $fixturePosts;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),

            'title' => str($this->faker->sentence())->beforeLast('.')->title(),
            'body' => $this->faker->realText(600),
        ];
    }

    public function withFixture(): static
    {
        $posts = static::getFixturePosts()
            ->map(fn (string $contents) => str($contents)->explode("\n", 2))
            ->map(fn (Collection $parts) => [
                'title' => str($parts[0])->trim()->after('# '),
                'body' => str($parts[1])->trim(),
            ]);

        return $this->sequence(...$posts->toArray());
    }

    private static function getFixturePosts(): Collection
    {
        if (! isset(self::$fixturePosts)) {
            self::$fixturePosts = collect(File::files(database_path('factories/fixtures/posts')))
                ->map(fn (SplFileInfo $fileInfo) => $fileInfo->getContents());
        }

        return self::$fixturePosts;
    }
}
