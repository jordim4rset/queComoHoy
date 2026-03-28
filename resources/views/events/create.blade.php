@extends('layout.layout')

@section('title', 'Crear Evento')

@section('content')
    <h1>Crear Evento</h1>

    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>Nombre:</label>
        <input type="text" name="title" required><br>

        <label>Descripción:</label>
        <textarea name="description" required></textarea><br>

        <label>Fecha de inicio:</label>
        <input type="date" name="start_date" required><br>

        <label>Fecha de finalización:</label>
        <input type="date" name="end_date" required><br>


        <label>Visibilidad:</label>
        <input type="checkbox" name="visibility"><br>


        <button type="submit">Guardar</button>
    </form>

    <a href="{{ route('events.index') }}">Volver a la lista</a>
@endsection
