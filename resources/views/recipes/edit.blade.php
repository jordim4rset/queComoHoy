@extends('layout.layout')

@section('title', 'Editar Receta')

@section('content')
<h1>Editar Receta</h1>

<form action="{{ route('recetas.update', ['receta' => $receta->id]) }}" method="POST" enctype="multipart/form-data">
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

    <div class="recipe-upload-grid">
        <div class="recipe-upload-field">
            <label>Subir portada:</label>
            <input type="file" name="image" accept="image/*">

            @if($receta->image)
                <small>Portada actual cargada.</small>
            @endif
        </div>

        <div class="recipe-upload-field">
            <label>Subir video:</label>
            <input type="file" name="video" accept="video/mp4,video/quicktime,video/x-msvideo,video/webm">

            @if($receta->video)
                <small>Vídeo actual cargado.</small>
            @endif
        </div>
    </div>

    @include('recipes.partials.ingredients-form')

    <button type="submit">Actualizar</button>
</form>

<a href="{{ route('recetas.show', ['receta' => $receta->id]) }}">Cancelar</a>
@endsection
