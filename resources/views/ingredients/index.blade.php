@extends('layout.layout')

@section('title', 'Ingredientes')

@section('content')
    <section class="ingredients-page">
        <div class="ingredients-page-header">
            <div>
                <h1>Ingredientes</h1>
                <p>Gestiona los ingredientes disponibles para crear recetas.</p>
            </div>

            <a href="{{ route('ingredientes.create') }}" class="btn">Crear ingrediente</a>
        </div>

        <form class="ingredients-filter" method="GET" action="{{ route('ingredientes.index') }}">
            <label for="category">Filtrar por categoría</label>
            <select name="category" id="category" onchange="this.form.submit()">
                <option value="">Todas las categorías</option>
                @foreach(\App\Models\Ingredient::CATEGORIES as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>
        </form>

        <div class="ingredients-grid">
            @foreach ($ingredients as $ingr)
                <article class="ingredient-card">
                    <div class="ingredient-card-badge">
                        {{ mb_substr($ingr->name, 0, 1) }}
                    </div>

                    <h3>{{ $ingr->name }}</h3>
                    <p>{{ $ingr->category }}</p>

                    <div class="ingredient-actions">
                        <a href="{{ route('ingredientes.edit', ['ingrediente' => $ingr->id]) }}" class="btn btn-sm">Editar</a>
                        <form
                            action="{{ route('ingredientes.destroy', ['ingrediente' => $ingr->id]) }}"
                            method="POST"
                            onsubmit="return confirm('Eliminar ingrediente?')"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
