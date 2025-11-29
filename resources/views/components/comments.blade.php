<div class="mt-8 p-6 bg-gray-800/50 backdrop-blur rounded-lg" x-data="commentsComponent()">
    <h3 class="text-xl font-bold mb-6 text-white">
        Comments (<span x-text="comments.length"></span>)
    </h3>
    
    <!-- Comment Form -->
    @auth
    <div class="mb-6">
        <textarea 
            x-model="newComment"
            placeholder="Write a comment..."
            rows="3"
            class="w-full px-4 py-3 bg-gray-700/50 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
        ></textarea>
        <div class="mt-2 flex justify-end">
            <button 
                @click="postComment()"
                :disabled="!newComment.trim()"
                class="px-6 py-2 bg-purple-600 hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg transition-colors"
            >
                Post Comment
            </button>
        </div>
    </div>
    @else
    <p class="text-gray-400 mb-6">
        <a href="{{ route('login') }}" class="text-purple-500 hover:underline">Login</a> to comment
    </p>
    @endauth
    
    <!-- Comments List -->
    <div class="space-y-4">
        <template x-for="comment in comments" :key="comment.id">
            <div class="p-4 bg-gray-700/30 rounded-lg">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                            <span x-text="comment.user.name.charAt(0).toUpperCase()"></span>
                        </div>
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold text-white" x-text="comment.user.name"></span>
                            <span class="text-xs text-gray-400" x-text="formatDate(comment.created_at)"></span>
                        </div>
                        
                        <p class="text-gray-300 whitespace-pre-wrap" x-text="comment.content"></p>
                        
                        @auth
                        <div class="mt-2 flex items-center gap-4 text-sm">
                            <button 
                                @click="toggleReply(comment.id)"
                                class="text-purple-400 hover:text-purple-300"
                            >
                                Reply
                            </button>
                            
                            <template x-if="comment.user.id === {{ auth()->id() }}">
                                <button 
                                    @click="deleteComment(comment.id)"
                                    class="text-red-400 hover:text-red-300"
                                >
                                    Delete
                                </button>
                            </template>
                        </div>
                        @endauth
                        
                        <!-- Reply Form -->
                        <div x-show="replyingTo === comment.id" class="mt-3">
                            <textarea 
                                x-model="replyContent"
                                placeholder="Write a reply..."
                                rows="2"
                                class="w-full px-3 py-2 bg-gray-700/50 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                            ></textarea>
                            <div class="mt-2 flex gap-2">
                                <button 
                                    @click="postReply(comment.id)"
                                    class="px-4 py-1 bg-purple-600 hover:bg-purple-700 text-white rounded text-sm"
                                >
                                    Reply
                                </button>
                                <button 
                                    @click="replyingTo = null"
                                    class="px-4 py-1 bg-gray-600 hover:bg-gray-700 text-white rounded text-sm"
                                >
                                    Cancel
                                </button>
                            </div>
                        </div>
                        
                        <!-- Nested Replies -->
                        <template x-if="comment.replies && comment.replies.length > 0">
                            <div class="mt-4 ml-6 space-y-3 border-l-2 border-purple-500/30 pl-4">
                                <template x-for="reply in comment.replies" :key="reply.id">
                                    <div>
                                        <div class="flex items-start gap-2">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                                <span x-text="reply.user.name.charAt(0).toUpperCase()"></span>
                                            </div>
                                            
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-semibold text-white text-sm" x-text="reply.user.name"></span>
                                                    <span class="text-xs text-gray-400" x-text="formatDate(reply.created_at)"></span>
                                                </div>
                                                <p class="text-gray-300 text-sm" x-text="reply.content"></p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

@push('scripts')
<script>
function commentsComponent() {
    return {
        comments: @json($item->comments),
        newComment: '',
        replyContent: '',
        replyingTo: null,
        
        async postComment() {
            const response = await fetch('/comments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    commentable_type: '{{ $type }}',
                    commentable_id: {{ $item->id }},
                    content: this.newComment
                })
            });
            
            const data = await response.json();
            if (data.success) {
                this.comments.unshift(data.comment);
                this.newComment = '';
            }
        },
        
        async postReply(parentId) {
            const response = await fetch('/comments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    commentable_type: '{{ $type }}',
                    commentable_id: {{ $item->id }},
                    parent_id: parentId,
                    content: this.replyContent
                })
            });
            
            const data = await response.json();
            if (data.success) {
                location.reload(); // Refresh to show nested reply
            }
        },
        
        async deleteComment(commentId) {
            if (!confirm('Delete this comment?')) return;
            
            const response = await fetch(`/comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            if (data.success) {
                this.comments = this.comments.filter(c => c.id !== commentId);
            }
        },
        
        toggleReply(commentId) {
            this.replyingTo = this.replyingTo === commentId ? null : commentId;
            this.replyContent = '';
        },
        
        formatDate(date) {
            return new Date(date).toLocaleString();
        }
    }
}
</script>
@endpush
