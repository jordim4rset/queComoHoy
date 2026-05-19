@extends('layout.layout')
@section('title', 'Crear Receta')
@section('content')
    <h1>Crear Receta</h1>
    <form action="{{ route('recetas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>Nombre:</label>
        <input type="text" name="name" value="{{ old('name') }}"><br>
        @error('name')
            <span style="color: red;">{{ $message }}</span><br>
        @enderror

        <label>Descripción:</label>
        <textarea name="description">{{ old('description') }}</textarea><br>
        @error('description')
            <span style="color: red;">{{ $message }}</span><br>
        @enderror

        <label>Tiempo (minutos):</label>
        <input type="number" name="time" value="{{ old('time') }}"><br>
        @error('time')
            <span style="color: red;">{{ $message }}</span><br>
        @enderror

        <label>Etiquetas:</label>
        <input type="text" name="tags" value="{{ old('tags') }}"><br>

        <label>Visibilidad:</label>
        <input type="checkbox" name="visibility"><br>

        <div class="recipe-upload-grid">
            <div class="recipe-upload-field">
                <label>Subir portada:</label>
                <input type="file" name="image" accept="image/*">
                @error('image')
                    <span style="color: red;">{{ $message }}</span><br>
                @enderror
            </div>
            <div class="recipe-upload-field">
                <label>Subir video:</label>
                <input type="file" name="video" accept="video/mp4,video/quicktime,video/x-msvideo,video/webm">
                @error('video')
                    <span style="color: red;">{{ $message }}</span><br>
                @enderror
            </div>
        </div>

        @include('recipes.partials.ingredients-form')
        <button type="submit">Guardar</button>
    </form>
    <a href="{{ route('recetas.index') }}">Volver a la lista</a>
@endsection
