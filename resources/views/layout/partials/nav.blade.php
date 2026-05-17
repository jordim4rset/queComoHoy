<div class="nav-container">
    <div class="nav-logo">
        <div class="logo">
            <h1><a href="{{ route('index') }}">QUECOCINOHOY</a></h1>
        </div>
    </div>

    <div class="nav-search">
        <input
            type="text"
            id="nav-search-input"
            placeholder="Buscar usuario..."
            autocomplete="off"
        >
        <div id="nav-search-results" class="nav-search-results"></div>
    </div>

    <div class="nav-right">
        @auth
            <a href="{{ route('account') }}" class="user-info">MI PERFIL, {{ Auth::user()->name }}</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="auth-btn logout-btn">Cerrar Sesion</button>
            </form>
        @else
            <a href="{{ route('auth.login') }}" class="auth-btn">Login</a>
            <a href="{{ route('auth.signup') }}" class="auth-btn signup">Registrarse</a>
        @endauth
    </div>
</div>
