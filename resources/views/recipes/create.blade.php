@extends('layout.layout')

@section('title', 'Crear Receta')

@section('content')

    <h1>Crear Receta</h1>

    <form action="{{ route('recetas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>Nombre:</label>
        <input type="text" name="name" required><br>

        <label>Descripción:</label>
        <textarea name="description" required></textarea><br>

        <label>Tiempo (minutos):</label>
        <input type="number" name="time"><br>

        <label>Etiquetas:</label>
        <input type="text" name="tags"><br>

        <label>Visibilidad:</label>
        <input type="checkbox" name="visibility"><br>

        <div class="recipe-upload-grid">
            <div class="recipe-upload-field">
                <label>Subir portada:</label>
                <input type="file" name="image" accept="image/*" required>
            </div>

            <div class="recipe-upload-field">
                <label>Subir vídeo:</label>
                <input type="file" name="video" accept="video/mp4,video/quicktime,video/x-msvideo,video/webm">
            </div>
        </div>

        @include('recipes.partials.ingredients-form')

        <button type="submit">Guardar</button>
    </form>

    <a href="{{ route('recetas.index') }}">Volver a la lista</a>
@endsection
