<div class="lang-switcher">
    @foreach (['es' => 'ES', 'en' => 'EN'] as $locale => $label)
        <form method="POST" action="{{ route('locale.update') }}">
            @csrf
            <input type="hidden" name="locale" value="{{ $locale }}">
            <button type="submit" class="lang-btn {{ app()->getLocale() === $locale ? 'active' : '' }}">
                {{ $label }}
            </button>
        </form>
    @endforeach
</div>
