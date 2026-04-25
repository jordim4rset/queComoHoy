@extends('layout.layout')

@section('content')
<h1>Seguidores de {{ $user->name }}</h1>

@foreach ($followers as $follower)
    <div>
        <a href="/profile/{{ $follower->id }}">
            {{ $follower->name }}
        </a>

        @auth
            @if(auth()->user()->following->contains($follower->id))
                <form method="POST" action="/unfollow/{{ $follower->id }}">
                    @csrf
                    @method('DELETE')
                    <button>Dejar de seguir</button>
                </form>
            @else
                <form method="POST" action="/follow/{{ $follower->id }}">
                    @csrf
                    <button>Seguir</button>
                </form>
            @endif
        @endauth
    </div>
@endforeach
@endsection