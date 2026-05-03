@extends('layout.layout')

@section('content')
<h1>Usuarios</h1>

@foreach($users as $user)
    <div>
        <a href="/profile/{{ $user->id }}">
            {{ $user->name }}
        </a>
    </div>
@endforeach

@endsection