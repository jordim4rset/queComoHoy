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

        <div class="ingredients-grid" data-infinite-scroll-container>
            @include('ingredients.partials.ingredient-cards', ['ingredients' => $ingredients])

            <div
                data-infinite-scroll-trigger
                data-next-page-url="{{ $ingredients->nextPageUrl() }}"
                aria-hidden="true"
            ></div>
        </div>
    </section>
@endsection
