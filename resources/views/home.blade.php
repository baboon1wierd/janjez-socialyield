@extends('layouts.app')

@section('title', 'Janjez-Socio - AI social growth')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-12">
        <div class="inline-flex items-center gap-2 bg-gray-100 px-4 py-2 rounded-full text-sm font-medium mb-6">
            <i class="fas fa-bolt text-purple-600"></i> AI Agent for business growth · new
        </div>
        
        <h1 class="text-4xl md:text-5xl font-bold mb-6">
            Social media growth, <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600">powered by AI</span> for every platform.
        </h1>
        
        <p class="text-lg text-gray-600 max-w-2xl mb-8">
            Facebook, YouTube, TikTok, Instagram, WhatsApp & X — create, schedule, and optimize posts in one click.
        </p>
        
        <div class="flex flex-wrap gap-3 mb-8">
            @foreach(['Facebook', 'YouTube', 'TikTok', 'Instagram', 'WhatsApp', 'X'] as $platform)
                <span class="bg-white border border-gray-200 rounded-full px-4 py-2 text-sm font-medium flex items-center gap-2">
                    <i class="fab fa-{{ strtolower($platform) === 'x' ? 'x-twitter' : strtolower($platform) }} text-purple-600"></i> {{ $platform }}
                </span>
            @endforeach
        </div>
        
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('social-creative.create') }}" class="bg-black text-white px-6 py-3 rounded-full font-medium hover:bg-purple-600 transition flex items-center gap-2">
                <i class="fas fa-rocket"></i> Start free trial
            </a>
            <a href="#" class="bg-white border border-gray-300 px-6 py-3 rounded-full font-medium hover:bg-gray-100 transition flex items-center gap-2">
                <i class="fas fa-play"></i> Live demo
            </a>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-8 mb-12">
        <h2 class="text-2xl font-bold mb-8">From product to published post in 3 steps</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="flex flex-col gap-2">
                <div class="w-9 h-9 bg-gray-100 rounded-full flex items-center justify-center font-bold">1</div>
                <h4 class="font-semibold">Upload & configure</h4>
                <p class="text-sm text-gray-500">Add images, choose campaign goal (Showcase, Launch, Promo) and platform ratio.</p>
            </div>
            <div class="flex flex-col gap-2">
                <div class="w-9 h-9 bg-gray-100 rounded-full flex items-center justify-center font-bold">2</div>
                <h4 class="font-semibold">Make it yours</h4>
                <p class="text-sm text-gray-500">Add copy, logo, brand colors, or let AI write captions based on your strategy.</p>
            </div>
            <div class="flex flex-col gap-2">
                <div class="w-9 h-9 bg-gray-100 rounded-full flex items-center justify-center font-bold">3</div>
                <h4 class="font-semibold">Generate & export</h4>
                <p class="text-sm text-gray-500">Get complete posts: visuals, captions, hashtags. Download or regenerate instantly.</p>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        @foreach([
            ['icon' => 'pen-fancy', 'title' => "Not just an image. Complete post.", 'desc' => "Every post includes platform-optimized creative, on-brand caption, and relevant hashtags — all in one run."],
            ['icon' => 'bullhorn', 'title' => "Content that knows its goal", 'desc' => "Choose Showcase, Launch, or Promo — every element is shaped around that purpose."],
            ['icon' => 'layer-group', 'title' => "Full package: visuals, captions & hashtags", 'desc' => "No more piecing things together. Get everything you need, ready to publish."],
            ['icon' => 'users', 'title' => "For every creator & team", 'desc' => "Designers, agencies, social media managers, SMBs — produce on-brand variations in bulk."]
        ] as $card)
            <div class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition">
                <div class="text-2xl text-purple-600 mb-4">
                    <i class="fas fa-{{ $card['icon'] }}"></i>
                </div>
                <h3 class="font-bold mb-2">{{ $card['title'] }}</h3>
                <p class="text-sm text-gray-500">{{ $card['desc'] }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection