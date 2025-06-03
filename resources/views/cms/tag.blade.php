@extends('layouts.cms')

@section('title', $tag->meta_title ?? $tag->name)
@section('meta_description', $tag->meta_description)
@section('meta_keywords', $tag->meta_keywords)

@section('content')
<div class="max-w-4xl mx-auto">
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $tag->name }}</h1>
        @if($tag->description)
            <p class="text-lg text-gray-600">{{ $tag->description }}</p>
        @endif
    </header>

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
                                @foreach($post->tags as $postTag)
                                    <a href="{{ route('cms.tag', $postTag->slug) }}" class="inline-block bg-gray-100 text-sm text-gray-600 px-3 py-1 rounded-full hover:bg-gray-200 {{ $postTag->id === $tag->id ? 'bg-blue-100 text-blue-600' : '' }}">
                                        {{ $postTag->name }}
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
        <p class="text-gray-600">No hay publicaciones disponibles con esta etiqueta.</p>
    @endif
</div>
@endsection