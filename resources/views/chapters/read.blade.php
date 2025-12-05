@extends('layouts.app')

@section('title', $chapter->series->title . ' - Bölüm ' . $chapter->chapter_number)

@section('content')
<div class="min-h-screen bg-black" x-data="chapterReader()">
    <!-- Top Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-black/90 backdrop-blur-sm z-50 transition-transform duration-300"
         :class="{ '-translate-y-full': !showControls }">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="/series/{{ $chapter->series->slug }}" class="flex items-center space-x-3 text-gray-300 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="font-semibold">{{ $chapter->series->title }}</span>
                </a>
                
                <div class="flex items-center space-x-4">
                    <span class="text-gray-400 text-sm">Bölüm {{ $chapter->chapter_number }}</span>
                    @if($chapter->is_premium && !$isUnlocked)
                        <button @click="showUnlockModal = true" 
                                class="px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg text-sm font-semibold hover:shadow-lg transition">
                            🔓 Kilidi Aç ({{ $chapter->unlock_cost }} Ki)
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Premium Lock Overlay -->
    @if($chapter->is_premium && !$isUnlocked)
        <div class="fixed inset-0 bg-black/95 flex items-center justify-center z-40 backdrop-blur-sm">
            <div class="text-center max-w-md mx-auto px-4">
                <div class="w-24 h-24 bg-gradient-to-br from-purple-600 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h2 class="text-3xl font-bold mb-4">Premium Bölüm</h2>
                <p class="text-gray-400 mb-6">Bu bölümü okumak için kilidi açmanız gerekiyor</p>
                
                <div class="glass-effect rounded-xl p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-400">Açma Maliyeti</span>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-2xl font-bold text-yellow-500">{{ $chapter->unlock_cost }}</span>
                        </div>
                    </div>
                    @auth
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Mevcut Bakiyeniz</span>
                            <span class="text-xl font-bold {{ auth()->user()->ki_balance >= $chapter->unlock_cost ? 'text-green-500' : 'text-red-500' }}">
                                {{ auth()->user()->ki_balance }} Ki
                            </span>
                        </div>
                    @endauth
                </div>

                @auth
                    @if(auth()->user()->ki_balance >= $chapter->unlock_cost)
                        <button @click="unlockChapter()" 
                                class="w-full px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg font-semibold text-lg hover:shadow-xl hover:shadow-purple-500/50 transition mb-3">
                            🔓 Kilidi Aç ve Oku
                        </button>
                    @else
                        <a href="/coin-packages" 
                           class="block w-full px-8 py-4 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-lg font-semibold text-lg hover:shadow-xl transition mb-3">
                            💰 Ki Coin Satın Al
                        </a>
                    @endif
                @else
                    <a href="/login?redirect={{ urlencode(request()->url()) }}" 
                       class="block w-full px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg font-semibold text-lg hover:shadow-xl transition mb-3">
                        Giriş Yap ve Kilidi Aç
                    </a>
                @endauth

                <a href="/series/{{ $chapter->series->slug }}" 
                   class="block text-gray-400 hover:text-white transition">
                    ← Bölüm Listesine Dön
                </a>
            </div>
        </div>
    @else
        <!-- Reader Content -->
        <div class="pt-16">
            @if($chapter->series->type === 'manga')
                <!-- Manga Reader (Image-based) -->
                <div class="container mx-auto max-w-4xl" @click="toggleControls()">
                    @foreach($chapter->images as $index => $image)
                        <img src="{{ Str::startsWith($image, 'images/') ? asset($image) : Storage::url($image) }}" 
                             alt="Page {{ $index + 1 }}" 
                             class="w-full h-auto"
                             loading="lazy">
                    @endforeach
                </div>
            @elseif($chapter->series->type === 'novel')
                <!-- Novel Reader (Text-based) -->
                <div class="container mx-auto max-w-3xl px-4 py-12">
                    <div class="prose prose-invert prose-lg max-w-none">
                        {!! $chapter->content !!}
                    </div>
                </div>
            @elseif($chapter->series->type === 'anime')
                <!-- Anime Player (Video) -->
                <div class="container mx-auto max-w-5xl px-4">
                    <div class="aspect-video bg-black rounded-xl overflow-hidden">
                        <iframe src="{{ $chapter->video_url }}" 
                                class="w-full h-full" 
                                frameborder="0" 
                                allowfullscreen>
                        </iframe>
                    </div>
                </div>
            @endif
        </div>

        <!-- Bottom Navigation -->
        <nav class="fixed bottom-0 left-0 right-0 bg-black/90 backdrop-blur-sm z-50 transition-transform duration-300"
             :class="{ 'translate-y-full': !showControls }">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between h-16">
                    @if($previousChapter)
                        <a href="/chapter/{{ $previousChapter->slug }}" 
                           class="flex items-center space-x-2 px-4 py-2 glass-effect rounded-lg hover:bg-purple-600/20 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>Önceki Bölüm</span>
                        </a>
                    @else
                        <div></div>
                    @endif

                    <a href="/series/{{ $chapter->series->slug }}" 
                       class="px-4 py-2 glass-effect rounded-lg hover:bg-purple-600/20 transition">
                        📚 Bölüm Listesi
                    </a>

                    @if($nextChapter)
                        <a href="/chapter/{{ $nextChapter->slug }}" 
                           class="flex items-center space-x-2 px-4 py-2 glass-effect rounded-lg hover:bg-purple-600/20 transition">
                            <span>Sonraki Bölüm</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <div></div>
                    @endif
                </div>
            </div>
        </nav>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function chapterReader() {
        return {
            showControls: true,
            controlTimeout: null,

            toggleControls() {
                this.showControls = !this.showControls;
                this.resetControlTimeout();
            },

            resetControlTimeout() {
                clearTimeout(this.controlTimeout);
                this.showControls = true;
                this.controlTimeout = setTimeout(() => {
                    this.showControls = false;
                }, 3000);
            },

            async unlockChapter() {
                try {
                    const response = await fetch('/api/chapters/{{ $chapter->id }}/unlock', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Kilidi açarken bir hata oluştu');
                    }
                } catch (error) {
                    alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                }
            },

            init() {
                this.resetControlTimeout();
                
                // Mouse move detection
                document.addEventListener('mousemove', () => {
                    this.resetControlTimeout();
                });

                // Keyboard navigation
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowLeft' && '{{ $previousChapter?->slug }}') {
                        window.location.href = '/chapter/{{ $previousChapter?->slug }}';
                    } else if (e.key === 'ArrowRight' && '{{ $nextChapter?->slug }}') {
                        window.location.href = '/chapter/{{ $nextChapter?->slug }}';
                    }
                });
            }
        }
    }
</script>
@endpush

<!-- Reactions -->
@include('components.reactions', ['item' => $chapter, 'type' => 'chapter'])

<!-- Comments -->
@include('components.comments', ['item' => $chapter, 'type' => 'chapter'])
