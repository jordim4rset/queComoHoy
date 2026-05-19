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

@once
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-recipe-media]').forEach(function (slider) {
            const slides = Array.from(slider.querySelectorAll('[data-recipe-media-slide]'));
            const dots = Array.from(slider.querySelectorAll('[data-recipe-media-dot]'));
            const previousButton = slider.querySelector('[data-recipe-media-prev]');
            const nextButton = slider.querySelector('[data-recipe-media-next]');
            let activeIndex = 0;

            function showSlide(index) {
                if (!slides.length) {
                    return;
                }

                activeIndex = (index + slides.length) % slides.length;

                slides.forEach(function (slide, slideIndex) {
                    const isActive = slideIndex === activeIndex;
                    slide.hidden = !isActive;
                    slide.classList.toggle('is-active', isActive);

                    if (!isActive) {
                        slide.querySelectorAll('video').forEach(function (video) {
                            video.pause();
                        });
                    }
                });

                dots.forEach(function (dot, dotIndex) {
                    dot.classList.toggle('is-active', dotIndex === activeIndex);
                });
            }

            if (previousButton) {
                previousButton.addEventListener('click', function () {
                    showSlide(activeIndex - 1);
                });
            }

            if (nextButton) {
                nextButton.addEventListener('click', function () {
                    showSlide(activeIndex + 1);
                });
            }

            dots.forEach(function (dot) {
                dot.addEventListener('click', function () {
                    showSlide(Number(dot.dataset.recipeMediaDot));
                });
            });
        });
    });
    </script>
@endonce
