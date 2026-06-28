@extends('layouts.app')

@section('title', 'Social Creative - Janjez-Socio')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Social Creative</h1>
        <a href="{{ route('social-creative.create') }}" class="bg-black text-white px-6 py-3 rounded-full hover:bg-purple-600 transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Create Post
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        @foreach([
            ['label' => 'Total Posts', 'value' => $stats['total'], 'icon' => 'file-alt'],
            ['label' => 'Published', 'value' => $stats['published'], 'icon' => 'check-circle', 'color' => 'green'],
            ['label' => 'Scheduled', 'value' => $stats['scheduled'], 'icon' => 'clock', 'color' => 'yellow'],
            ['label' => 'Engagement', 'value' => $stats['engagement'] ?? 0, 'icon' => 'chart-line', 'color' => 'purple']
        ] as $stat)
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-bold">{{ $stat['value'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-{{ $stat['icon'] }} {{ isset($stat['color']) ? 'text-' . $stat['color'] . '-600' : '' }}"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-500 border-b">
                        <th class="pb-3">Platform</th>
                        <th class="pb-3">Content</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Engagement</th>
                        <th class="pb-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr class="border-b">
                            <td class="py-3">
                                <i class="fab fa-{{ strtolower($post->platform) }} w-6"></i> {{ ucfirst($post->platform) }}
                            </td>
                            <td class="py-3 truncate max-w-xs">{{ Str::limit($post->caption ?? $post->content, 50) }}</td>
                            <td>
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100">{{ $post->status }}</span>
                            </td>
                            <td class="py-3">{{ $post->engagement_score }}</td>
                            <td>
                                <a href="{{ route('social-creative.show', $post) }}" class="text-purple-600 hover:text-purple-800 text-sm">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">No posts yet. Create your first post!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection