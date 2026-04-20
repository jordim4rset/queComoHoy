@extends('layout.layout')

@section('title', 'Index Ingredients')

@section('content')


    <a href="{{ route('ingredientes.create') }}">
        <button>Crear Ingrediente</button>
    </a>

    <div class="flex-container">
        @foreach ($ingredients as $ingr)
            <div class="flex-item">
                <div class="card-content">
                    <img src="{{ asset('storage/' . $ingr->icon) }}" alt="Icono {{ $ingr->name }}"
                        style="width: 100px; height: 100px;">
                    <h3>{{ $ingr->name }}</h3>
                    <p>{{ $ingr->category }}</p>


                    <a href="{{ route('ingredientes.edit', $ingr->id) }}">
                        <button>Editar</button>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

@endsection
