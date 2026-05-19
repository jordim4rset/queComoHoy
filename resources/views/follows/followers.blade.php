@extends('layout.layout')

@section('title', 'Seguidores de ' . $user->username)

@section('content')
    <div class="follow-page">
        <div class="follow-header">
            <a href="{{ route('profile', ['id' => $user->id]) }}" class="btn btn-secondary">Volver al perfil</a>
            <div>
                <h1>Seguidores</h1>
                <p>{{ $user->username }}</p>
            </div>
        </div>

        <div class="follow-list">
            @forelse ($followers as $follower)
                <div class="follow-user">
                    <a href="{{ route('profile', ['id' => $follower->id]) }}" class="follow-user-main">
                        <img src="{{ $follower->profilePhotoUrl() }}" alt="{{ $follower->username }}">

                        <span>
                            <strong>{{ $follower->name }}</strong>
                            <small>{{ '@' . $follower->username }}</small>
                        </span>
                    </a>

                    @auth
                        @if(auth()->id() !== $follower->id)
                            @if(auth()->user()->following->contains($follower->id))
                                <form method="POST" action="{{ url('/unfollow/' . $follower->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary btn-sm">Dejar de seguir</button>
                                </form>
                            @else
                                <form method="POST" action="{{ url('/follow/' . $follower->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm">Seguir</button>
                                </form>
                            @endif
                        @endif
                    @endauth
                </div>
            @empty
                <p class="follow-empty">Este usuario todavía no tiene seguidores.</p>
            @endforelse
        </div>
    </div>
@endsection
