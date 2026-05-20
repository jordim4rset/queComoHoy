<footer class="site-footer">
    <div class="site-footer-grid">
        <div class="site-footer-brand">
            <h2>Que Cocino Hoy</h2>
            <p>
                {{ __('messages.footer_description') }}
            </p>
        </div>

        <div class="site-footer-column">
            <h3>{{ __('messages.footer_explore') }}</h3>

            <nav class="site-footer-links" aria-label="{{ __('messages.footer_pages_label') }}">
                <a href="{{ route('index') }}">{{ __('messages.home') }}</a>
                <a href="{{ route('recetas.search') }}">{{ __('messages.search_recipes') }}</a>
                <a href="{{ route('users.index') }}">{{ __('messages.search_user_menu') }}</a>
                <a href="{{ route('eventos.index') }}">{{ __('messages.events') }}</a>
                <a href="{{ route('ingredientes.index') }}">{{ __('messages.ingredients') }}</a>
                <a href="{{ route('shop') }}">{{ __('messages.chefpoints_shop') }}</a>
            </nav>
        </div>

        <div class="site-footer-column">
            <h3>{{ __('messages.more') }}</h3>

            <nav class="site-footer-links" aria-label="{{ __('messages.legal_pages_label') }}">
                <a href="{{ route('legal.privacy') }}">{{ __('messages.privacy_policy') }}</a>
                <a href="{{ route('legal.cookies') }}">{{ __('messages.cookies_policy') }}</a>
            </nav>
        </div>
    </div>

    <div class="site-footer-bottom">
        <p>&copy; 2026 Que Cocino Hoy. {{ __('messages.powered_by') }} Jordi, Alex, Mahyoui Jr.</p>
    </div>
</footer>
