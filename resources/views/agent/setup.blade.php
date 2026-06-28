@extends('layouts.app')

@section('title', 'Configure Agent - Janjez-Socio')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold mb-8">Configure DigitalOcean Agent</h1>
    
    <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100">
        <form action="{{ route('agent.configure') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Agent Name</label>
                <input type="text" name="agent_name" value="{{ $agent->agent_name ?? '' }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" 
                       required>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Agent Type</label>
                <select name="agent_type" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    <option value="video_processing">Video Processing</option>
                    <option value="ai_generation">AI Generation</option>
                    <option value="analytics">Analytics</option>
                </select>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Agent Endpoint URL</label>
                <input type="url" name="agent_endpoint" value="{{ $agent->agent_endpoint ?? '' }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" 
                       placeholder="https://your-agent.digitalocean.com" required>
                <p class="text-xs text-gray-500 mt-1">Your DigitalOcean agent endpoint URL</p>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">API Key</label>
                <input type="text" name="api_key" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" 
                       required>
                <p class="text-xs text-gray-500 mt-1">API key for authentication</p>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Capabilities</label>
                <div class="grid grid-cols-2 gap-3">
                    @foreach(['video_processing', 'image_enhancement', 'content_analysis', 'caption_generation'] as $capability)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="capabilities[]" value="{{ $capability }}" class="rounded">
                            <span class="text-sm">{{ ucfirst(str_replace('_', ' ', $capability)) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            
            <button type="submit" class="w-full bg-black text-white px-6 py-3 rounded-full hover:bg-purple-600 transition">
                Save Configuration
            </button>
        </form>
    </div>
</div>
@endsection