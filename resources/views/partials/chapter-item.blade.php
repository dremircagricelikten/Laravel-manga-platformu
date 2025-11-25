<a href="/chapter/{{ $chapter->slug }}" 
   class="flex items-center justify-between p-4 glass-effect rounded-lg hover:bg-purple-600/10 hover:border-purple-500/30 border border-transparent transition group">
    <div class="flex items-center space-x-4 flex-1">
        <div class="flex-shrink-0">
            @if($chapter->series->type === 'manga' && $chapter->cover_image)
                <img src="{{ Storage::url($chapter->cover_image) }}" 
                     alt="Chapter {{ $chapter->chapter_number }}" 
                     class="w-16 h-20 object-cover rounded">
            @else
                <div class="w-16 h-20 bg-gradient-to-br from-purple-600 to-pink-600 rounded flex items-center justify-center">
                    <span class="text-2xl font-bold">{{ $chapter->chapter_number }}</span>
                </div>
            @endif
        </div>
        
        <div class="flex-1 min-w-0">
            <h3 class="font-semibold text-lg group-hover:text-purple-500 transition">
                Bölüm {{ $chapter->chapter_number }}
                @if($chapter->volume_number)
                    <span class="text-gray-500 text-sm">• Cilt {{ $chapter->volume_number }}</span>
                @endif
            </h3>
            @if($chapter->title)
                <p class="text-sm text-gray-400 truncate">{{ $chapter->title }}</p>
            @endif
            <div class="flex items-center space-x-3 mt-1 text-xs text-gray-500">
                <span>{{ $chapter->published_at->diffForHumans() }}</span>
                @if($chapter->page_count)
                    <span>• {{ $chapter->page_count }} sayfa</span>
                @endif
            </div>
        </div>
    </div>

    <div class="flex items-center space-x-3">
        @if($chapter->is_premium)
            <div class="flex items-center space-x-1 px-3 py-1 bg-yellow-500/10 text-yellow-500 rounded-lg">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                </svg>
                <span class="font-semibold text-sm">{{ $chapter->unlock_cost }}</span>
            </div>
        @else
            <span class="px-3 py-1 bg-green-500/10 text-green-500 rounded-lg text-sm font-semibold">Ücretsiz</span>
        @endif
        
        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </div>
</a>
