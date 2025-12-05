@extends('layouts.app')

@section('title', $series->title . ' - ' . config('app.name'))

@section('content')
<!-- NSFW Warning Modal -->
@if($series->is_nsfw && !session('nsfw_accepted_' . $series->id))
<div class="fixed inset-0 bg-black/95 backdrop-blur-sm z-50 flex items-center justify-center" x-data="{ show: true }" x-show="show">
    <div class="max-w-md mx-auto px-4">
        <div class="glass-effect rounded-2xl p-8 text-center">
            <div class="w-20 h-20 bg-red-600/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="text-4xl">🔞</span>
            </div>
            <h2 class="text-3xl font-bold mb-4">Yetişkin İçerik Uyarısı</h2>
            <p class="text-gray-300 mb-2">Bu seri yetişkin içerik (18+) içermektedir.</p>
            <p class="text-gray-400 text-sm mb-8">Devam etmek için 18 yaşından büyük olduğunuzu onaylamanız gerekmektedir.</p>
            
            <div class="space-y-3">
                <form method="POST" action="/series/{{ $series->slug }}/nsfw-accept">
                    @csrf
                    <button type="submit" 
                            class="w-full px-6 py-3 bg-gradient-to-r from-red-600 to-orange-600 rounded-lg font-semibold hover:shadow-xl hover:shadow-red-500/50 transition">
                        18 Yaşındayım, Devam Et
                    </button>
                </form>
                <a href="/browse" 
                   class="w-full px-6 py-3 glass-effect rounded-lg font-semibold hover:bg-white/10 transition text-center block">
                    Geri Dön
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Hero Banner -->
<section class="relative h-[500px] overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0f] via-[#0a0a0f]/80 to-transparent"></div>
</section>

<!-- Series Info -->
<section class="container mx-auto px-4 -mt-32 relative z-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cover & Actions -->
        <div class="lg:col-span-1">
            <div class="sticky top-20">
                <img src="{{ $series->cover_image ? Storage::url($series->cover_image) : '/images/placeholder.jpg' }}" 
                     alt="{{ $series->title }}" 
                     class="w-full rounded-2xl shadow-2xl shadow-purple-500/20">
                
                <div class="mt-6 space-y-3">
                    @if($series->chapters->count() > 0)
                        @php
                            $firstChapter = $series->chapters->sortBy('chapter_number')->first();
                            $latestChapter = $series->chapters->sortByDesc('chapter_number')->first();
                        @endphp
                        <a href="/chapter/{{ $firstChapter->slug }}" 
                           class="block w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg text-center font-semibold hover:shadow-xl hover:shadow-purple-500/50 transition">
                            İlk Bölümü Oku
                        </a>
                        <a href="/chapter/{{ $latestChapter->slug }}" 
                           class="block w-full px-6 py-3 glass-effect rounded-lg text-center font-semibold hover:bg-white/10 transition">
                            Son Bölüme Devam Et
                        </a>
                    @endif
                    
                    <button onclick="toggleBookmark()" 
                            class="block w-full px-6 py-3 glass-effect rounded-lg text-center font-semibold hover:bg-white/10 transition"
                            x-data="{ bookmarked: false }">
                        <span x-show="!bookmarked">📖 Kütüphaneme Ekle</span>
                        <span x-show="bookmarked">✅ Kütüphanemde</span>
                    </button>
                </div>

                <!-- Stats -->
                <div class="mt-6 glass-effect rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Durum</span>
                        <span class="px-3 py-1 bg-purple-600/20 text-purple-500 rounded-full text-sm font-semibold">
                            {{ ucfirst($series->status) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Tür</span>
                        <span class="font-semibold">{{ strtoupper($series->type) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Bölüm Sayısı</span>
                        <span class="font-semibold">{{ $series->chapters->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Görüntülenme</span>
                        <span class="font-semibold">{{ number_format($series->views) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $series->title }}</h1>
            
            @if($series->alternative_titles)
                <p class="text-gray-400 mb-4">{{ $series->alternative_titles }}</p>
            @endif

            <!-- Categories -->
            <div class="flex flex-wrap gap-2 mb-6">
                @foreach($series->categories as $category)
                    <a href="/browse?category={{ $category->slug }}" 
                       class="px-4 py-2 glass-effect rounded-lg text-sm hover:bg-purple-600/20 hover:text-purple-500 transition">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <!-- Description -->
            <div class="glass-effect rounded-xl p-6 mb-8">
                <h2 class="text-2xl font-bold mb-4 gradient-text">📖 Açıklama</h2>
                <p class="text-gray-300 leading-relaxed whitespace-pre-line">{{ $series->description }}</p>
            </div>

            <!-- Chapter List -->
            <div class="glass-effect rounded-xl p-6" x-data="{ sortAsc: true }">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold gradient-text">📚 Bölümler ({{ $series->chapters->count() }})</h2>
                    <button @click="sortAsc = !sortAsc" 
                            class="px-4 py-2 glass-effect rounded-lg text-sm hover:bg-white/10 transition">
                        <span x-show="sortAsc">↑ Eskiden Yeniye</span>
                        <span x-show="!sortAsc">↓ Yeniden Eskiye</span>
                    </button>
                </div>

                <div class="space-y-2">
                    @php
                        $chapters = $series->chapters->sortBy('chapter_number');
                    @endphp
                    
                    <template x-if="sortAsc">
                        <div class="space-y-2">
                            @foreach($chapters as $chapter)
                                @include('partials.chapter-item', ['chapter' => $chapter])
                            @endforeach
                        </div>
                    </template>
                    
                    <template x-if="!sortAsc">
                        <div class="space-y-2">
                            @foreach($chapters->reverse() as $chapter)
                                @include('partials.chapter-item', ['chapter' => $chapter])
                            @endforeach
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Reactions -->
@include('components.reactions', ['item' => $series, 'type' => 'series'])

<!-- Comments -->
@include('components.comments', ['item' => $series, 'type' => 'series'])

@endsection

@push('scripts')
<script>
    function toggleBookmark() {
        // TODO: Implement bookmark via AJAX
        alert('Kütüphane özelliği yakında eklenecek!');
    }
</script>
@endpush
