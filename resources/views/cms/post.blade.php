@extends('layouts.cms')

@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description ?? $post->excerpt)
@section('meta_keywords', $post->meta_keywords)

@section('content')
<article class="max-w-4xl mx-auto">
    @if($post->featured_image)
        <div class="mb-8">
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-96 object-cover rounded-lg shadow">
        </div>
    @endif

    <header class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>
        
        <div class="flex items-center text-sm text-gray-500 mb-4">
            <span>{{ $post->published_at?->format('d/m/Y') }}</span>
            @if($post->category)
                <span class="mx-2">·</span>
                <a href="{{ route('cms.category', $post->category->slug) }}" class="hover:text-blue-600">
                    {{ $post->category->name }}
                </a>
            @endif
        </div>

        @if($post->excerpt)
            <p class="text-xl text-gray-600">{{ $post->excerpt }}</p>
        @endif
    </header>

    <div class="prose prose-lg max-w-none mb-8">
        {!! $post->content !!}
    </div>

    @if($post->tags->count() > 0)
        <div class="border-t border-gray-200 pt-8 mb-8">
            <h3 class="text-lg font-bold mb-4">Etiquetas:</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($post->tags as $tag)
                    <a href="{{ route('cms.tag', $tag->slug) }}" class="inline-block bg-gray-100 text-sm text-gray-600 px-3 py-1 rounded-full hover:bg-gray-200">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <div class="border-t border-gray-200 pt-8">
        <h3 class="text-lg font-bold mb-4">Posts relacionados</h3>
        @php
            $relatedPosts = \App\Models\Post::where('id', '!=', $post->id)
                ->where('status', 'published')
                ->where(function($query) use ($post) {
                    $query->where('category_id', $post->category_id)
                        ->orWhereHas('tags', function($q) use ($post) {
                            $q->whereIn('tags.id', $post->tags->pluck('id'));
                        });
                })
                ->latest('published_at')
                ->take(3)
                ->get();
        @endphp

        @if($relatedPosts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedPosts as $relatedPost)
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        @if($relatedPost->featured_image)
                            <img src="{{ asset('storage/' . $relatedPost->featured_image) }}" alt="{{ $relatedPost->title }}" class="w-full h-48 object-cover">
                        @endif
                        <div class="p-4">
                            <h4 class="font-bold mb-2">
                                <a href="{{ route('cms.post', $relatedPost->slug) }}" class="text-gray-900 hover:text-blue-600">
                                    {{ $relatedPost->title }}
                                </a>
                            </h4>
                            <div class="text-sm text-gray-500">
                                {{ $relatedPost->published_at?->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No hay posts relacionados disponibles.</p>
        @endif
    </div>
</article>
@endsection