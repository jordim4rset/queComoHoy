@auth
    <div class="comment-form-container" style="margin-top: 20px; border-top: 1px solid #e0e0e0; padding-top: 15px;">
        <form
            id="comment-form-{{ $recipe->id }}"
            class="comment-form"
            data-recipe-id="{{ $recipe->id }}"
            data-comment-url="{{ route('comments.store', $recipe) }}"
        >
            @csrf
            <div style="display: flex; gap: 10px; align-items: flex-start;">
                <img
                    src="{{ auth()->user()->profilePhotoUrl() }}"
                    alt="{{ auth()->user()->username }}"
                    class="avatar"
                    style="width: 40px; height: 40px; border-radius: 50%;">

                <div style="flex: 1;">
                    <textarea
                        name="content"
                        placeholder="{{ __('messages.add_comment_placeholder') }}"
                        required
                        style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 8px; font-family: inherit; font-size: 14px; resize: vertical; min-height: 50px;"
                    ></textarea>
                    <div style="margin-top: 10px; text-align: right;">
                        <button
                            type="submit"
                            class="btn-primary"
                            style="padding: 8px 16px; background-color: #FF6B6B; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                            {{ __('messages.comment') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('comment-form-{{ $recipe->id }}').addEventListener('submit', async (e) => {
            e.preventDefault();

            const textarea = e.target.querySelector('textarea');
            const content = textarea.value.trim();

            if (!content) return;

            try {
                const response = await fetch('/recetas/{{ $recipe->id }}/comments', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
                    },
                    body: JSON.stringify({ content })
                });

                const data = await response.json();

                if (data.success) {
                    // Agregar el nuevo comentario a la lista
                    const commentsContainer = document.querySelector('.comments-list-{{ $recipe->id }}');
                    const emptyPlaceholder = commentsContainer.querySelector('.no-comments-placeholder');
                    if (emptyPlaceholder) {
                        emptyPlaceholder.remove();
                    }
                    const newComment = `
                        <div class="comment" data-comment-id="${data.comment.id}" style="display: flex; gap: 10px; margin-bottom: 15px;">
                            <img
                                src="${data.comment.avatar}"
                                alt="${data.comment.username}"
                                class="avatar"
                                style="width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;">

                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <strong>${data.comment.username}</strong>
                                    <span style="font-size: 12px; color: #999;">${data.comment.created_at}</span>
                                </div>
                                <p style="margin: 5px 0 0 0; color: #333;">${escapeHtml(data.comment.content)}</p>
                                <button class="delete-comment-btn" data-comment-id="${data.comment.id}" data-recipe-id="{{ $recipe->id }}" style="margin-top: 8px; background: none; border: none; color: #999; cursor: pointer; font-size: 12px;">{{ __('messages.delete') }}</button>
                            </div>
                        </div>
                    `;

                    commentsContainer.insertAdjacentHTML('afterbegin', newComment);
                    textarea.value = '';

                    // Actualizar el contador de comentarios
                    const commentCount = document.querySelector('.comments-count-{{ $recipe->id }}');
                    commentCount.textContent = parseInt(commentCount.textContent) + 1;
                }
            } catch (error) {
                console.error('{{ __('messages.comment_add_error') }}', error);
                alert('{{ __('messages.comment_add_error') }}');
            }
        });

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
@else
    <div style="margin-top: 20px; padding: 15px; background-color: #f5f5f5; border-radius: 8px; text-align: center;">
        <p style="color: #666; margin: 0;">
            <a href="{{ route('auth.login') }}" style="color: #FF6B6B; text-decoration: none; font-weight: 500;">{{ __('messages.login') }}</a> {{ __('messages.to_comment') }}
        </p>
    </div>
@endauth
