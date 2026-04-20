@extends('layout.layout')

@section('title', 'Lista de Recetas')

@section('content')
    <h1>Recetas</h1>

    <a href="{{ route('recetas.create') }}">
        <button>Crear Receta</button>
    </a>

    @forelse ($recetas as $receta)
        <div>
            <h3>{{ $receta->name }}</h3>
            <p>Visibilidad: {{ $receta->visibility ? 'Pública' : 'Privada' }}</p>

            @if ($receta->photo)
                <img src="{{ asset('storage/' . $receta->photo) }}" alt="Foto de {{ $receta->name }}">
            @endif

            <div>

                <a href="{{ route('recetas.show', ['receta' => $receta->id]) }}">Ver</a>
                <a href="{{ route('recetas.edit', ['receta' => $receta->id]) }}">Editar</a>

                <form action="{{ route('recetas.destroy', ['receta' => $receta->id]) }}" method="POST">
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
