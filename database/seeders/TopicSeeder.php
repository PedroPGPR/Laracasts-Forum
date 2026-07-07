<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'slug' => 'general',
                'name' => 'General',
                'description' => 'General discussion',
            ],
            [
                'slug' => 'reviews',
                'name' => 'Reviews',
                'description' => 'Reviews of movies, books, and more',
            ],
            [
                'slug' => 'questions',
                'name' => 'Questions',
                'description' => 'Questions and answers about movies, books, and more',
            ],
            [
                'slug' => 'announcements',
                'name' => 'Announcements',
                'description' => 'Announcements about new features and updates',
            ],
            [
                'slug' => 'conspiracies',
                'name' => 'Conspiracies',
                'description' => 'Conspiracies and rumors about movies, books, and more',
            ],
            [
                'slug' => 'fan-fiction',
                'name' => 'Fan Fiction',
                'description' => 'You got an idea for a sequel? Share it here!',
            ],
        ];

        Topic::upsert($data, ['slug']);
    }
}
