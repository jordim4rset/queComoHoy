@auth
    <div class="comment-form-container">
        <form
            id="comment-form-{{ $recipe->id }}"
            class="comment-form"
            data-recipe-id="{{ $recipe->id }}"
            data-comment-url="{{ route('comments.store', $recipe) }}"
        >
            @csrf
            <div class="comment-form-row">
                <img
                    src="{{ auth()->user()->profilePhotoUrl() }}"
                    alt="{{ auth()->user()->username }}"
                    class="avatar comment-avatar">

                <div class="comment-form-main">
                    <textarea
                        name="content"
                        placeholder="{{ __('messages.add_comment_placeholder') }}"
                        required
                    ></textarea>
                    <div class="comment-form-actions">
                        <button type="submit" class="btn btn-sm">
                            {{ __('messages.comment') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@else
    <div class="comment-login-box">
        <p>
            <a href="{{ route('auth.login') }}">{{ __('messages.login') }}</a> {{ __('messages.to_comment') }}
        </p>
    </div>
@endauth
