@extends('layout.layout')

@section('title', 'Index Ingredients')

@section('content')

    <div class="flex-container">
    @foreach($ingredients as $ingr)
        <div class="flex-item">
            <div class="card-content">
                <img src="{{ $ingr->icon }}" alt="Icono {{ $ingr->name }}">
                <span>{{ $ingr->icon }}</span>
                <h3>{{ $ingr->name }}</h3>
                <p>{{ $ingr->category }}</p>
            </div>
        </div>
    @endforeach
</div>

@endsection

