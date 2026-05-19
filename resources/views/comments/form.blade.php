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
                        placeholder="Añade un comentario..."
                        required
                        style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 8px; font-family: inherit; font-size: 14px; resize: vertical; min-height: 50px;"
                    ></textarea>
                    <div style="margin-top: 10px; text-align: right;">
                        <button
                            type="submit"
                            class="btn-primary"
                            style="padding: 8px 16px; background-color: #FF6B6B; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                            Comentar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@else
    <div style="margin-top: 20px; padding: 15px; background-color: #f5f5f5; border-radius: 8px; text-align: center;">
        <p style="color: #666; margin: 0;">
            <a href="{{ route('auth.login') }}" style="color: #FF6B6B; text-decoration: none; font-weight: 500;">Inicia sesión</a> para comentar
        </p>
    </div>
@endauth
