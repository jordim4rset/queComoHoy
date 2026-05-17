@extends('layout.layout')

@section('title', $receta->name . ' - QueComoHoy')

@section('content')
<div class="app-container">

    <main class="feed">

        <div class="post post-show">

            <div class="post-header">
                <div class="user-info">
                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode($receta->user->username ?? 'Usuario') }}"
                        alt="{{ $receta->user->username ?? 'Usuario' }}"
                        class="avatar"
                    >

                    <span class="username">
                        {{ $receta->user->username ?? 'usuario_desconocido' }}
                    </span>
                </div>

                @auth
                    @if(auth()->id() !== $receta->user_id)
                        <button class="follow-btn-small">Seguir</button>
                    @endif
                @endauth
            </div>

            <div class="post-image-wrapper">
                @if($receta->image)
                    <img
                        src="{{ asset('storage/' . $receta->image) }}"
                        alt="{{ $receta->name }}"
                        class="post-image"
                    >
                @else
                    <img
                        src="https://via.placeholder.com/600x500"
                        alt="{{ $receta->name }}"
                        class="post-image"
                    >
                @endif
            </div>

            <div class="post-footer">

                <div class="post-stats">

                    <div class="stat">
                        <span class="icon-stat">
                            <!-- SVG likes -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M20.8 4.6c-1.7-1.7-4.5-1.7-6.2 0L12 7.2 9.4 4.6c-1.7-1.7-4.5-1.7-6.2 0s-1.7 4.5 0 6.2L12 19.6l8.8-8.8c1.7-1.7 1.7-4.5 0-6.2z"/>
                            </svg>
                        </span>
                        <span class="count">0</span>
                    </div>

                    <div class="stat">
                        <span class="icon-stat">
                            <!-- SVG comentarios -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/>
                            </svg>
                        </span>
                        <span class="count">0</span>
                    </div>

                    <div class="stat">
                        <span class="icon-stat">
                            <!-- SVG tiempo -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 10.6V7h-2v6.4l5 3 1-1.7z"/>
                            </svg>
                        </span>
                        <span class="count">{{ $receta->time }} min</span>
                    </div>

                </div>

                <div class="post-description">

                    <p class="recipe-name">
                        {{ $receta->name }}
                    </p>

                    <div class="recipe-description-row">
                        <strong class="recipe-username">
                            {{ $receta->user->username ?? 'usuario_desconocido' }}
                        </strong>

                        <span class="recipe-description-text">
                            {{ $receta->description }}
                        </span>
                    </div>

                    <div class="comments-box"></div>

                    @if($receta->ingredients->count())
                        <div class="recipe-ingredients-box">
                            <h3>Ingredientes</h3>

                            <ul>
                                @foreach($receta->ingredients as $ingredient)
                                    <li>
                                        <strong>{{ $ingredient->name }}</strong>

                                        @if($ingredient->pivot->quantity)
                                            - {{ $ingredient->pivot->quantity }}
                                        @endif

                                        @if($ingredient->pivot->unit)
                                            {{ $ingredient->pivot->unit }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($receta->tags)
                        <p class="recipe-tags">
                            @foreach(explode(',', $receta->tags) as $tag)
                                <span>#{{ trim($tag) }}</span>
                            @endforeach
                        </p>
                    @endif

                </div>

                <div class="show-actions">
                    <a href="{{ url()->previous() ?: route('eventos.index') }}" class="btn btn-secondary btn-sm">
                        Volver
                    </a>

                    @auth
                        @if(auth()->id() === $receta->user_id)
                            <a href="{{ route('recetas.edit', ['receta' => $receta->id]) }}" class="btn btn-sm">
                                Editar receta
                            </a>
                        @endif
                    @endauth
                </div>

            </div>

        </div>

    </main>

</div>
@endsection
