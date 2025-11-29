@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8">Shopping Cart</h1>

    @if($cartItems->isEmpty())
        <div class="glass-effect rounded-xl p-12 text-center">
            <div class="text-6xl mb-4">🛒</div>
            <h2 class="text-2xl font-bold mb-2">Your cart is empty</h2>
            <p class="text-gray-400 mb-6">Add some Ki Coin packages to get started!</p>
            <a href="{{ route('coin-packages') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg font-semibold hover:shadow-xl transition">
                Browse Packages
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4" x-data="cartManager()">
                @foreach($cartItems as $item)
                    <div class="glass-effect rounded-xl p-6" x-data="{ quantity: {{ $item->quantity }} }">
                        <div class="flex items-center gap-6">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold mb-2">{{ $item->coinPackage->title }}</h3>
                                <p class="text-gray-400 mb-2">{{ number_format($item->coinPackage->coins) }} Ki Coins</p>
                                <p class="text-2xl font-bold text-purple-500">₺{{ number_format($item->coinPackage->price, 2) }}</p>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <button @click="updateQuantity({{ $item->id }}, quantity - 1)" 
                                            :disabled="quantity <= 1"
                                            class="w-10 h-10 bg-gray-700 rounded-lg hover:bg-gray-600 disabled:opacity-50">-</button>
                                    <span class="w-12 text-center font-bold" x-text="quantity"></span>
                                    <button @click="updateQuantity({{ $item->id }}, quantity + 1)"
                                            :disabled="quantity >= 99"
                                            class="w-10 h-10 bg-gray-700 rounded-lg hover:bg-gray-600 disabled:opacity-50">+</button>
                                </div>

                                <button @click="removeItem({{ $item->id }})" class="text-red-500 hover:text-red-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="glass-effect rounded-xl p-6 sticky top-20">
                    <h2 class="text-2xl font-bold mb-6">Order Summary</h2>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Subtotal</span>
                            <span class="font-bold">₺{{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-xl font-bold pt-3 border-t border-gray-700">
                            <span>Total</span>
                            <span class="text-purple-500">₺{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout') }}" class="block w-full px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg text-center font-bold hover:shadow-xl transition mb-3">
                        Proceed to Checkout
                    </a>

                    <a href="{{ route('coin-packages') }}" class="block w-full px-6 py-3 glass-effect rounded-lg text-center font-semibold hover:bg-white/10 transition">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function cartManager() {
    return {
        async updateQuantity(itemId, newQuantity) {
            if (newQuantity < 1 || newQuantity > 99) return;

            const response = await fetch(`/cart/${itemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ quantity: newQuantity })
            });

            if (response.ok) {
                location.reload();
            }
        },

        async removeItem(itemId) {
            if (!confirm('Remove this item from cart?')) return;

            const response = await fetch(`/cart/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (response.ok) {
                location.reload();
            }
        }
    }
}
</script>
@endpush
@endsection
