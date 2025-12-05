@extends('layouts.app')

@section('title', 'Ana Sayfa - ' . config('app.name'))

@section('content')
<!-- Hero Slider -->
<section class="relative h-screen">
    <div class="swiper heroSwiper h-full">
        <div class="swiper-wrapper">
            @foreach($featuredSeries as $series)
            <div class="swiper-slide relative">
                <img src="{{ $series->cover_image ? (Str::startsWith($series->cover_image, 'images/') ? asset($series->cover_image) : Storage::url($series->cover_image)) : '/images/placeholder.jpg' }}" 
                     alt="{{ $series->title }}" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 hero-overlay"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8 md:p-16">
                    <div class="container mx-auto">
                        <div class="max-w-3xl">
                            <span class="inline-block px-3 py-1 bg-purple-600 rounded-full text-xs font-semibold mb-4">
                                {{ strtoupper($series->type) }}
                            </span>
                            <h1 class="text-4xl md:text-6xl font-bold mb-4">{{ $series->title }}</h1>
                            <p class="text-gray-300 text-lg mb-6 line-clamp-3">{{ $series->description }}</p>
                            <div class="flex flex-wrap gap-2 mb-6">
                                @foreach($series->categories as $category)
                                    <span class="px-3 py-1 bg-gray-800/70 rounded-full text-sm">{{ $category->name }}</span>
                                @endforeach
                            </div>
                            <div class="flex items-center space-x-4">
                                <a href="/series/{{ $series->slug }}" 
                                   class="px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg hover:shadow-xl hover:shadow-purple-500/50 transition font-semibold">
                                    Şimdi Oku
                                </a>
                                <a href="/series/{{ $series->slug }}" 
                                   class="px-8 py-3 glass-effect rounded-lg hover:bg-white/10 transition font-semibold">
                                    Detaylar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</section>

<!-- Trending Section -->
<section class="container mx-auto px-4 py-16">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold gradient-text">🔥 Trend Olanlar</h2>
        <a href="/browse?sort=trending" class="text-purple-500 hover:text-purple-400 transition">Tümünü Gör →</a>
    </div>
    
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
        @foreach($trendingSeries as $series)
            <a href="/series/{{ $series->slug }}" class="card-hover group">
                <div class="relative rounded-xl overflow-hidden">
                    <img src="{{ $series->cover_image ? (Str::startsWith($series->cover_image, 'images/') ? asset($series->cover_image) : Storage::url($series->cover_image)) : '/images/placeholder.jpg' }}" 
                         alt="{{ $series->title }}" 
                         class="w-full aspect-[3/4] object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="absolute top-2 right-2">
                        <span class="px-2 py-1 bg-purple-600 rounded text-xs font-semibold">{{ strtoupper($series->type) }}</span>
                    </div>
                    @if($series->chapters_count > 0)
                        <div class="absolute bottom-2 left-2 right-2">
                            <span class="block px-2 py-1 glass-effect rounded text-xs text-center">{{ $series->chapters_count }} Bölüm</span>
                        </div>
                    @endif
                </div>
                <h3 class="mt-2 font-semibold text-sm line-clamp-2 group-hover:text-purple-500 transition">{{ $series->title }}</h3>
            </a>
        @endforeach
    </div>
</section>

<!-- Latest Chapters -->
<section class="bg-[#121218] py-16">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold gradient-text">📚 Son Eklenen Bölümler</h2>
            <a href="/latest" class="text-purple-500 hover:text-purple-400 transition">Tümünü Gör →</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($latestChapters->take(9) as $chapter)
                <a href="/chapter/{{ $chapter->slug }}" class="flex items-center space-x-4 glass-effect rounded-xl p-4 hover:bg-white/5 transition group">
                    <img src="{{ $chapter->series->cover_image ? (Str::startsWith($chapter->series->cover_image, 'images/') ? asset($chapter->series->cover_image) : Storage::url($chapter->series->cover_image)) : '/images/placeholder.jpg' }}" 
                         alt="{{ $chapter->series->title }}" 
                         class="w-20 h-28 object-cover rounded-lg">
                    <div class="flex-1">
                        <h3 class="font-semibold line-clamp-1 group-hover:text-purple-500 transition">{{ $chapter->series->title }}</h3>
                        <p class="text-sm text-gray-400 mt-1">Bölüm {{ $chapter->chapter_number }}</p>
                        @if($chapter->title)
                            <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $chapter->title }}</p>
                        @endif
                        <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                            <span>{{ $chapter->published_at->diffForHumans() }}</span>
                            @if($chapter->is_premium)
                                <span class="flex items-center space-x-1 text-yellow-500">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ $chapter->unlock_cost }} Ki</span>
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Popular Series -->
<section class="container mx-auto px-4 py-16">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold gradient-text">⭐ Popüler Seriler</h2>
        <a href="/popular" class="text-purple-500 hover:text-purple-400 transition">Tümünü Gör →</a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($popularSeries as $series)
            <a href="/series/{{ $series->slug }}" class="glass-effect rounded-xl overflow-hidden card-hover group">
                <div class="relative">
                    <img src="{{ $series->cover_image ? (Str::startsWith($series->cover_image, 'images/') ? asset($series->cover_image) : Storage::url($series->cover_image)) : '/images/placeholder.jpg' }}" 
                         alt="{{ $series->title }}" 
                         class="w-full aspect-[16/9] object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60 group-hover:opacity-80 transition"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4">
                        <h3 class="font-bold text-lg line-clamp-2">{{ $series->title }}</h3>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-400 line-clamp-2 mb-3">{{ $series->description }}</p>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span>{{ $series->chapters_count }} Bölüm</span>
                        <span class="px-2 py-1 bg-purple-600/20 text-purple-500 rounded">{{ ucfirst($series->status) }}</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Initialize Hero Swiper
    const heroSwiper = new Swiper('.heroSwiper', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
    });
</script>
@endpush
