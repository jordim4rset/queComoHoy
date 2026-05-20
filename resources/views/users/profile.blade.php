@extends('layout.layout')

@section('content')
    <div class="profile-container">
        <h1>{{ $user->name }}</h1>
        <p><strong>{{ __('messages.user') }}:</strong> {{ $user->username }}</p>
        <p><strong>{{ __('messages.role') }}:</strong> {{ $user->rol }}</p>
        <p><strong>ChefPoints:</strong> {{ $user->chefpoints }}</p>
        <p>
            <strong><a href="{{ route('user.following', $user->id) }}">{{ __('messages.following') }}</a>:</strong>
            {{ $user->following()->count() }}
        </p>
        <p>
            <strong><a href="{{ route('user.followers', $user->id) }}">{{ __('messages.followers') }}</a>:</strong>
            {{ $user->followers()->count() }}
        </p>

        @auth
            @if (Auth::id() !== $user->id)
                <div class="profile-actions">
                    @if (Auth::user()->following()->where('following_id', $user->id)->exists())
                        <form method="POST" action="/unfollow/{{ $user->id }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-secondary">{{ __('messages.unfollow') }}</button>
                        </form>
                    @else
                        <form method="POST" action="/follow/{{ $user->id }}">
                            @csrf
                            <button class="btn btn-primary">{{ __('messages.follow') }}</button>
                        </form>
                    @endif
                </div>

                <div class="profile-block-actions">
                    @if (Auth::user()->hasBlocked($user))
                        <form method="POST" action="{{ route('users.unblock', $user) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger">{{ __('messages.unblock') }}</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('users.block', $user) }}">
                            @csrf
                            <button class="btn btn-danger">{{ __('messages.block') }}</button>
                        </form>
                    @endif
                </div>

            @endif
        @endauth

        <hr>

        <section class="recipes-section">
            <h2>{{ __('messages.recipes_by', ['name' => $user->name]) }}</h2>

            @if ($recipes->count() > 0)
                <div class="recipes-grid">
                    @foreach ($recipes as $recipe)
                        <div class="recipe-card">
                            @include('recipes.partials.media-slider', ['recipe' => $recipe, 'class' => 'recipe-media-card'])
                            <h3>{{ $recipe->name }}</h3>
                            <p>{{ Str::limit($recipe->description, 100) }}</p>
                            <p><strong>{{ __('messages.time') }}:</strong> {{ $recipe->time }} {{ __('messages.min') }}</p>
                            <a href="{{ route('recetas.show', $recipe->id) }}" class="btn btn-sm">{{ __('messages.view_recipe') }}</a>
                        </div>
                    @endforeach
                </div>

                <div class="pagination-wrapper">
                    {{ $recipes->links() }}
                </div>
            @else
                <p>{{ __('messages.user_has_no_recipes', ['name' => $user->name]) }}</p>
            @endif
        </section>
    </div>
@endsection
