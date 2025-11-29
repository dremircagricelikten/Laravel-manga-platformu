@extends('layouts.app')

@section('title', 'Payment Failed')

@section('content')
<div class="container mx-auto px-4 py-16 text-center max-w-2xl">
    <div class="glass-effect rounded-xl p-12">
        <div class="text-8xl mb-6">❌</div>
        <h1 class="text-4xl font-bold mb-4">Payment Failed</h1>
        <p class="text-xl text-gray-300 mb-8">There was a problem processing your payment. Please try again.</p>
        
        <div class="flex gap-4 justify-center">
            <a href="{{ route('checkout') }}" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg font-semibold hover:shadow-xl transition">
                Try Again
            </a>
            <a href="{{ route('coin-packages') }}" class="px-6 py-3 glass-effect rounded-lg font-semibold hover:bg-white/10 transition">
                Back to Packages
            </a>
        </div>
    </div>
</div>
@endsection
