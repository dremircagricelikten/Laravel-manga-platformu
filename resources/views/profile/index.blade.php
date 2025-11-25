@extends('layouts.app')

@section('title', 'Profilim - ' . config('app.name'))

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Profile Header -->
    <div class="glass-effect rounded-2xl p-8 mb-8">
        <div class="flex items-center space-x-6">
            <div class="w-24 h-24 bg-gradient-to-br from-purple-600 to-pink-600 rounded-full flex items-center justify-center text-3xl font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="flex-1">
                <h1 class="text-3xl font-bold mb-2">{{ auth()->user()->name }}</h1>
                <p class="text-gray-400">{{ auth()->user()->email }}</p>
                @if(auth()->user()->is_vip)
                    <span class="inline-block mt-2 px-3 py-1 bg-yellow-500/20 text-yellow-500 rounded-full text-sm font-semibold">
                        ⭐ VIP Üye
                    </span>
                @endif
            </div>
            <div class="text-right">
                <div class="flex items-center space-x-2 mb-2">
                    <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-3xl font-bold text-yellow-500">{{ number_format(auth()->user()->ki_balance) }}</span>
                </div>
                <a href="/coin-packages" class="text-sm text-purple-500 hover:text-purple-400 transition">Ki Coin Satın Al →</a>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-8" x-data="{ tab: 'library' }">
        <div class="flex space-x-4 border-b border-gray-800 mb-6">
            <button @click="tab = 'library'" 
                    :class="tab === 'library' ? 'border-purple-500 text-purple-500' : 'border-transparent text-gray-400'"
                    class="px-4 py-3 border-b-2 font-semibold transition">
                📚 Kütüphanem
            </button>
            <button @click="tab = 'unlocked'" 
                    :class="tab === 'unlocked' ? 'border-purple-500 text-purple-500' : 'border-transparent text-gray-400'"
                    class="px-4 py-3 border-b-2 font-semibold transition">
                🔓 Açılan Bölümler
            </button>
            <button @click="tab = 'transactions'" 
                    :class="tab === 'transactions' ? 'border-purple-500 text-purple-500' : 'border-transparent text-gray-400'"
                    class="px-4 py-3 border-b-2 font-semibold transition">
                💰 İşlem Geçmişi
            </button>
        </div>

        <!-- Library Tab -->
        <div x-show="tab === 'library'" x-transition>
            @if($bookmarks->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                    @foreach($bookmarks as $bookmark)
                        <a href="/series/{{ $bookmark->series->slug }}" class="card-hover group">
                            <div class="relative rounded-xl overflow-hidden">
                                <img src="{{ $bookmark->series->cover_image ? Storage::url($bookmark->series->cover_image) : '/images/placeholder.jpg' }}" 
                                     alt="{{ $bookmark->series->title }}" 
                                     class="w-full aspect-[3/4] object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                            <h3 class="mt-2 font-semibold text-sm line-clamp-2 group-hover:text-purple-500 transition">{{ $bookmark->series->title }}</h3>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20">
                    <div class="text-6xl mb-4">📚</div>
                    <h3 class="text-xl font-semibold mb-2">Kütüphanende henüz seri yok</h3>
                    <p class="text-gray-400 mb-6">Sevdiğin serileri ekleyerek başla!</p>
                    <a href="/browse" class="inline-block px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg font-semibold hover:shadow-xl transition">
                        Serileri Keşfet
                    </a>
                </div>
            @endif
        </div>

        <!-- Unlocked Chapters Tab -->
        <div x-show="tab === 'unlocked'" x-transition>
            @if($unlockedChapters->count() > 0)
                <div class="space-y-3">
                    @foreach($unlockedChapters as $unlock)
                        <a href="/chapter/{{ $unlock->chapter->slug }}" 
                           class="flex items-center justify-between p-4 glass-effect rounded-lg hover:bg-purple-600/10 transition group">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $unlock->chapter->series->cover_image ? Storage::url($unlock->chapter->series->cover_image) : '/images/placeholder.jpg' }}" 
                                     alt="{{ $unlock->chapter->series->title }}" 
                                     class="w-16 h-20 object-cover rounded">
                                <div>
                                    <h3 class="font-semibold group-hover:text-purple-500 transition">{{ $unlock->chapter->series->title }}</h3>
                                    <p class="text-sm text-gray-400">Bölüm {{ $unlock->chapter->chapter_number }}</p>
                                    <p class="text-xs text-gray-500">{{ $unlock->created_at->diffForHumans() }} açıldı</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-yellow-500 font-semibold">{{ $unlock->cost }} Ki</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20">
                    <div class="text-6xl mb-4">🔓</div>
                    <h3 class="text-xl font-semibold mb-2">Henüz premium bölüm açmadınız</h3>
                    <p class="text-gray-400">Premium bölümleri Ki Coin ile açabilirsiniz</p>
                </div>
            @endif
        </div>

        <!-- Transactions Tab -->
        <div x-show="tab === 'transactions'" x-transition>
            @if($transactions->count() > 0)
                <div class="space-y-3">
                    @foreach($transactions as $transaction)
                        <div class="flex items-center justify-between p-4 glass-effect rounded-lg">
                            <div>
                                <h3 class="font-semibold">{{ $transaction->description }}</h3>
                                <p class="text-sm text-gray-400">{{ $transaction->created_at->format('d.m.Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-lg {{ $transaction->type === 'credit' ? 'text-green-500' : 'text-red-500' }}">
                                    {{ $transaction->type === 'credit' ? '+' : '-' }}{{ number_format($transaction->amount) }} Ki
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="text-center py-20">
                    <div class="text-6xl mb-4">💰</div>
                    <h3 class="text-xl font-semibold mb-2">İşlem geçmişiniz boş</h3>
                    <p class="text-gray-400">Ki Coin satın aldığınızda veya harcadığınızda burada görebilirsiniz</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
