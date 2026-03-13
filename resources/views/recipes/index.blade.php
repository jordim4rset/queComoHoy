@extends('layout')

@section('title', 'Listado de Recetas')

@section('content')

    <h1>Listado de Recetas</h1>

    <a href="{{ route('recipes.create') }}">Nueva Receta</a>

    @forelse ($recetas as $receta)
        <div>
            <h2>{{ $receta->nombre }}</h2>
            <p>{{ $receta->descripcion }}</p>
            <p>Tiempo de elaboración: {{ $receta->tiempo_elaboracion }} min</p>

            @isset($receta->etiquetas)
                <p>Etiquetas: {{ $receta->etiquetas }}</p>
            @endisset

            <p>Visibilidad: {{ $receta->visibilidad ? 'Pública' : 'Privada' }}</p>

            <a href="{{ route('recipes.show', $receta->id_receta) }}">Ver</a>
            <a href="{{ route('recipes.edit', $receta->id_receta) }}">Editar</a>

            <form action="{{ route('recipes.destroy', $receta->id_receta) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Eliminar</button>
            </form>
        </div>
    @empty
        <p>No hay recetas disponibles.</p>
    @endforelse

@endsection
