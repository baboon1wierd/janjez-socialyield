@extends('layouts.app')

@section('title', 'Video Library - Janjez-Socio')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Video Library</h1>
        <div class="flex gap-3">
            <a href="{{ route('videos.agent-dashboard') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
                <i class="fas fa-robot"></i> Agent Dashboard
            </a>
            <a href="{{ route('videos.create') }}" class="bg-black text-white px-6 py-3 rounded-full hover:bg-purple-600 transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Upload Video
            </a>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($videos as $video)
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition">
                <div class="relative">
                    <img src="{{ $video->thumbnail_url ?? '/assets/video-placeholder.jpg' }}" 
                         alt="{{ $video->title }}" 
                         class="w-full h-48 object-cover">
                    <div class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                        <i class="fas fa-play"></i> {{ $video->duration ? gmdate('i:s', $video->duration) : '00:00' }}
                    </div>
                    @if($video->status === 'processing')
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <div class="text-white text-center">
                                <i class="fas fa-spinner fa-spin text-3xl"></i>
                                <p class="text-sm mt-2">Processing with Agent</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-semibold truncate">{{ $video->title }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $video->views }} views</p>
                    <div class="flex items-center gap-4 mt-3">
                        <span class="text-sm text-gray-500"><i class="fas fa-heart"></i> {{ $video->likes }}</span>
                        <span class="text-sm text-gray-500"><i class="fas fa-comment"></i> {{ $video->comments }}</span>
                        <span class="text-sm text-gray-500"><i class="fas fa-share"></i> {{ $video->shares }}</span>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs px-2 py-1 rounded-full bg-gray-100">{{ $video->status }}</span>
                        <a href="{{ route('videos.show', $video) }}" class="text-purple-600 hover:text-purple-800 text-sm">
                            View <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center py-12">
                <i class="fas fa-video text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">No videos yet. Upload your first video!</p>
            </div>
        @endforelse
    </div>
</div>
@endsection