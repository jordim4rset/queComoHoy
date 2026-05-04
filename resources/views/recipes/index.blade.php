@extends('layout.layout')

@section('title', 'Lista de Recetas')

@section('content')
    <div class="recipes-header">
        <h1>Recetas</h1>
        <a href="{{ route('recipes.create') }}" class="btn btn-primary">Crear receta</a>
    </div>

    @forelse ($recetas as $receta)
        <div>
            <h3>{{ $receta->name }}</h3>
            <p>Visibilidad: {{ $receta->visibility ? 'Pública' : 'Privada' }}</p>

            @if ($receta->image)
                <img src="{{ asset('storage/' . $receta->image) }}" alt="Foto de {{ $receta->name }}">
            @endif

            <div>
                <a href="{{ route('recipes.show', ['recipe' => $receta->id]) }}">Ver</a>

                @auth
                    @if(Auth::id() === $receta->user_id)
                        <a href="{{ route('recipes.edit', ['recipe' => $receta->id]) }}">Editar</a>

                        <form action="{{ route('recipes.destroy', ['recipe' => $receta->id]) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>
    @empty
        <p>No hay recetas disponibles.</p>
    @endforelse
@endsection
