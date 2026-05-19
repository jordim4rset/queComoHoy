@extends('layout.layout')

@section('title', 'Ver Ingredientes')

@section('content')
    <section class="ingredients-page ingredients-show-page">
        <div class="ingredient-detail-card">
            <h1>{{ $ingrediente->name }}</h1>
            <p>{{ $ingrediente->category }}</p>

            <div class="ingredient-actions">
                <a href="{{ route('ingredientes.edit', $ingrediente) }}" class="btn">Editar</a>
                <a href="{{ route('ingredientes.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </section>
@endsection
