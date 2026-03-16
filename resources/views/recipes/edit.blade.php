@extends('layout')

@section('title', 'Editar Receta')

@section('content')

    <h1>Editar Receta</h1>

    <form action="{{ route('recipes.update', $receta->id_receta) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" maxlength="30"
                   value="{{ $receta->nombre }}" required>
        </div>

        <div>
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" maxlength="150" required>{{ $receta->descripcion }}</textarea>
        </div>

        <div>
            <label for="tiempo_elaboracion">Tiempo de elaboración (min)</label>
            <input type="number" name="tiempo_elaboracion" id="tiempo_elaboracion" step="0.01"
                   value="{{ $receta->tiempo_elaboracion }}" required>
        </div>

        <div>
            <label for="etiquetas">Etiquetas</label>
            <input type="text" name="etiquetas" id="etiquetas" maxlength="30"
                   value="{{ $receta->etiquetas }}">
        </div>

        <div>
            <label for="visibilidad">Visibilidad</label>
            <select name="visibilidad" id="visibilidad" required>
                <option value="1" {{ $receta->visibilidad ? 'selected' : '' }}>Pública</option>
                <option value="0" {{ !$receta->visibilidad ? 'selected' : '' }}>Privada</option>
            </select>
        </div>

        <button type="submit">Guardar Cambios</button>
        <a href="{{ route('recipes.index') }}">Cancelar</a>

    </form>

@endsection
