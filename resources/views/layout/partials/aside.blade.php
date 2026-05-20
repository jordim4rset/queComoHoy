<nav class="menu">
    <a href="{{ route('index') }}" class="menu-item {{ request()->is('/') ? 'active' : '' }}"
        onclick="selectItem(this, event)">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                <path
                    d="M341.8 72.6C329.5 61.2 310.5 61.2 298.3 72.6L74.3 280.6C64.7 289.6 61.5 303.5 66.3 315.7C71.1 327.9 82.8 336 96 336L112 336L112 512C112 547.3 140.7 576 176 576L464 576C499.3 576 528 547.3 528 512L528 336L544 336C557.2 336 569 327.9 573.8 315.7C578.6 303.5 575.4 289.5 565.8 280.6L341.8 72.6zM304 384L336 384C362.5 384 384 405.5 384 432L384 528L256 528L256 432C256 405.5 277.5 384 304 384z" />
            </svg>
        </span>
        {{ __('messages.for_you') }}
    </a>

    <a href="{{ route('users.index') }}" class="menu-item {{ request()->is('users') ? 'active' : '' }}"
        onclick="selectItem(this, event)">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="7" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
        </span>
        {{ __('messages.search_user_menu') }}
    </a>

    <a href="{{ route('eventos.index') }}" class="menu-item {{ request()->is('eventos*') ? 'active' : '' }}"
        onclick="selectItem(this, event)">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M182.4 53.5L157.8 95.6C154 102.1 152 109.6 152 117.2L152 120C152 142.1 169.9 160 192 160C214.1 160 232 142.1 232 120L232 117.2C232 109.6 230 102.2 226.2 95.6L201.6 53.5C199.6 50.1 195.9 48 192 48C188.1 48 184.4 50.1 182.4 53.5zM310.4 53.5L285.8 95.6C282 102.1 280 109.6 280 117.2L280 120C280 142.1 297.9 160 320 160C342.1 160 360 142.1 360 120L360 117.2C360 109.6 358 102.2 354.2 95.6L329.6 53.5C327.6 50.1 323.9 48 320 48C316.1 48 312.4 50.1 310.4 53.5zM413.8 95.6C410 102.1 408 109.6 408 117.2L408 120C408 142.1 425.9 160 448 160C470.1 160 488 142.1 488 120L488 117.2C488 109.6 486 102.2 482.2 95.6L457.6 53.5C455.6 50.1 451.9 48 448 48C444.1 48 440.4 50.1 438.4 53.5L413.8 95.6zM224 224C224 206.3 209.7 192 192 192C174.3 192 160 206.3 160 224L160 277.5C122.7 290.6 96 326.2 96 368L96 388.8C116.9 390.1 137.6 396.1 156.3 406.8L163.4 410.9C189.7 425.9 222.3 424.3 247 406.7C290.7 375.5 349.3 375.5 393 406.7C417.6 424.3 450.3 426 476.6 410.9L483.7 406.8C502.4 396.1 523 390.1 544 388.8L544 368C544 326.2 517.3 290.6 480 277.5L480 224C480 206.3 465.7 192 448 192C430.3 192 416 206.3 416 224L416 272L352 272L352 224C352 206.3 337.7 192 320 192C302.3 192 288 206.3 288 224L288 272L224 272L224 224zM544 437C531.3 438.2 518.9 442 507.5 448.5L500.4 452.6C457.8 476.9 405 474.3 365.1 445.8C338.1 426.5 301.9 426.5 274.9 445.8C235 474.3 182.2 477 139.6 452.6L132.5 448.5C121.1 442 108.7 438.1 96 437L96 512C96 547.3 124.7 576 160 576L480 576C515.3 576 544 547.3 544 512L544 437z"/></svg>
        </span>
        {{ __('messages.events') }}
    </a>

    @auth
        <a href="{{ route('following.feed') }}"
            class="menu-item {{ request()->routeIs('following.feed') ? 'active' : '' }}" onclick="selectItem(this, event)">
            <span class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                    <path
                        d="M320 64C355.3 64 384 92.7 384 128C384 163.3 355.3 192 320 192C284.7 192 256 163.3 256 128C256 92.7 284.7 64 320 64zM416 376C416 401 403.3 423 384 435.9L384 528C384 554.5 362.5 576 336 576L304 576C277.5 576 256 554.5 256 528L256 435.9C236.7 423 224 401 224 376L224 336C224 283 267 240 320 240C373 240 416 283 416 336L416 376zM160 96C190.9 96 216 121.1 216 152C216 182.9 190.9 208 160 208C129.1 208 104 182.9 104 152C104 121.1 129.1 96 160 96zM176 336L176 368C176 400.5 188.1 430.1 208 452.7L208 528C208 529.2 208 530.5 208.1 531.7C199.6 539.3 188.4 544 176 544L144 544C117.5 544 96 522.5 96 496L96 439.4C76.9 428.4 64 407.7 64 384L64 352C64 299 107 256 160 256C172.7 256 184.8 258.5 195.9 262.9C183.3 284.3 176 309.3 176 336zM432 528L432 452.7C451.9 430.2 464 400.5 464 368L464 336C464 309.3 456.7 284.4 444.1 262.9C455.2 258.4 467.3 256 480 256C533 256 576 299 576 352L576 384C576 407.7 563.1 428.4 544 439.4L544 496C544 522.5 522.5 544 496 544L464 544C451.7 544 440.4 539.4 431.9 531.7C431.9 530.5 432 529.2 432 528zM480 96C510.9 96 536 121.1 536 152C536 182.9 510.9 208 480 208C449.1 208 424 182.9 424 152C424 121.1 449.1 96 480 96z" />
                </svg>
            </span>
            {{ __('messages.following') }}
        </a>
    @endauth

    <a href="{{ route('recetas.search') }}"
        class="menu-item {{ request()->routeIs('recetas.search') ? 'active' : '' }}" onclick="selectItem(this, event)">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Pro v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2026 Fonticons, Inc.--><path d="M480 576L192 576C139 576 96 533 96 480L96 160C96 107 139 64 192 64L496 64C522.5 64 544 85.5 544 112L544 400C544 420.9 530.6 438.7 512 445.3L512 512C529.7 512 544 526.3 544 544C544 561.7 529.7 576 512 576L480 576zM192 448C174.3 448 160 462.3 160 480C160 497.7 174.3 512 192 512L448 512L448 448L192 448zM224 216C224 229.3 234.7 240 248 240L424 240C437.3 240 448 229.3 448 216C448 202.7 437.3 192 424 192L248 192C234.7 192 224 202.7 224 216zM248 288C234.7 288 224 298.7 224 312C224 325.3 234.7 336 248 336L424 336C437.3 336 448 325.3 448 312C448 298.7 437.3 288 424 288L248 288z"/></svg>
        </span>
        {{ __('messages.search_recipes') }}
    </a>

    <a href="{{ route('recetas.index') }}" class="menu-item {{ request()->routeIs('recetas.index') ? 'active' : '' }}"
        onclick="selectItem(this, event)">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M64 240C64 204.7 92.7 176 128 176C128.5 176 129.1 176 129.6 176C137 139.5 169.3 112 208 112C223 112 237 116.1 248.9 123.2C262.2 97.5 289 80 320 80C351 80 377.8 97.6 391.1 123.2C403.1 116.1 417.1 112 432 112C470.7 112 503 139.5 510.4 176C510.9 176 511.5 176 512 176C547.3 176 576 204.7 576 240C576 251.7 572.9 262.6 567.4 272L72.6 272C67.1 262.6 64 251.7 64 240zM64 347.4C64 332.3 76.3 320 91.4 320L548.5 320C563.6 320 575.9 332.3 575.9 347.4C575.9 417.9 531.5 478.1 469.2 501.5L467.5 516C465.5 532 451.9 544 435.7 544L204.2 544C188.1 544 174.4 532 172.4 516L170.6 501.6C108.4 478.1 64 417.9 64 347.4z"/></svg>
        </span>
        {{ __('messages.recipes') }}
    </a>
