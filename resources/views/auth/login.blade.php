@extends('layouts.app')

@section('title', 'Giriş Yap - ' . config('app.name'))

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold gradient-text mb-2">Tekrar Hoş Geldin!</h1>
            <p class="text-gray-400">Kütüphanene erişmek için giriş yap</p>
        </div>

        <div class="glass-effect rounded-2xl p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">E-posta</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email') }}"
                           required 
                           autofocus
                           class="w-full px-4 py-3 bg-[#1a1a24] border border-gray-700 rounded-lg focus:outline-none focus:border-purple-500 transition">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Şifre</label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           required
                           class="w-full px-4 py-3 bg-[#1a1a24] border border-gray-700 rounded-lg focus:outline-none focus:border-purple-500 transition">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="remember" 
                               class="w-4 h-4 text-purple-600 bg-[#1a1a24] border-gray-700 rounded focus:ring-purple-500">
                        <span class="ml-2 text-sm text-gray-400">Beni Hatırla</span>
                    </label>
                    <a href="/forgot-password" class="text-sm text-purple-500 hover:text-purple-400 transition">
                        Şifreni mi unuttun?
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg font-semibold hover:shadow-xl hover:shadow-purple-500/50 transition">
                    Giriş Yap
                </button>

                <!-- Social Login -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-[#121218] text-gray-400">veya</span>
                    </div>
                </div>

                <!-- Register Link -->
                <div class="text-center">
                    <p class="text-gray-400">
                        Hesabın yok mu?
                        <a href="/register" class="text-purple-500 hover:text-purple-400 font-semibold transition">
                            Kayıt Ol
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
