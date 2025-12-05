<div class="mt-8 p-6 bg-gray-800/50 backdrop-blur rounded-lg">
    <h3 class="text-xl font-bold mb-4 text-white">Reactions</h3>
    
    <div class="flex gap-3 flex-wrap" x-data="reactionComponent()">
        @foreach(\App\Models\Reaction::types() as $type => $emoji)
            <button 
                @click="toggleReaction('{{ $type }}')"
                :class="userReaction === '{{ $type }}' ? 'ring-2 ring-purple-500 bg-purple-500/20' : 'hover:bg-gray-700/50'"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-700/30 transition-all duration-200 hover:scale-105"
            >
                <span class="text-2xl">{{ $emoji }}</span>
                <span class="text-sm font-semibold text-gray-300" x-text="reactions['{{ $type }}']?.count || 0"></span>
            </button>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
function reactionComponent() {
    return {
        reactions: @json($item->getReactionsSummary()),
        userReaction: null,
        
        init() {
            this.loadUserReaction();
        },
        
        async loadUserReaction() {
            const response = await fetch('/api/reactions?reactionable_type={{ $type }}&reactionable_id={{ $item->id }}');
            const data = await response.json();
            this.userReaction = data.user_reaction;
        },
        
        async toggleReaction(type) {
            if (!{{ auth()->check() ? 'true' : 'false' }}) {
                window.location.href = '/login';
                return;
            }
            
            const response = await fetch('/reactions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    reactionable_type: '{{ $type }}',
                    reactionable_id: {{ $item->id }},
                    type: type
                })
            });
            
            const data = await response.json();
            if (data.success) {
                this.reactions = data.reactions;
                this.userReaction = data.action === 'removed' ? null : type;
            }
        }
    }
}
</script>
@endpush
