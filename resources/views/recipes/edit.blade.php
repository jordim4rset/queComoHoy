@extends('layout.layout')
@section('title', 'Editar Receta')
@section('content')
<h1>Editar Receta</h1>
<form action="{{ route('recetas.update', ['receta' => $receta->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <label>Nombre:</label>
    <input type="text" name="name" value="{{ old('name', $receta->name) }}"><br>
    @error('name')
        <span style="color: red;">{{ $message }}</span><br>
    @enderror

    <label>Descripción:</label>
    <textarea name="description">{{ old('description', $receta->description) }}</textarea><br>
    @error('description')
        <span style="color: red;">{{ $message }}</span><br>
    @enderror

    <label>Tiempo (minutos):</label>
    <input type="number" name="time" value="{{ old('time', $receta->time) }}"><br>
    @error('time')
        <span style="color: red;">{{ $message }}</span><br>
    @enderror

    <label>Etiquetas:</label>
    <input type="text" name="tags" value="{{ old('tags', $receta->tags) }}"><br>

    <label>Visibilidad:</label>
    <input type="checkbox" name="visibility" {{ $receta->visibility ? 'checked' : '' }}><br>

    <div class="recipe-upload-grid">
        <div class="recipe-upload-field">
            <label>Subir portada:</label>
            <input type="file" name="image" accept="image/*">
            @if($receta->image)
                <small>Portada actual cargada.</small>
            @endif
            @error('image')
                <span style="color: red;">{{ $message }}</span><br>
            @enderror
        </div>
        <div class="recipe-upload-field">
            <label>Subir video:</label>
            <input type="file" name="video" accept="video/mp4,video/quicktime,video/x-msvideo,video/webm">
            @if($receta->video)
                <small>Vídeo actual cargado.</small>
            @endif
            @error('video')
                <span style="color: red;">{{ $message }}</span><br>
            @enderror
        </div>
    </div>

    @include('recipes.partials.ingredients-form')
    <button type="submit">Actualizar</button>
</form>
<a href="{{ route('recetas.show', ['receta' => $receta->id]) }}">Cancelar</a>
@endsection
