@extends('layout.layout')

@section('title')
    Inicio - QueComoHoy
@endsection

@section('content')
    <div class="app-container">

        <main class="feed" data-infinite-scroll-container>

            @if($recipes->count())
                @include('recipes.partials.feed-posts', ['recipes' => $recipes, 'followingUserIds' => $followingUserIds])
            @else
                <p>{{ $emptyMessage ?? 'No hay recetas todavia.' }}</p>
            @endif

            <div
                data-infinite-scroll-trigger
                data-next-page-url="{{ $recipes->nextPageUrl() }}"
                aria-hidden="true"
            ></div>

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
