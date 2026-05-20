@extends('layout.layout')

@section('title', $event->display_name)

@section('content')
    <section class="event-show-hero">
        <div class="event-show-copy">
            <a href="{{ route('eventos.index') }}" class="event-show-back">Volver a eventos</a>

            <div class="event-show-kicker">
                <span>{{ $event->active ? 'Evento activo' : 'Evento inactivo' }}</span>
                <span>{{ $event->recipes->count() }} recetas</span>
            </div>

            <h1>{{ $event->display_name }}</h1>
            <p>{{ $event->description }}</p>
        </div>

        <div class="event-show-gallery">
            @forelse(($event->images ?? []) as $img)
                <img src="{{ asset('storage/' . $img) }}" alt="{{ $event->display_name }}">
            @empty
                <div class="event-show-empty-media">
                    <span>{{ mb_substr($event->display_name, 0, 1) }}</span>
                </div>
            @endforelse
        </div>
    </section>

    <div class="app-container event-recipes-layout">
        <main class="feed">
            <div class="event-recipes-header">
                <div>
                    <span>Recetas del evento</span>
                    <h2>Inspiracion relacionada</h2>
                </div>
            </div>

            @forelse($event->recipes as $recipe)
                @php $recipeUser = $recipe->user; @endphp
                <div class="post">

                    <div class="post-header">
                        <div class="user-info">
                            @if ($recipeUser)
                                <a href="{{ route('profile', ['id' => $recipeUser->id]) }}">
                                    <img src="{{ $recipeUser->profilePhotoUrl() }}"
                                        alt="{{ $recipeUser->username }}" class="avatar">
                                </a>

                                <a href="{{ route('profile', ['id' => $recipeUser->id]) }}" class="username username-link">
                                    {{ $recipeUser->username }}
                                </a>
                            @else
                                <img src="https://ui-avatars.com/api/?name=Usuario" alt="Usuario" class="avatar">
                                <span class="username username-link">usuario_desconocido</span>
                            @endif
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

                    <a href="{{ route('recetas.show', ['receta' => $recipe->id]) }}" class="post-image-wrapper">
                        @if ($recipe->image)
                            <img src="{{ asset('storage/' . $recipe->image) }}" alt="{{ $recipe->name }}"
                                class="post-image">
                        @else
                            <img src="https://via.placeholder.com/600x500" alt="{{ $recipe->name }}" class="post-image">
                        @endif
                    </a>

                    <div class="post-footer">
                        <div class="post-stats">
                            <div class="stat">
                                <span class="icon-stat like-btn" data-recipe-id="{{ $recipe->id }}"
                                    data-liked="{{ auth()->check() && $recipe->likes->contains('user_id', auth()->id()) ? 'true' : 'false' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path
                                            d="M20.8 4.6c-1.7-1.7-4.5-1.7-6.2 0L12 7.2 9.4 4.6c-1.7-1.7-4.5-1.7-6.2 0s-1.7 4.5 0 6.2L12 19.6l8.8-8.8c1.7-1.7 1.7-4.5 0-6.2z" />
                                    </svg>
                                </span>
                                <span class="count">{{ $recipe->likes->count() }}</span>
                            </div>

                            <div class="stat comment-toggle" data-recipe-id="{{ $recipe->id }}">
                                <span class="icon-stat">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z" />
                                    </svg>
                                </span>
                                <span class="count comments-count-{{ $recipe->id }}">{{ $recipe->comments_count }}</span>
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
                                <a href="{{ route('recetas.show', ['receta' => $recipe->id]) }}" class="recipe-title-link">
                                    {{ $recipe->name }}
                                </a>
                            </p>

                            <div class="recipe-description-row">
                                @if ($recipeUser)
                                    <a href="{{ route('profile', ['id' => $recipeUser->id]) }}"
                                        class="recipe-username username-link">
                                        {{ $recipeUser->username }}
                                    </a>
                                @else
                                    <span class="recipe-username username-link">usuario_desconocido</span>
                                @endif

                                <span class="recipe-description-text">
                                    {{ $recipe->description }}
                                </span>
                            </div>

                            <div class="comments-box comments-box-{{ $recipe->id }} is-hidden">
                                @include('comments.list', ['recipe' => $recipe])
                                @include('comments.form', ['recipe' => $recipe])
                            </div>

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
                <p class="event-empty-recipes">No hay recetas asociadas todavia.</p>
            @endforelse
        </main>
    </div>
@endsection
