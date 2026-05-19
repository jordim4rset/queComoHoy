@extends('layout.layout')

@section('title', 'Tienda ChefPoints')

@section('content')
    <div class="shop-page">
        <h1>Tienda ChefPoints</h1>
        <p>Canjea tus ChefPoints por recompensas dentro de la aplicación.</p>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @auth
            <div class="demo-buttons">
                <form method="POST" action="{{ route('demo.chefpoints.add') }}">
                    @csrf
                    <button type="submit" class="btn">+1000 ChefPoints</button>
                </form>
                <form method="POST" action="{{ route('demo.chefpoints.remove') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary">-1000 ChefPoints</button>
                </form>
            </div>
        @endauth

        <div class="shop-grid">
            <div class="shop-card">
                <h2>Publicar receta destacada</h2>
                <p>Haz que tu receta aparezca en la sección destacada por 1000 ChefPoints.</p>
                <span class="shop-price">1000 ChefPoints</span>
            </div>
            <div class="shop-card">
                <h2>Pack de stickers</h2>
                <p>Recibe un conjunto exclusivo de stickers de cocina.</p>
                <span class="shop-price">750 ChefPoints</span>
            </div>
            <div class="shop-card">
                <h2>Avatar premium</h2>
                <p>Personaliza tu perfil con un avatar premium.</p>
                <span class="shop-price">500 ChefPoints</span>
            </div>
        </div>
    </div>
@endsection
