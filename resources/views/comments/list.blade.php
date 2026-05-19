<div class="comments-list-{{ $recipe->id }}" style="margin-top: 20px;">
    @forelse($recipe->comments as $comment)
        <div class="comment" data-comment-id="{{ $comment->id }}" style="display: flex; gap: 10px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0;">
            <img
                src="{{ $comment->user->profilePhotoUrl() }}"
                alt="{{ $comment->user->username }}"
                class="avatar"
                style="width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;">

            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <strong>{{ $comment->user->username }}</strong>
                    <span style="font-size: 12px; color: #999;">{{ $comment->created_at->format('d M Y H:i') }}</span>
                </div>
                <p style="margin: 5px 0 0 0; color: #333;">{{ $comment->content }}</p>

                @auth
                    @if(auth()->id() === $comment->user_id || auth()->id() === $recipe->user_id)
                        <button
                            class="delete-comment-btn"
                            data-comment-id="{{ $comment->id }}"
                            data-recipe-id="{{ $recipe->id }}"
                            style="margin-top: 8px; background: none; border: none; color: #999; cursor: pointer; font-size: 12px; transition: color 0.2s;">
                            Eliminar
                        </button>
                    @endif
                @endauth
            </div>
        </div>
    @empty
        <div class="no-comments-placeholder" style="text-align: center; padding: 20px; color: #999;">
            <p>Sin comentarios aún. ¡Sé el primero en comentar!</p>
        </div>
    @endforelse
</div>

