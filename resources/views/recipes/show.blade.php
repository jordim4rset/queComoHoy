@extends('layout.layout')

@section('title', $receta->name)

@section('content')
    <h1>{{ $receta->name }}</h1>
    <p>{{ $receta->description }}</p>
    <p>Tiempo: {{ $receta->time }} minutos</p>
    <p>Etiquetas: {{ $receta->tags }}</p>
    <p>Visibilidad: {{ $receta->visibility ? 'Pública' : 'Privada' }}</p>

    @if ($receta->image)
        <img src="{{ asset('storage/' . $receta->image) }}" alt="Foto de {{ $receta->name }}" width="300">
    @endif

    <br>
    <a href="{{ route('recipes.index') }}">Volver a la lista</a>
    <a href="{{ route('recipes.edit', ['recipe' => $receta->id]) }}">Editar receta</a>
@endsection
