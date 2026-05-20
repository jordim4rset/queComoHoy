<div class="comments-list comments-list-{{ $recipe->id }}">
    @forelse($recipe->comments as $comment)
        <div class="comment" data-comment-id="{{ $comment->id }}">
            <img
                src="{{ $comment->user->profilePhotoUrl() }}"
                alt="{{ $comment->user->username }}"
                class="avatar comment-avatar">

            <div class="comment-body">
                <div class="comment-header">
                    <strong>{{ $comment->user->username }}</strong>
                    <span>{{ $comment->created_at->format('d M Y H:i') }}</span>
                </div>
                <p>{{ $comment->content }}</p>

                @auth
                    @if(auth()->id() === $comment->user_id || auth()->id() === $recipe->user_id)
                        <button
                            class="delete-comment-btn"
                            data-comment-id="{{ $comment->id }}"
                            data-recipe-id="{{ $recipe->id }}">
                            {{ __('messages.delete') }}
                        </button>
                    @endif
                @endauth
            </div>
        </div>
    @empty
        <div class="no-comments-placeholder">
            <p>{{ __('messages.no_comments_yet') }}</p>
        </div>
    @endforelse
</div>
