@extends('layouts.app')

@section('title', 'Ki Coin Paketleri - ' . config('app.name'))

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
        <div class="text-6xl mb-4">💰</div>
        <h1 class="text-4xl font-bold gradient-text mb-4">Ki Coin Satın Al</h1>
        <p class="text-gray-400 text-lg">Premium bölümleri açmak için Ki Coin satın alın</p>
        
        @auth
            <div class="mt-6 inline-flex items-center space-x-3 px-6 py-3 glass-effect rounded-xl">
                <span class="text-gray-400">Mevcut Bakiyeniz:</span>
                <div class="flex items-center space-x-2">
                    <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-2xl font-bold text-yellow-500">{{ number_format(auth()->user()->ki_balance) }}</span>
                </div>
            </div>
        @endauth
    </div>

    <!-- Packages -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
        @foreach($packages as $package)
            <div class="glass-effect rounded-2xl p-8 hover:scale-105 transition-transform duration-300 {{ $package->is_popular ? 'ring-2 ring-purple-500' : '' }}">
                @if($package->is_popular)
                    <div class="mb-4">
                        <span class="px-3 py-1 bg-purple-600 rounded-full text-xs font-semibold">⭐ EN POPÜLER</span>
                    </div>
                @endif

                <div class="text-center mb-6">
                    <div class="flex items-center justify-center space-x-2 mb-2">
                        <svg class="w-12 h-12 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-4xl font-bold text-yellow-500">{{ number_format($package->amount) }}</span>
                    </div>
                    <h3 class="text-xl font-bold mb-1">{{ $package->name }}</h3>
                    @if($package->bonus_amount > 0)
                        <p class="text-sm text-green-500 font-semibold">+{{ number_format($package->bonus_amount) }} Bonus Ki 🎁</p>
                    @endif
                </div>

                @if($package->description)
                    <p class="text-gray-400 text-sm text-center mb-6">{{ $package->description }}</p>
                @endif

                <div class="text-center mb-6">
                    <span class="text-3xl font-bold">₺{{ number_format($package->price, 2) }}</span>
                </div>

                @auth
                    <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="coin_package_id" value="{{ $package->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" 
                                    class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg font-semibold hover:shadow-xl hover:shadow-purple-500/50 transition transform hover:-translate-y-1">
                                Sepete Ekle
                            </button>
                        </form>
                @else
                    <a href="/login?redirect={{ urlencode(request()->url()) }}" 
                       class="block w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg text-center font-semibold hover:shadow-xl hover:shadow-purple-500/50 transition">
                        Giriş Yap ve Satın Al
                    </a>
                @endauth
            </div>
        @endforeach
    </div>

    <!-- Payment Methods Info -->
    <div class="mt-16 max-w-3xl mx-auto">
        <div class="glass-effect rounded-2xl p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">💳 Ödeme Yöntemleri</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="p-4">
                    <div class="text-3xl mb-2">💳</div>
                    <p class="text-sm text-gray-400">Kredi Kartı</p>
                </div>
                <div class="p-4">
                    <div class="text-3xl mb-2">🏦</div>
                    <p class="text-sm text-gray-400">Banka Kartı</p>
                </div>
                <div class="p-4">
                    <div class="text-3xl mb-2">📱</div>
                    <p class="text-sm text-gray-400">Mobil Ödeme</p>
                </div>
                <div class="p-4">
                    <div class="text-3xl mb-2">💰</div>
                    <p class="text-sm text-gray-400">Havale/EFT</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ -->
    <div class="mt-12 max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-center">❓ Sık Sorulan Sorular</h2>
        <div class="space-y-4">
            <div class="glass-effect rounded-xl p-6">
                <h3 class="font-bold mb-2">Ki Coin nedir?</h3>
                <p class="text-gray-400 text-sm">Ki Coin, platformumuzda premium bölümleri açmak için kullanılan sanal para birimidir.</p>
            </div>
            <div class="glass-effect rounded-xl p-6">
                <h3 class="font-bold mb-2">Ki Coin'ler ne zaman hesabıma gelir?</h3>
                <p class="text-gray-400 text-sm">Ödeme işlemi tamamlandıktan hemen sonra Ki Coin'ler otomatik olarak hesabınıza yüklenir.</p>
            </div>
            <div class="glass-effect rounded-xl p-6">
                <h3 class="font-bold mb-2">Ki Coin'lerin kullanım süresi var mı?</h3>
                <p class="text-gray-400 text-sm">Hayır, Ki Coin'lerinizin kullanım süresi yoktur. İstediğiniz zaman kullanabilirsiniz.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
         class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 animate-bounce">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
         class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg z-50">
        {{ session('error') }}
    </div>
@endif
@endpush
