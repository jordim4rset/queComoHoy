@extends('layout.layout')

@section('title', 'Buscar recetas')

@section('content')
    <section class="recipe-search-page">
        <div class="recipe-search-header">
            <div>
                <h1>Buscar recetas</h1>
                <p>Encuentra recetas por nombre, usuario, ingrediente, etiquetas o tiempo.</p>
            </div>

        </div>

        <form method="GET" action="{{ route('recetas.search') }}" class="recipe-search-filters">
            <div class="filter-field filter-field-wide">
                <label for="q">Receta</label>
                <input
                    type="search"
                    id="q"
                    name="q"
                    value="{{ $filters['q'] ?? '' }}"
                    placeholder="Tortilla, arroz, pasta..."
                >
            </div>

            <div class="filter-field">
                <label for="user">Usuario</label>
                <input
                    type="search"
                    id="user"
                    name="user"
                    value="{{ $filters['user'] ?? '' }}"
                    placeholder="Nombre o usuario"
                >
            </div>

            <div class="filter-field">
                <label for="ingredient">Ingrediente</label>
                <input
                    type="search"
                    id="ingredient"
                    name="ingredient"
                    value="{{ $filters['ingredient'] ?? '' }}"
                    list="recipe-search-ingredients"
                    placeholder="Tomate, pollo..."
                >
                <datalist id="recipe-search-ingredients">
                    @foreach($ingredients as $ingredient)
                        <option value="{{ $ingredient->name }}"></option>
                    @endforeach
                </datalist>
            </div>

            <div class="filter-field">
                <label for="tag">Etiqueta</label>
                <input
                    type="search"
                    id="tag"
                    name="tag"
                    value="{{ $filters['tag'] ?? '' }}"
                    placeholder="rapido, cena..."
                >
            </div>

            <div class="filter-field">
                <label for="max_time">Tiempo máx.</label>
                <input
                    type="number"
                    id="max_time"
                    name="max_time"
                    min="0"
                    value="{{ $filters['max_time'] ?? '' }}"
                    placeholder="Minutos"
                >
            </div>

            <div class="filter-field">
                <label for="media">Multimedia</label>
                <select id="media" name="media">
                    <option value="any" {{ ($filters['media'] ?? 'any') === 'any' ? 'selected' : '' }}>Todas</option>
                    <option value="video" {{ ($filters['media'] ?? 'any') === 'video' ? 'selected' : '' }}>Con video</option>
                </select>
            </div>

            <div class="recipe-search-actions">
                <button type="submit" class="btn">Buscar</button>
                <a href="{{ route('recetas.search') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </form>

        <div class="recipe-search-summary">
            {{ $recipes->count() }} {{ $recipes->count() === 1 ? 'receta encontrada' : 'recetas encontradas' }}
        </div>

        <div class="recipes-list">
            @forelse($recipes as $recipe)
                <article class="recipe-card">
                    @include('recipes.partials.media-slider', ['recipe' => $recipe, 'class' => 'recipe-media-card'])

                    <h3>
                        <a href="{{ route('recetas.show', ['receta' => $recipe->id]) }}" class="recipe-title-link">
                            {{ $recipe->name }}
                        </a>
                    </h3>

                    <p>{{ $recipe->description }}</p>

                    <p>
                        <strong>Usuario:</strong>
                        <a href="{{ route('profile', ['id' => $recipe->user_id]) }}" class="recipe-title-link">
                            {{ $recipe->user->username ?? 'usuario_desconocido' }}
                        </a>
                    </p>

                    <p>
                        <strong>Tiempo:</strong> {{ $recipe->time }} min
                    </p>

                    @if($recipe->ingredients->count())
                        <p class="recipe-search-ingredients">
                            <strong>Ingredientes:</strong>
                            {{ $recipe->ingredients->pluck('name')->take(4)->join(', ') }}
                        </p>
                    @endif
                </article>
            @empty
                <p class="recipe-search-empty">No hay recetas que coincidan con esos filtros.</p>
            @endforelse
        </div>
    </section>
@endsection
