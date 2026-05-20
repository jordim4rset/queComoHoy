<div class="nav-container">
    <div class="nav-logo">
        <div class="logo">
            <h1><a href="{{ route('index') }}">QUECOCINOHOY</a></h1>
        </div>
    </div>

    <button class="hamburger" onclick="toggleMenu()" aria-label="{{ __('messages.open_menu') }}">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div class="nav-search">
        <input type="text" id="nav-search-input" placeholder="{{ __('messages.search_user') }}" autocomplete="off">
        <div id="nav-search-results" class="nav-search-results"></div>
    </div>
    <div class="nav-right">
        <div class="lang-switcher">
            <form method="POST" action="{{ route('locale.update') }}" style="display:inline">
                @csrf
                <input type="hidden" name="locale" value="es">
                <button type="submit" class="lang-btn {{ app()->getLocale() === 'es' ? 'active' : '' }}">ES</button>
            </form>
            <form method="POST" action="{{ route('locale.update') }}" style="display:inline">
                @csrf
                <input type="hidden" name="locale" value="en">
                <button type="submit" class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</button>
            </form>
        </div>

        @auth
            <a href="{{ route('profile', ['id' => Auth::id()]) }}" class="user-info">
                <img src="{{ Auth::user()->profilePhotoUrl() }}" alt="{{ Auth::user()->username }}">
                <span>{{ '@' . Auth::user()->username }}</span>
            </a>
            <form action="{{ route('logout', [], false) }}" method="POST">
                @csrf
                <button type="submit" class="auth-btn logout-btn">{{ __('messages.logout') }}</button>
            </form>
        @else
            <a href="{{ route('auth.login') }}" class="auth-btn">{{ __('messages.login') }}</a>
            <a href="{{ route('auth.signup') }}" class="auth-btn signup">{{ __('messages.signup') }}</a>
        @endauth
    </div>
</div>
