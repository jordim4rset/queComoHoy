@extends('layout.layout')

@section('title', 'Crear Ingrediente')

@section('content')
    <h1>CREAR INGREDIENTE</h1>

    <div class="div-create-ingr">
        <form class="form-create-ingr" action="{{ route('ingredientes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label>Nombre:</label>
            <input type="text" name="name" required>
            <br>
            <label>Categoria:</label>
            <select name="category" id="category">
                @foreach(\App\Models\Ingredient::CATEGORIES as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>
            <br>
            <label>Icono:</label>
            <input type="file" name="icon">
            <br>
            <button type="submit">Guardar</button>
        </form>
    </div>
@endsection

