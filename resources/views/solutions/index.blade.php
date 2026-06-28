@extends('layouts.app')

@section('title', 'Solutions - Janjez-Socio')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-4">Solutions</h1>
        <p class="text-gray-500">AI-powered tools for your marketing strategy</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        @foreach([
            ['label' => 'Total Reach', 'value' => number_format($performance['total_reach'] ?? 0), 'icon' => 'eye'],
            ['label' => 'Total Engagement', 'value' => $performance['total_engagement'] ?? 0, 'icon' => 'heart'],
            ['label' => 'Conversions', 'value' => $performance['total_conversions'] ?? 0, 'icon' => 'shopping-cart'],
            ['label' => 'ROI', 'value' => ($performance['roi'] ?? 0) . '%', 'icon' => 'chart-line']
        ] as $stat)
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-bold">{{ $stat['value'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-{{ $stat['icon'] }} text-purple-600"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100">
            <h2 class="text-xl font-semibold mb-4">Campaign Performance</h2>
            <div class="space-y-4">
                @forelse($campaigns as $campaign)
                    <div class="border-b pb-4 last:border-0">
                        <h3 class="font-medium">{{ $campaign->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $campaign->total_reach }} reach</p>
                    </div>
                @empty
                    <p class="text-gray-500">No campaigns yet. <a href="{{ route('solutions.campaign-builder') }}" class="text-purple-600">Create your first campaign</a></p>
                @endforelse
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100">
            <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('solutions.campaign-builder') }}" class="block w-full bg-black text-white px-4 py-3 rounded-lg hover:bg-purple-600 transition text-center">
                    <i class="fas fa-bullhorn"></i> Launch New Campaign
                </a>
                <a href="{{ route('solutions.analytics') }}" class="block w-full border border-gray-300 px-4 py-3 rounded-lg hover:bg-gray-50 transition text-center">
                    <i class="fas fa-chart-bar"></i> View Analytics
                </a>
                <a href="{{ route('agent.setup') }}" class="block w-full border border-gray-300 px-4 py-3 rounded-lg hover:bg-gray-50 transition text-center">
                    <i class="fas fa-robot"></i> Configure Agent
                </a>
            </div>
        </div>
    </div>
</div>
@endsection