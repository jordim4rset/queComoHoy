@extends('layout.layout')

@section('title', 'Editar Evento')

@section('content')
    <h1>Editar Evento</h1>

    <form action="{{ route('events.update', ['event' => $eventos->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Nombre:</label>
        <input type="text" name="name" value="{{ $eventos->name }}" required><br>

        <label>Descripción:</label>
        <textarea name="description" required>{{ $eventos->description }}</textarea><br>

        <label>Fecha de inicio:</label>
        <input type="date" name="start_date" value="{{ $eventos->start_date }}" required><br>

        <label>Fecha de fin:</label>
        <input type="date" name="end_date" value="{{ $eventos->end_date }}" required><br>

        <label>Visibilidad:</label>
        <input type="checkbox" name="visibility" {{ $eventos->visibility ? 'checked' : '' }}><br>

         <label>Foto:</label>
        <input type="file" name="photo"><br><br>

        <button type="submit">Actualizar</button>
    </form>

    <a href="{{ route('events.show', ['event' => $eventos->id]) }}">Cancelar</a>
@endsection
