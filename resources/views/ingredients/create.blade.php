@extends('layout.layout')

@section('title', 'Crear Ingrediente')

@section('content')
    <section class="ingredients-page ingredients-form-page">
        <div class="ingredients-page-header">
            <div>
                <h1>Crear ingrediente</h1>
                <p>Añade ingredientes limpios y categorizados para usarlos en tus recetas.</p>
            </div>
        </div>

        <form class="form-create-ingr ingredient-panel" action="{{ route('ingredientes.store') }}" method="POST">
            @csrf
            @if ($errors->any())
                <div class="form-errors">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <label>Nombre:</label>
            <input type="text" name="name" required value="{{ old('name') }}">

            <label>Categoria:</label>
            <select name="category" id="category">
                @foreach(\App\Models\Ingredient::CATEGORIES as $category)
                    <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                @endforeach
            </select>

            <button type="submit">Guardar</button>
        </form>
    </section>
@endsection
