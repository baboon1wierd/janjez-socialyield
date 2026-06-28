@extends('layouts.app')

@section('title', 'Pricing - Janjez-Socio')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold mb-4">Simple, Transparent Pricing</h1>
        <p class="text-gray-500 max-w-2xl mx-auto">Choose the plan that fits your needs. All plans include our core AI features.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
        @foreach([
            ['name' => 'Free', 'price' => 0, 'features' => ['5 posts/month', 'Basic templates', 'Community support'], 'popular' => false],
            ['name' => 'Pro', 'price' => 29, 'features' => ['Unlimited posts', 'All platforms', 'AI generation', 'Priority support'], 'popular' => true],
            ['name' => 'Business', 'price' => 99, 'features' => ['Team collaboration', 'Analytics', 'API access', 'Dedicated agent'], 'popular' => false]
        ] as $plan)
            <div class="relative bg-white rounded-2xl p-8 shadow-sm border {{ $plan['popular'] ? 'border-purple-500' : 'border-gray-200' }}">
                @if($plan['popular'])
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-purple-600 text-white px-4 py-1 rounded-full text-sm font-medium">
                        Most Popular
                    </div>
                @endif
                
                <h3 class="text-xl font-semibold mb-2">{{ $plan['name'] }}</h3>
                <div class="mb-6">
                    <span class="text-4xl font-bold">${{ $plan['price'] }}</span>
                    <span class="text-gray-500">/month</span>
                </div>
                
                <ul class="space-y-3 mb-8">
                    @foreach($plan['features'] as $feature)
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500"></i>
                            <span class="text-sm">{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>
                
                <a href="#" class="block w-full text-center bg-black text-white px-6 py-3 rounded-full hover:bg-purple-600 transition">
                    Get Started
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection