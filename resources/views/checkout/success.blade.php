@extends('layouts.app')

@section('title', 'Payment Successful')

@section('content')
<div class="container mx-auto px-4 py-16 text-center max-w-2xl">
    <div class="glass-effect rounded-xl p-12">
        <div class="text-8xl mb-6">✅</div>
        <h1 class="text-4xl font-bold mb-4">Payment Successful!</h1>
        <p class="text-xl text-gray-300 mb-8">Your Ki Coins have been added to your wallet.</p>
        
        <div class="flex gap-4 justify-center">
            <a href="{{ route('profile') }}" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg font-semibold hover:shadow-xl transition">
                View My Wallet
            </a>
            <a href="{{ route('browse') }}" class="px-6 py-3 glass-effect rounded-lg font-semibold hover:bg-white/10 transition">
                Browse Content
            </a>
        </div>
    </div>
</div>
@endsection
