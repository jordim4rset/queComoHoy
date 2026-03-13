@extends('layout.layout')

@section('title')
Inicio - QueComoHoy
@endsection
@section('content')
    <div class="container">
        <h1>Bienvenido a QueComoHoy</h1>
        <p>Descubre recetas deliciosas y saludables para cada día.</p>
        <a href="{{ route('recipes.index') }}" class="btn btn-primary">Ver Recetas</a>
    </div>
@endsection

