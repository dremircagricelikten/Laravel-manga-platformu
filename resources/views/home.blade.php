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
