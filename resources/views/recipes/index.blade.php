@extends('layout.layout')

@section('title', 'Lista de Recetas')

@section('content')
    <div class="recipes-header">
        <h1>Recetas</h1>
        <a href="{{ route('recetas.create') }}" class="btn btn-primary">Crear receta</a>
    </div>

    @forelse ($recetas as $receta)
        <div>
            <h3>{{ $receta->name }}</h3>
            <p>Visibilidad: {{ $receta->visibility ? 'Pública' : 'Privada' }}</p>

            @if ($receta->photo)
                <img src="{{ asset('/storage/' . $receta->photo) }}" alt="Foto de {{ $receta->name }}">
            @endif

            <div>
                <a href="{{ route('recetas.show', ['receta' => $receta->id]) }}">Ver</a>
                @auth
                    @if(Auth::id() === $receta->user_id)
                        <a href="{{ route('recetas.edit', ['receta' => $receta->id]) }}">Editar</a>

                        <form action="{{ route('recetas.destroy', ['receta' => $receta->id]) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    @endif
                @endauth
            </div>
        @empty
            <p>No hay recetas disponibles.</p>
        @endforelse
    </div>
@endsection
