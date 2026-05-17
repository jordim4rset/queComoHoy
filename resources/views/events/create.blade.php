@extends('layout.layout')

@section('title', 'Crear Evento')

@section('content')
    <h1>Crear Evento</h1>

    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>Nombre:</label>
        <input type="text" name="name" value="{{ old('name') }}"><br>

        <label>Título (opcional):</label>
        <input type="text" name="title" value="{{ old('title') }}"><br>

        <label>Descripción:</label>
        <textarea name="description">{{ old('description') }}</textarea><br>

        <label>Imágenes (puedes subir varias):</label>
        <input type="file" name="images[]" multiple><br>

        <label>Activo:</label>
        <input type="checkbox" name="active"><br>

        <button type="submit">Guardar</button>
    </form>

    <a href="{{ route('events.index') }}">Volver a la lista</a>
@endsection
