@extends('layout.layout')

@section('title', __('messages.my_recipes'))

@section('content')
    <div class="recipes-header">
        <h1>{{ __('messages.my_recipes') }}</h1>

        @auth
            <a href="{{ route('recetas.create') }}" class="btn btn-primary">
                {{ __('messages.create_recipe') }}
            </a>
        @endauth
    </div>

    <div class="recipes-list">

        @forelse ($recetas as $receta)
            <div class="recipe-card">

                @include('recipes.partials.media-slider', ['recipe' => $receta, 'class' => 'recipe-media-card'])

                <h3>
                    <a
                        href="{{ route('recetas.show', ['receta' => $receta->id]) }}"
                        class="recipe-title-link"
                    >
                        {{ $receta->name }}
                    </a>
                </h3>

                <p>
                    {{ $receta->description }}
                </p>

                <p>
                    <strong>{{ __('messages.visibility') }}:</strong>
                    {{ $receta->visibility ? __('messages.public') : __('messages.private') }}
                </p>

                <div class="recipe-card-buttons">

                    <a
                        href="{{ route('recetas.edit', ['receta' => $receta->id]) }}"
                        class="btn btn-edit"
                    >
                        {{ __('messages.edit') }}
                    </a>

                    <form
                        action="{{ route('recetas.destroy', ['receta' => $receta->id]) }}"
                        method="POST"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-delete"
                            onclick="return confirm('{{ __('messages.delete_recipe_confirm') }}')"
                        >
                            {{ __('messages.delete') }}
                        </button>
                    </form>

                </div>

            </div>
        @empty
            <p>{{ __('messages.no_own_recipes') }}</p>
        @endforelse

    </div>
@endsection
