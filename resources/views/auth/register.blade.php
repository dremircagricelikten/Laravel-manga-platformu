@extends('layouts.app')

@section('title', 'Kayıt Ol - ' . config('app.name'))

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold gradient-text mb-2">Aramıza Katıl!</h1>
            <p class="text-gray-400">Manga dünyasına adım at</p>
        </div>

        <div class="glass-effect rounded-2xl p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Kullanıcı Adı</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name') }}"
                           required 
                           autofocus
                           class="w-full px-4 py-3 bg-[#1a1a24] border border-gray-700 rounded-lg focus:outline-none focus:border-purple-500 transition">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">E-posta</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email') }}"
                           required
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
                    <p class="mt-1 text-xs text-gray-500">En az 8 karakter olmalıdır</p>
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Şifre Tekrar</label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation" 
                           required
                           class="w-full px-4 py-3 bg-[#1a1a24] border border-gray-700 rounded-lg focus:outline-none focus:border-purple-500 transition">
                </div>

                <!-- Terms -->
                <div class="flex items-start">
                    <input type="checkbox" 
                           name="terms" 
                           id="terms"
                           required
                           class="w-4 h-4 mt-1 text-purple-600 bg-[#1a1a24] border-gray-700 rounded focus:ring-purple-500">
                    <label for="terms" class="ml-2 text-sm text-gray-400">
                        <a href="/terms" class="text-purple-500 hover:text-purple-400">Kullanım Şartlarını</a> ve
                        <a href="/privacy" class="text-purple-500 hover:text-purple-400">Gizlilik Politikasını</a> kabul ediyorum
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg font-semibold hover:shadow-xl hover:shadow-purple-500/50 transition">
                    Kayıt Ol
                </button>

                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-gray-400">
                        Zaten hesabın var mı?
                        <a href="/login" class="text-purple-500 hover:text-purple-400 font-semibold transition">
                            Giriş Yap
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
