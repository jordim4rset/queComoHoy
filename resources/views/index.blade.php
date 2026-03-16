@extends('layout.layout')

@section('title')
Inicio - QueComoHoy
@endsection

@section('content')
<!-- Header con Buscador -->
<header class="app-header">
    <div class="search-container">
        <input type="text" class="search-input" placeholder="🔍 Buscar recetas...">
    </div>
</header>

<div class="app-container">
    <!-- Sidebar Izquierdo -->
    <aside class="sidebar-left">
        <div class="logo">
            <h1>QUECOCINOHOY</h1>
            <span class="logo-icon">👨‍🍳</span>
        </div>
        <nav class="menu">
            <a href="#" class="menu-item active">
                <span class="icon">🏠</span> For You
            </a>
            <a href="#" class="menu-item">
                <span class="icon">👥</span> Following
            </a>
            <a href="#" class="menu-item">
                <span class="icon">📹</span> Live
            </a>
        </nav>
    </aside>

    <!-- Feed Central -->
    <main class="feed">
        <!-- Post 1 -->
        <div class="post">
            <div class="post-header">
                <div class="user-info">
                    <img src="https://via.placeholder.com/40" alt="Usuario" class="avatar">
                    <span class="username">username</span>
                </div>
                <button class="follow-btn">Follow</button>
            </div>
            <img src="https://via.placeholder.com/500x400" alt="Comida" class="post-image">
            <div class="post-footer">
                <div class="post-stats">
                    <div class="stat">
                        <span class="icon-stat">❤️</span>
                        <span class="count">12k</span>
                    </div>
                    <div class="stat">
                        <span class="icon-stat">💬</span>
                        <span class="count">2k</span>
                    </div>
                    <div class="stat">
                        <span class="icon-stat">✅</span>
                        <span class="count">211</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Post 2 -->
        <div class="post">
            <div class="post-header">
                <div class="user-info">
                    <img src="https://via.placeholder.com/40" alt="Usuario" class="avatar">
                    <span class="username">username</span>
                </div>
                <button class="follow-btn">Follow</button>
            </div>
            <img src="https://via.placeholder.com/500x400" alt="Comida" class="post-image">
            <div class="post-footer">
                <div class="post-stats">
                    <div class="stat">
                        <span class="icon-stat">❤️</span>
                        <span class="count">8.5k</span>
                    </div>
                    <div class="stat">
                        <span class="icon-stat">💬</span>
                        <span class="count">1.2k</span>
                    </div>
                    <div class="stat">
                        <span class="icon-stat">✅</span>
                        <span class="count">145</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Sidebar Derecho -->
    <aside class="sidebar-right">
        <div class="suggestions-header">Sugerencias para ti</div>
        
        <div class="user-suggestion">
            <img src="https://via.placeholder.com/50" alt="Usuario" class="avatar-lg">
            <div class="user-details">
                <div class="username-suggested">username</div>
                <div class="user-comment">comment</div>
            </div>
        </div>

        <div class="user-suggestion">
            <img src="https://via.placeholder.com/50" alt="Usuario" class="avatar-lg">
            <div class="user-details">
                <div class="username-suggested">username</div>
                <div class="user-comment">comment</div>
            </div>
        </div>

        <div class="user-suggestion">
            <img src="https://via.placeholder.com/50" alt="Usuario" class="avatar-lg">
            <div class="user-details">
                <div class="username-suggested">username</div>
                <div class="user-comment">comment</div>
            </div>
        </div>
    </aside>
</div>
@endsection