@extends('layout.layout')

@section('title', 'Editar Receta')

@section('content')
<h1>Editar Receta</h1>

<form action="{{ route('recetas.update', ['recipe' => $receta->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label>Nombre:</label>
    <input type="text" name="name" value="{{ $receta->name }}" required><br>

    <label>Descripción:</label>
    <textarea name="description" required>{{ $receta->description }}</textarea><br>

    <label>Tiempo (minutos):</label>
    <input type="number" name="time" value="{{ $receta->time }}"><br>

    <label>Etiquetas:</label>
    <input type="text" name="tags" value="{{ $receta->tags }}"><br>

    <label>Visibilidad:</label>
    <input type="checkbox" name="visibility" {{ $receta->visibility ? 'checked' : '' }}><br>

    <label>Foto:</label>
    <input type="file" name="image"><br><br>

    <button type="submit">Actualizar</button>
</form>

<a href="{{ route('recetas.show', ['recipe' => $receta->id]) }}">Cancelar</a>
@endsection
