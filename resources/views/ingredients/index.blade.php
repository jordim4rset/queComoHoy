@extends('layout.layout')

@section('title', 'Index Ingredients')

@section('content')

    <a href="{{ route('ingredientes.create') }}">Crear Ingrediente</a>

    <div class="flex-container">
        @foreach ($ingredients as $ingr)
            <div class="flex-item">
                <div class="card-content">
                    <img src="{{ asset('storage/' . $ingr->icon) }}" alt="Icono {{ $ingr->name }}"
                        onerror="this.style.display='none'"> 
                    <h3>{{ $ingr->name }}</h3>
                    <p>{{ $ingr->category }}</p>
                    <a href="{{ route('ingredientes.edit', ['ingrediente' => $ingr->id]) }}">Editar</a>
                </div>
            </div>
        @endforeach
    </div>

@endsection
