@extends('layouts.app')

@section('title', 'DigitalOcean Agent Dashboard - Janjez-Socio')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold">AI Agent Dashboard</h1>
            <p class="text-gray-500 mt-1">Powered by DigitalOcean</p>
        </div>
        <a href="{{ route('videos.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left"></i> Back to Videos
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        @foreach([
            ['label' => 'Total Tasks', 'value' => $stats['total'], 'icon' => 'tasks', 'bg' => 'purple'],
            ['label' => 'Processing', 'value' => $stats['processing'], 'icon' => 'spinner', 'bg' => 'yellow'],
            ['label' => 'Completed', 'value' => $stats['completed'], 'icon' => 'check-circle', 'bg' => 'green'],
            ['label' => 'Failed', 'value' => $stats['failed'], 'icon' => 'exclamation-circle', 'bg' => 'red']
        ] as $stat)
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-bold">{{ $stat['value'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-{{ $stat['bg'] }}-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-{{ $stat['icon'] }} text-{{ $stat['bg'] }}-600"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    @if($agent)
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mb-8">
            <h2 class="text-xl font-semibold mb-4">Agent Status</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Agent Name</p>
                    <p class="font-medium">{{ $agent->agent_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="inline-flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $agent->status === 'active' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                        {{ ucfirst($agent->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Capacity</p>
                    <p class="font-medium">{{ $agent->current_tasks }} / {{ $agent->max_concurrent_tasks }} tasks</p>
                </div>
            </div>
        </div>
    @endif
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h2 class="text-xl font-semibold mb-4">Recent Tasks</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-500 border-b">
                        <th class="pb-3">Task Type</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Priority</th>
                        <th class="pb-3">Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        <tr class="border-b">
                            <td class="py-3">{{ $task->task_type }}</td>
                            <td>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $task->status === 'completed' ? 'bg-green-100 text-green-700' : 
                                       ($task->status === 'failed' ? 'bg-red-100 text-red-700' : 
                                       ($task->status === 'processing' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700')) }}">
                                    {{ ucfirst($task->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $task->priority === 'high' ? 'bg-red-100 text-red-700' : 
                                       ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </td>
                            <td class="text-sm text-gray-500">{{ $task->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-500">No tasks yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection