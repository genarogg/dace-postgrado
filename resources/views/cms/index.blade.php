@extends('layouts.cms')

@section('title', 'Blog')
@section('meta_description', 'Últimas publicaciones y noticias')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <div class="lg:col-span-3">
        <h1 class="text-3xl font-bold mb-8">Blog</h1>

        @if($posts->count() > 0)
            <div class="space-y-8">
                @foreach($posts as $post)
                    <article class="bg-white shadow rounded-lg overflow-hidden">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-64 object-cover">
                        @endif
                        <div class="p-6">
                            <h2 class="text-2xl font-bold mb-4">
                                <a href="{{ route('cms.post', $post->slug) }}" class="text-gray-900 hover:text-blue-600">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            @if($post->excerpt)
                                <p class="text-gray-600 mb-4">{{ $post->excerpt }}</p>
                            @endif
                            <div class="flex items-center text-sm text-gray-500">
                                <span>{{ $post->published_at?->format('d/m/Y') }}</span>
                                @if($post->category)
                                    <span class="mx-2">·</span>
                                    <a href="{{ route('cms.category', $post->category->slug) }}" class="hover:text-blue-600">
                                        {{ $post->category->name }}
                                    </a>
                                @endif
                            </div>
                            @if($post->tags->count() > 0)
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach($post->tags as $tag)
                                        <a href="{{ route('cms.tag', $tag->slug) }}" class="inline-block bg-gray-100 text-sm text-gray-600 px-3 py-1 rounded-full hover:bg-gray-200">
                                            {{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @else
            <p class="text-gray-600">No hay publicaciones disponibles.</p>
        @endif
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h3 class="text-lg font-bold mb-4">Categorías</h3>
            @php
                $categories = \App\Models\Category::withCount('posts')
                    ->whereHas('posts', function($query) {
                        $query->where('status', 'published')
                            ->where(function($q) {
                                $q->whereNull('published_at')
                                    ->orWhere('published_at', '<=', now());
                            });
                    })
                    ->orderBy('posts_count', 'desc')
                    ->take(10)
                    ->get();
            @endphp
            @if($categories->count() > 0)
                <ul class="space-y-2">
                    @foreach($categories as $category)
                        <li>
                            <a href="{{ route('cms.category', $category->slug) }}" class="text-gray-600 hover:text-blue-600">
                                {{ $category->name }} ({{ $category->posts_count }})
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600">No hay categorías disponibles.</p>
            @endif
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-bold mb-4">Etiquetas populares</h3>
            @php
                $tags = \App\Models\Tag::withCount('posts')
                    ->whereHas('posts', function($query) {
                        $query->where('status', 'published')
                            ->where(function($q) {
                                $q->whereNull('published_at')
                                    ->orWhere('published_at', '<=', now());
                            });
                    })
                    ->orderBy('posts_count', 'desc')
                    ->take(20)
                    ->get();
            @endphp
            @if($tags->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($tags as $tag)
                        <a href="{{ route('cms.tag', $tag->slug) }}" class="inline-block bg-gray-100 text-sm text-gray-600 px-3 py-1 rounded-full hover:bg-gray-200">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">No hay etiquetas disponibles.</p>
            @endif
        </div>
    </div>
</div>
@endsection