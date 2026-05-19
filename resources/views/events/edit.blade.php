@extends('layout.layout')
@section('title', 'Editar Evento')
@section('content')
    <h1>Editar Evento</h1>
    <form action="{{ route('events.update', ['event' => $event->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <label>Nombre:</label>
        <input type="text" name="name" value="{{ old('name', $event->name) }}"><br>

        <label>Título:</label>
        <input type="text" name="title" value="{{ old('title', $event->title) }}"><br>
        @error('title')
            <span style="color: red;">{{ $message }}</span><br>
        @enderror

        <label>Descripción:</label>
        <textarea name="description">{{ old('description', $event->description) }}</textarea><br>
        @error('description')
            <span style="color: red;">{{ $message }}</span><br>
        @enderror

        <label>Imágenes actuales:</label>
        <div>
            @if($event->images)
                @foreach($event->images as $img)
                    <img src="{{ asset('storage/' . $img) }}" alt="" style="height:80px;margin:4px">
                @endforeach
            @endif
        </div>

        <label>Subir más imágenes:</label>
        <input type="file" name="images[]" multiple><br>

        <label>Activo:</label>
        <input type="checkbox" name="active" {{ $event->active ? 'checked' : '' }}><br>

        <button type="submit">Actualizar</button>
    </form>
    <a href="{{ route('events.index') }}">Volver</a>
@endsection
