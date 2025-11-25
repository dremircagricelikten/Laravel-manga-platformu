@extends('layouts.app')

@section('title', 'Tüm Seriler - ' . config('app.name'))

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold gradient-text mb-4">Tüm Seriler</h1>
        <p class="text-gray-400">{{ $series->total() }} seri bulundu</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Filters Sidebar -->
        <div class="lg:col-span-1">
            <div class="glass-effect rounded-xl p-6 sticky top-20">
                <h2 class="text-xl font-bold mb-6">Filtreler</h2>

                <form method="GET" action="/browse" class="space-y-6">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Ara</label>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Seri adı..." 
                               class="w-full px-4 py-2 bg-[#1a1a24] border border-gray-700 rounded-lg focus:outline-none focus:border-purple-500 transition">
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Tür</label>
                        <select name="type" class="w-full px-4 py-2 bg-[#1a1a24] border border-gray-700 rounded-lg focus:outline-none focus:border-purple-500 transition">
                            <option value="">Tümü</option>
                            <option value="manga" {{ request('type') === 'manga' ? 'selected' : '' }}>Manga</option>
                            <option value="novel" {{ request('type') === 'novel' ? 'selected' : '' }}>Novel</option>
                            <option value="anime" {{ request('type') === 'anime' ? 'selected' : '' }}>Anime</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Durum</label>
                        <select name="status" class="w-full px-4 py-2 bg-[#1a1a24] border border-gray-700 rounded-lg focus:outline-none focus:border-purple-500 transition">
                            <option value="">Tümü</option>
                            <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Devam Ediyor</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                            <option value="hiatus" {{ request('status') === 'hiatus' ? 'selected' : '' }}>Ara</option>
                        </select>
                    </div>

                    <!-- Categories -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Kategoriler</label>
                        <div class="space-y-2 max-h-60 overflow-y-auto">
                            @foreach($categories as $category)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="categories[]" 
                                           value="{{ $category->id }}"
                                           {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}
                                           class="w-4 h-4 text-purple-600 bg-[#1a1a24] border-gray-700 rounded focus:ring-purple-500">
                                    <span class="ml-2 text-sm text-gray-300">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Sırala</label>
                        <select name="sort" class="w-full px-4 py-2 bg-[#1a1a24] border border-gray-700 rounded-lg focus:outline-none focus:border-purple-500 transition">
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>En Yeni</option>
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>En Popüler</option>
                            <option value="trending" {{ request('sort') === 'trending' ? 'selected' : '' }}>Trend</option>
                            <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>İsme Göre</option>
                        </select>
                    </div>

                    <!-- Apply Filters -->
                    <button type="submit" 
                            class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg font-semibold hover:shadow-xl hover:shadow-purple-500/50 transition">
                        Filtrele
                    </button>

                    @if(request()->hasAny(['search', 'type', 'status', 'categories', 'sort']))
                        <a href="/browse" 
                           class="block w-full px-6 py-3 glass-effect rounded-lg text-center font-semibold hover:bg-white/10 transition">
                            Filtreleri Temizle
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Series Grid -->
        <div class="lg:col-span-3">
            @if($series->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach($series as $item)
                        <a href="/series/{{ $item->slug }}" class="card-hover group">
                            <div class="relative rounded-xl overflow-hidden">
                                <img src="{{ $item->cover_image ? Storage::url($item->cover_image) : '/images/placeholder.jpg' }}" 
                                     alt="{{ $item->title }}" 
                                     class="w-full aspect-[3/4] object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="absolute top-2 right-2">
                                    <span class="px-2 py-1 bg-purple-600 rounded text-xs font-semibold">{{ strtoupper($item->type) }}</span>
                                </div>
                                @if($item->chapters_count > 0)
                                    <div class="absolute bottom-2 left-2 right-2">
                                        <span class="block px-2 py-1 glass-effect rounded text-xs text-center">{{ $item->chapters_count }} Bölüm</span>
                                    </div>
                                @endif
                            </div>
                            <h3 class="mt-2 font-semibold text-sm line-clamp-2 group-hover:text-purple-500 transition">{{ $item->title }}</h3>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $series->links() }}
                </div>
            @else
                <div class="text-center py-20">
                    <div class="text-6xl mb-4">🔍</div>
                    <h3 class="text-xl font-semibold mb-2">Sonuç bulunamadı</h3>
                    <p class="text-gray-400 mb-6">Farklı filtreler deneyebilirsiniz</p>
                    <a href="/browse" 
                       class="inline-block px-6 py-3 glass-effect rounded-lg font-semibold hover:bg-white/10 transition">
                        Filtreleri Temizle
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
