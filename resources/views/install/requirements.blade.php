@extends('install.layout')

@section('title', 'System Requirements')
@section('subtitle', 'Checking your server configuration')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6">System Requirements</h2>

    @php
        $allMet = true;
        foreach ($requirements['extensions'] as $ext) {
            if (!$ext['met']) $allMet = false;
        }
        foreach ($requirements['permissions'] as $perm) {
            if (!$perm['met']) $allMet = false;
        }
    @endphp

    <!-- PHP Version -->
    <div class="mb-6">
        <h3 class="font-bold text-gray-700 mb-3">PHP Version</h3>
        <div class="bg-{{ $requirements['php']['met'] ? 'green' : 'red' }}-50 border border-{{ $requirements['php']['met'] ? 'green' : 'red' }}-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <span class="font-medium text-gray-800">{{ $requirements['php']['name'] }}</span>
                @if($requirements['php']['met'])
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                @else
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                @endif
            </div>
            <p class="text-sm text-gray-600 mt-1">Current: PHP {{ PHP_VERSION }}</p>
        </div>
    </div>

    <!-- Extensions -->
    <div class="mb-6">
        <h3 class="font-bold text-gray-700 mb-3">PHP Extensions</h3>
        <div class="space-y-2">
            @foreach($requirements['extensions'] as $ext)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-800">{{ $ext['name'] }}</span>
                    @if($ext['met'])
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Permissions -->
    <div class="mb-8">
        <h3 class="font-bold text-gray-700 mb-3">Directory Permissions</h3>
        <div class="space-y-2">
            @foreach($requirements['permissions'] as $perm)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-800">{{ $perm['name'] }}</span>
                    @if($perm['met'])
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-between">
        <a href="{{ route('install.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">
            &larr; Back
        </a>

        @if($allMet)
            <a href="{{ route('install.database') }}" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-lg hover:shadow-lg transition">
                Next: Database Setup &rarr;
            </a>
        @else
            <button disabled class="px-6 py-3 bg-gray-300 text-gray-500 font-bold rounded-lg cursor-not-allowed">
                Fix Requirements First
            </button>
        @endif
    </div>
@endsection
