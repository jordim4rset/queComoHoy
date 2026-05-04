@extends('layout.layout')

@section('content')
    <div class="profile-container">
        <h1>{{ $user->name }}</h1>
        <p><strong>Usuario:</strong> {{ $user->username }}</p>
        <p><strong>Rol:</strong> {{ $user->rol }}</p>
        <p>
            <strong><a href="{{ route('user.following', $user->id) }}">Siguiendo</a>:</strong>
            {{ $user->following()->count() }}
        </p>
        <p>
            <strong><a href="{{ route('user.followers', $user->id) }}">Seguidores</a>:</strong>
            {{ $user->followers()->count() }}
        </p>

        @auth
            @if (Auth::id() !== $user->id)
                @if (Auth::user()->following()->where('following_id', $user->id)->exists())
                    <form method="POST" action="/unfollow/{{ $user->id }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-secondary">Dejar de seguir</button>
                    </form>
                @else
                    <form method="POST" action="/follow/{{ $user->id }}" style="display: inline;">
                        @csrf
                        <button class="btn btn-primary">Seguir</button>
                    </form>
                @endif

                @if (App\Models\Block::where('id_bloqueador', Auth::id())->where('id_bloqueado', $user->id)->exists())
                    <form method="POST" action="{{ route('unblock') }}" style="display: inline;">
                        @csrf
                        <input type="hidden" name="id_bloqueador" value="{{ Auth::id() }}">
                        <input type="hidden" name="id_bloqueado" value="{{ $user->id }}">
                        <button class="btn btn-danger">Desbloquear</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('block') }}" style="display: inline;">
                        @csrf
                        <input type="hidden" name="id_bloqueador" value="{{ Auth::id() }}">
                        <input type="hidden" name="id_bloqueado" value="{{ $user->id }}">
                        <button class="btn btn-danger">Bloquear</button>
                    </form>
                @endif

            @endif
        @endauth

        <hr>

        <section class="recipes-section">
            <h2>Recetas de {{ $user->name }}</h2>

            @if ($recipes->count() > 0)
                <div class="recipes-grid">
                    @foreach ($recipes as $recipe)
                        <div class="recipe-card">
                            @if ($recipe->image)
                                <img src="{{ asset('storage/' . $recipe->image) }}" alt="{{ $recipe->name }}">
                            @else
                                <div class="recipe-no-image">Sin imagen</div>
                            @endif
                            <h3>{{ $recipe->name }}</h3>
                            <p>{{ Str::limit($recipe->description, 100) }}</p>
                            <p><strong>Tiempo:</strong> {{ $recipe->time }} min</p>
                            <a href="{{ route('recetas.show', $recipe->id) }}" class="btn btn-sm">Ver receta</a>
                        </div>
                    @endforeach
                </div>

                <div class="pagination-wrapper">
                    {{ $recipes->links() }}
                </div>
            @else
                <p>{{ $user->name }} no ha creado recetas aún.</p>
            @endif
        </section>
    </div>
@endsection
