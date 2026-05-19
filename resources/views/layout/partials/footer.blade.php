<footer class="site-footer">
    <div class="site-footer-grid">
        <div class="site-footer-brand">
            <h2>Que Cocino Hoy</h2>
            <p>
                Comparte recetas, descubre ideas nuevas y encuentra platos por ingredientes,
                eventos o usuarios de la comunidad.
            </p>
        </div>

        <div class="site-footer-column">
            <h3>&iquest;Que vemos?</h3>

            <nav class="site-footer-links" aria-label="Paginas de Que Cocino Hoy">
                <a href="{{ route('index') }}">Inicio</a>
                <a href="{{ route('recetas.search') }}">Buscar recetas</a>
                <a href="{{ route('users.index') }}">Buscar usuario</a>
                <a href="{{ route('eventos.index') }}">Eventos</a>
                <a href="{{ route('ingredientes.index') }}">Ingredientes</a>
                <a href="{{ route('shop') }}">Tienda ChefPoints</a>
            </nav>
        </div>

        <div class="site-footer-column">
            <h3>Mas cosas</h3>

            <div class="site-footer-links site-footer-static-links">
                <span>Politica de privacidad</span>
                <span>Politica de cookies</span>
            </div>
        </div>
    </div>

    <div class="site-footer-bottom">
        <p>&copy; 2026 Que Cocino Hoy. Powered by Jordi, Alex, Mahyoui Jr.</p>
    </div>
</footer>
