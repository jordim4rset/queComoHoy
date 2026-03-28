@extends('layout.layout')

@section('title', $evento->title)

@section('content')
    <h1>{{ $evento->title }}</h1>
    <p>{{ $evento->description }}</p>
    <p>Fecha de inicio: {{ $evento->start_date }}</p>
    <p>Fecha de fin: {{ $evento->end_date }}</p>
    <p>Etiquetas: {{ $evento->tags }}</p>
    <p>Visibilidad: {{ $evento->visibility ? 'Pública' : 'Privada' }}</p>

    @if ($evento->photo)
        <img src="{{ asset('storage/' . $evento->photo) }}" alt="Foto de {{ $evento->title }}" width="300">
    @endif

    <br>
    <a href="{{ route('events.index') }}">Volver a la lista</a>
    <a href="{{ route('events.edit', ['event' => $evento->id]) }}">Editar evento</a>
@endsection
