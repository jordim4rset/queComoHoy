@extends('layout.layout')

@section('title', 'Mis Recetas')

@section('content')
    <div class="recipes-header">
        <h1>Mis recetas</h1>

        @auth
            <a href="{{ route('recetas.create') }}" class="btn btn-primary">
                Crear receta
            </a>
        @endauth
    </div>

    <div class="recipes-list">

        @forelse ($recetas as $receta)
            <div class="recipe-card">

                @if ($receta->image)
                    <img
                        src="{{ asset('/storage/' . $receta->image) }}"
                        alt="Foto de {{ $receta->name }}"
                    >
                @else
                    <img
                        src="https://via.placeholder.com/600x400"
                        alt="Sin imagen"
                    >
                @endif

                <h3>
                    <a
                        href="{{ route('recetas.show', ['receta' => $receta->id]) }}"
                        class="recipe-title-link"
                    >
                        {{ $receta->name }}
                    </a>
                </h3>

                <p>
                    {{ $receta->description }}
                </p>

                <p>
                    <strong>Visibilidad:</strong>
                    {{ $receta->visibility ? 'Pública' : 'Privada' }}
                </p>

                <div class="recipe-card-buttons">

                    <a
                        href="{{ route('recetas.edit', ['receta' => $receta->id]) }}"
                        class="btn btn-edit"
                    >
                        Editar
                    </a>

                    <form
                        action="{{ route('recetas.destroy', ['receta' => $receta->id]) }}"
                        method="POST"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-delete"
                            onclick="return confirm('¿Seguro que quieres eliminar esta receta?')"
                        >
                            Eliminar
                        </button>
                    </form>

                </div>

            </div>
        @empty
            <p>No tienes recetas creadas todavía.</p>
        @endforelse

    </div>
@endsection
