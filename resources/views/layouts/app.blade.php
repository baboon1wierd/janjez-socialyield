<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Janjez-Socio · AI social growth')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div id="app">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center gap-2">
                                <div class="bg-black text-white w-10 h-10 rounded-xl flex items-center justify-center font-bold text-xl">
                                    <i class="fas fa-arrow-trend-up"></i>
                                </div>
                                <span class="font-bold text-2xl">Janjez<span class="text-purple-600">·</span>Socio</span>
                            </a>
                        </div>
                        
                        <div class="hidden space-x-6 lg:ml-10 lg:flex">
                            <a href="{{ route('images.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Image</a>
                            <a href="{{ route('videos.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Video</a>
                            <a href="{{ route('social-creative.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Social Creative</a>
                            <a href="{{ route('solutions.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Solutions</a>
                            <a href="{{ route('blog.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Blog</a>
                            <a href="{{ route('pricing') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Pricing</a>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ route('videos.agent-dashboard') }}" class="text-sm text-purple-600 hover:text-purple-800">
                                <i class="fas fa-robot"></i> Agent
                            </a>
                            <span class="text-sm text-gray-600">{{ auth()->user()->username }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700">Log in</a>
                            <a href="{{ route('register') }}" class="bg-black text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-purple-600 transition">Sign up</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
        
        <main class="min-h-screen">
            @yield('content')
        </main>
        
        <footer class="bg-gray-50 border-t border-gray-200 mt-12 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                    <div class="min-w-[180px]">
                        <a href="{{ route('home') }}" class="flex items-center gap-2">
                            <div class="bg-black text-white w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm">
                                <i class="fas fa-arrow-trend-up"></i>
                            </div>
                            <span class="font-bold">Janjez·Socio</span>
                        </a>
                        <p class="text-sm text-gray-500 mt-4">AI Agent for Business Design</p>
                    </div>
                    
                    <div>
                        <h5 class="font-semibold mb-3">Image</h5>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li><a href="#" class="hover:text-purple-600">Enhancer</a></li>
                            <li><a href="#" class="hover:text-purple-600">Upscaler</a></li>
                            <li><a href="#" class="hover:text-purple-600">Background Remover</a></li>
                            <li><a href="#" class="hover:text-purple-600">AI Photo Editor</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h5 class="font-semibold mb-3">Video</h5>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li><a href="#" class="hover:text-purple-600">Product Video Generator</a></li>
                            <li><a href="#" class="hover:text-purple-600">Remix</a></li>
                            <li><a href="#" class="hover:text-purple-600">Enhancer</a></li>
                            <li><a href="#" class="hover:text-purple-600">Background Remover</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h5 class="font-semibold mb-3">Social Creative</h5>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li><a href="#" class="hover:text-purple-600">Social Media Posts</a></li>
                            <li><a href="#" class="hover:text-purple-600">Logo Design</a></li>
                            <li><a href="#" class="hover:text-purple-600">Flyer Generator</a></li>
                            <li><a href="#" class="hover:text-purple-600">Mockup Generator</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h5 class="font-semibold mb-3">Connect</h5>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li><a href="#" class="hover:text-purple-600">About</a></li>
                            <li><a href="#" class="hover:text-purple-600">Contact</a></li>
                            <li><a href="#" class="hover:text-purple-600">Help Center</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="mt-8 pt-8 border-t border-gray-200 text-center text-sm text-gray-500">
                    &copy; 2026 T&A Technology Pty Ltd
                </div>
            </div>
        </footer>
    </div>
    
    @stack('scripts')
</body>
</html>