@extends('install.layout')

@section('title', 'Installation Complete')
@section('subtitle', 'Your Manga CMS is ready to use!')

@section('content')
    <div class="text-center">
        <svg class="w-24 h-24 mx-auto text-green-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>

        <h2 class="text-3xl font-bold text-gray-800 mb-4">Installation Complete!</h2>
        
        <p class="text-gray-600 mb-8 max-w-xl mx-auto">
            Congratulations! Your Manga CMS has been successfully installed and is ready to use.
        </p>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-6 mb-8">
            <h3 class="font-bold text-purple-900 mb-4">What's Next?</h3>
            <ul class="text-left text-sm text-purple-800 space-y-2">
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Log in to the admin panel at <strong>/admin</strong></span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Configure your site settings (colors, currency, etc.)</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Create categories for your content</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Start adding your manga, novels, or anime series!</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Set up coin packages for monetization</span>
                </li>
            </ul>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
            <p class="text-yellow-800 text-sm">
                <strong>Security Reminder:</strong> For security reasons, it's recommended to delete the <code class="bg-yellow-100 px-2 py-1 rounded">/install</code> directory or at minimum the <code class="bg-yellow-100 px-2 py-1 rounded">storage/installed</code> file to prevent re-installation.
            </p>
        </div>

        <a href="/admin" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-lg hover:shadow-lg transition transform hover:-translate-y-0.5">
            Go to Admin Panel
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
        </a>
    </div>
@endsection
