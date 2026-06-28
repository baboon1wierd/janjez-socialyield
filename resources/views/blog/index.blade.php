@extends('layouts.app')

@section('title', 'Blog - Janjez-Socio')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold mb-8">Blog</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @for($i = 1; $i <= 6; $i++)
            <article class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h2 class="text-xl font-semibold mb-2">
                    <a href="{{ route('blog.show', 'blog-post-slug') }}" class="hover:text-purple-600">Blog Post Title {{ $i }}</a>
                </h2>
                <p class="text-gray-500 text-sm mb-4">Published on {{ now()->format('M d, Y') }}</p>
                <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <a href="{{ route('blog.show', 'blog-post-slug') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                    Read more <i class="fas fa-arrow-right"></i>
                </a>
            </article>
        @endfor
    </div>
</div>
@endsection