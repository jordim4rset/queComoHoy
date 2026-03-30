@extends('layout.laoyut');

@section('title', 'Edit Ingredients')

@section('content')
    <h1>CREAR INGREDIENTE</h1>

    <form action="{{ route('ingredientes.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>Nombre:</label>
        <input type="text" name="name" required value="{{ $ingredients->name }}">
        <br>
        <label>Categoria:</label>
        <select name="category" id="category">
            @foreach(\App\Models\Ingredient::CATEGORIES as $category)
                <option value="{{ $category }}">{{ $category }}</option>
            @endforeach
        </select>
        <br>
        <label>Icono:</label>
        <input type="file" name="icon" value="{{ $ingredients->icon }}">
        <br>
        <button type="submit">Guardar</button>
    </form>
@endsection

