<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Page;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CmsController extends Controller
{
    public function index(): View
    {
        $posts = Post::where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->with(['category', 'tags'])
            ->latest('published_at')
            ->paginate(10);

        return view('cms.index', compact('posts'));
    }

    public function showPost(string $slug): View
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->with(['category', 'tags'])
            ->firstOrFail();

        return view('cms.post', compact('post'));
    }

    public function showPage(string $slug): View
    {
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('cms.page', compact('page'));
    }

    public function showCategory(string $slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $posts = Post::where('category_id', $category->id)
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->with(['category', 'tags'])
            ->latest('published_at')
            ->paginate(10);

        return view('cms.category', compact('category', 'posts'));
    }

    public function showTag(string $slug): View
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        $posts = $tag->posts()
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->with(['category', 'tags'])
            ->latest('published_at')
            ->paginate(10);

        return view('cms.tag', compact('tag', 'posts'));
    }
}