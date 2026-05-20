@extends('layout.layout')

@section('title', __('messages.search_recipes'))

@section('content')
    <section class="recipe-search-page">
        <div class="recipe-search-header">
            <div>
                <h1>{{ __('messages.search_recipes') }}</h1>
                <p>{{ __('messages.search_recipes_help') }}</p>
            </div>

        </div>

        <form method="GET" action="{{ route('recetas.search') }}" class="recipe-search-filters">
            <div class="filter-field filter-field-wide">
                <label for="q">{{ __('messages.recipe') }}</label>
                <input
                    type="search"
                    id="q"
                    name="q"
                    value="{{ $filters['q'] ?? '' }}"
                    placeholder="{{ __('messages.recipe_search_placeholder') }}"
                >
            </div>

            <div class="filter-field">
                <label for="user">{{ __('messages.user') }}</label>
                <input
                    type="search"
                    id="user"
                    name="user"
                    value="{{ $filters['user'] ?? '' }}"
                    placeholder="{{ __('messages.user_search_placeholder') }}"
                >
            </div>

            <div class="filter-field">
                <label for="ingredient">{{ __('messages.ingredient') }}</label>
                <input
                    type="search"
                    id="ingredient"
                    name="ingredient"
                    value="{{ $filters['ingredient'] ?? '' }}"
                    list="recipe-search-ingredients"
                    placeholder="{{ __('messages.ingredient_search_placeholder') }}"
                >
                <datalist id="recipe-search-ingredients">
                    @foreach($ingredients as $ingredient)
                        <option value="{{ $ingredient->name }}"></option>
                    @endforeach
                </datalist>
            </div>

            <div class="filter-field">
                <label for="tag">{{ __('messages.tag') }}</label>
                <input
                    type="search"
                    id="tag"
                    name="tag"
                    value="{{ $filters['tag'] ?? '' }}"
                    placeholder="{{ __('messages.tag_search_placeholder') }}"
                >
            </div>

            <div class="filter-field">
                <label for="max_time">{{ __('messages.max_time') }}</label>
                <input
                    type="number"
                    id="max_time"
                    name="max_time"
                    min="0"
                    value="{{ $filters['max_time'] ?? '' }}"
                    placeholder="{{ __('messages.minutes') }}"
                >
            </div>

            <div class="filter-field">
                <label for="media">{{ __('messages.media') }}</label>
                <select id="media" name="media">
                    <option value="any" {{ ($filters['media'] ?? 'any') === 'any' ? 'selected' : '' }}>{{ __('messages.all') }}</option>
                    <option value="video" {{ ($filters['media'] ?? 'any') === 'video' ? 'selected' : '' }}>{{ __('messages.with_video') }}</option>
                </select>
            </div>

            <div class="recipe-search-actions">
                <button type="submit" class="btn">{{ __('messages.search') }}</button>
                <a href="{{ route('recetas.search') }}" class="btn btn-secondary">{{ __('messages.clear') }}</a>
            </div>
        </form>

        <div class="recipe-search-summary">
            {{ trans_choice('messages.recipes_found', $recipes->count(), ['count' => $recipes->count()]) }}
        </div>

        <div class="recipes-list">
            @forelse($recipes as $recipe)
                <article class="recipe-card">
                    @include('recipes.partials.media-slider', ['recipe' => $recipe, 'class' => 'recipe-media-card'])

                    <h3>
                        <a href="{{ route('recetas.show', ['receta' => $recipe->id]) }}" class="recipe-title-link">
                            {{ $recipe->name }}
                        </a>
                    </h3>

                    <p>{{ $recipe->description }}</p>

                    <p>
                        <strong>{{ __('messages.user') }}:</strong>
                        <a href="{{ route('profile', ['id' => $recipe->user_id]) }}" class="recipe-title-link">
                            {{ $recipe->user->username ?? 'usuario_desconocido' }}
                        </a>
                    </p>

                    <p>
                        <strong>{{ __('messages.time') }}:</strong> {{ $recipe->time }} {{ __('messages.min') }}
                    </p>

                    @if($recipe->ingredients->count())
                        <p class="recipe-search-ingredients">
                            <strong>{{ __('messages.ingredients') }}:</strong>
                            {{ $recipe->ingredients->pluck('name')->take(4)->join(', ') }}
                        </p>
                    @endif
                </article>
            @empty
                <p class="recipe-search-empty">{{ __('messages.no_matching_recipes') }}</p>
            @endforelse
        </div>
    </section>
@endsection
