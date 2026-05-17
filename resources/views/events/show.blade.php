@extends('layout.layout')

@section('title', $event->display_name)

@section('content')
    <h1>{{ $event->display_name }}</h1>
    <p>{{ $event->description }}</p>

    @if($event->images)
        <div>
            @foreach($event->images as $img)
                <img src="{{ asset('storage/' . $img) }}" alt="" style="height:150px;margin:6px">
            @endforeach
        </div>
    @endif

    <h3>Recetas relacionadas</h3>
    @if($event->recipes && $event->recipes->count())
        <ul>
            @foreach($event->recipes as $receta)
                <li><a href="{{ route('recetas.show', ['receta' => $receta->id]) }}">{{ $receta->name ?? $receta->title ?? 'Receta' }}</a></li>
            @endforeach
        </ul>
    @else
        <p>No hay recetas asociadas todavía.</p>
    @endif

    <a href="/">Volver</a>
@endsection