</nav>

@auth
    <a href="{{ route('shop') }}" class="chefpoints-widget">
        <span class="icon chefpoints-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l1-4h16l1 4" />
                <path d="M5 9v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V9" />
                <polyline points="3 9 12 2 21 9" />
            </svg>
        </span>

        <div class="chefpoints-text">
            <span>ChefPoints</span>
            <strong>{{ auth()->user()->chefpoints }}</strong>

            <div class="chef-progress-label">
                <span>{{ __('messages.level') }} {{ auth()->user()->chefLevel() }}</span>
                <small>{{ auth()->user()->chefLevelName() }}</small>
            </div>

            <div class="chef-progress-bar">
                <div class="chef-progress-fill" style="width: {{ auth()->user()->chefLevelPercent() }}%"></div>
            </div>

            <small class="chef-progress-meta">
                {{ auth()->user()->chefLevelProgress() }} / 1000 {{ __('messages.points') }}
                @if(auth()->user()->chefLevel() < 5)
                    (+{{ auth()->user()->chefPointsToNextLevel() }} {{ __('messages.to_next_level') }})
                @else
                    {{ __('messages.max_level_reached') }}
                @endif
            </small>
        </div>
    </a>
@endauth


<div class="mobile-only-menu">
    @include('layout.partials.lang-switcher')

    @auth
        <a href="{{ route('profile', ['id' => Auth::id()]) }}" class="menu-item">
            <span class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
            </span>
            {{ __('messages.mi_perfil_menu') }} ({{ Auth::user()->name }})
        </a>
        <form action="{{ route('logout', [], false) }}" method="POST">
            @csrf
            <button type="submit" class="menu-item menu-logout">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                </span>
                {{ __('messages.logout') }}
            </button>
        </form>
    @else
        <a href="{{ route('auth.login') }}" class="menu-item">{{ __('messages.login') }}</a>
        <a href="{{ route('auth.signup') }}" class="menu-item">{{ __('messages.signup') }}</a>
    @endauth
</div>
