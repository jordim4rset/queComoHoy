@extends('layout')

@section('title')

@section('content')

    <h1>{{ $receta->nombre }}</h1>

    <p>{{ $receta->descripcion }}</p>

    <p>Tiempo de elaboración: {{ $receta->tiempo_elaboracion }} min</p>

    @isset($receta->etiquetas)
        <p>Etiquetas: {{ $receta->etiquetas }}</p>
    @endisset

    <p>Visibilidad: {{ $receta->visibilidad ? 'Pública' : 'Privada' }}</p>

    <a href="{{ route('recipes.edit', $receta->id_receta) }}">Editar</a>
    <a href="{{ route('recipes.index') }}">Volver al listado</a>

@endsection
