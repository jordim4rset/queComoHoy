@extends('layout.layout')

@section('title', 'Index Ingredients')

@section('content')

    <a href="{{ route('ingredientes.create') }}"  class="btn">Crear Ingrediente</a>


     <form method="GET" action="{{ route('ingredientes.index') }}">
        <select name="category" onchange="this.form.submit()">
            <option value="">Todas las categorías</option>
            @foreach(\App\Models\Ingredient::CATEGORIES as $category)
                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                    {{ $category }}
                </option>
            @endforeach
        </select>
    </form>





    <div class="flex-container">
        @foreach ($ingredients as $ingr)
            <div class="flex-item">
                <div class="card-content">
                    <img src="{{ asset('storage/' . $ingr->icon) }}" alt="Icono {{ $ingr->name }}"
                        onerror="this.style.display='none'">
                    <h3>{{ $ingr->name }}</h3>
                    <p>{{ $ingr->category }}</p>
                    <a href="{{ route('ingredientes.edit', ['ingrediente' => $ingr->id]) }}" class="btn">Editar</a>
                    <form
                        action="{{ route('ingredientes.destroy', ['ingrediente' => $ingr->id]) }}"
                        method="POST"
                        style="display:inline"
                        onsubmit="return confirm('Eliminar ingrediente?')"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

@endsection
