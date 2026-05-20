@php
    $mediaRecipe = $recipe ?? $receta;
    $mediaClass = $class ?? '';
@endphp

<div class="recipe-media-slider {{ $mediaClass }}" data-recipe-media>
    <div class="recipe-media-track">
        <div class="recipe-media-slide is-active" data-recipe-media-slide>
            @if($mediaRecipe->image)
                <img
                    src="{{ asset('storage/' . $mediaRecipe->image) }}"
                    alt="Portada de {{ $mediaRecipe->name }}"
                >
            @else
                <img
                    src="https://via.placeholder.com/600x500"
                    alt="Sin portada"
                >
            @endif
        </div>

        @if($mediaRecipe->video)
            <div class="recipe-media-slide" data-recipe-media-slide hidden>
                <video controls preload="metadata">
                    <source src="{{ asset('storage/' . $mediaRecipe->video) }}">
                    Tu navegador no puede reproducir este video.
                </video>
            </div>
        @endif
    </div>

    @if($mediaRecipe->video)
        <button type="button" class="recipe-media-control recipe-media-prev" data-recipe-media-prev aria-label="Ver portada anterior">
            &lsaquo;
        </button>
        <button type="button" class="recipe-media-control recipe-media-next" data-recipe-media-next aria-label="Ver video siguiente">
            &rsaquo;
        </button>

        <div class="recipe-media-dots" aria-label="Archivos de la receta">
            <button type="button" class="is-active" data-recipe-media-dot="0" aria-label="Ver portada"></button>
            <button type="button" data-recipe-media-dot="1" aria-label="Ver video"></button>
        </div>
    @endif
</div>
