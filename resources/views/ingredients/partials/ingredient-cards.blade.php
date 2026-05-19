@foreach ($ingredients as $ingr)
    <article class="ingredient-card">
        <div class="ingredient-card-badge">
            {{ mb_substr($ingr->name, 0, 1) }}
        </div>

        <h3>{{ $ingr->name }}</h3>
        <p>{{ $ingr->category }}</p>

        <div class="ingredient-actions">
            <a href="{{ route('ingredientes.edit', ['ingrediente' => $ingr->id]) }}" class="btn btn-sm">Editar</a>
            <form
                action="{{ route('ingredientes.destroy', ['ingrediente' => $ingr->id]) }}"
                method="POST"
                onsubmit="return confirm('Eliminar ingrediente?')"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
            </form>
        </div>
    </article>
@endforeach
