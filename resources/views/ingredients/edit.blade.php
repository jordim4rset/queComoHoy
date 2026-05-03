@extends('layout.layout')

@section('title', 'Editar Ingrediente')

@section('content')
    <h1>EDITAR INGREDIENTE</h1>

    <form action="{{ route('ingredientes.update', ['ingrediente' => $ingrediente->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <label>Nombre:</label>
    <input type="text" name="name" required value="{{ $ingrediente->name }}">
    <br>
    <label>Categoria:</label>
    <select name="category" id="category">
        @foreach(\App\Models\Ingredient::CATEGORIES as $category)
            <option value="{{ $category }}" {{ $ingrediente->category === $category ? 'selected' : '' }}>{{ $category }}</option>
        @endforeach
    </select>
    <br>
    <label>Icono:</label>
    <input type="file" name="icon">
    <br>
    <button type="submit">Guardar</button>
</form>

@endsection
