<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'DACE UNERG POSTGRADO') }} - @yield('title')</title>
    <meta name="description" content="@yield('meta_description')">
    <meta name="keywords" content="@yield('meta_keywords')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <header class="bg-white shadow">
        <nav class="container mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <a href="{{ route('cms.index') }}" class="text-2xl font-bold text-gray-800">
                    {{ config('app.name', 'DACE UNERG POSTGRADO') }}
                </a>
                <div class="space-x-4">
                    <a href="{{ route('cms.index') }}" class="text-gray-600 hover:text-gray-800">Blog</a>
                    @foreach(\App\Models\Page::where('status', 'published')->whereNull('parent_id')->orderBy('order')->get() as $page)
                        <a href="{{ route('cms.page', $page->slug) }}" class="text-gray-600 hover:text-gray-800">{{ $page->title }}</a>
                    @endforeach
                </div>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <footer class="bg-white shadow mt-8">
        <div class="container mx-auto px-4 py-6">
            <div class="text-center text-gray-600">
                &copy; {{ date('Y') }} {{ config('app.name', 'DACE UNERG POSTGRADO') }}. Todos los derechos reservados.
            </div>
        </div>
    </footer>
</body>
</html>