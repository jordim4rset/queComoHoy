@extends('layout.layout')
@section('title', 'Ver Ingredientes')
@section('content')
    <div class="card-content">
        <img src="{{ asset('storage/' . $ingrediente->icon) }}" alt="Icono {{ $ingrediente->name }}"
            onerror="this.style.display='none'">
        <h1>{{ $ingrediente->name }}</h1>
        <p>{{ $ingrediente->category }}</p>
        <a href="{{ route('ingredientes.edit', $ingrediente) }}" class="btn">Editar</a>
        <a href="{{ route('ingredientes.index') }}" class="btn">Volver</a>
    </div>
@endsection
