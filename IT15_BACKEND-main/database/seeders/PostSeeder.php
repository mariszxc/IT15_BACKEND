<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::query()->delete();

        $technology = Category::where('slug', 'technology')->first();
        $lifestyle = Category::where('slug', 'lifestyle')->first();
        $travel = Category::where('slug', 'travel')->first();
        $food = Category::where('slug', 'food')->first();

        $posts = [
            [
                'category' => $technology,
                'title' => 'Small Tools, Big Impact',
                'description' => 'A quick look at tiny utilities that save hours every week.',
            ],
            [
                'category' => $technology,
                'title' => 'Clean Interfaces That Win',
                'description' => 'Why simple layouts still outperform flashy designs.',
            ],
            [
                'category' => $lifestyle,
                'title' => 'Morning Routines That Stick',
                'description' => 'Build a steady rhythm with a few easy habits.',
            ],
            [
                'category' => $lifestyle,
                'title' => 'Declutter Your Week',
                'description' => 'A short checklist for keeping priorities clear.',
            ],
            [
                'category' => $travel,
                'title' => 'City Walks, Slow Days',
                'description' => 'How to explore on foot and keep plans flexible.',
            ],
            [
                'category' => $travel,
                'title' => 'Packing Light, Living Big',
                'description' => 'Everything you need in a single carry-on.',
            ],
            [
                'category' => $food,
                'title' => 'One-Pan Comfort',
                'description' => 'A simple dinner that feels like a weekend treat.',
            ],
            [
                'category' => $food,
                'title' => 'Fresh Flavors, Fast Meals',
                'description' => 'Keep meals bright without spending hours cooking.',
            ],
        ];

        foreach ($posts as $post) {
            if (!$post['category']) {
                continue;
            }

            Post::create([
                'category_id' => $post['category']->id,
                'title' => $post['title'],
                'description' => $post['description'],
            ]);
        }
    }
}
