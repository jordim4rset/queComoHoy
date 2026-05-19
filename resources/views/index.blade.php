@extends('layout.layout')

@section('title')
    Inicio - QueComoHoy
@endsection

@section('content')
    <div class="app-container">

        <main class="feed">

            @forelse($recipes as $recipe)
                <div class="post">

                    <div class="post-header">
                        <div class="user-info">
                            <a href="{{ route('profile', ['id' => $recipe->user_id]) }}">
                                <img src="{{ $recipe->user?->profilePhotoUrl() ?? 'https://ui-avatars.com/api/?name=Usuario' }}"
                                    alt="{{ $recipe->user->username ?? 'Usuario' }}" class="avatar">
                            </a>

                            <a href="{{ route('profile', ['id' => $recipe->user_id]) }}" class="username username-link">
                                {{ $recipe->user->username ?? 'usuario_desconocido' }}
                            </a>
                        </div>

                        @auth
                            @if (auth()->id() !== $recipe->user_id)
                                @unless (in_array($recipe->user_id, $followingUserIds, true))
                                    <form method="POST" action="{{ url('/follow/' . $recipe->user_id) }}" class="follow-form-small">
                                        @csrf
                                        <button type="submit" class="follow-btn-small">Seguir</button>
                                    </form>
                                @endunless
                            @endif
                        @endauth
                    </div>

                    <div class="post-image-wrapper">
                        @include('recipes.partials.media-slider', ['recipe' => $recipe])
                    </div>

                    <div class="post-footer">
                        <div class="post-stats">
                            <div class="stat">
                                <span class="icon-stat like-btn" data-recipe-id="{{ $recipe->id }}"
                                    data-liked="{{ auth()->check() && $recipe->likes->contains('user_id', auth()->id()) ? 'true' : 'false' }}"
                                    style="cursor: pointer; color: {{ auth()->check() && $recipe->likes->contains('user_id', auth()->id()) ? 'red' : 'currentColor' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path
                                            d="M20.8 4.6c-1.7-1.7-4.5-1.7-6.2 0L12 7.2 9.4 4.6c-1.7-1.7-4.5-1.7-6.2 0s-1.7 4.5 0 6.2L12 19.6l8.8-8.8c1.7-1.7 1.7-4.5 0-6.2z" />
                                    </svg>
                                </span>
                                <span class="count">{{ $recipe->likes->count() }}</span>
                            </div>

                            <div class="stat">
                                <span class="icon-stat">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z" />
                                    </svg>
                                </span>
                                <span class="count">0</span>
                            </div>

                            <div class="stat">
                                <span class="icon-stat">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 10.6V7h-2v6.4l5 3 1-1.7z" />
                                    </svg>
                                </span>
                                <span class="count">{{ $recipe->time }} min</span>
                            </div>
                        </div>

                        <div class="post-description">

                            <p class="recipe-name">
                                {{ $recipe->name }}
                            </p>

                            <div class="recipe-description-row">
                                <a href="{{ route('profile', ['id' => $recipe->user_id]) }}"
                                    class="recipe-username username-link">
                                    {{ $recipe->user->username ?? 'usuario_desconocido' }}
                                </a>

                                <span class="recipe-description-text">
                                    {{ $recipe->description }}
                                </span>
                            </div>

                            <div class="comments-box"></div>

                            @if ($recipe->tags)
                                <p class="recipe-tags">
                                    @foreach (explode(',', $recipe->tags) as $tag)
                                        <span>#{{ trim($tag) }}</span>
                                    @endforeach
                                </p>
                            @endif

                        </div>

                    </div>

                </div>
            @empty
                <p>{{ $emptyMessage ?? 'No hay recetas todavía.' }}</p>
            @endforelse

        </main>

        @if($showSuggestions ?? true)
        <aside class="sidebar-right">

            <div class="suggestions-header">
                <span>Sugerencias para ti</span>
                <a href="{{ route('users.index') }}" class="view-all">Ver todo</a>
            </div>

            <div class="suggestions-list">

                @forelse($suggestions ?? [] as $user)
                    <div class="user-suggestion">
                        <a href="{{ route('profile', ['id' => $user->id]) }}">
                            <img src="{{ $user->profilePhotoUrl() }}"
                                alt="{{ $user->username }}" class="avatar-lg">
                        </a>

                        <div class="user-details">
                            <a href="{{ route('profile', ['id' => $user->id]) }}"
                                class="username-suggested username-link">
                                {{ $user->username }}
                            </a>

                            <div class="user-comment">
                                Nuevo en QueComoHoy
                            </div>
                        </div>

                    </div>
                @empty
                    <p>No hay sugerencias.</p>
                @endforelse

            </div>

        </aside>
        @endif

    </div>
@endsection
