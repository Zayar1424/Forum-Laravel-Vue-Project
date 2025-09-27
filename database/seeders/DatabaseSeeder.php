<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Forum;
use App\Models\Tag;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Thread::factory(10)->create();

        // Seed 5 specific categories with slug
        $categories = [
            [
                'name' => 'General Discussion',
                'description' => 'Talk about anything related to coding and development.',
                'slug' => Str::slug('General Discussion'),
            ],
            [
                'name' => 'Technology',
                'description' => 'Discuss the latest in tech, gadgets, and software.',
                'slug' => Str::slug('Technology'),
            ],
            [
                'name' => 'Help & Support',
                'description' => 'Get help with your code or troubleshoot issues.',
                'slug' => Str::slug('Help & Support'),
            ],
            [
                'name' => 'Community',
                'description' => 'Connect with other members and share experiences.',
                'slug' => Str::slug('Community'),
            ],
            [
                'name' => 'Announcements',
                'description' => 'Official news and updates from the forum team.',
                'slug' => Str::slug('Announcements'),
            ],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Seed 5 tags with slug
        $tags = [
            ['name' => 'laravel', 'slug' => 'laravel'],
            ['name' => 'vue', 'slug' => 'vue'],
            ['name' => 'javascript', 'slug' => 'javascript'],
            ['name' => 'html', 'slug' => 'html'],
            ['name' => 'css', 'slug' => 'css'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        };
    }
}
