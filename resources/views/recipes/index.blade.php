@extends('layout.layout')

@section('title', 'Lista de Recetas')

@section('content')
    <div class="recipes-header">
        <h1>Recetas</h1>
        <a href="{{ route('recipes.create') }}" class="btn btn-primary">Crear receta</a>
    </div>



    {{-- Filtros --}}
    <form method="GET" action="{{ route('recipes.index') }}">
        <input type="text" name="name" placeholder="Buscar por nombre" value="{{ request('name') }}">

        <select name="visibility">
            <option value="">Todas</option>
            <option value="1" {{ request('visibility') == '1' ? 'selected' : '' }}>Pública</option>
            <option value="0" {{ request('visibility') == '0' ? 'selected' : '' }}>Privada</option>
        </select>

        <input type="number" name="time" placeholder="Tiempo máximo (min)" value="{{ request('time') }}">

        <button type="submit">Filtrar</button>
        <a href="{{ route('recipes.index') }}" class="btn">Limpiar</a>
    </form>





    <div class="recipes-list">
        @forelse ($recetas as $receta)
            <div class="recipe-card">
                @if ($receta->image)
                    <img src="{{ asset('storage/' . $receta->image) }}" alt="Foto de {{ $receta->name }}">
                @endif
                <h3>{{ $receta->name }}</h3>
                <p>Visibilidad: {{ $receta->visibility ? 'Pública' : 'Privada' }}</p>
                <div class="recipe-card-buttons">
                    <a href="{{ route('recipes.show', ['recipe' => $receta->id]) }}" class="btn btn-sm">Ver</a>
                    @auth
                        @if (Auth::id() === $receta->user_id)
                            <a href="{{ route('recipes.edit', ['recipe' => $receta->id]) }}" class="btn btn-sm">Editar</a>
                            <form action="{{ route('recipes.destroy', ['recipe' => $receta->id]) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        @empty
            <p>No hay recetas disponibles.</p>
        @endforelse
    </div>
@endsection
