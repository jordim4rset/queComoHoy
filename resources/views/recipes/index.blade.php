@extends('layout.layout')

@section('title', 'Lista de Recetas')

@section('content')
    <h1>Recetas</h1>

    @forelse ($recetas as $receta)
        <div>
            <h3>{{ $receta->name }}</h3>
            <p>Visibilidad: {{ $receta->visibility ? 'Pública' : 'Privada' }}</p>

            @if ($receta->image)
                <img src="{{ asset('storage/' . $receta->image) }}" alt="Foto de {{ $receta->name }}">
            @endif

            <div>

                <a href="{{ route('recipes.show', ['recipe' => $receta->id]) }}">Ver</a>
                <a href="{{ route('recipes.edit', ['recipe' => $receta->id]) }}">Editar</a>

                <form action="{{ route('recipes.destroy', ['recipe' => $receta->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar</button>
                </form>
            </div>
        </div>
    @empty
        <p>No hay recetas disponibles.</p>
    @endforelse
@endsection
