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

            @if($user->isBanned())
                <p class="ban-status profile-ban-status">
                    Usuario baneado por tiempo indefinido
                </p>
            @endif

            <div class="profile-stats">
                <div class="profile-stat">
                    <strong>{{ $totalRecipes }}</strong>
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
                    @if(auth()->user()->rol === 'admin')
                        <div class="profile-admin-actions">
                            @if($user->isBanned())
                                <form method="POST" action="{{ route('users.unban', $user) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary">Desbanear usuario</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('users.ban', $user) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Banear usuario</button>
                                </form>
                            @endif
                        </div>
                    @endif

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

                    <div class="profile-actions">
                        @if($isBlocked)
                            <form method="POST" action="{{ route('users.unblock', $user) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary">Desbloquear usuario</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('users.block', $user) }}">
                                @csrf
                                <button type="submit" class="btn btn-danger">Bloquear usuario</button>
                            </form>
                        @endif
                    </div>
                @endif
            @endauth
        </div>

    </div>

    <h2 class="profile-section-title">Recetas publicadas</h2>

    <div class="recipes-list" data-infinite-scroll-container>

        @if($recipes->count())
            @include('users.partials.recipe-cards', ['recipes' => $recipes])
        @else
            <p>Este usuario todavía no tiene recetas públicas.</p>
        @endif

        <div
            data-infinite-scroll-trigger
            data-next-page-url="{{ $recipes->nextPageUrl() }}"
            aria-hidden="true"
        ></div>

    </div>

</div>
@endsection
