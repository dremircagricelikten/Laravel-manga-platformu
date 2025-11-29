@extends('layouts.app')

@section('title', 'Bank Transfer Payment')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-4xl font-bold mb-8">Bank Transfer Instructions</h1>

    <div class="glass-effect rounded-xl p-8 mb-8">
        <div class="text-center mb-6">
            <div class="text-6xl mb-4">🏦</div>
            <h2 class="text-2xl font-bold mb-2">Order #{{ $order->order_number }}</h2>
            <p class="text-3xl font-bold text-purple-500">₺{{ number_format($order->final_amount, 2) }}</p>
        </div>

        <div class="space-y-4 mb-8">
            <div class="bg-gray-800/50 rounded-lg p-4">
                <div class="text-sm text-gray-400 mb-1">Bank Name</div>
                <div class="font-bold">{{ $bankSettings['bank_name'] ?? 'N/A' }}</div>
            </div>

            <div class="bg-gray-800/50 rounded-lg p-4">
                <div class="text-sm text-gray-400 mb-1">Account Holder</div>
                <div class="font-bold">{{ $bankSettings['bank_account_holder'] ?? 'N/A' }}</div>
            </div>

            <div class="bg-gray-800/50 rounded-lg p-4">
                <div class="text-sm text-gray-400 mb-1">IBAN</div>
                <div class="font-bold font-mono">{{ $bankSettings['bank_iban'] ?? 'N/A' }}</div>
            </div>

            <div class="bg-gray-800/50 rounded-lg p-4">
                <div class="text-sm text-gray-400 mb-1">Branch</div>
                <div class="font-bold">{{ $bankSettings['bank_branch'] ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="bg-yellow-500/10 border border-yellow-500/50 rounded-lg p-4 mb-8">
            <div class="font-bold mb-2">⚠️ Important:</div>
            <ul class="text-sm text-gray-300 space-y-1">
                <li>• Please include order number <strong>{{ $order->order_number }}</strong> in transfer description</li>
                <li>• Transfer exact amount: <strong>₺{{ number_format($order->final_amount, 2) }}</strong></li>
                <li>• Upload receipt for faster processing</li>
                <li>• Processing time: 24-48 hours after receipt upload</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('checkout.bank-transfer.submit', $order) }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Transfer Date *</label>
                <input type="date" name="transfer_date" required max="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-3 bg-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2">Upload Receipt/Proof *</label>
                <input type="file" name="bank_receipt" accept="image/*" required
                       class="w-full px-4 py-3 bg-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <p class="text-xs text-gray-400 mt-1">JPG, PNG (Max 2MB)</p>
            </div>

            <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg font-bold hover:shadow-xl transition">
                Submit Receipt
            </button>
        </form>
    </div>

    <div class="text-center">
        <a href="{{ route('profile') }}" class="text-purple-500 hover:underline">
            View My Orders →
        </a>
    </div>
</div>
@endsection
