@extends('layout.layout')

@section('title', 'Editar Ingrediente')

@section('content')
    <section class="ingredients-page ingredients-form-page">
        <div class="ingredients-page-header">
            <div>
                <h1>Editar ingrediente</h1>
                <p>Actualiza el nombre o la categoria del ingrediente.</p>
            </div>
        </div>

        <form class="ingredient-panel" action="{{ route('ingredientes.update', ['ingrediente' => $ingrediente->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Nombre:</label>
            <input type="text" name="name" required value="{{ $ingrediente->name }}">

            <label>Categoria:</label>
            <select name="category" id="category">
                @foreach(\App\Models\Ingredient::CATEGORIES as $category)
                    <option value="{{ $category }}" {{ $ingrediente->category === $category ? 'selected' : '' }}>{{ $category }}</option>
                @endforeach
            </select>

            <button type="submit">Guardar</button>
        </form>
    </section>
@endsection
