@extends('install.layout')

@section('title', 'Welcome')
@section('subtitle', 'Let\'s get your Manga CMS up and running!')

@section('content')
    <div class="text-center">
        <svg class="w-24 h-24 mx-auto text-purple-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>

        <h2 class="text-3xl font-bold text-gray-800 mb-4">Welcome to Manga CMS!</h2>
        
        <p class="text-gray-600 mb-8 max-w-xl mx-auto">
            This installation wizard will help you set up your Manga/Novel/Anime content management system in just a few simple steps.
        </p>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-6 mb-8">
            <h3 class="font-bold text-purple-900 mb-4">What you'll need:</h3>
            <ul class="text-left text-sm text-purple-800 space-y-2">
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span><strong>MySQL Database:</strong> Name, username, and password</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span><strong>Admin Account:</strong> Email and password for the first admin user</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span><strong>About 2-3 minutes:</strong> The installation is quick and easy!</span>
                </li>
            </ul>
        </div>

        <a href="{{ route('install.requirements') }}" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-lg hover:shadow-lg transition transform hover:-translate-y-0.5">
            Get Started
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
        </a>
    </div>
@endsection
