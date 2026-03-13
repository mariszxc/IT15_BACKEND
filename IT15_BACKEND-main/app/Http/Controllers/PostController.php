<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $activeCategory = null;
        $categorySlug = $request->query('category');

        if ($categorySlug) {
            $activeCategory = $categories->firstWhere('slug', $categorySlug);
        }

        if (!$activeCategory && $categories->isNotEmpty()) {
            $activeCategory = $categories->first();
        }

        $postsQuery = Post::query()->with('category')->latest();

        if ($activeCategory) {
            $postsQuery->where('category_id', $activeCategory->id);
        }

        $posts = $postsQuery->get();

        return view('welcome', [
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'posts' => $posts,
        ]);
    }
}
