@extends('layout.layout')

@section('content')
<h1>{{ $user->name }} sigue a:</h1>

@foreach ($following as $followed)
    <div>
        <a href="/profile/{{ $followed->id }}">
            {{ $followed->name }}
        </a>

        @auth
            <form method="POST" action="/unfollow/{{ $followed->id }}">
                @csrf
                @method('DELETE')
                <button>Dejar de seguir</button>
            </form>
        @endauth
    </div>
@endforeach
@endsection