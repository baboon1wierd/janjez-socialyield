@extends('layouts.app')

@section('title', 'Image Tools - Janjez-Socio')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Image Library</h1>
        <a href="{{ route('images.create') }}" class="bg-black text-white px-6 py-3 rounded-full hover:bg-purple-600 transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Upload Image
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($images as $image)
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition">
                <img src="{{ $image->image_url }}" alt="{{ $image->title }}" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="font-semibold">{{ $image->title }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $image->views }} views</p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs px-2 py-1 rounded-full bg-gray-100">{{ $image->status }}</span>
                        <a href="{{ route('images.show', $image) }}" class="text-purple-600 hover:text-purple-800 text-sm">View</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center py-12">
                <i class="fas fa-image text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">No images yet. Upload your first image!</p>
            </div>
        @endforelse
    </div>
</div>
@endsection