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
            <a href="{{ route('account') }}" class="menu-item user-info">MI PERFIL, {{ Auth::user()->name }}</a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="auth-btn logout-btn">Cerrar Sesión</button>
            </form>
        @else
            <a href="{{ route('auth.login') }}" class="menu-item auth-btn">Login</a>
            <a href="{{ route('auth.signup') }}" class="menu-item auth-btn signup">Registrarse</a>
        @endauth
    </div>

<button class="hamburger" id="hamburger" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
    </button>

</div>


</div>

<div id="mobile-menu" class="nav-mobile-menu">
    <a href="{{ route('index') }}" class="menu-item">🏠 For You</a>
    <a href="{{ route('users.index') }}" class="menu-item">🔍 Buscar usuario</a>
    @auth
        <a href="{{ route('user.following', Auth::id()) }}" class="menu-item">Following</a>
        <a href="{{ route('user.followers', Auth::id()) }}" class="menu-item">Followers</a>
        <a href="{{ route('account') }}" class="menu-item">Mi Perfil</a>
    @endauth
    <a href="{{ route('recetas.index') }}" class="menu-item">🎬 Recetas</a>
    @guest
        <a href="{{ route('auth.login') }}" class="menu-item">Login</a>
        <a href="{{ route('auth.signup') }}" class="menu-item">Registrarse</a>
    @endguest
</div>
