<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Manga CMS'))</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        :root {
            --primary: #8b5cf6;
            --primary-dark: #7c3aed;
            --secondary: #ec4899;
            --dark-bg: #0a0a0f;
            --dark-card: #121218;
            --dark-hover: #1a1a24;
        }
        
        body {
            background: var(--dark-bg);
            color: #e5e7eb;
        }
        
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(139, 92, 246, 0.3);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-overlay {
            background: linear-gradient(to bottom, transparent 0%, rgba(10, 10, 15, 0.7) 50%, rgba(10, 10, 15, 1) 100%);
        }
        
        .glass-effect {
            background: rgba(18, 18, 24, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(139, 92, 246, 0.2);
        }
    </style>
    
    @stack('styles')
</head>
<body class="min-h-screen">
    <!-- Navigation -->
    <nav class="glass-effect fixed top-0 w-full z-50" x-data="{ mobileMenu: false }">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-pink-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-xl">M</span>
                    </div>
                    <span class="text-xl font-bold gradient-text">{{ setting('site_name', 'Manga CMS') }}</span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    @php
                        $menuItems = \App\Models\MenuItem::where('is_active', true)->orderBy('sort_order')->get();
                    @endphp
                    @foreach($menuItems as $item)
                        <a href="{{ route($item->route) }}" class="text-gray-300 hover:text-purple-500 transition">{{ $item->title }}</a>
                    @endforeach
                </div>
                
                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="/profile" class="text-gray-300 hover:text-purple-500 transition flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>{{ auth()->user()->name }}</span>
                        </a>
                        <div class="flex items-center space-x-2 px-3 py-1 bg-purple-600/20 rounded-lg">
                            <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm font-semibold text-yellow-500">{{ auth()->user()->ki_balance }} Ki</span>
                        </div>
                        <form method="POST" action="/logout" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition">Çıkış</button>
                        </form>
                    @else
                        <a href="/login" class="text-gray-300 hover:text-purple-500 transition">Giriş Yap</a>
                        <a href="/register" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg hover:shadow-lg hover:shadow-purple-500/50 transition">Kayıt Ol</a>
                    @endauth
                </div>
                
                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileMenu" x-transition class="md:hidden border-t border-gray-800">
            <div class="px-4 py-4 space-y-3">
                @foreach($menuItems as $item)
                    <a href="{{ route($item->route) }}" class="block text-gray-300 hover:text-purple-500">{{ $item->title }}</a>
                @endforeach
                @guest
                    <a href="/login" class="block text-purple-500">Giriş Yap</a>
                    <a href="/register" class="block px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg text-center">Kayıt Ol</a>
                @endguest
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="pt-16">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-[#121218] border-t border-gray-800 mt-20">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold gradient-text mb-4">{{ setting('site_name', 'Manga CMS') }}</h3>
                    <p class="text-gray-400 text-sm">{{ setting('site_description', 'En iyi manga okuma deneyimi') }}</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Hızlı Linkler</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="/browse" class="hover:text-purple-500 transition">Tüm Seriler</a></li>
                        <li><a href="/latest" class="hover:text-purple-500 transition">Son Bölümler</a></li>
                        <li><a href="/popular" class="hover:text-purple-500 transition">Popüler</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Destek</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="/help" class="hover:text-purple-500 transition">Yardım</a></li>
                        <li><a href="/contact" class="hover:text-purple-500 transition">İletişim</a></li>
                        <li><a href="/terms" class="hover:text-purple-500 transition">Kullanım Şartları</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Sosyal Medya</h4>
                    <div class="flex space-x-4">
                        @if(setting('facebook_url'))
                            <a href="{{ setting('facebook_url') }}" class="text-gray-400 hover:text-purple-500 transition">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        @endif
                        @if(setting('twitter_url'))
                            <a href="{{ setting('twitter_url') }}" class="text-gray-400 hover:text-purple-500 transition">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                        @endif
                        @if(setting('discord_url'))
                            <a href="{{ setting('discord_url') }}" class="text-gray-400 hover:text-purple-500 transition">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20.317 4.37a19.791 19.791 0 00-4.885-1.515.074.074 0 00-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 00-5.487 0 12.64 12.64 0 00-.617-1.25.077.077 0 00-.079-.037A19.736 19.736 0 003.677 4.37a.07.07 0 00-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 00.031.057 19.9 19.9 0 005.993 3.03.078.078 0 00.084-.028 14.09 14.09 0 001.226-1.994.076.076 0 00-.041-.106 13.107 13.107 0 01-1.872-.892.077.077 0 01-.008-.128 10.2 10.2 0 00.372-.292.074.074 0 01.077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 01.078.01c.12.098.246.198.373.292a.077.077 0 01-.006.127 12.299 12.299 0 01-1.873.892.077.077 0 00-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 00.084.028 19.839 19.839 0 006.002-3.03.077.077 0 00.032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 00-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} {{ setting('site_name', 'Manga CMS') }}. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </footer>
    
    @stack('scripts')
</body>
</html>
