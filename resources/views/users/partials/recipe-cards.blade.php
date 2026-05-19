@foreach($recipes as $recipe)
    <div class="recipe-card">

        @include('recipes.partials.media-slider', ['recipe' => $recipe, 'class' => 'recipe-media-card'])

        <h3>
            <a
                href="{{ route('recetas.show', ['receta' => $recipe->id]) }}"
                class="recipe-title-link"
            >
                {{ $recipe->name }}
            </a>
        </h3>

        <p>{{ $recipe->description }}</p>

        <p>
            <strong>Tiempo:</strong>
            {{ $recipe->time }} min
        </p>

    </div>
@endforeach
