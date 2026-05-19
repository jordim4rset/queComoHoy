@extends('layout.layout')

@section('title', $user->username . ' - QueComoHoy')

@section('content')
<div class="profile-page">

    <div class="profile-header">

        <img
            src="{{ $user->profilePhotoUrl() }}"
            alt="{{ $user->username }}"
            class="profile-avatar"
        >

        <div class="profile-info">
            <h1>{{ $user->username }}</h1>

            <p class="profile-name">
                {{ $user->name }}
            </p>

            <div class="profile-stats">
                <div class="profile-stat">
                    <strong>{{ $recipes->count() }}</strong>
                    <span>Recetas</span>
                </div>

                <div class="profile-stat">
                    <strong>{{ $user->followers_count }}</strong>
                    <span>
                        <a href="{{ route('user.followers', $user->id) }}">Seguidores</a>
                    </span>
                </div>

                <div class="profile-stat">
                    <strong>{{ $user->following_count }}</strong>
                    <span>
                        <a href="{{ route('user.following', $user->id) }}">Seguidos</a>
                    </span>
                </div>

                <div class="profile-stat">
                    <strong>{{ $totalLikes }}</strong>
                    <span>Likes</span>
                </div>
            </div>

            @auth
                @if(auth()->id() === $user->id)
                    <div class="profile-actions">
                        <a href="{{ route('users.editCurrent') }}" class="btn">Editar usuario</a>
                    </div>
                @else
                    <div class="profile-actions">
                        @if($isFollowing)
                            <form method="POST" action="{{ url('/unfollow/' . $user->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary">Dejar de seguir</button>
                            </form>
                        @else
                            <form method="POST" action="{{ url('/follow/' . $user->id) }}">
                                @csrf
                                <button type="submit" class="btn">Seguir</button>
                            </form>
                        @endif
                    </div>
                @endif
            @endauth
        </div>

    </div>

    <h2 class="profile-section-title">Recetas publicadas</h2>

    <div class="recipes-list">

        @forelse($recipes as $recipe)
            <div class="recipe-card">

                @include('recipes.partials.media-slider', ['recipe' => $recipe, 'class' => 'recipe-media-card'])

                <h3>
                    <a
                        href="{{ route('recetas.show', ['receta' => $recipe->id]) }}"
                        class="recipe-title-link"
                    >
                        {{ $recipe->name }}
                    </a>
                </h3>

                <p>{{ $recipe->description }}</p>

                <p>
                    <strong>Tiempo:</strong>
                    {{ $recipe->time }} min
                </p>

            </div>
        @empty
            <p>Este usuario todavía no tiene recetas públicas.</p>
        @endforelse

    </div>

</div>
@endsection
