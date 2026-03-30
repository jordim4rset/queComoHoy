@extends('layout.layout')

@section('title', 'Lista de Eventos')

@section('content')
    <h1>Eventos</h1>

    @forelse ($eventos as $evento)
        <div>
            <h3>{{ $evento->title }}</h3>
            <p>Visibilidad: {{ $evento->visibility ? 'Pública' : 'Privada' }}</p>

            @if ($evento->photo)
                <img src="{{ asset('storage/' . $evento->photo) }}" alt="Foto de {{ $evento->title }}">
            @endif

            <div>

                <a href="{{ route('events.show', ['event' => $evento->id]) }}">Ver</a>
                <a href="{{ route('events.edit', ['event' => $evento->id]) }}">Editar</a>

                <form action="{{ route('events.destroy', ['event' => $evento->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar</button>
                </form>
            </div>
        </div>
    @empty
        <p>No hay eventos disponibles.</p>
    @endforelse
@endsection
