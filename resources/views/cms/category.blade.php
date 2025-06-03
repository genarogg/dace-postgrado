@extends('layouts.cms')

@section('title', $category->meta_title ?? $category->name)
@section('meta_description', $category->meta_description)
@section('meta_keywords', $category->meta_keywords)

@section('content')
<div class="max-w-4xl mx-auto">
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $category->name }}</h1>
        @if($category->description)
            <p class="text-lg text-gray-600">{{ $category->description }}</p>
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
        <p class="text-gray-600">No hay publicaciones disponibles en esta categoría.</p>
    @endif

    @if($category->children->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6">Subcategorías</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($category->children as $child)
                    <a href="{{ route('cms.category', $child->slug) }}" class="block p-6 bg-white shadow rounded-lg hover:shadow-md transition-shadow">
                        <h3 class="font-bold text-lg mb-2">{{ $child->name }}</h3>
                        @if($child->description)
                            <p class="text-gray-600 text-sm">{{ Str::limit($child->description, 100) }}</p>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection