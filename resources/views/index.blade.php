@extends('layout.layout')

@section('title')
Inicio - QueComoHoy
@endsection

@section('content')
    <div class="app-container">

        <main class="feed">

            <div class="post">
                <div class="post-header">
                    <div class="user-info">
                        <img src="https://via.placeholder.com/40" alt="Usuario" class="avatar">
                        <span class="username">usuario_pro</span>
                    </div>
                    <button>Seguir</button>
                </div>

                <img src="https://via.placeholder.com/600x500" alt="Receta deliciosa" class="post-image">

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
                    <div class="post-description">
                        <p><strong>usuario_pro</strong> Receta de pasta #cocina #foodie</p>
                    </div>
                </div>
            </div>

            <div class="post">
                <div class="post-header">
                    <div class="user-info">
                        <img src="https://via.placeholder.com/40" alt="Usuario" class="avatar">
                        <span class="username">chef_quecocino</span>
                    </div>
                    <button class="follow-btn">Seguir</button>
                </div>

                <img src="https://via.placeholder.com/600x500" alt="Comida saludable" class="post-image">

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
                    <div class="post-description">
                        <p><strong>chef_quecocino</strong> Ensalada con tomates </p>
                    </div>
                </div>
            </div>

        </main>

        <aside class="sidebar-right">
            <div class="suggestions-header">
                <span>Sugerencias para ti</span>
                <a href="#" class="view-all">Ver todo</a>
            </div>

            <div class="suggestions-list">
                <div class="user-suggestion">
                    <img src="https://via.placeholder.com/50" alt="Usuario" class="avatar-lg">
                    <div class="user-details">
                        <div class="username-suggested">pedro_cocina</div>
                        <div class="user-comment">Nuevo en QueComoHoy</div>
                    </div>
                    <a href="#" class="follow-link">Seguir</a>
                </div>

                <div class="user-suggestion">
                    <img src="https://via.placeholder.com/50" alt="Usuario" class="avatar-lg">
                    <div class="user-details">
                        <div class="username-suggested">ana_pasteles</div>
                        <div class="user-comment">Seguido por usuario_pro</div>
                    </div>
                    <a href="#" class="follow-link">Seguir</a>
                </div>

                <div class="user-suggestion">
                    <img src="https://via.placeholder.com/50" alt="Usuario" class="avatar-lg">
                    <div class="user-details">
                        <div class="username-suggested">recetas_faciles</div>
                        <div class="user-comment">Sugerencia para ti</div>
                    </div>
                    <a href="#" class="follow-link">Seguir</a>
                </div>
            </div>
        </aside>

    </div>
@endsection
