<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Manga CMS Installation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-3xl w-full">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-8 text-white">
                <h1 class="text-3xl font-bold">Manga CMS Installation</h1>
                <p class="mt-2 opacity-90">@yield('subtitle')</p>
            </div>

            <!-- Progress Bar -->
            <div class="bg-gray-100 px-8 py-4">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex-1 text-center">
                        <div class="w-8 h-8 mx-auto rounded-full @if(request()->routeIs('install.index') || request()->routeIs('install.requirements')) bg-purple-600 text-white @else bg-gray-300 @endif flex items-center justify-center font-bold">
                            1
                        </div>
                        <p class="mt-1 text-xs">Welcome</p>
                    </div>
                    <div class="flex-1 h-1 bg-gray-300"></div>
                    <div class="flex-1 text-center">
                        <div class="w-8 h-8 mx-auto rounded-full @if(request()->routeIs('install.database') || request()->routeIs('install.admin')) bg-purple-600 text-white @else bg-gray-300 @endif flex items-center justify-center font-bold">
                            2
                        </div>
                        <p class="mt-1 text-xs">Database</p>
                    </div>
                    <div class="flex-1 h-1 bg-gray-300"></div>
                    <div class="flex-1 text-center">
                        <div class="w-8 h-8 mx-auto rounded-full @if(request()->routeIs('install.finalize')) bg-purple-600 text-white @else bg-gray-300 @endif flex items-center justify-center font-bold">
                            3
                        </div>
                        <p class="mt-1 text-xs">Complete</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                @yield('content')
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-white text-sm">
            <p>Manga CMS v1.0 &copy; {{ date('Y') }}</p>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
