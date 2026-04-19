@extends('layout.layout')

@section('title', 'Index Ingredients')

@section('content')

    <a href="{{ route('ingredientes.create') }}">Crear Ingrediente</a>

    <div class="flex-container">
    @foreach($ingredients as $ingr)
        <div class="flex-item">
            <div class="card-content">
                <img src="{{ $ingr->icon }}" alt="Icono {{ $ingr->name }}">
                <span>{{ $ingr->icon }}</span>
                <h3>{{ $ingr->name }}</h3>
                <p>{{ $ingr->category }}</p>
                <a href="{{ route('ingredientes.edit', $ingr) }}">Editar</a>
            </div>
        </div>
    @endforeach
    </div>

@endsection
