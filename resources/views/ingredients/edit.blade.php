@extends('layout.layout')

@section('title', 'Edit Ingredients')

@section('content')
    <h1>EDITAR INGREDIENTE</h1>

    <form action="{{ route('ingredientes.update', $ingr) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <label>Nombre:</label>
        <input type="text" name="name" required value="{{ $ingr->name }}">
        <br>
        <label>Categoria:</label>
        <select name="category" id="category">
            @foreach(\App\Models\Ingredient::CATEGORIES as $category)
                <option value="{{ $category }}" {{ $ingr->category === $category ? 'selected' : '' }}>{{ $category }}</option>
            @endforeach
        </select>
        <br>
        <label>Icono:</label>
        <input type="file" name="icon">
        <br>
        <button type="submit">Guardar</button>
    </form>
@endsection
