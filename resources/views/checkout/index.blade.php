@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8">Checkout</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Payment Method Selection -->
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('checkout.process') }}" x-data="{ method: 'paytr' }">
                @csrf

                <div class="glass-effect rounded-xl p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-6">Select Payment Method</h2>

                    <!-- PayTR -->
                    <label class="block p-4 rounded-lg cursor-pointer mb-4"
                           :class="method === 'paytr' ? 'bg-purple-600/20 border-2 border-purple-500' : 'bg-gray-800/50 border-2 border-gray-700'">
                        <div class="flex items-center gap-4">
                            <input type="radio" name="payment_method" value="paytr" 
                                   x-model="method" class="w-5 h-5">
                            <div class="flex-1">
                                <div class="font-bold text-lg">Credit/Debit Card</div>
                                <div class="text-sm text-gray-400">Pay securely with PayTR</div>
                            </div>
                            <div class="text-3xl">💳</div>
                        </div>
                    </label>

                    <!-- Bank Transfer -->
                    <label class="block p-4 rounded-lg cursor-pointer"
                           :class="method === 'bank_transfer' ? 'bg-purple-600/20 border-2 border-purple-500' : 'bg-gray-800/50 border-2 border-gray-700'">
                        <div class="flex items-center gap-4">
                            <input type="radio" name="payment_method" value="bank_transfer" 
                                   x-model="method" class="w-5 h-5">
                            <div class="flex-1">
                                <div class="font-bold text-lg">Bank Transfer / EFT</div>
                                <div class="text-sm text-gray-400">Manual approval required</div>
                            </div>
                            <div class="text-3xl">🏦</div>
                        </div>
                    </label>
                </div>

                <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg font-bold text-lg hover:shadow-xl transition">
                    Continue to Payment
                </button>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="glass-effect rounded-xl p-6">
                <h2 class="text-2xl font-bold mb-6">Order Summary</h2>
                
                <div class="space-y-3 mb-6">
                    @foreach($cartItems as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">{{ $item->coinPackage->title }} x{{ $item->quantity }}</span>
                            <span>₺{{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-between text-xl font-bold pt-3 border-t border-gray-700">
                    <span>Total</span>
                    <span class="text-purple-500">₺{{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
