@extends('layout.layout')

@section('title')
Inicio - QueComoHoy
@endsection

@section('content')

<!-- Header con buscador de recetas -->
<header class="app-header">
    <!-- Contenedor del buscador -->
    <div class="search-container">
        <!-- Input para buscar recetas -->
        <input type="text" class="search-input" placeholder="Buscar recetas...">
    </div>
</header>

<div class="app-container">

    <aside class="sidebar-left">
        @include('partials.nav')
    </aside>
    <main class="feed">
        <div class="post">
            <div class="post-header">
                <div class="user-info">
                    <img src="https://via.placeholder.com/40" alt="Usuario" class="avatar">
                    <span class="username">usuario</span>
                </div>
                <button class="follow-btn">Seguir</button>
            </div>
            <img src="https://via.placeholder.com/500x400" alt="Video" class="post-image">
            <div class="post-footer">
                <div class="post-stats">
                    <div class="stat">
                        <span class="icon-stat">❤️</span>
                        {{-- esto debe ser una variable de likes --}}
                        <span class="count">12k</span>
                    </div>
                    <div class="stat">
                        <span class="icon-stat">💬</span>
                        {{-- esto debe ser una variable comentarios--}}
                        <span class="count">2k</span>
                    </div>
                    <div class="stat">
                        <span class="icon-stat">✅</span>
                        {{-- esto debe ser una variable compartidos--}}
                        <span class="count">211</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="post">
            <div class="post-header">
                <div class="user-info">
                    <img src="https://via.placeholder.com/40" alt="Usuario" class="avatar">
                    <span class="username">usuario</span>
                </div>
                <button class="follow-btn">Seguir</button>
            </div>
            <img src="https://via.placeholder.com/500x400" alt="Comida" class="post-image">
            <div class="post-footer">
                <div class="post-stats">
                    <div class="stat">
                        <span class="icon-stat">❤️</span>
                        {{-- esto debe ser una variable de likes --}}
                        <span class="count">8.5k</span>
                    </div>
                    <div class="stat">
                        <span class="icon-stat">💬</span>
                        {{-- esto debe ser una variable comentarios --}}
                        <span class="count">1.2k</span>
                    </div>
                    <div class="stat">
                        <span class="icon-stat">✅</span>
                        {{-- esto debe ser una variable compartidos --}}
                        <span class="count">145</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <aside class="sidebar-right">
        <div class="suggestions-header">Sugerencias para ti</div>
        
        <div class="user-suggestion">
            <img src="https://via.placeholder.com/50" alt="Usuario" class="avatar-lg">
            <div class="user-details">
                {{-- esto debe ser una variable de nombre de usuario --}}
                <div class="username-suggested">usuario</div>
                {{-- esto debe ser una variable de comentario --}}
                <div class="user-comment">comentario</div>
            </div>
        </div>

        <div class="user-suggestion">
            <img src="https://via.placeholder.com/50" alt="Usuario" class="avatar-lg">
            <div class="user-details">
                {{-- esto debe ser una variable de nombre de usuario --}}
                <div class="username-suggested">usuario</div>
                {{-- esto debe ser una variable de comentario --}}
                <div class="user-comment">comentario</div>
            </div>
        </div>

        <div class="user-suggestion">
            <img src="https://via.placeholder.com/50" alt="Usuario" class="avatar-lg">
            <div class="user-details">
                {{-- esto debe ser una variable de nombre de usuario --}}
                <div class="username-suggested">usuario</div>
                {{-- esto debe ser una variable de comentario --}}
                <div class="user-comment">comentario</div>
            </div>
        </div>
    </aside>
</div>
@endsection